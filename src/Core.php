<?php

namespace  Grooveland\Settings;

class Core
{
    const TYPES = [
        'string',
        'boolean',
        'integer',
        'double',
        'array',
        'relation'
    ];

    const DEFAULT_TYPE = 'string';

    public static function groups()
    {
        $settings = config('groovie');
        $result = [];
        foreach ($settings as $setting => $value) {
            array_push($result, $setting);
        }
        return $result;
    }

    public static function type($data): string
    {
        $type = gettype($data);
        
        if (in_array($type, static::TYPES)) {
            return $type;
        }
    }
    
    public static function cast($data, $to)
    {
        $from = gettype($data);
        if (method_exists(Core::class, $to)) {
            return Core::$to($data, $from);
        }
        return $data;
    }


    protected function getSchema(string $group): array
    {
        $configs = config("groovie.$group.settings");
        if (is_array($configs)) {
            return \Jasny\objectify($configs);
        }
        return [];
    }


    private static function string($data, $type): string
    {
        $result = '';

        switch ($type) {
            case 'array':
                $result = json_encode($data);
                break;
            default:
                $result = $data;
                break;
        }

        return $result;
    }

    private static function boolean($data, $type): boo
    {
        return boolval($data);
    }

    private static function integer($data, $type): int
    {
        return intval($data);
    }

    private static function double($data, $type): double
    {
        return doubleval($data);
    }

    private static function array($data, $type): array
    {
        $array = json_decode($data, true);
        return is_array($array)? $array : [];
    }


    public static function schema(string $group): array
    {
        $settings = new Settings();
        return $settings->getSchema($group);
    }
}
