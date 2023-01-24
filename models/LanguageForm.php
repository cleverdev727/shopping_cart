<?php

namespace app\models;
use yii\base\Model;
use yii;
// use floor12\phone\PhoneValidator;

class LanguageForm extends Model
{
    public $lang_code;
    public $lang;

    public function rules()
    {
        return [
            [['lang_code', 'lang'], 'required'],
            [['lang'], 'string', 'max'=>20],
        ];
    }
}