<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logging extends Model
{
    const ACTION_IN = "Вход в систему";
    const ACTION_OUT = "Выход из системы";
    const ACTION_IMPORT_INVOICES = "Импорт инвойсов";
    const ACTION_CREATE_INVOICE = "Создание инвойса";
    const ACTION_UPDATE_INVOICE_1 = "Редактирование инвойса на этапе внесения доставок";
    const ACTION_UPDATE_INVOICE_2 = "Редактирование инвойса на этапе подготовки заявок";
    const ACTION_DELETE_INVOICE = "Удаление инвойса";
    const ACTION_DELETE_INVOICES = "Удаление инвойсов";
    const ACTION_NEXT_STATUS = "Переход на следующий этап";
    const ACTION_PREV_STATUS = "Возврат к предыдущему этапу";
    const ACTION_UPDATE_POVERKI = "Редактирование поверки";
    const ACTION_CREATE_VENDOR = "Создание модели";
    const ACTION_UPDATE_VENDOR = "Редактирование модели";
    const ACTION_DELETE_VENDOR = "Удаление модели";
    const ACTION_CREATE_SUT = "Создание СУТ";
    const ACTION_UPDATE_SUT = "Редактирование СУТ";
    const ACTION_DELETE_SUT = "Удаление СУТ";

    protected $table = 'logging';

    protected $fillable = [
        'user',
        'action'
    ];

    public static function getTableName()
    {
        return ((new self)->getTale());
    }

    public static function setAction(string $user, string $action, $message = null)
    {
        $res = new Logging;
        $res->user = $user;
        $message_str = "";
        if (!empty($message))
            if (is_array($message))
                $message_str = self::arrayToString($message);
            else
                $message_str = $message; 
        $res->action = 'Action: ' . $action . $message_str;
        $res->save();
        return $res;
    }

    private static function arrayToString(Array $message)
    {
        $count = count($message);
        $is_associative = self::isAssociativeArray($message);
        $res = " (";
        foreach ($message as $key => $value) {
            if (is_array($value)){
                $count--;
                $key_item = $is_associative ? $key . ": " : ""; 
                $res .= $key_item . static::arrayToString($value);
                $res .= ($count != 0) ? ", " : "";
            } else {
                $count--;
                $key_item = $is_associative ? $key . ": " : ""; 
                $res .= $key_item . $value;
                $res .= ($count != 0) ? ", " : "";
            }
        }
        $res .= ")";
        return $res;
    }

    private static function isAssociativeArray($array) {
        if (!is_array($array)) {
            return false;
        }
        return array_keys($array) !== range(0, count($array) - 1);
    }
    

}
