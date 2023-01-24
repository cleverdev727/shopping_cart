<?php

namespace app\models;
use \yii\db\ActiveRecord;
use \yii\web\IdentityInterface;
use \yii\db\Expression;

class Domain extends ActiveRecord
{

    public static function tableName()
    {
        return 'domain';
    }

    public function rules()
    {
        return [
            [['domain', 'language_id'], 'required'],
            [['domain'], 'unique'],
            ['domain', 'filter', 'filter'=>'strtolower'],
        ];
    }

    public static function primaryKey(){
        return array('id');
    }

    public function attributeLabels()
    {
        return [
            'domain' => 'Domain',
            'language_id' => 'Language',
        ];
    }

    public function getLanguage() {
        return $this->hasOne(Language::class, ['id' => 'language_id']);
    }
}
