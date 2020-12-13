<?php

/*
 * AVAILABLE VARIABLE :
 * - {field_name} is name of input field that was posted
 * - {value} is content after ":" from rules. It can be used only for min and max
 */

return [
    'required' => "{field_name} field is required",
    'email' => "{field_name} field must be email format",
    'url' => "{field_name} field is not URL",
    'min' => "{field_name} field at least have {value} characters",
    'max' => "{field_name} field can't have more than {value} characters",
];