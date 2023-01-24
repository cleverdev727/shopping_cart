<?php

namespace app\controllers;

use Yii;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use \yii\db\Expression;


use app\controllers\Controller;
use app\models\BuyForm;
use app\models\CheckoutForm;
use app\models\Domain;
use app\models\Language;
use app\models\Order;
use app\components\CustomFunction;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;

class MainController extends Controller
{
    public $layout = 'layout';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex($language = null){
        $ip = $this->getUserIP();
        $order_count = Order::find()->where(["ip" => $ip, 'order_status' => 'WaitingApproval'])->count();
        if(Yii::$app->request->isPost){
            $ua = Yii::$app->request->getUserAgent();
            $result = array();
            $data = Yii::$app->request->post();
            $model = new BuyForm();
            $model->first_name = $data['first_name'];
            $model->email = $data['email'];
            if($order_count >= 2){
                $model->verifyCode = $data['verifyCode'];
                $model->scenario = 'verify_code';
            }else{
                $model->scenario = 'usual';
            }
            if( $model->validate() ){
                if($data["order_id"]){
                    $order = Order::findOne(base64_decode($data["order_id"]));
                }else{
                    $order = new Order;
                }
                $order->date = new Expression('NOW()');
                $order->order_status = "Uncompleted";
                $order->first_name = $data['first_name'];
                $order->email = $data['email'];
                $language = $language !== null ? $language : $this->getLanguage($language);
                $language === 'cookie' && ($language = $_COOKIE['language']);
                $order->lang = $language;
                $order->ua = $ua;
                $order->ip = $ip;
                $order->domain = $_SERVER['HTTP_HOST'];
                $order->save();
                $result["status"] = "success";
                $result["order_id"] = base64_encode($order->id);
            }else{
                $result["status"] = "fail";
                $result["message"] = $model->errors;
            }
            $cookies = Yii::$app->response->cookies;
            $cookies->add(new \yii\web\Cookie([
                'name' => 'first_name',
                'value' => $data['first_name'], 
                'secure' => true,  
            ]));
            $cookies->add(new \yii\web\Cookie([
                'name' => 'email',
                'value' => $data['email'],
                'secure' => true,
            ]));
            return json_encode($result);
        }else{
            // $this->layout = false;
            $language = $this->getLanguage($language);
            if ($language === '') {
                return $this->redirect('/');
            } else if ($language === 'cookie') {
                return $this->redirect('/' . $_COOKIE["language"]);
            }
            $metaData = $this->getMetaData(Yii::getAlias('@app/views/main') . "/" . $language."-index.php");
            $this->view->params['metaTitle'] = isset($metaData['title']) ? $metaData['title'] : '';
            $this->view->params['metaDescription'] = isset($metaData['description']) ? $metaData['description'] : '';
            $this->view->params['metaKeyword'] = isset($metaData['keyword']) ? $metaData['keyword'] : '';
            $this->view->params['curLanguage'] = $language;
            return $this->render($language."-index", ["order_count" => $order_count]);
        }        
    }

