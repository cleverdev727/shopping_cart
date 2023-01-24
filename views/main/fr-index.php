&amp;amp;lt;?php

/** @var yii\web\View $this */

use yii\captcha\Captcha;
use yii\web\View;

$this-&amp;amp;gt;title = &#039;Pay&#039;;

$this-&amp;amp;gt;registerCssFile(&quot;@web/plugin/toastr/toastr.css&quot;, [
    &#039;depends&#039; =&amp;amp;gt; [yii\bootstrap5\BootstrapAsset::class],
]);
$this-&amp;amp;gt;registerJsFile(
    &quot;@web/plugin/toastr/toastr.js&quot;,
    [&#039;depends&#039; =&amp;amp;gt; [\yii\web\JqueryAsset::class]]
);
$this-&amp;amp;gt;registerJsFile(
    &quot;@web/js/pay.js&quot;,
    [&#039;depends&#039; =&amp;amp;gt; [\yii\web\JqueryAsset::class]]
);
$this-&amp;amp;gt;registerJs(
    &#039;
        $(document).ready(function(){
            var firstName = localStorage.getItem(&quot;first_name&quot;);
            var email = localStorage.getItem(&quot;email&quot;);
            $(&quot;#firstName&quot;).val(firstName);
            $(&quot;#email&quot;).val(email);
            pay.init();
        });
    &#039;,
    View::POS_READY,
);
?&amp;amp;gt;
&amp;amp;lt;div class=&quot;container&quot;&amp;amp;gt;
    &amp;amp;lt;div class=&quot;row&quot;&amp;amp;gt;
        &amp;amp;lt;div class=&quot;col-3&quot;&amp;amp;gt;

        &amp;amp;lt;/div&amp;amp;gt;
        &amp;amp;lt;div class=&quot;col-6&quot;&amp;amp;gt;
        &amp;amp;lt;h1&amp;amp;gt;EN&amp;amp;lt;/h1&amp;amp;gt;
            &amp;amp;lt;div class=&quot;mt-5&quot;&amp;amp;gt;
                &amp;amp;lt;form&amp;amp;gt;
                    &amp;amp;lt;input type=&quot;hidden&quot; name=&quot;&amp;amp;lt;?= Yii::$app-&amp;amp;gt;request-&amp;amp;gt;csrfParam; ?&amp;amp;gt;&quot; value=&quot;&amp;amp;lt;?= Yii::$app-&amp;amp;gt;request-&amp;amp;gt;csrfToken; ?&amp;amp;gt;&quot; /&amp;amp;gt;
                    &amp;amp;lt;div class=&quot;mb-3 mt-3&quot;&amp;amp;gt;
                        &amp;amp;lt;label for=&quot;firstName&quot;&amp;amp;gt;First Name:&amp;amp;lt;/label&amp;amp;gt;
                        &amp;amp;lt;input type=&quot;text&quot; class=&quot;form-control mt-3&quot; id=&quot;firstName&quot; placeholder=&quot;Enter First Name&quot; name=&quot;first_name&quot;&amp;amp;gt;
                    &amp;amp;lt;/div&amp;amp;gt;
                    &amp;amp;lt;div class=&quot;mb-3&quot;&amp;amp;gt;
                        &amp;amp;lt;label for=&quot;email&quot;&amp;amp;gt;Email:&amp;amp;lt;/label&amp;amp;gt;
                        &amp;amp;lt;input type=&quot;email&quot; class=&quot;form-control mt-3&quot; id=&quot;email&quot; placeholder=&quot;Enter Email&quot; name=&quot;email&quot;&amp;amp;gt;
                    &amp;amp;lt;/div&amp;amp;gt;
                    &amp;amp;lt;?php if ($order_count &amp;amp;gt;= 2): ?&amp;amp;gt;
                    &amp;amp;lt;div class=&quot;mb-3&quot;&amp;amp;gt;
                        &amp;amp;lt;label for=&quot;email&quot;&amp;amp;gt;Verify Code:&amp;amp;lt;/label&amp;amp;gt;
                        &amp;amp;lt;?= 
                            Captcha::widget([
                                &#039;captchaAction&#039; =&amp;amp;gt; [&#039;/main/captcha&#039;],
                                &#039;name&#039; =&amp;amp;gt; &#039;captcha&#039;,
                                &#039;template&#039; =&amp;amp;gt; &#039;&amp;amp;lt;div class=&quot;row&quot;&amp;amp;gt;&amp;amp;lt;div class=&quot;col-lg-3&quot;&amp;amp;gt;{image}&amp;amp;lt;/div&amp;amp;gt;&amp;amp;lt;div class=&quot;col-lg-6&quot;&amp;amp;gt;{input}&amp;amp;lt;/div&amp;amp;gt;&amp;amp;lt;/div&amp;amp;gt;&#039;,
                            ]);
                        ?&amp;amp;gt;
                    &amp;amp;lt;/div&amp;amp;gt;
                    &amp;amp;lt;?php endif ?&amp;amp;gt;
                    &amp;amp;lt;button type=&quot;button&quot; id=&quot;buyNow&quot; class=&quot;btn btn-primary&quot;&amp;amp;gt;Buy Now&amp;amp;lt;/button&amp;amp;gt;
                &amp;amp;lt;/form&amp;amp;gt;
            &amp;amp;lt;/div&amp;amp;gt;
        &amp;amp;lt;/div&amp;amp;gt;
    &amp;amp;lt;/div&amp;amp;gt;
&amp;amp;lt;/div&amp;amp;gt;