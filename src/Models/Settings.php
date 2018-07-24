<?php

namespace Grooveland\Settings\Models;

use Groovie\Core\Pages;
use Grooveland\Settings\Core;

class Settings extends BaseModel
{
    protected $fillable = [
        'name',
        'val',
        'type',
        'group'
    ];

    protected $hidden = [
        'model'
    ];

    /**
     * Add a settings value
     *
     * @param $name
     * @param $val
     * @param string $type | optional
     * @return bool
     */
    public static function add($group, $name, $val, $type = Core::DEFAULT_TYPE)
    {
        if ($id = static::exists($name)) {
            return self::edit($id, $group, $name, $val, $type);
        }

        $value = static::cast($val, $type);

        return self::create([
            'group' => $group,
            'name' => $name,
            'val' => $value,
            'type' => Core::type($val)
        ]) ? $val : false;
    }

    /**
     * Edit a settings by id
     * allow to change name, group, value and type
     *
     * @param $id
     * @param $group
     * @param $name
     * @param $val
     * @param string $type | optional
     * @return bool
     */
    public static function edit($id, $group, $name, $val, $type = Core::DEFAULT_TYPE)
    {
        $value = static::cast($val, $type);

        return self::where('id', $id)->update([
            'group' => $group,
            'name' => $name,
            'val' => $value,
            'type' => Core::type($val)
        ]) ? $val : false;
    }

    /**
     * Get settings by group or by group and name
     * if first is true return only first found setting
     *
     * @param string $group
     * @param string $name
     * @param boolean $first (false)
     * @return Settings | Array
     */
    public static function get(string $group, string $name = null, bool $first = false)
    {
        $where['group'] = $group;
        if (!is_null($name)) {
            $where['name'] = $name;
        }

        $data = self::where($where);
        
        if ($first) {
            $data = $data->first();
        } else {
            $data = $data->get();
        }

        if ($data instanceof \Grooveland\Settings\Models\Settings) {
            $data->val = self::cast($data->val, $data->type);
        } else {
            foreach ($data as $setting) {
                $setting->val = self::cast($setting->val, $setting->type);
            }
        }

        return $data;
    }

    /**
     * Get first settings by group or by group and name
     *
     * @param string $group
     * @param string $name
     * @return Settings | Array
     */
    public static function first(string $group, string $name = null)
    {
        return self::get($group, $name, true);
    }

    /**
     * Get first settings name
     *
     * @param string $name
     * @return Settings | Array
     */
    public static function one(string $name)
    {
        $data = self::where('name', $name)->first();

        if (!is_null($data)) {
            $data->val = self::cast($data->val, $data->type);
            static::schema($data);
        }

        return $data;
    }

    /**
     * Check if exists settings by name
     *
     * @param string $name
     * @return boolean
     */
    public static function exists($name)
    {
        $value = self::select('id')->where('name', $name)->first();
        return (!is_null($value))? $value->id : false;
    }

    /***********************************PRIVATE METHODS***********************************/

    private static function cast($val, $to)
    {
        return Core::cast($val, $to);
    }

    private static function schema(&$setting)
    {
        $schemas = Core::schema($setting->group);
        foreach ($schemas as $schema) {
            if (isset($schema->name) && $schema->name === $setting->name) {
                $setting->val = static::val($setting->val, $schema->type);
            }
        }
    }

    private static function val($value, $type)
    {
        $page = new Pages();
        if (method_exists($page, $type)) {
            $page->$type($value);
        }
        return $value;
    }
}
