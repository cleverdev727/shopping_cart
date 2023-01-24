"use strict";

var pay = function () {
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
    
	var setLocalStorage = function() {
        $(":input").change(function(){
            var name = $(this).attr("name");
            var value = $(this).val();
            if(name !== "captcha" && name !== "language"){
                localStorage.setItem(name, value);
            }
        });
	}

    var handleBuyNow = function(){
        $("#buyNow").click(function(){
            var firstName = $("#firstName").val();
            var email = $("#email").val();
            var verify_code = $("input[name=captcha]").val();
            var orderId = localStorage.getItem("order_id");
            if(orderId == null || orderId == ""){
                orderId = "";
            }
            $.ajax({
                url: "",
                method: "POST",
                data: { 
                    _csrf: csrfToken, 
                    first_name: firstName, 
                    email: email, 
                    order_id: orderId,
                    verifyCode: verify_code
                },
                success: function(res){
                    var res_data = JSON.parse(res);
                    if(res_data["status"] == "success"){
                        localStorage.setItem("first_name", firstName);
                        localStorage.setItem("email", email);
                        localStorage.setItem("order_id", res_data["order_id"]);
                        if(language){
                            window.location = '/' + language + '/checkout.html'
                        }else{
                            window.location = '/checkout.html'
                        }      
                    }else{
                        var messages = res_data["message"];
                        for( var key in messages){
                            toastr["warning"](messages[key][0]);
                        }
                    }
                },
                error: function(res){
                    toastr["warning"]("Something went wrong!");
                }
            })
        });
    }

	return {
		init: function () {
			setLocalStorage();
            handleBuyNow();
		}
	};
}();