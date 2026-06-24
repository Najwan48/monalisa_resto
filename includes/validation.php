<?php

function validate_input($data, $type, $length = null) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

    if ($length && strlen($data) > $length) {
        return false;
    }

    switch ($type) {
        case 'int':
            return filter_var($data, FILTER_VALIDATE_INT);
        case 'email':
            return filter_var($data, FILTER_VALIDATE_EMAIL);
        case 'url':
            return filter_var($data, FILTER_VALIDATE_URL);
        case 'string':
            return !empty($data) ? $data : false;
        default:
            return $data;
    }
}
?>