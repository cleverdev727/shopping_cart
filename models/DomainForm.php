<?php

namespace app\models;
use yii\base\Model;
use yii;
// use floor12\phone\PhoneValidator;

class DomainForm extends Model
{
    public $domin;
    public $language_id;

    public function rules()
    {
        return [
            [['domain', 'language_id'], 'required']
        ];
    }
}