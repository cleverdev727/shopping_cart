<?php

namespace app\models;
use yii\base\Model;
use yii;
class BuyForm extends Model
{
    public $first_name;
    public $email;
    public $verifyCode;

    const SCENARIO_USUAL = 'usual';
    const SCENARIO_VERIFY_CODE = 'verify_code';
    /**
     * @return array the validation rules.
     */

    public function scenarios()
    {
        return [
            self::SCENARIO_USUAL => ['first_name', 'email'],
            self::SCENARIO_VERIFY_CODE => ['first_name', 'email', 'verifyCode'],
        ];
    }
    public function rules()
    {
        return [
            [['first_name', 'email'], 'required'],
            ['email', 'email'],
            [['verifyCode'], 'required', 'on' => self::SCENARIO_VERIFY_CODE],
            ['verifyCode', 'codeVerify'],
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
