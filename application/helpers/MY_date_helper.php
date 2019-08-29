<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function x_week_range($date)
{
    $ts = strtotime($date);
    $start = (date('w', $ts) == 1) ? $ts : strtotime('last Monday', $ts);

    return date('Y-m-d', $start);
}

function date_to_mysql($date = NULL)
{
    if(is_null($date)) {
        return date("Y-m-d H:i:s");
    }

    $datestring = date_to_unix($date);
    $datestring = date("Y-m-d H:i:s", $datestring);

    return $datestring;
}

function datetime_to_mysql($datetime)
{
    if(is_null($datetime) || $datetime == '' || $datetime == FALSE) {
        return FALSE;
    }

    $datetime_arr = explode(' ', $datetime);
    $date = $datetime_arr[0];
    $time = $datetime_arr[1];

    $datestring = uk_to_us_date($date) . ' ' . $time;
    $datestring = strtotime($datestring);
    $datestring = date("Y-m-d H:i:s", $datestring);

    return $datestring;
}

function date_to_unix($date)
{
    //check date is in correct format before processing
    $date_regex = '/(0[1-9]|[12][0-9]|3[01])[- \/.](0[1-9]|1[012])[- \/.](19|20)\d\d/';

    if(!preg_match($date_regex, $date)) {
        return NULL;
    }

    //convert to US date format for use with strtotime
    $uk_date_string = uk_to_us_date($date);

    $datestring = strtotime($uk_date_string);

    return $datestring;
}

function uk_to_us_date($date)
{
    $date_arr = explode('/', $date);
    $uk_date_string = $date_arr[1] . '/' . $date_arr[0] . '/' . $date_arr[2];

    return $uk_date_string;
}

function unix_to_mysql($date)
{
    $datestring = date("Y-m-d H:i:s", $date);

    return $datestring;
}

function week_of_month($unixtime)
{
    $week_of_the_month = ceil(date('d', $unixtime) / 7);

    return $week_of_the_month;
}