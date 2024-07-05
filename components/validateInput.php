<?php

function validateInput($input) {
    $inputSanitized = trim($input);
    $inputSanitized = stripslashes($input);
    $inputSanitized = htmlspecialchars($input);
    return $inputSanitized;
}