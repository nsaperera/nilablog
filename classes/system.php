<?php



class SYSTEM
{

    private static $current = array();

    // URL Variables
    public static function get_request_value($name, $default = "", $type = "GET")
    {

        $array = isset($GLOBALS["_{$type}"]) ? $GLOBALS["_{$type}"] : array();
        return (!empty($array[$name])) ? $array[$name] : $default;

    }

    public static function request_get($name, $default = "")
    {

        return self::get_request_value($name, $default, "GET");

    }

    public static function request_post($name, $default = "")
    {

        return self::get_request_value($name, $default, "POST");

    }

    public static function post_data($accepted_post_fields = [])
    {

        $post_data = [];
        if (is_array($accepted_post_fields) && !empty($accepted_post_fields)) {
            foreach ($accepted_post_fields as $field) {
                $post_data[$field] = !empty(SELF::request_post($field)) ? SELF::request_post($field) : "";
            }
        }

        return $post_data;

    }

    public static function request($name, $default = "")
    {

        $post = self::request_post($name, null);
        $get = self::request_get($name, null);

        if (null != $post) {
            return $post;
        } elseif (null != $get) {
            return $get;
        } else {
            return $default;
        }

    }

    // Formatting
    public static function sanitize($string)
    {

        return preg_replace("/[\-]+/", "-", (preg_replace("/^\-+|\-+\$/m", "", preg_replace("/[^a-z0-9]/i", "-", strtolower($string)))));

    }

    // Array Functions
    public static function get_array_key_value($array, $key, $default = "")
    {

        return isset($array[$key]) ? $array[$key] : $default;

    }

    public static function in_multi_array_get_element_by_value($needle, $array, $key = "id")
    {

        if (!is_array($array)) {
            return false;
        }

        foreach ($array as $index => $data) {

            if (isset($data[$key]) && $needle == $data[$key]) {
                return $data;
            }

        }

        return false;

    }
    public static function in_multi_array($needle, $array, $key = "id")
    {

        return ((self::in_multi_array_get_element_by_value($needle, $array, $key)) !== false);

    }

    public static function copy_key_to_value($array)
    {

        $keys = array_keys($array);

        return array_combine($keys, $keys);

    }

    public static function construct_custom_array($array, $key = "id", $value = "{value}", $use_actual_key = false)
    {

        if (!is_array($array)) {
            return array();
        }

        $return = [];

        preg_match_all("/(\{.*?\})/i", $value, $value_keys);
        $value_keys = array_shift($value_keys);

        foreach ($array as $index => $data) {

            if (isset($data[$key]) || $use_actual_key) {

                $key_value = $value;

                foreach ($value_keys as $value_key) {

                    $search_key = preg_replace("/[\{\}]/", "", $value_key);

                    if (isset($data[$search_key])) {
                        $key_value = str_replace($value_key, $data[$search_key], $key_value);
                    } else {
                        $key_value = str_replace($value_key, "", $key_value);
                    }

                }

                if ($use_actual_key) {
                    $return[$index] = $key_value;
                } else {
                    $return[$data[$key]] = $key_value;
                }

            }

        }

        return $return;

    }

    public static function array_get_value_from_multi_array_by_key($array, $key)
    {

        $data = [];

        foreach ($array as $sub) {
            $data[] = self::get_array_key_value($sub, $key);
        }

        return $data;

    }

    public static function array_get_array_from_multi_array_by_key($array, $key, $value)
    {

        foreach ($array as $sub) {

            if (self::get_array_key_value($sub, $key) == $value) {
                return $sub;
            }

        }

        return [];

    }

    public static function array_values_have_required_keys($array, $required_keys, $accepted_keys = [])
    {

        global $error_messages;

        if (!is_array($array) || !is_array($required_keys) || !is_array($accepted_keys)) {
            $error_messages[] = "Error! Please try again later.";
        }

        foreach ($array as $key => $value) {

            if (!empty($accepted_keys) && !in_array($value, $accepted_keys)) {
                $error_messages[] = "Field \"{$value}\" cannot be recognized. Please try again later.";
            }

            unset($required_keys[$value]);

        }

        if (!empty($required_keys)) {

            foreach ($required_keys as $key => $value) {
                $error_messages[] = "Field \"{$value}\" is required.";
            }

        }

        return empty($error_messages);

    }

