<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = ['name', 'value', 'type'];

    public static $types = [
        'string' => 0,
        'integer' => 1,
        'float' => 2,
        'boolean' => 3,
        'array' => 4,
        'object' => 5,
        'null' => 6
    ];

    /**
     * Set a setting's value.
     *
     * @param array $name
     *
     * @return boolean
     */
    public static function has($name)
    {
        return static::whereName($name)->exists();
    }


    /**
     * Retrieve a setting value.
     *
     * @param array $name
     *
     * @return mixed
     */
    public static function get($name)
    {
        $setting = static::whereName($name)->get()->first();
        $type = static::unserializeType($setting->type);
        $value = unserialize($setting->value);

        settype($value, $type);
        return $value;
    }

    /**
     * Type to int
     *
     * @param $value
     *
     * @return mixed
     */
    protected static function serializeType($value)
    {
        $key = gettype($value);
        return static::$types[$key];
    }

    /**
     * Int to type
     *
     * @param $type
     *
     * @return mixed
     */
    protected static function unserializeType($type)
    {
        return array_search($type, static::$types);
    }

    /**
     * Set a setting's value.
     *
     * @param array $name
     */
    public static function set($name, $value)
    {
        $type = is_null($value) ? static::$types['null'] : static::serializeType($value);
        $value = serialize($value);

        return static::create([
            'name' => $name,
            'value' => $value,
            'type' => $type
        ]);
    }
}
