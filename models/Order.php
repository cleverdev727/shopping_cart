<?php

namespace app\models;
use yii;
use yii\db\ActiveRecord;
use \yii\db\Expression;

class Order extends ActiveRecord
{
    public $num;
    
    public function beforeSave($insert)
    {
        if ($this->isNewRecord)
            $this->created_at = new Expression('NOW()');
        else
            $this->updated_at = new Expression('NOW()');
        return parent::beforeSave($insert);
    }

    public static function tableName(){
        return 'order';
    }

    public static function primaryKey(){
        return array('id');
    }

    public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'date' => 'Date',
			'order_status' => 'Order Status',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'street1' => 'Street1',
            'street2' => 'Street2',
            'zip' => 'Zip',
            'city' => 'City',
            'country' => 'Country',
            'payment_method' => 'Payment Method',
            'ua' => 'UA',
            'ip' => 'IP',
            'uoi' => 'Unique Order Identifier',
            'domain' => 'Domain',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
		);
	}
}