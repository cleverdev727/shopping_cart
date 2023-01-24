<?php

namespace app\controllers\admin;

use Yii;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use \yii\db\Expression;
use yii\data\Pagination;

use app\controllers\Controller;
use app\models\LoginForm;
use app\models\Order;
use app\models\User;
use app\models\Domain;
use app\models\Language;
use app\models\Page;
use app\models\PageForm;

class MainController extends Controller
{
    public $layout = '@vendor/hail812/yii2-adminlte3/src/views/layouts/main';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'dashboard', 'statistic', 'order', 'setting', 'page', 'lang', 'domain'],
                'rules' => [
                    [
                        'actions' => ['logout', 'dashboard', 'statistic', 'order', 'setting', 'page', 'lang', 'domain'],
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

    public function actionIndex(){
        $url = "";
        if(!\Yii::$app->user->isGuest){
            $url = "/admin/dashboard";
        }else{
            $url = "/admin/login"; 
        }
        return $this->redirect($url);
    }

    public function actionLogin(){

        $this->layout = '@vendor/hail812/yii2-adminlte3/src/views/layouts/main-login';

        if (!Yii::$app->user->isGuest) {
            return $this->redirect("/admin/dashboard");
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect("/admin/dashboard");
        }

        $model->password = '';
        return $this->render('@app/views/admin/login', ["model" => $model]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect("/admin/login");
    }

    public function actionDashboard(){
        return $this->render('@app/views/admin/dashboard');
    }

    public function actionOrder(){
        $getParams = [];
        if(isset($_GET)){
            foreach($_GET as $getKey => $getValue){
                $getParams[$getKey] = $getValue;
            }
        }
        $pageSizes = [5, 10, 15, 20];
        if(!empty($_GET['per-page'])){
            $pageSize = $_GET['per-page'];
        }else{
            $pageSize = $pageSizes[0];
        }
        $query = Order::find()->orderBy(['date' => SORT_DESC]);
        if(isset($_GET["from"]) || isset($_GET["to"])){
            if($_GET["from"] && $_GET["to"]){
                $query = $query
                ->where(['between', 'date', $getParams["from"], $getParams["to"]]);
            }else{
                if($_GET["from"]){
                    $query = $query->andFilterWhere(['>=', 'date', $getParams["from"]]);
                }
                if($_GET["to"]){
                    $query = $query->andFilterWhere(['<=', 'date', $getParams["to"]]);
                }
            }
            
            
        }
        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(), 
            'pageSize' => $pageSize, 
            'defaultPageSize' => $pageSizes[0],
            'params' => $getParams
        ]);
        $models = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('@app/views/admin/user',[
            'models' => $models, 'pagination' => $pagination, 'pageSizes' => $pageSizes]);
    }

    public function actionStatistic(){
        $data = [];
        $query = Order::find()->orderBy('date')->one();
        $minYear = date('Y', strtotime($query->date));
        $curYear = date('Y');
        if (isset($_GET["type"]) && $_GET["type"] !== "week") {
            if ($_GET["type"] === 'month') {
                $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                $selectedyear = $_GET["year"];
                for ($i = 0; $i < 12; $i ++) {
                    $temp = [
                        'day' => $months[$i]
                    ];
                    $startDate = date('Y-m-01', strtotime($selectedyear . '-' . ($i + 1)));
                    $endDate = date('Y-m-t', strtotime($selectedyear . '-' . ($i + 1)));
                    $rows = $this->getOrderDataByPeriod($startDate, $endDate);
                    foreach ($rows as $row) {
                        $temp[$row->order_status] = $row->num;
                    }
                    $data[] = $temp;
                }
            } else if ($_GET["type"] === 'year') {
                for ($i = $minYear; $i <= $curYear; $i ++) {
                    $temp = [
                        'day' => $i
                    ];
                    $startDate = $i . '-01-01';
                    $endDate = $i . '-12-31';
                    $rows = $this->getOrderDataByPeriod($startDate, $endDate);
                    foreach ($rows as $row) {
                        $temp[$row->order_status] = $row->num;
                    }
                    $data[] = $temp;
                }
            }
        } else {
            $dateVal = isset($_GET['date']) ? date('d-m-Y', strtotime($_GET['date'])) : date('d-m-Y');
            $dates = $this->week_from_monday($dateVal);
            $weeks = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            foreach($dates as $key => $date) {
                $temp = [
                    'day' => $weeks[$key] . ' ('. date('d.m.Y', strtotime($date)) .')'
                ];
                $rows = $this->getOrderDataByPeriod($date, $date);
                foreach ($rows as $row) {
                    $temp[$row->order_status] = $row->num;
                }
                $data[] = $temp;
            }
        }
        return $this->render('@app/views/admin/statistic',['data' => $data, 'startYear' => $minYear, 'curYear' => $curYear]);
    }

    private function week_from_monday($date) {
        // Assuming $date is in format DD-MM-YYYY
        list($day, $month, $year) = explode("-", $date);
    
        // Get the weekday of the given date
        $wkday = date('l',mktime('0','0','0', $month, $day, $year));
    
        switch($wkday) {
            case 'Monday': $numDaysToMon = 0; break;
            case 'Tuesday': $numDaysToMon = 1; break;
            case 'Wednesday': $numDaysToMon = 2; break;
            case 'Thursday': $numDaysToMon = 3; break;
            case 'Friday': $numDaysToMon = 4; break;
            case 'Saturday': $numDaysToMon = 5; break;
            case 'Sunday': $numDaysToMon = 6; break;   
        }
    
        // Timestamp of the monday for that week
        $monday = mktime('0','0','0', $month, $day-$numDaysToMon, $year);
    
        $seconds_in_a_day = 86400;
    
        // Get date for 7 days from Monday (inclusive)
        for($i=0; $i<7; $i++)
        {
            $dates[$i] = date('Y-m-d',$monday+($seconds_in_a_day*$i));
        }
    
        return $dates;
    }

    private function getOrderDataByPeriod($startDate, $endDate) {
        $rows = Order::find()->select('order_status, count(*) as num')->where(['between', 'date(date)', $startDate, $endDate])->groupBy('order_status')->all();
        return $rows;
    }

    public function actionSetting(){
        $getParams = [];
        if(isset($_GET)){
            foreach($_GET as $getKey => $getValue){
                $getParams[$getKey] = $getValue;
            }
        }
        $pageSizes = [5, 10, 15, 20];
        if(!empty($_GET['per-page'])){
            $pageSize = $_GET['per-page'];
        }else{
            $pageSize = $pageSizes[0];
        }
        $query = User::find()->orderBy(['created_at' => SORT_DESC]);
        if(isset($_GET["from"]) || isset($_GET["to"])){
            if($_GET["from"] && $_GET["to"]){
                $query = $query
                ->where(['between', 'created_at', $getParams["from"], $getParams["to"]]);
            }else{
                if($_GET["from"]){
                    $query = $query->andFilterWhere(['>=', 'created_at', $getParams["from"]]);
                }
                if($_GET["to"]){
                    $query = $query->andFilterWhere(['<=', 'created_at', $getParams["to"]]);
                }
            }
            
            
        }
        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(), 
            'pageSize' => $pageSize, 
            'defaultPageSize' => $pageSizes[0],
            'params' => $getParams
        ]);
        $models = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('@app/views/admin/setting',[
            'models' => $models, 'pagination' => $pagination, 'pageSizes' => $pageSizes]);
    }

    public function actionSettingEdit(){
        $model = User::find()->where(['id' => $_GET["id"] ])->one();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            return $this->redirect("/admin/settings");
        }
        return $this->render('@app/views/admin/setting_edit', ["model" => $model]);
    }

    public function actionDomain(){
        $domains = Domain::find()->all();
        return $this->render('@app/views/admin/domain', ["domains" => $domains]);
    }

    public function actionDomainCreate(){
        $languages = [];
        $language = Language::find()->select(["id", "lang_code", "lang"])->asArray()->all();
        foreach($language as $item){
            array_push($languages, [$item["id"] => $item["lang"]]);
        }
        $model = new Domain();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            return $this->redirect("/admin/domains");
        }
        return $this->render('@app/views/admin/domain_create', ["model" => $model, "languages" => $languages]);
    }

    public function actionDomainEdit(){
        $languages = [];
        $language = Language::find()->select(["id", "lang_code", "lang"])->asArray()->all();
        foreach($language as $item){
            array_push($languages, [$item["id"] => $item["lang"]]);
        }
        $model = Domain::find()->where(['id' => $_GET["id"] ])->one();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            return $this->redirect("/admin/domains");
        }
        return $this->render('@app/views/admin/domain_create', ["model" => $model, "languages" => $languages]);
    }

    public function actionDomainDelete(){
        $model = Domain::find()->where(['id' => $_GET["id"] ])->one();
        $model->delete();
        return $this->redirect("/admin/domains");
    }

    public function actionLang(){
        $lang = Language::find()->all();
        return $this->render('@app/views/admin/lang', ["lang" => $lang]);
    }

    public function actionLangCreate(){
        $model = new Language();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            $url = Yii::getAlias('@app/views/main');
            file_put_contents($url."/".$model->lang_code."-index.php", "");
            file_put_contents($url."/".$model->lang_code."-checkout.php", "");
            file_put_contents($url."/".$model->lang_code."-success.php", "");
            return $this->redirect("/admin/languages");
        }
        return $this->render('@app/views/admin/lang_create', ["model" => $model]);
    }

    public function actionLangEdit(){
        $model = Language::find()->where(['id' => $_GET["id"] ])->one();
        $pre_lang = $model->lang_code;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            if ($model->lang_code !== $pre_lang) {
                $url = Yii::getAlias('@app/views/main');
                $index = file_get_contents($url."/".$pre_lang."-index.php");
                $checkout = file_get_contents($url."/".$pre_lang."-checkout.php");
                $success = file_get_contents($url."/".$pre_lang."-success.php");
                file_put_contents($url."/".$model->lang_code."-index.php", $index);
                file_put_contents($url."/".$model->lang_code."-checkout.php", $checkout);
                file_put_contents($url."/".$model->lang_code."-success.php", $success);
                unlink($url."/".$pre_lang."-index.php");
                unlink($url."/".$pre_lang."-checkout.php");
                unlink($url."/".$pre_lang."-success.php");
            }
            return $this->redirect("/admin/languages");
        }
        return $this->render('@app/views/admin/lang_create', ["model" => $model]);
    }

    public function actionLangDelete(){
        $model = Language::find()->where(['id' => $_GET["id"] ])->one();
        $url = Yii::getAlias('@app/views/main');
        unlink($url."/".$model->lang_code."-index.php");
        unlink($url."/".$model->lang_code."-checkout.php");
        unlink($url."/".$model->lang_code."-success.php");
        $model->delete();
        return $this->redirect("/admin/languages");
    }

    public function actionPage(){
        $tmp = [];
        $url = Yii::getAlias('@app/views/main');
        $language = Language::find()->select(["lang_code", "lang"])->asArray()->all();
        foreach($language as $item){
            // array_push($tmp, [$item["lang_code"] => $item["lang"]]);
            $tmp[$item["lang_code"]] = $item["lang"];
        }
        $tmp['template'] = 'Create Template Page';
        $files = scandir($url);
        $templates = [];
        foreach ($files as $file) {
            strpos($file, 'template-') === 0 && $templates[$file] = $file;
        }
        $model = new PageForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $lang_code = Yii::$app->request->post("PageForm")["lang_code"];
            $file_name = Yii::$app->request->post("PageForm")["file_name"];
            $flag = isset(Yii::$app->request->post("PageForm")["flag"]) ? Yii::$app->request->post("PageForm")["flag"] : '';
            if ($lang_code !== "template" && $flag === "template") {
                list($prefix, $realFileName) = explode("template-", $file_name);
                $filePath = $url . "/" . $lang_code . "-" . $realFileName;
                copy($url . "/" . $file_name, $filePath);
            } else {
                $url = $url . "/" . $lang_code . "-" . $file_name . ".php";
                file_put_contents($url, "");
            }
            return $this->redirect("/admin/pages");
        }
        return $this->render('@app/views/admin/page', ["files" => $files, "templates" => $templates, "model" => $model, 'language' => $tmp]);
    }

    public function actionPageEdit(){
        $url = Yii::getAlias('@app/views/main') . "/" . $_GET['file'];
        $data = file_get_contents($url);
        $model = new Page();
        $model->content = htmlspecialchars($data,ENT_HTML5);
        $model->title = '';
        $model->description = '';
        $model->keywords = '';
        $file = file($url);
        $lines = max(0, count($file));
        $pattern1 = '~"name"=>"([^"]+)".*"content"=>"([^"]*)~i';
        $pattern2 = "~this->title\s*=\s*'([^\"]*)'~i";
        $temp = 0;
        if ($lines > 0) {
            for ($i = 0; $i < $lines; $i ++) {
                $line = $file[$i];
                // $line = '$this->registerMetaTag(["name"=>"description", "content"=>"description..."]);';
                if ($temp === 3) break;
                if (strpos($line, 'registerMetaTag') !== false) {
                    preg_match($pattern1, $line, $matches);
                    if (count($matches) > 0) {
                        $model[$matches[1]] = $matches[2];
                        $temp ++;
                    }
                } else if (strpos($line, 'this->title') !== false) {
                    preg_match($pattern2, $line, $matches);
                    if (count($matches) > 0) {
                        $model->title = $matches[1];
                        $temp ++;
                    }
                }
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $content = htmlspecialchars_decode(Yii::$app->request->post("Page")["content"]);
            file_put_contents($url,$content);
            
            $file = file($url);
            $lines = max(0, count($file));
            $phpLineNum = null;
            $flag = false;
            for ($i = 0; $i < $lines; $i ++) {
                $line = $file[$i];
                if (strpos($line, '<?php') !== false) {
                    $flag = true;
                    $phpLineNum === null && ($phpLineNum = $i);
                } else if (strpos($line, '?>') !== false) {
                    $flag = false;
                }
                if ($flag === true) {
                    if (strpos($line, 'registerMetaTag') !== false) {
                        preg_match($pattern1, $line, $matches);
                        if (count($matches) > 0) {
                            if ($matches[1] === 'description' || $matches[1] === 'keywords') {
                                unset($file[$i]);
                            }
                        }
                    } else if (strpos($line, 'this->title') !== false) {
                        preg_match($pattern2, $line, $matches);
                        if (count($matches) > 0) {
                            unset($file[$i]);
                        }
                    }
                }
            }
            file_put_contents($url, rtrim(implode("", $file)));
            $contentAry = file($url);
            $temp = 0;
            if ($phpLineNum === null) {
                $firstLine = "<?php" . PHP_EOL;
                array_splice($contentAry, 0, 0, $firstLine);
                $insertLineNum = 1;
            } else {
                $insertLineNum = $phpLineNum + 1;
            }
            if (Yii::$app->request->post("Page")["title"] !== '') {
                $title = '$this->title = \''. Yii::$app->request->post("Page")["title"] .'\';' . PHP_EOL;
                array_splice($contentAry, $insertLineNum, 0, $title);
                $temp ++;
            }
            if (Yii::$app->request->post("Page")["description"] !== '') {
                $description = '$this->registerMetaTag(["name"=>"description", "content"=>"'. Yii::$app->request->post("Page")["description"] .'"]);' . PHP_EOL;
                array_splice($contentAry, $insertLineNum, 0, $description);
                $temp ++;
            }
            if (Yii::$app->request->post("Page")["keywords"] !== '') {
                $keywords = '$this->registerMetaTag(["name"=>"keywords", "content"=>"'. Yii::$app->request->post("Page")["keywords"] .'"]);' . PHP_EOL;
                array_splice($contentAry, $insertLineNum, 0, $keywords);
                $temp ++;
            }
            if ($phpLineNum === null) {
                $endLine = "?>" . PHP_EOL;
                array_splice($contentAry, $insertLineNum + $temp, 0, $endLine);
            }
            $fileContent = implode($contentAry);
            file_put_contents($url, $fileContent);
            
            return $this->redirect("/admin/pages");
        }
        return $this->render('@app/views/admin/page_edit', ["model" => $model]);
    }

    public function actionPageDelete(){
        $url = Yii::getAlias('@app/views/main') . "/" . $_GET['file'];
        unlink($url);
        return $this->redirect("/admin/pages");
    }

    public function actionIsDefault(){
        $lang = Language::find()->all();
        foreach($lang as $item){
            if($item->id == $_GET["id"]){
                $item->is_default = 1;
            }else{
                $item->is_default = 0;
            }
            $item->save();
        }
        return $this->redirect("/admin/languages");
    }
}