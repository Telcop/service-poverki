<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'name',
        'value'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public static function getTableName()
    {
        return ((new self)->getTale());
    }

    public static function getValue($name) 
    {
        $res = self::query()->where('name', $name)->get()->first();
        return $res ? $res['value'] : false;
    }

    public static function setValue($name, $value)
    {
        $res = self::query()->where('name', $name)->get()->first();
        if (!$res) {
            $res = new Settings;
            $res->name = $name;
        }
        $res->value = $value;
        $res->save();
        return $res;
    }

    public static function incrementNmberRequest(): string
    {
        $res = self::query()->where('name', 'NumberRequest')->get()->first();
        if (!$res) {
            $res = new Settings;
            $res->name = 'NumberRequest';
            $res->value = env('VERIFICATION_NUMBER_REQUEST_START', 0); 
        }
        $value = (int)$res->value ?? env('VERIFICATION_NUMBER_REQUEST_START', 0);
        $value++;
        $res->value = (string)$value;
        $res->save();
        return $value;
    }
}
