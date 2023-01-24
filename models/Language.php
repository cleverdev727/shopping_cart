<?php

namespace app\models;
use \yii\db\ActiveRecord;
use \yii\web\IdentityInterface;
use \yii\db\Expression;

class Language extends ActiveRecord
{

    public static function tableName()
    {
        return 'language';
    }

    public function rules()
    {
        return [
            [['lang_code', 'lang'], 'required'],
            [['lang'], 'string', 'max'=>20],
            [['lang_code', 'lang'], 'unique'],
            ['lang_code', 'filter', 'filter'=>'strtolower'],
        ];
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord)
            $this->created_at = new Expression('NOW()');
        else
            $this->updated_at = new Expression('NOW()');
        return parent::beforeSave($insert);
    }

    public static function primaryKey(){
        return array('id');
    }

    public function attributeLabels()
    {
        return [
            'lang_code' => 'Language Code',
            'lang' => 'Language',
        ];
    }

}
