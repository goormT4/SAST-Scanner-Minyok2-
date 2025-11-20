<?php

namespace Wappointment\Repositories;

use Wappointment\Managers\Central;
use Wappointment\WP\Staff;
class CalendarsBack extends \Wappointment\Repositories\AbstractRepository
{
    use \Wappointment\Repositories\MustRefreshAvailability;
    public $cache_key = 'calendars_back';
    public function query()
    {
        $calendarsQry = Central::get('CalendarModel')::orderBy('sorting')->with(['services']);
        $calendars = $calendarsQry->fetch();
        $staffs = [];
        foreach ($calendars->toArray() as $calendar) {
            $staffs[] = (new Staff($calendar))->fullData();
        }
        $this->refreshAvailability();
        return $staffs;
    }
    public static function findById($id)
    {
        static $repository = \false;
        if ($repository === \false) {
            $repository = new static();
        }
        return \WappointmentLv::collect($repository->get())->filter(function ($calendar) use($id) {
            return (int) $calendar['id'] === (int) $id;
        })->toArray()[0];
    }
}
