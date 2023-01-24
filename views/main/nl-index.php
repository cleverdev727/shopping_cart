<?php

/** @var yii\web\View $this */

use yii\captcha\Captcha;
use yii\web\View;

$this->title = 'Pay';

$this->registerCssFile("@web/plugin/toastr/toastr.css", [
    'depends' => [yii\bootstrap5\BootstrapAsset::class],
]);
$this->registerJsFile(
    "@web/plugin/toastr/toastr.js",
    ['depends' => [\yii\web\JqueryAsset::class]]
);
$this->registerJsFile(
    "@web/js/pay.js",
    ['depends' => [\yii\web\JqueryAsset::class]]
);
$this->registerJs(
    '
        $(document).ready(function(){
            var firstName = localStorage.getItem("first_name");
            var email = localStorage.getItem("email");
            $("#firstName").val(firstName);
            $("#email").val(email);
            pay.init();
        });
    ',
    View::POS_READY,
);
?>
<div class="container">
    <div class="row">
        <div class="col-3">

        </div>
        <div class="col-6">
        <h1>NL</h1>
            <div class="mt-5">
                <form>
                    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                    <div class="mb-3 mt-3">
                        <label for="firstName">First Name:</label>
                        <input type="text" class="form-control mt-3" id="firstName" placeholder="Enter First Name" name="first_name">
                    </div>
                    <div class="mb-3">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control mt-3" id="email" placeholder="Enter Email" name="email">
                    </div>
                    <?php if ($order_count >= 2): ?>
                    <div class="mb-3">
                        <label for="email">Verify Code:</label>
                        <?= 
                            Captcha::widget([
                                'captchaAction' => ['/main/captcha'],
                                'name' => 'captcha',
                                'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                            ]);
                        ?>
                    </div>
                    <?php endif ?>
                    <button type="button" id="buyNow" class="btn btn-primary">Buy Now</button>
                </form>
            </div>
        </div>
    </div>
</div>