    public static function array_has_required_keys($array, $required_keys)
    {

        global $error_messages;

        if (!is_array($array) || !is_array($required_keys)) {
            $error_messages[] = "Error! Please try again later.";
        }

        foreach ($array as $key => $value) {
            if (!empty($value)) {
                unset($required_keys[$key]);
            }
        }

        if (!empty($required_keys)) {

            foreach ($required_keys as $key => $value) {
                $error_messages[] = "Field \"{$value}\" is required.";
            }

        }

        return empty($error_messages);

    }

    public static function merge_array_keys($keys, $values)
    {

        $data = [];

        foreach ($values as $index => $value) {

            if (isset($keys[$index]) && !empty($keys[$index])) {
                $data[$keys[$index]] = $value;
            }

        }

        return $data;

    }

    public static function array_get_values_for_keys($array, $keys, $default = "")
    {

        $return = [];
        foreach ($keys as $key) {

            $return[$key] = self::get_array_key_value($array, $key, $default);

        }

        return $return;

    }

    // Object Functions

    public static function object_property_value($object, $property, $default = "")
    {

        return (property_exists($object, $property) && !is_null($object->$property)) ? $object->$property : $default;

    }

    public static function object_get_values_for_properties($object, $properties, $default = "")
    {

        $return = [];
        foreach ($properties as $property) {

            $return[$property] = self::object_property_value($object, $property, $default);

        }

        return $return;

    }

    public static function object_set_values_from_array($object, $array)
    {

        foreach ($array as $property => $value) {

            if (property_exists($object, $property)) {
                $object->$property = $value;
            }

        }

        return $object;

    }

    // Misc Functions
    public static function cache_control_check($filename = "", $etag = "", $last_modified = "")
    {

        if ($filename) {

            if (file_exists($filename)) {

                $last_modified = gmdate("D, d M Y H:i:s \G\M\T", filemtime($filename));
                $etag = substr(md5($last_modified), 0, 16);

            }

        }

        $browser_modified_since = isset($_SERVER["HTTP_IF_MODIFIED_SINCE"]) ? $_SERVER["HTTP_IF_MODIFIED_SINCE"] : "";
        $browser_etag = isset($_SERVER["HTTP_IF_NONE_MATCH"]) ? $_SERVER["HTTP_IF_NONE_MATCH"] : "";

        if (($browser_etag == $etag) || ($browser_modified_since && ($browser_modified_since <= $last_modified))) {

            header("HTTP/1.1 304 Not Modified");
            header("ETag: {$etag}");
            die();

        }

        header("Last-Modified: {$last_modified}");
        header("ETag: {$etag}");

    }

    public static function flush_messages()
    {

        $message_types = ["error", "warning", "success", "announcement"];

        foreach ($message_types as $message_type) {

            if (empty($GLOBALS["{$message_type}_messages"]) || !is_array($GLOBALS["{$message_type}_messages"])) {
                continue;
            }

            foreach ($GLOBALS["{$message_type}_messages"] as $message) {

                echo "<div class=\"message {$message_type}\">{$message}</div>";

            }

        }

    }

    public static function phone_number_format($phone_number)
    {

        $formatted_phone_number = preg_replace("/[^0-9]/i", "", $phone_number);

        $phone_number_length = strlen($formatted_phone_number);

        if ($phone_number_length == 10 || $phone_number_length == 11) {

            return trim(preg_replace("/([0-9])?([0-9]{3})([0-9]{3})([0-9]{4})\$/m", "\\1 (\\2) \\3-\\4", $formatted_phone_number));

        } else {
            return $phone_number;
        }

    }

    public static function redirect($relative_path = "")
    {

        header("Location: " . $relative_path);
        die();

    }

