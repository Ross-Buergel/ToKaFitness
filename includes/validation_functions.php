<?php
function check_presence($item_name, $variable)
{
    if (isset($variable) and !empty($variable)) {

    }
    else{
        return "Please enter a " . $item_name;
    }
}

function check_contains_integer($item_name, $variable)
{
    //splits variable into characters
    $characters = str_split($variable);

    $contains_digit = False;

    foreach ($characters as $character) {
        if (is_int($character)) {
            $contains_digit = True;
        }
    }

    if (isset($contains_digit) && $contains_digit == True)
    {
        return $item_name . " cannot contain an integer";
    }
}

function check_length($item_name, $variable, $min_length, $max_length) {
    if (strlen($variable) > $max_length){
        return $item_name . " cannot be longer than " . $max_length . " characters";
    }

    else if (strlen($variable) < $min_length){
        return $item_name . " cannot be shorter than " . $min_length . " characters";
    }
}