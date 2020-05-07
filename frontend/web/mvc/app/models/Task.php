<?php


namespace app\models;


use core\Model;

class Task extends Model
{
    public $userName;
    public $email;
    public $text;
    public $isDone;

    public function rules()
    {
        return [
            'userName' => [
                [
                    'method' => 'required',
                ],
                [
                    'method' => FILTER_CALLBACK,
                    'callback' => static function ($value) {
                        return strip_tags($value);
                    }
                ],
            ],
            'email' => [
                [
                    'method' => 'required',
                ],
                [
                    'method' => FILTER_VALIDATE_EMAIL,
                    'message' => 'Not valid email',
                ],
            ],
            'text' => [
                [
                    'method' => 'required',
                ],
            ],
            'isDone' => [
                [
                    'method' => FILTER_CALLBACK,
                    'callback' => static function ($value) {
                        return (bool) $value;
                    }
                ],
                [
                    'method' => FILTER_VALIDATE_BOOLEAN,
                    'message' => 'Completed should be boolean',
                ],
            ],
        ];
    }
}
