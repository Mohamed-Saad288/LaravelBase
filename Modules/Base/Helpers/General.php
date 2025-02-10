<?php


// convert string to int
use Illuminate\Support\Facades\Hash;


function convertToInt($value)
{
    return intval($value);
}

/**
 * Undocumented function
 *
 * @param [type] $password
 *
 */
function hash_password($password)
{
    return Hash::make($password);
}

function ConvertToArray($value) : array
{
    $value = is_array($value) ? $value : [$value];
    return $value;
}



