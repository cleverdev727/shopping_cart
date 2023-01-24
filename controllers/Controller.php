<?php    
namespace app\controllers;

use app\models\Language;
use Yii;
class Controller extends \yii\web\Controller
{
    public $lang;
    public function init(){
        $this->lang = Language::find()->select(["lang_code", "lang"])->asArray()->all();
        // if(isset($_GET['language'])){
        //     $language = $_GET['language'];
        // }else{
        //     $language = "";
        // }
        
        parent::init();
        // $default_language = Language::find()->where(["is_default" => 1])->one();
        // if( $language == $default_language->lang_code){
        //     $url = substr(Yii::$app->request->url, 3);
        //     return $this->redirect($url);
        // }
        
    }

    public function getUserIP(){
        $ip = "";
        if(isset($_SERVER["HTTP_CF_CONNECTING_IP"])){
            $ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }else{
            $ip = $_SERVER["REMOTE_ADDR"];
        }
        return $ip;
    }

    public function getLang(){
        $lang = "";
        $default_language = Language::find()->where(["is_default" => 1])->one();
        $default_language = $default_language->lang_code;
        if(isset($_COOKIE["language"])){
            if($_COOKIE["language"]){
                $lang = $_COOKIE["language"];
            }else{
                $lang = $default_language;
            }
        }else{
            $lang = $default_language;
        }
        return $lang;
    }
}