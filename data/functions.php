<?php

// Time formating functions
function formatTime($time, $format = null)
{

    $time = (int) $time;
    if (!$format) {
        return date("M j, Y", $time) . ' at ' . date("H:m", $time);
    } elseif ($format == "ago") {
        return ago($time);
    } elseif ($format == "unix") {
        return $time;
    } elseif ($format == "fulldate") {
        return date('h:i a l, F j, Y', $time) ;//. ' at ' . date('h:ia', $time);
    }
}

function ago($time)
{
    $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
    $lengths = array("60", "60", "24", "7", "4.35", "12", "10");

    $now = time();

    $difference = $now - $time;
    $tense = "ago";

    for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
        $difference /= $lengths[$j];
    }

    $difference = round($difference);

    if ($difference != 1) {
        $periods[$j] .= "s";
    }

    return "$difference $periods[$j] ago ";
}

function addressString(Application\Entity\Address $address)
{
    $fullAddress = $address->getName() . ' | ';
    $fullAddress .= $address->getStreet() . ', ';
    $fullAddress .= $address->getCity() . ', ';
    $fullAddress .= $address->getPostal() . ', ';
    $fullAddress .= $address->getCountry();

    return $fullAddress;
}

//end time formating functions
function getInterval($startDate = null, $endDate = null)
{

    $interval = date_diff(date_create($startDate), date_create($endDate));

    if ($interval->d <= 1) {
        $range = array(
            'start' => 0,
            'end' => 23
        );
    } else {
        $range = array(
            'start' => 1,
            'end' => ($interval->d + 1)
        );
    }
    return $range;
}