    public static function is_ajax_request()
    {

        return (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest");

    }

    public static function flush_ajax_response()
    {

        global $ajax_status;

        die(json_encode($ajax_status));

    }

    // Form Validation
    public static function form_validate($fields)
    {

        $GLOBALS["error_messages"] = self::get_array_key_value($GLOBALS, "error_messages", []);
        $error_fields = [];

        foreach ($fields as $field) {

            if (!empty($field["validation"])) {

                $validation = explode("|", $field["validation"]);
                $type = trim(self::get_array_key_value($field, "type", ""));
                if ("checkbox" == $type || "select" == $type) {
                    $value = trim(self::get_array_key_value($field, "default", ""));
                } else {
                    $value = trim(self::get_array_key_value($field, "value", ""));
                }

                $name = trim(self::get_array_key_value($field, "name", ""));
                $caption = trim(self::get_array_key_value($field, "caption", $name));

                if ("select" == $type) {

                    $data = self::get_array_key_value($field, "data", []);
                    if (!isset($data[$value])) {

                        $GLOBALS["error_messages"][] = "Invalid value for {$caption} field.";
                        $error_fields[] = $name;
                        continue;

                    }

                }

                foreach ($validation as $validate) {

                    if (!preg_match("/^([^\[]+)(\[(.*?)\])?/im", $validate, $data)) {
                        continue;
                    }

                    $function = $data[1];
                    $parameters = explode(",", System::get_array_key_value($data, 3));

                    switch ($function) {

                        case "required":
                            if (empty($value)) {

                                $GLOBALS["error_messages"][] = "{$caption} field is required to be filled.";
                                $error_fields[] = $name;

                            }
                            break;

                        case "max_length":

                            if (strlen($value) > $parameters[0]) {

                                $GLOBALS["error_messages"][] = "{$caption} field is too long. Maximum {$parameters[0]} characters allowed.";
                                $error_fields[] = $name;

                            }
                            break;

                        case "slug":

                            if (!preg_match("/^[a-z0-9\-]+\$/im", $value)) {

                                $GLOBALS["error_messages"][] = "{$caption} field only accepts alphanumeric characters and dashs(-) character.";
                                $error_fields[] = $name;

                            }
                            break;

                        case "email":

                            if (!preg_match("/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,6}\$/im", $value)) {

                                $GLOBALS["error_messages"][] = "Invalid email adddress.";
                                $error_fields[] = $name;

                            }
                            break;

                        case "match":

                            if (empty($parameters[0])) {
                                break;
                            }

                            $check_field = self::array_get_array_from_multi_array_by_key($fields, "name", $parameters[0]);
                            $check_value = self::get_array_key_value($check_field, "value", "");
                            $caption = self::get_array_key_value($check_field, "caption", "");

                            if ($value != $check_value) {

                                $GLOBALS["error_messages"][] = "{$caption} fields do not match.";
                                $error_fields[] = $name;

                            }
                            break;

                        default:

                    }

                }

            }

        }

        return $error_fields;

    }

    // HTML Functions
    public static function html_tabbed_form($tabs, $footer_elements = [], $form_settings = null, $class = "", $id = "")
    {

        echo "<div" . (!empty($class) ? " class=\"{$class}\"" : "") . (!empty($id) ? " id=\"{$id}\"" : "") . ">";

        if (!empty($form_settings)) {

            extract($form_settings);
            echo "<form" .
                (!empty($method) ? " method=\"{$method}\"" : "post") .
                (!empty($action) ? " action=\"{$action}\"" : "") .
                (!empty($enctype) ? " enctype=\"{$enctype}\"" : "") .
                ">";

        }

        echo "<div class=\"tabs\"><ul>";
        foreach ($tabs as $tab_name => $tab) {

            if (empty($tab["elements"])) {
                continue;
            }

            echo "<li>{$tab_name}</li>";

        }
        echo "</ul></div>";

        echo "<div class=\"tabs-content\">";
        foreach ($tabs as $tab) {

            if (empty($tab["elements"])) {
                continue;
            }

            $elements = SYSTEM::get_array_key_value($tab, "elements", []);
            $form = SYSTEM::get_array_key_value($tab, "form_settings", []);
            $field_wrapper = SYSTEM::get_array_key_value($tab, "field_wrapper", true);
            $tab_class = SYSTEM::get_array_key_value($tab, "class", "tab");
            $tab_id = SYSTEM::get_array_key_value($tab, "id", "");

            self::html_form($elements, $form, $field_wrapper, $tab_class, $tab_id);

        }
        echo "</div>";

        if (!empty($footer_elements)) {
            self::html_form($footer_elements, null, false, "footer_element");
        }

        if (!empty($form_settings)) {
            echo "</form>";
        }

        echo "</div>";

    }

    public static function html_form($elements, $form_settings = null, $field_wrapper = true, $class = "", $id = "")
    {

        echo "<div" . (!empty($class) ? " class=\"{$class}\"" : "") . (!empty($id) ? " id=\"{$id}\"" : "") . ">";

        if (!empty($form_settings)) {

            extract($form_settings);
            echo "<form" .
                (!empty($method) ? " method=\"{$method}\"" : "post") .
                (!empty($action) ? " action=\"{$action}\"" : "") .
                (!empty($enctype) ? " enctype=\"{$enctype}\"" : "") .
                ">";

        }

        foreach ($elements as $element) {

            if ($field_wrapper) {
                echo "<div>";
            }

            $options = self::array_get_values_for_keys($element, ["type", "label", "label_pre", "label_post", "label_after", "label_wrapper", "pre", "post", "id", "placeholder", "name", "class", "value", "sort", "add_empty_option", "data", "default", "rows", "cols", "maxlength"]);
            extract($options);

            if (!empty($error_fields[$name])) {
                $class = trim("{$class} error");
            }

            if (!empty($label) && !$label_after) {

                if ($label_wrapper) {
                    echo "<p>";
                }

                if ($label_pre) {
                    echo $label_pre;
                }

                self::html_field_label($id, $label);
                if ($label_post) {
                    echo $label_post;
                }

                if ($label_wrapper) {
                    echo "</p>";
                }

            }

            if ($pre) {
                echo $pre;
            }

            switch ($type) {

                case "submit":
                    self::html_field_submit($name, $value, $class, $id);
                    break;

                case "text":
                    self::html_field_text($name, $value, $maxlength, $class, $id, $placeholder);
                    break;

                case "hidden":
                    self::html_field_hidden($name, $value, $class, $id);
                    break;

                case "password":
                    self::html_field_password($name, $value, $class, $id, $placeholder);
                    break;

                case "checkbox":
                    self::html_field_checkbox($name, $value, $default, $class, $id);
                    break;

                case "select":
                    self::html_field_select($name, $data, $default, $sort, $class, $id, $add_empty_option);
                    break;

                case "textarea":
                    self::html_field_textarea($name, $value, $class, $id, $rows, $cols);
                    break;

                default:

            }
            if ($post) {
                echo $post;
            }

            if (!empty($label) && $label_after) {

                if ($label_wrapper) {
                    echo "<p>";
                }

                if ($label_pre) {
                    echo $label_pre;
                }

                self::html_field_label($id, $label);
                if ($label_post) {
                    echo $label_post;
                }

                if ($label_wrapper) {
                    echo "</p>";
                }

            }

            if ($field_wrapper) {
                echo "</div>";
            }

        }

        if (!empty($form_settings)) {
            echo "</form>";
        }

        echo "</div>";

    }

    public static function html_field_select($name, $data, $default = "", $sort = false, $class = "", $id = "", $add_empty_option = false)
    {

        if ($sort) {
            asort($data);
        }

        if ($add_empty_option) {
            $data = ["" => ""] + $data;
        }

        echo "<select name=\"{$name}\" " . (!empty($class) ? " class=\"{$class}\"" : "") . (!empty($id) ? " id=\"{$id}\"" : "") . ">";
        foreach ($data as $value => $key) {

            echo "<option value=\"" . htmlspecialchars($value) . "\"" . ($default == $value ? " selected=\"selected\"" : "") . ">{$key}</option>";

        }
        echo "</select>";

    }

    public static function html_field_label($for, $caption)
    {

        echo "<label for=\"{$for}\">{$caption}</label>";

    }

    public static function html_field_input($settings)
    {

        extract($settings);
        echo "<input" .
            (!empty($type) ? " type=\"{$type}\"" : "") .
            (!empty($name) ? " name=\"{$name}\"" : "") .
            (!empty($class) ? " class=\"{$class}\"" : "") .
            (!empty($id) ? " id=\"{$id}\"" : "") .
            (!empty($value) ? " value=\"" . htmlspecialchars($value) . "\"" : "") .
            (!empty($maxlength) ? " maxlength=\"{$maxlength}\"" : "") .
            (!empty($placeholder) ? " placeholder=\"{$placeholder}\"" : "") .
            (!empty($checked) ? " checked=\"checked\"" : "") .
            " />";

    }

    public static function html_field_text($name, $value = "", $maxlength = 50, $class = "", $id = "", $placeholder = "")
    {

        self::html_field_input(["type" => "text", "name" => $name, "value" => $value, "maxlength" => $maxlength, "class" => $class, "id" => $id, "placeholder" => $placeholder]);

    }

    public static function html_field_hidden($name, $value = "", $class = "", $id = "")
    {

        self::html_field_input(["type" => "hidden", "name" => $name, "value" => $value, "class" => $class, "id" => $id]);

    }

    public static function html_field_submit($name, $value = "", $class = "", $id = "")
    {

        self::html_field_input(["type" => "submit", "name" => $name, "value" => $value, "class" => $class, "id" => $id]);

    }

    public static function html_field_password($name, $value = "", $class = "", $id = "", $placeholder = "")
    {

        self::html_field_input(["type" => "password", "name" => $name, "value" => $value, "class" => $class, "id" => $id, "placeholder" => $placeholder]);

    }

    public static function html_field_checkbox($name, $value, $default = "", $class = "", $id = "")
    {

        self::html_field_input(["type" => "checkbox", "name" => $name, "value" => $value, "class" => $class, "id" => $id, "checked" => ($value == $default)]);

    }

    public static function html_field_textarea($name, $value = "", $class = "", $id = "", $rows = "10", $cols = "40")
    {

        echo "<textarea rows=\"{$rows}\" cols=\"{$cols}\"" . (!empty($name) ? " name=\"{$name}\"" : "") . (!empty($class) ? " class=\"{$class}\"" : "") . (!empty($id) ? " id=\"{$id}\"" : "") . ">" . htmlspecialchars($value) . "</textarea>";

    }

    public static function html_image($src, $alt)
    {

        echo "<img src=\"{$src}\" name=\"" . htmlspecialchars($alt) . "\" />";

    }

    // Pagination
    public static function html_pagination($total_pages, $current_page, $base_url, $displayed_max_links = 10, $page_var = "page", $auto_add_variables = true)
    {

        $uri = parse_url($base_url);
        if ($auto_add_variables) {
            $base_url = (!empty($uri["query"])) ? "{$base_url}&" : "{$base_url}?";
        }

        $total_pages = $total_pages ?: $current_page;

        $center = $displayed_max_links / 2;
        $center = ($center % 2 == 1) ? $center : $center++;

        $start_page = (($current_page - $center) < 1) ? 1 : $current_page - $center;

        echo "<ul class=\"pagination\">";

        if ($current_page > 1) {

            echo "\r\n<li><a href=\"{$base_url}{$page_var}=1\">&lt;&lt; First</a></li>";
            echo "\r\n<li><a href=\"{$base_url}{$page_var}=" . ($current_page - 1) . "\">&lt; Previous</a></li>";

        } else {

            echo "\r\n<li class=\"disabled\"><a>&lt;&lt; First</a></li>";
            echo "\r\n<li class=\"disabled\"><a>&lt; Previous</a></li>";

        }

        for ($page_number = $start_page; $page_number < ($start_page + $displayed_max_links); $page_number++) {

            if ($page_number > $total_pages) {
                break;
            }

            if ($page_number == $current_page) {
                echo "\r\n<li class=\"active\"><a>{$page_number}</a></li>";
            } else {
                echo "\r\n<li><a href=\"{$base_url}{$page_var}={$page_number}\">{$page_number}</a></li>";
            }

        }

        if ($current_page < $total_pages) {

            echo "\r\n<li><a href=\"{$base_url}{$page_var}=" . ($current_page + 1) . "\">Next &gt;</a></li>";
            echo "\r\n<li><a href=\"{$base_url}{$page_var}={$total_pages}\">Last &gt;&gt;</a></li>";

        } else {

            echo "\r\n<li class=\"disabled\"><a>Next &gt;</a></li>";
            echo "\r\n<li class=\"disabled\"><a>Last &gt;&gt;</a></li>";

        }

        echo "</ul>";

    }

    // SQL Functions
    public static function generate_sql_conditions($filters, $prepend_operator = false, $and_operator = true)
    {

        global $dbi;

        $operator = ($and_operator) ? " AND " : " OR ";

        $filters_sql = [];
        foreach ($filters as $field => $value) {
            $filters_sql[] = "{$field} = '" . $dbi->escape($value) . "'";
        }

        if (!empty($filters_sql) && $prepend_operator) {
            $filters_sql[] = "";
        }

        $filters_sql = implode($operator, $filters_sql);

        return $filters_sql;

    }

    public static function get_safe_fields_array($safe_fields, $params)
    {

        $data = [];
        foreach ($safe_fields as $field) {

            if (isset($params[$field])) {

                if (!is_array($params[$field])) {
                    $data[$field] = trim($params[$field]);
                } else {
                    $data[$field] = serialize($params[$field]);
                }

            }

        }

        return $data;

    }

    public static function include_compressed_css_data($file_list)
    {

        $data = "";
        foreach ($file_list as $file) {

            if (file_exists($file)) {

                $data_file = file_get_contents($file);

                $data_file = preg_replace_callback("/url\(['\"]?(.*?)['\"]?\)/i", function ($matches) {

                    return (preg_match("/^(http|data)/is", $matches[1])) ? $matches[0] : sprintf('url("%s")', TEMPLATE_CSS_URL . "/" . $matches[1]);

                }, $data_file);
                $data .= $data_file;

            }

        }

        $data = preg_replace(["/[\r\n ]/i", "/[ ]+/"], " ", $data);
        echo $data;

    }

    // URL
    public static function generate_url_for_params($base_url, $params)
    {

        $data = parse_url($base_url);
        !empty($data["query"]) ? parse_str($data["query"], $uri_params) : $uri_params = [];
        foreach ($_GET as $key => $value) {
            $uri_params[$key] = $value;
        }

        foreach ($params as $key => $value) {
            $uri_params[$key] = $value;
        }

        $uri_params = array_filter($uri_params);

        return (!empty($data["scheme"]) ? "{$data["scheme"]}://" : "") . (!empty($data["host"]) ? $data["host"] : "") . (!empty($data["path"]) ? $data["path"] : "") . (!empty($uri_params) ? "?" . http_build_query($uri_params) : "");

    }

    /**
     * Fetch a URI Segment
     *
     * This function returns the URI segment based on the number provided.
     *
     * @param    integer
     * @param    bool
     * @return    string
     */
    public static function uri_segments($n = false, $no_result = false)
    {
        $uri = $_SERVER['REQUEST_URI'];

        if ($uri == '/' || empty($uri)) {
            return $no_result;
        }
        $uri = parse_url($uri, PHP_URL_PATH);

        $uri = str_replace('//', '/', trim($uri, '/'));

        $segments = [];

        foreach (explode("/", $uri) as $val) {

            $val = trim($val);

            if ($val != '') {
                $segments[] = $val;
            }
        }

        if (empty($n)) {
            return $segments;
        }

        $rewrite_segment = -1;
        //Repair $rewrite_segment if base url is in a subdirectory
        if (($partsof_baseurl = count(explode("/", trim(BASE_URL, '/'))) - 3) > 0) {
            $rewrite_segment += $partsof_baseurl;
        }
        // 3 = base url should have maximum 2 slashes in it

        return (!isset($segments[$n + $rewrite_segment])) ? $no_result : $segments[$n + $rewrite_segment];
    }

    public static function ajax_calls()
    {
        if ($aj = SELF::request_post('ajc')):
            if ("_pm_cre" == $aj && "action" == SYSTEM::uri_segments(1)) {
                $pm_cre = SELF::request_post('pm_cre', false);
                $pm_cre = json_decode($pm_cre);
                if (is_array($pm_cre) && !empty($pm_cre)) {
                    $secured = 1;
                    $user_logged_in = true;
                    if ($user_logged_in) {
                        // print_r($pm_cre);die;
                    } else {
                        foreach ($pm_cre as $k => $v) {
                            if (!preg_match('/^[a-zA-Z0-9_]+$/', $v))
                        //if(!ctype_alnum($v))
                        {
                                $secured = 0;
                                break;
                            }
                            if (strlen($v) > 20) {
                                $secured = 0;
                                break;
                            }
                        }
                    }

                    if (!$secured) {
                        die('dont try to break this !!');
                    }

                    if (!file_exists(HELPERS_PATH . DIRECTORY_SEPARATOR . '_crmajaxcalls.php')) {
                        die('_c error');
                    }

                    $ajaxcalls = include HELPERS_PATH . DIRECTORY_SEPARATOR . '_crmajaxcalls.php';

                    if (array_key_exists($pm_cre[0], $ajaxcalls) && count($ajaxcalls[$pm_cre[0]]) >= 2) {
                        $pm_cont = SELF::request_post('pm_cont');
                        SELF::set_current('pm_cont', trim($pm_cont));
                        SELF::set_current('post_data', $pm_cre);

                        $class = $ajaxcalls[$pm_cre[0]][0] . MODULE_SUFFIX;
                        $method = $ajaxcalls[$pm_cre[0]][1];

                        try {
                            $class = new $class();
                            $class->$method();
                        } catch (Exception $e) {
                            echo $e->getMessage(), "\n";
                        }

                    }

                }

                die;
            }

        endif;
    }

    public static function set_current($key, $value, $setOnce = true)
    {
        if ($setOnce && !array_key_exists($key, self::$current)) {
            self::$current[$key] = $value;
        }

        if (!$setOnce) {
            self::$current[$key] = $value;
        }

    }

    public static function get_current($key = '')
    {
        if ($key != '') {
            if (array_key_exists($key, self::$current)) {
                return self::$current[$key];
            }

            return false;
        } else {
            return self::$current;
        }

    }

}
