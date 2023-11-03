

<?php
    global $flg_error;
    if( ! empty($flg_error) ) echo "<script>$('#myModal').modal('show');$('#login-error-popup').modal('show');</script>";
?>
<link rel="stylesheet" href="<?php echo TEMPLATE_CSS_URL?>style.css">
<script src="<?php echo TEMPLATE_JS_URL?>java.js"></script>
</body>
</html>