<?php

namespace app\models;
use yii\base\Model;
use yii;
// use floor12\phone\PhoneValidator;

class CheckoutForm extends Model
{
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $street1;
    public $street2;
    public $zip;
    public $country;
    public $city;
    public $payment_method;
    public $verifyCode;

    const SCENARIO_USUAL = 'usual';
    const SCENARIO_VERIFY_CODE = 'verify_code';

    public function scenarios()
    {
        return [
            self::SCENARIO_USUAL => ['first_name', 'last_name', 'email', 'phone', 'street1', 'street2', 'zip', 'city', 'country'],
            self::SCENARIO_VERIFY_CODE => ['first_name', 'last_name', 'email', 'phone', 'street1', 'street2', 'zip', 'city', 'country', 'verifyCode'],
        ];
    }

    public function rules()
    {
        return [
            [['first_name', 'last_name', 'email', 'phone', 'street1', 'street2', 'zip', 'city', 'country'], 'required'],
            [['first_name', 'last_name', 'email', 'phone', 'street1', 'street2', 'zip', 'city', 'country'], 'string', 'max'=>255],
            ['email', 'email'],
            [['verifyCode'], 'required', 'on' => self::SCENARIO_VERIFY_CODE],
            ['verifyCode', 'codeVerify'],
            // ['phone', PhoneValidator::class]
        ];
    }

    public function codeVerify($attribute) {
        $captcha_validate  = new \yii\captcha\CaptchaAction('captcha',Yii::$app->controller);
        if($this->$attribute){
            $code = $captcha_validate->getVerifyCode();
            if($this->$attribute!=$code){
                $this->addError($attribute, 'The verification code is incorrect.');
            }
        }
    }
}