<?php

class DBI
{

    public $connection,
    $log_errors = true,
    $log_path = '',
    $auto_retries_on_connection_lost = 0,
    $connection_lost_retry_interval = 10000,
    $auto_retries_on_deadlock = 0,
    $deadlock_retry_interval = 2000,
    $log_query_execution_time = false,
    $log_format_csv = false;

    /**
     * Connect to the database
     *
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $database
     */
    public function connect($host, $username, $password, $database, $log_errors = true, $log_query_execution_time = true)
    {

        @$this->connection = new mysqli($host, $username, $password, $database);

        if ($this->connection->connect_error) {

            die("<h2>Error: Database connection failed! Please try again in a few minutes.</h2>");

        }

        $this->connection->set_charset("utf8");
        $this->log_errors = $log_errors;
        $this->log_query_execution_time = $log_query_execution_time;
        $this->log_path = getcwd();

        return true;

    }

    /**
     * Execute query
     *
     * @param string $sql
     * @return mixed
     */
    public function query($sql)
    {

        $deadlock_retry_count = $this->auto_retries_on_deadlock;
        $connection_lost_retry_count = $this->auto_retries_on_connection_lost;

        if ($this->log_errors || $this->log_query_execution_time) {

            $trace = debug_backtrace();
            array_shift($trace);
            $trace = array_reverse($trace);

            $trace_steps = array_map(function ($value) {

                $replace_path = defined("ABS_PATH") ? ABS_PATH : __DIR__;
                return str_replace($replace_path, "", "{$value["file"]} ({$value["line"]})");

            }, $trace);
            $trace_steps = implode(" > ", $trace_steps);
            $current_time = date("Y-m-d H:i:s");

            $log_path = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $this->log_path);
            if (substr($log_path, -1) != DIRECTORY_SEPARATOR) {
                $log_path .= DIRECTORY_SEPARATOR;
            }

        }

        while (true) {

            $this->connection->ping();
            $start_time = microtime(true);
            $return = $this->connection->query($sql);
            $execution_time = round(microtime(true) - $start_time, 5);

            if (!$this->connection->error) {
                break;
            }

            if ($connection_lost_retry_count && (2006 == $this->connection->errno)) {

                usleep($this->connection_lost_retry_interval);
                $connection_lost_retry_count--;

            } else if ($deadlock_retry_count && (1213 == $this->connection->errno || 1205 == $this->connection->errno)) {

                usleep($this->deadlock_retry_interval);
                $deadlock_retry_count--;

            } else {
                break;
            }

        }

        if ($this->log_query_execution_time) {

            $fp = fopen($log_path . "db_query_log" . ($this->log_format_csv ? ".csv" : ""), "a");
            if ($this->log_format_csv) {
                fputcsv($fp, [$current_time, str_replace('\"', '\""', $sql), $trace_steps, $execution_time], ",", '"', '"');
            } else {
                fputs($fp, $current_time . " : {$sql}\r\nTrace: {$trace_steps}\r\nExecution Time: {$execution_time}\r\n\r\n");
            }

            fclose($fp);

        }

        if ($this->log_errors && !empty($this->connection->error)) {

            $fp = fopen($log_path . "db_err_log" . ($this->log_format_csv ? ".csv" : ""), "a");
            if ($this->log_format_csv) {
                fputcsv($fp, [$current_time, $this->connection->errno, $this->connection->error, str_replace('\"', '\""', $sql), $trace_steps], ",", '"', '"');
            } else {
                fputs($fp, $current_time . " : {$this->connection->error} : {$sql}\r\nError Code: {$this->connection->errno}\r\nTrace: {$trace_steps}\r\n\r\n");
            }

            fclose($fp);

        }

        return $return;

    }

    /**
     * Execute query and get the result as an multi-dimensional associative array
     *
     * @param string $sql
     * @return mixed
     */
    public function query_to_multi_array($sql)
    {
//echo $sql;
        $result = $this->query($sql);

        if (!$result) {
            return array();
        }

        $result_array = array();

        while ($data = $result->fetch_assoc()) {

            $result_array[] = $data;

        }

        $result->free();

        return $result_array;

    }

    /**
     * Execute query and get the result as a single row associative array
     *
     * @param string $sql
     * @return mixed
     */
    public function query_to_array($sql)
    {

        if (!$result = $this->query_to_multi_array($sql)) {
            return array();
        } else {
            return $result[0];
        }

    }

    /**
     * Insert row to the specified table.
     *
     * @param string $table
     * @param mixed $data
     * @return bool
     */
    public function insert($table, $data)
    {

        $sql = "INSERT INTO `{$table}` ( `" . implode("`, `", array_keys($data)) . "` ) VALUES ( ";

        foreach ($data as $key => $value) {

            $value = $value === null ? "NULL" : (is_numeric($value) ? $value : "'" . $this->escape($value) . "'");
            $sql .= "{$value}, ";

        }

        $sql = substr($sql, 0, -2) . " );";

        if ($result = $this->query($sql)) {
            return true;
        } else {
            return false;
        }

    }



    /**
     * Escape a String
     *
     * @param string $string
     * @return string
     */
    public function escape($string)
    {

        //$string = (get_magic_quotes_gpc()) ? stripslashes($string) : $string;
        return $this->connection->real_escape_string($string);

    }

}