    public function actionCheckout($language = null){
        $ip = $this->getUserIP();
        $order_count = Order::find()->where(["ip" => $ip, 'order_status' => 'WaitingApproval'])->count();
        if(Yii::$app->request->isPost){
            $ua = Yii::$app->request->getUserAgent();
            $result = array();
            $data = Yii::$app->request->post();
            $model = new CheckoutForm();
            $model->first_name = $data['first_name'];
            $model->last_name = $data['last_name'];
            $model->email = $data['email'];
            $model->phone = $data['phone'];
            $model->street1 = $data['street1'];
            $model->street2 = $data['street2'];
            $model->city = $data['city'];
            $model->zip = $data['zip'];
            $model->country = $data['country'];
            if($order_count >= 2){
                $model->verifyCode = $data['verifyCode'];
                $model->scenario = 'verify_code';
            }else{
                $model->scenario = 'usual';
            }
            // $model->payment_method = $data['payment_method'];
            if( $model->validate() ){
                if($data["order_id"]){
                    $order = Order::findOne(base64_decode($data["order_id"]));
                }else{
                    $order = new Order;
                }
                $order->date = new Expression('NOW()');
                $order->order_status = "WaitingApproval";
                $order->first_name = $data['first_name'];
                $order->last_name = $data['last_name'];
                $order->email = $data['email'];
                $order->phone = $data['phone'];
                $order->street1 = $data['street1'];
                $order->street2 = $data['street2'];
                $order->zip = $data['zip'];
                $order->city = $data['city'];
                $order->country = $data['country'];
                $language = $language !== null ? $language : $this->getLanguage($language);
                $language === 'cookie' && ($language = $_COOKIE['language']);
                $order->lang = $language;
                // $order->payment_method = $data['payment_method'];
                $order->ua = $ua;
                $order->ip = $ip;
                $order->domain = $_SERVER['HTTP_HOST'];
                $order->save();
                $result["status"] = "success";
                $result["order_id"] = base64_encode($order->id);
            }else{
                $result["status"] = "fail";
                $result["message"] = $model->errors;
            }
            return json_encode($result);
        }else{
            // $country_data = Country::find()->select(["country_id", "name"])->asArray()->all();
            $language = $this->getLanguage($language);
            if ($language === '') {
                return $this->redirect('/checkout.html');
            } else if ($language === 'cookie') {
                return $this->redirect('/' . $_COOKIE["language"] . '/checkout.html');
            }
            $metaData = $this->getMetaData(Yii::getAlias('@app/views/main') . "/" . $language."-checkout.php");
            $this->view->params['metaTitle'] = isset($metaData['title']) ? $metaData['title'] : '';
            $this->view->params['metaDescription'] = isset($metaData['description']) ? $metaData['description'] : '';
            $this->view->params['metaKeyword'] = isset($metaData['keyword']) ? $metaData['keyword'] : '';
            $this->view->params['curLanguage'] = $language;

            return $this->render($language."-checkout", ['order_count'=>$order_count]);
        }
    }

    public function actionSuccess($language = null){
        $language = $this->getLanguage($language);
        if ($language === '') {
            return $this->redirect('/success.html');
        } else if ($language === 'cookie') {
            return $this->redirect('/' . $_COOKIE["language"] . '/success.html');
        }
        $metaData = $this->getMetaData(Yii::getAlias('@app/views/main') . "/" . $language."-success.php");
        $this->view->params['metaTitle'] = isset($metaData['title']) ? $metaData['title'] : '';
        $this->view->params['metaDescription'] = isset($metaData['description']) ? $metaData['description'] : '';
        $this->view->params['metaKeyword'] = isset($metaData['keyword']) ? $metaData['keyword'] : '';
        $this->view->params['curLanguage'] = $language;
        
        return $this->render($language."-success");
    }
    public function actionView($page, $language = null){
        $ip = $this->getUserIP();
        $order_count = Order::find()->where(["ip" => $ip, 'order_status' => 'WaitingApproval'])->count();
        if(Yii::$app->request->isPost){
            $ua = Yii::$app->request->getUserAgent();
            $result = array();
            $data = Yii::$app->request->post();
            $model = new CheckoutForm();
            $model->first_name = $data['first_name'];
            $model->last_name = $data['last_name'];
            $model->email = $data['email'];
            $model->phone = $data['phone'];
            $model->street1 = $data['street1'];
            $model->street2 = $data['street2'];
            $model->city = $data['city'];
            $model->zip = $data['zip'];
            $model->country = $data['country'];
            if($order_count >= 2){
                $model->verifyCode = $data['verifyCode'];
                $model->scenario = 'verify_code';
            }else{
                $model->scenario = 'usual';
            }
            // $model->payment_method = $data['payment_method'];
            if( $model->validate() ){
                if($data["order_id"]){
                    $order = Order::findOne(base64_decode($data["order_id"]));
                }else{
                    $order = new Order;
                }
                $order->date = new Expression('NOW()');
                $order->order_status = "WaitingApproval";
                $order->first_name = $data['first_name'];
                $order->last_name = $data['last_name'];
                $order->email = $data['email'];
                $order->phone = $data['phone'];
                $order->street1 = $data['street1'];
                $order->street2 = $data['street2'];
                $order->zip = $data['zip'];
                $order->city = $data['city'];
                $order->country = $data['country'];
                $language = $language !== null ? $language : $this->getLanguage($language);
                $language === 'cookie' && ($language = $_COOKIE['language']);
                $order->lang = $language;
                // $order->payment_method = $data['payment_method'];
                $order->ua = $ua;
                $order->ip = $ip;
                $order->domain = $_SERVER['HTTP_HOST'];
                $order->save();
                $result["status"] = "success";
                $result["order_id"] = base64_encode($order->id);
            }else{
                $result["status"] = "fail";
                $result["message"] = $model->errors;
            }
            return json_encode($result);
        }else{
            // $country_data = Country::find()->select(["country_id", "name"])->asArray()->all();
            
            $language = $this->getLanguage($language);
            if ($language === '') {
                return $this->redirect('/' . $page . '.html');
            } else if ($language === 'cookie') {
                return $this->redirect('/' . $_COOKIE["language"] . '/' . $page . '.html');
            }
            $metaData = $this->getMetaData(Yii::getAlias('@app/views/main') . "/" . $language."-". $page .".php");
            $this->view->params['metaTitle'] = isset($metaData['title']) ? $metaData['title'] : '';
            $this->view->params['metaDescription'] = isset($metaData['description']) ? $metaData['description'] : '';
            $this->view->params['metaKeyword'] = isset($metaData['keyword']) ? $metaData['keyword'] : '';
            $this->view->params['curLanguage'] = $language;
            return $this->render($language."-" . $page, ['order_count'=>$order_count]);

        }
    }

