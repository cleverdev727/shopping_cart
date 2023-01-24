<?php
use yii\helpers\Html;

use app\assets\AppAsset;
use app\components\CustomFunction;

AppAsset::register($this);
$getLanguage = CustomFunction::getGetLang();
$defaultLanguage = CustomFunction::getDefaultLang();
$language = CustomFunction::getLang();
$region = CustomFunction::getUserCountry() == "" ? "XX" : CustomFunction::getUserCountry();
$tempAry = explode('/', Yii::$app->request->url);
$selectedLanguage = strpos($tempAry[1], '.html') ? '' : $tempAry[1];
$selectedLanguage = $selectedLanguage ?: CustomFunction::getDomainLang();
$this->params['curLanguage'] = isset($this->params['curLanguage']) ? $this->params['curLanguage'] : $selectedLanguage;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?= Html::encode($this->title) ?></title>
        <meta charset="UTF-8">
        <meta name="language" content="<?= $language ?>" />
        <meta name="region" content="<?= $region ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/x-icon" href="/favicon.ico" />
        <?= Html::csrfMetaTags() ?>
        <?php $this->head() ?>
    </head>
    <body>
        
        <div class="container mt-3">
            <div class="row">
                <div class="col-7">

                </div>
                <div class="col-2">
                    <select class="form-select" id="language" name="language">
                        <!-- <option value="">Default</option> -->
                        <?php foreach($this->context->lang as $item){ ?>
                            <option value="<?= $item["lang_code"] ?>" <?= $this->params['curLanguage'] === $item["lang_code"] ? 'selected' : '' ?> ><?= $item["lang"] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <?php $this->beginBody() ?>
            <?= $content ?>
        <?php $this->endBody() ?>  
    </body>
    <script src="/plugin/jquery.cookie.min.js"></script>
    <script type="text/javascript">
        $("#language").change(function(){
            var currentUrl = "<?= Yii::$app->request->url ?>";
            console.log(currentUrl)
            if (currentUrl === '/' || !currentUrl.startsWith("/<?= $this->params['curLanguage'] ?>")) {
                currentUrl = '/' + $(this).val() + currentUrl;
            } else {
                currentUrl = currentUrl.replace("<?= $this->params['curLanguage'] ?>", $(this).val());
            }
            $.cookie('language', $(this).val(), { expires: 5 * 365, path: '/', sameSite: 'Lax', secure: true });
            window.location.href = currentUrl;
        })
        $(document).ready(function() {
            if ("<?= $this->params['curLanguage'] ?>" == '') {
                $.cookie('language', "<?= $defaultLanguage ?>", { expires: 5 * 365, path: '/', sameSite: 'Lax', secure: true });
            } else {
                $.cookie('language', "<?= $this->params['curLanguage'] ?>", { expires: 5 * 365, path: '/', sameSite: 'Lax', secure: true });
            }
        });
        // $(document).ready(function(){
        //     var currentUrl = "<?= Yii::$app->request->url ?>";
        //     var language = $.cookie('language');
        //     console.log(currentUrl, language, "<?= $getLanguage ?>", "<?= $defaultLanguage  ?>");
        //     if( "<?= $getLanguage ?>" !== language){
        //         $("#language").val("<?= $getLanguage ?>");
        //         if("<?= $getLanguage ?>" && ( "<?= $getLanguage ?>" !== "<?= $defaultLanguage  ?>")){
                
        //         }else{
        //             if(currentUrl.startsWith("/" + "<?= $getLanguage ?>" + "/")){
        //                 currentUrl = currentUrl.substr(3);
        //             }  
        //         }
        //         $.cookie('language', "<?= $getLanguage ?>", { expires: 5 * 365, path: '/', sameSite: 'Lax', secure: true });
        //         console.log(currentUrl);
        //         window.location.href = currentUrl;
        //     } else {
        //         console.log(language);
        //         $("#language").val(language);
        //     }
        // });
    </script>
</html>
<?php $this->endPage() ?>