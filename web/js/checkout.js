"use strict";

var checkout = function () {
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    var language = $('meta[name="language"]').attr("content");
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    var setLocalStorage = function(){
        $(":input").change(function(){
            var name = $(this).attr("name");
            var value = $(this).val();
            if(name !== "captcha" && name !== "language"){
                localStorage.setItem(name, value);
            }
        });
    }

    var handlePayNow = function(){
        $("#payNow").click(function(){
            var firstName = $("#firstName").val();
            var lastName = $("#lastName").val();
            var email = $("#email").val();
            var phone = $("#phone").val();
            var street1 = $("#street1").val();
            var street2 = $("#street2").val();
            var zip = $("#zip").val();
            var city = $("#city").val();
            var country = $( "#country_id" ).val();
            var orderId = localStorage.getItem("order_id");
            var verify_code = $("input[name=captcha]").val();
            if( orderId == null || orderId == ""){
                orderId = "";
            }
            $.ajax({
                url: "",
                method: "POST",
                data: { 
                    _csrf: csrfToken, 
                    first_name: firstName,
                    last_name: lastName,
                    email: email,
                    phone: phone,
                    street1: street1,
                    street2: street2,
                    zip: zip,
                    city: city,
                    country: country,
                    order_id: orderId,
                    verifyCode: verify_code
                },
                success: function(res){
                    var res_data = JSON.parse(res);
                    if(res_data["status"] == "success"){
                        localStorage.clear();
                        if(language){
                            window.location = "/" + language + "/success.html"
                        }else{
                            window.location = "/success.html"
                        }
                    }else{
                        var messages = res_data["message"];
                        for( var key in messages){
                            toastr["warning"](messages[key][0]);
                        }
                    }
                },
                error: function(res){
                    toastr["warning"]("Server Interval Error!");
                }
            })
        });
    }

    return {
		init: function () {
			setLocalStorage();
            handlePayNow();
		}
	};
}();