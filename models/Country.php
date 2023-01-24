<?php

namespace app\models;
use yii;
use yii\db\ActiveRecord;
use \yii\db\Expression;

class Country extends ActiveRecord
{
    public static function tableName(){
        return 'country';
    }

    public static function primaryKey(){
        return array('country_id');
    }

    public function attributeLabels()
	{
		return array(
			'country_id' => 'Country ID',
			'name' => 'Name',
			'iso_code_2' => 'ISO Code 2',
			'iso_code_3' => 'ISO Code 3',
		);
	}
}