<?php
namespace MaspostAPI\Helpers;

class Date {

    static function isWeekend($date) {
        return (date('N', strtotime($date)) >= 6);
    }
}
