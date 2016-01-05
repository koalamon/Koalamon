<?php

namespace Bauer\IncidentDashboard\CoreBundle\Util;

class DateHelper
{
    static public function formatDate($time)
    {
        if ($time >= strtotime("today 00:00")) {
            return date("H:i", $time) . " Uhr";
        } elseif ($time >= strtotime("yesterday 00:00")) {
            return "Yesterday, " . date("H:i", $time) . " Uhr";
        } elseif ($time >= strtotime("-6 day 00:00")) {
            return date("l \\a\\t g:i A", $time);
        } else {
            return date("d.m.y", $time);
        }
    }

    static public function formatDuration($duration)
    {
        if ($duration > 1440) {
            return round($duration / 1440, 0) . " day(s)";
        }
        if ($duration > 120) {
            return round($duration / 60, 0) . " hour(s)";
        }
        return $duration . " minute(s)";
    }

    static public function toAmPm($twentyFourTime)
    {
        if ($twentyFourTime == 24) {
            $amPmHour = "12 am";
        } elseif ($twentyFourTime == 12) {
            $amPmHour = "12 pm";
        } elseif ($twentyFourTime < 12) {
            $amPmHour = $twentyFourTime . " am";
        } else {
            $amPmHour = $twentyFourTime - 12 . " pm";
        }

        return $amPmHour;
    }
}