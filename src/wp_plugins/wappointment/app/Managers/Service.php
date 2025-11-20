<?php

namespace Wappointment\Managers;

class Service
{
    public static function model()
    {
        return \Wappointment\Managers\Central::get('ServiceModel');
    }
    public static function all()
    {
        return \Wappointment\Managers\Central::get('Service')::all();
    }
    public static function save($data)
    {
        return \Wappointment\Managers\Central::get('Service')::saveService($data);
    }
    public static function patch($service_id, $data)
    {
        return \Wappointment\Managers\Central::get('Service')::patch($service_id, $data);
    }
    public static function hasZoom($service)
    {
        if (!\method_exists(\Wappointment\Managers\Central::get('Service'), 'hasZoom')) {
            return \false;
        }
        return \Wappointment\Managers\Central::get('Service')::hasZoom($service);
    }
    public static function extractDurations($services)
    {
        if (\is_array($services)) {
            $services = \WappointmentLv::collect($services);
        }
        //'durations' => [Service::get()['duration']],
        if (\count($services) == 1 && !empty($services[0]['duration'])) {
            return [$services[0]['duration']];
        }
        $durations = $services->map(function ($item, $key) {
            $innerdur = [];
            foreach ($item['options']['durations'] as $key => $array) {
                $innerdur[] = $array['duration'];
            }
            return $innerdur;
        });
        $durations_filtered = \array_filter($durations->flatten()->unique()->toArray());
        \sort($durations_filtered);
        return $durations_filtered;
    }
}
