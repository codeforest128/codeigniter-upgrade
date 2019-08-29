<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function create_booking_meta_array($startdate)
{
    $default_booking = array(
        'booking_date' => $startdate,
        'repeat_interval' => NULL,
        'repeat_end' => NULL,
        'repeat_year' => NULL,
        'repeat_month' => NULL,
        'repeat_month_interval' => NULL,
        'repeat_week_im' => NULL,
        'repeat_weekday' => NULL,
    );

    return $default_booking;
}

function create_hour_array($required = TRUE)
{
    $hours_options = array();

    if(!$required) {
        $hours_options[''] = '';
    }

    for($i = 0; $i <= 23; $i++) {
        $hour = $i;
        $hour = $hour < 10 ? '0' . $hour : $hour;
        $hours_options[$hour] = $hour;
    }

    return $hours_options;
}

function create_minute_array($required = TRUE)
{
    $minute_options = array(
        '00' => '00',
        '05' => '05',
        '10' => '10',
        '15' => '15',
        '20' => '20',
        '25' => '25',
        '30' => '30',
        '35' => '35',
        '40' => '40',
        '45' => '45',
        '50' => '50',
        '55' => '55'
    );

    if(!$required) {
        $minute_options[''] = '';
    }

    return $minute_options;
}

function calculate_date_for_weekday_in_repeat($booking_startdate, $weekday)
{
    $start_weekday = date('N', $booking_startdate);
    $startdate = $booking_startdate;

    if($start_weekday != $weekday) {
        $day_difference = (int)$weekday - (int)$start_weekday;

        if($day_difference < 0) {
            $day_difference = $day_difference + 7;
        }

        $startdate = strtotime(sprintf('+ %s days', $day_difference), $booking_startdate);
    }

    return $startdate;
}

function calculate_months_for_repeat($starting_month, $no_months)
{
    $months = $starting_month . '|';
    for($i = 1; $i <= $no_months; $i++) {
        $month = (int)$starting_month + $i;

        // if we go beyond december, loop back round to january
        if($month > 12) {
            $month = $month - 12;
        }

        $months .= $month . '|';
    }

    return $months;
}

function calculate_years_for_repeat($starting_year, $no_years, $year_end)
{
    $years = $starting_year . '|';

    $year = (int)$starting_year;

    while($year < (int)$year_end) {
        $year = $year + (int)$no_years;
        $years .= $year . '|';
    }

    return $years;
}

function calculate_booking_end_after($start_date, $occurences, $repeats, $repeats_every) {
    //subtract 1 so that the first occurence is the start date, not x occurences after start date
    $occurences = (int)$occurences - 1;

    $duration = (int)$occurences * (int)$repeats_every;

    if($repeats == 'daily') {
        $adjustment =  'days';
    }
    elseif($repeats == 'weekly') {
        $adjustment = 'weeks';
    }
    elseif($repeats == 'monthly') {
        $adjustment = 'months';
    }
    elseif($repeats == 'yearly') {
        $adjustment = 'years';
    }

    $repeat_end = strtotime(sprintf('+ %s %s', (string)$duration, $adjustment), $start_date);

    return $repeat_end;
}

function calculate_booking_end_on($end_date) {
    $repeat_end = date_to_unix($end_date);

    return $repeat_end;
}