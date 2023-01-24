<?php

use yii\captcha\Captcha;
use yii\web\View;

$this->title = 'Pay';
$this->registerCssFile("@web/css/flag-icon.min.css", [
    'depends' => [yii\bootstrap5\BootstrapAsset::class],
]);

$this->registerCssFile("@web/plugin/toastr/toastr.css", [
    'depends' => [yii\bootstrap5\BootstrapAsset::class],
]);
$this->registerJsFile(
    "@web/plugin/toastr/toastr.js",
    ['depends' => [\yii\web\JqueryAsset::class]]
);
$this->registerCssFile("@web/plugin/country_select2/select2.css", [
    'depends' => [yii\bootstrap5\BootstrapAsset::class],
]);
$this->registerJsFile(
    "@web/plugin/country_select2/select2.js",
    ['depends' => [\yii\web\JqueryAsset::class]]
);
$this->registerJsFile(
    "@web/js/checkout.js",
    ['depends' => [\yii\web\JqueryAsset::class]]
);
$this->registerJs(
    '
    $(document).ready(function(){
        var isoCountries = [
            { id: "XX", text: "Select Country"},
            { id: "AL", text: "Albania"},
            { id: "AD", text: "Andorra"},
            { id: "AO", text: "Angola"},
            { id: "AR", text: "Argentina"},
            { id: "AM", text: "Armenia"},
            { id: "AW", text: "Aruba"},
            { id: "AU", text: "Australia"},
            { id: "AT", text: "Austria"},
            { id: "AZ", text: "Azerbaijan"},
            { id: "BD", text: "Bangladesh"},
            { id: "BY", text: "Belarus"},
            { id: "BE", text: "Belgium"},
            { id: "BZ", text: "Belize"},
            { id: "BO", text: "Bolivia"},
            { id: "BA", text: "Bosnia and herzegovina"},
            { id: "BR", text: "Brazil"},
            { id: "BN", text: "Brunei darussalam"},
            { id: "BG", text: "Bulgaria"},
            { id: "CA", text: "Canada"},
            { id: "CL", text: "Chile"},
            { id: "CN", text: "China"},
            { id: "CO", text: "Colombia"},
            { id: "CR", text: "Costa rica"},
            { id: "HR", text: "Croatia"},
            { id: "CW", text: "Curacao"},
            { id: "CY", text: "Cyprus"},
            { id: "CZ", text: "Czech republic"},
            { id: "DK", text: "Denmark"},
            { id: "DO", text: "Dominican republic"},
            { id: "EC", text: "Ecuador"},
            { id: "SV", text: "El salvador"},
            { id: "EE", text: "Estonia"},
            { id: "FO", text: "Faroe islands"},
            { id: "FI", text: "Finland"},
            { id: "FR", text: "France"},
            { id: "DE", text: "Germany"},
            { id: "GH", text: "Ghana"},
            { id: "GR", text: "Greece"},
            { id: "GL", text: "Greenland"},
            { id: "GT", text: "Guatemala"},
            { id: "GG", text: "Guernsey"},
            { id: "HK", text: "Hong kong"},
            { id: "HU", text: "Hungary"},
            { id: "IS", text: "Iceland"},
            { id: "IN", text: "India"},
            { id: "ID", text: "Indonesia"},
            { id: "IE", text: "Ireland"},
            { id: "IM", text: "Isle of man"},
            { id: "IL", text: "Israel"},
            { id: "IT", text: "Italy"},
            { id: "JP", text: "Japan"},
            { id: "JE", text: "Jersey"},
            { id: "KZ", text: "Kazakhstan"},
            { id: "KW", text: "Kuwait"},
            { id: "LV", text: "Latvia"},
            { id: "LI", text: "Liechtenstein"},
            { id: "LT", text: "Lithuania"},
            { id: "LU", text: "Luxembourg"},
            { id: "MO", text: "Macao"},
            { id: "MK", text: "Macedonia"},
            { id: "MY", text: "Malaysia"},
            { id: "MT", text: "Malta"},
            { id: "MH", text: "Marshall islands"},
            { id: "MU", text: "Mauritius"},
            { id: "MX", text: "Mexico"},
            { id: "MD", text: "Moldova"},
            { id: "MC", text: "Monaco"},
            { id: "MZ", text: "Mozambique"},
            { id: "NR", text: "Nauru"},
            { id: "NL", text: "Netherlands"},
            { id: "NZ", text: "New zealand"},
            { id: "NO", text: "Norway"},
            { id: "PK", text: "Pakistan"},
            { id: "PA", text: "Panama"},
            { id: "PY", text: "Paraguay"},
            { id: "PE", text: "Peru"},
            { id: "PH", text: "Philippines"},
            { id: "PL", text: "Poland"},
            { id: "PT", text: "Portugal"},
            { id: "RO", text: "Romania"},
            { id: "RU", text: "Russia"},
            { id: "LC", text: "Saint lucia"},
            { id: "WS", text: "Samoa"},
            { id: "SM", text: "San marino"},
            { id: "SA", text: "Saudi arabia"},
            { id: "RS", text: "Serbia"},
            { id: "SC", text: "Seychelles"},
            { id: "SG", text: "Singapore"},
            { id: "SK", text: "Slovak republic"},
            { id: "SI", text: "Slovenia"},
            { id: "ZA", text: "South africa"},
            { id: "KR", text: "South korea"},
            { id: "ES", text: "Spain"},
            { id: "SE", text: "Sweden"},
            { id: "CH", text: "Switzerland"},
            { id: "TW", text: "Taiwan"},
            { id: "TH", text: "Thailand"},
            { id: "TT", text: "Trinidad and tobago"},
            { id: "TR", text: "Turkey"},
            { id: "UA", text: "Ukraine"},
            { id: "AE", text: "United arab emirates"},
            { id: "GB", text: "United kingdom"},
            { id: "UY", text: "Uruguay"},
            { id: "US", text: "USA"},
            { id: "VE", text: "Venezuela"},
            { id: "VN", text: "Vietnam"},
        ];
        $("#country_id").select2({
            placeholder: "Select a country",
            templateResult: formatCountry,
            templateSelection: formatCountry,
            data: isoCountries,
        });
        var firstName = localStorage.getItem("first_name");
        var last_name = localStorage.getItem("last_name");
        var email = localStorage.getItem("email");
        var phone = localStorage.getItem("phone");
        var street1 = localStorage.getItem("street1");
        var street2 = localStorage.getItem("street2");
        var zip = localStorage.getItem("zip");
        var city = localStorage.getItem("city");
        var country_id = localStorage.getItem("country_id");
        var region = $("meta[name=\'region\']").attr("content");
        if(!country_id){
            country_id = region;
        }
        $("#firstName").val(firstName);
        $("#lastName").val(last_name);
        $("#email").val(email);
        $("#phone").val(phone);
        $("#street1").val(street1);
        $("#street2").val(street2);
        $("#zip").val(zip);
        $("#city").val(city);
        $("#country_id").val(country_id).change();
        function formatCountry(country) {
            if (!country.id) {
                return country.text;
            }
            var $country = $(
                \'<span class="flag-icon flag-icon-\' + country.id.toLowerCase() + \' flag-icon-squared"></span>\' +
                \'<span class="flag-text">\' + country.text + "</span>"
            );
            return $country;
        }
        checkout.init();
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
                    <div class="mb-3 mt-3">
                        <label for="firstName">Last Name:</label>
                        <input type="text" class="form-control mt-3" id="lastName" placeholder="Enter Last Name" name="last_name">
                    </div>
                    <div class="mb-3">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control mt-3" id="email" placeholder="Enter Email" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="phone">Phone:</label>
                        <input type="text" class="form-control mt-3" id="phone" placeholder="Enter Phone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="street1">Street1:</label>
                        <input type="text" class="form-control mt-3" id="street1" placeholder="Enter Street1" name="street1">
                    </div>
                    <div class="mb-3">
                        <label for="street2">Street2:</label>
                        <input type="text" class="form-control mt-3" id="street2" placeholder="Enter Street2" name="street2">
                    </div>
                    <div class="mb-3">
                        <label for="zip">ZIP:</label>
                        <input type="text" class="form-control mt-3" id="zip" placeholder="Enter Zip" name="zip">
                    </div>
                    <div class="mb-3">
                        <label for="city">City:</label>
                        <input type="text" class="form-control mt-3" id="city" placeholder="Enter City" name="city">
                    </div>
                    <div class="mb-3">
                        <label for="country" class="mb-3">Country:</label>
                        <select class="form-select select2 mt-3" id="country_id" name="country_id">
                        </select>
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
                    <button type="button" id="payNow" class="btn btn-primary">Pay Now</button>
                </form>
            </div>
        </div>
    </div>
</div>