    private function getLanguage($language) {
        $default_language = CustomFunction::getDefaultLang();
        $domainLanguage = CustomFunction::getDomainLang();
        if ($language === null) {
            if (isset(Yii::$app->session['status']) && Yii::$app->session['status'] !== '') {
                if (Yii::$app->session['status'] === 'domain') {
                    $language = $domainLanguage;
                } else if (Yii::$app->session['status'] === 'default') {
                    $language = $default_language;
                }
            }else if (isset($_COOKIE["language"]) && $_COOKIE["language"] !== "") {
                if ($_COOKIE["language"] === $domainLanguage) {
                    $language = $domainLanguage;
                } else {
                    if ($_COOKIE["language"] === $default_language && $domainLanguage === '') {
                        $language = $default_language;
                    } else {
                        $language = 'cookie';
                    }
                }
            } else {
                $language = $domainLanguage ? $domainLanguage : $default_language;
            }
            Yii::$app->session['status'] = '';
        } else if ($language === $domainLanguage) {
            Yii::$app->session['status'] = 'domain';
            $language = '';
        } else if ($domainLanguage === '' && $language === $default_language) {
            Yii::$app->session['status'] = 'default';
            $language = '';
        }
        return $language;
    }

    private function getMetaData($url) {
        $metaData = [];
        $file = file($url);
        $readLines = max(0, count($file) - 3);
        if ($readLines > 0) {
            $pattern = '~<\s*meta\b.*\bname="([^"]+)"\s.*\bcontent="([^"]*)~i';
            for ($i = $readLines; $i < count($file); $i ++) {
                $line = $file[$i];
                preg_match($pattern, $line, $matches);
                if (count($matches) > 0) {
                    $metaData[$matches[1]] = $matches[2];
                }
            }
        }
        return $metaData;
    }

    // public function actionHome($language = null){
    //     $default_language = CustomFunction::getDefaultLang();
    //     if ($language !== null) {
    //         $url = "/" . $language . "/index.html";
    //     } else {
    //         if(isset($_COOKIE["language"])){
    //             if($_COOKIE["language"] && ($_COOKIE["language"] !== $default_language)){
    //                 $url = "/" . $_COOKIE["language"] . "/index.html";
    //             }else{
    //                 $url = "/index.html";
    //             }
                
    //         }else{
    //             $url = "/index.html";
    //         }
    //     }
    //     return $this->redirect($url);
    // }
}