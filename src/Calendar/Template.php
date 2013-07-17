<?php

namespace Calendar;

/**
 * Description of Template
 *
 * @author RRoble
 */
abstract class Template
{

    public static $calendar = 'BEGIN:VCALENDAR
PRODID:-//Google Inc//Google Calendar 70.9054//EN
VERSION:2.0
CALSCALE:GREGORIAN
METHOD:PUBLISH
X-WR-CALNAME:PSE Dividends calendar
X-WR-TIMEZONE:Asia/Manila
X-WR-CALDESC:
BEGIN:VTIMEZONE
TZID:Asia/Manila
X-LIC-LOCATION:Asia/Manila
BEGIN:STANDARD
TZOFFSETFROM:+0800
TZOFFSETTO:+0800
TZNAME:PHT
DTSTART:19700101T000000
END:STANDARD
END:VTIMEZONE
@eventsEND:VCALENDAR
';
    public static $event = 'BEGIN:VEVENT
DTSTART;VALUE=DATE:@dstart
DTEND;VALUE=DATE:@dend
RRULE:FREQ=;BYDAY=MO;UNTIL=@until
DTSTAMP:@stamp
UID:@uid
CREATED:@created
DESCRIPTION:@description
LAST-MODIFIED:@lastmodified
LOCATION:PSE
SEQUENCE:0
STATUS:CONFIRMED
SUMMARY:@summary
TRANSP:TRANSPARENT
CATEGORIES:http://schemas.google.com/g/2005#event
END:VEVENT
';

    public static function getEvent(array $values)
    {
        $tpl = static::$event;
        foreach ($values as $key => $value) {
            $tpl = str_replace($key, $value, $tpl);
        }
        return $tpl;
    }

    public static function getCalendar(array $values)
    {
        $tpl = static::$calendar;
        foreach ($values as $key => $value) {
            $tpl = str_replace($key, $value, $tpl);
        }
        return $tpl;
    }

}
