"use strict";

$(function () {
    $('.grid').isotope({
        filter: '.transition'
    });
});

var $grid = $('.grid').isotope({
    itemSelector: '.element-item',
    layoutMode: 'fitRows',
});
$(document).ready(function () {
    $('a[href="' + $("#path_site").val() + '"]').addClass('active');
});
var filterFns = {
    numberGreaterThan50: function () {
        var number = $(this).find('.number').text();
        return parseInt(number, 10) > 50;
    },
    ium: function () {
        var name = $(this).find('.name').text();
        return name.match(/ium$/);
    }
};
$('.filters-select').on('change', function () {
    var filterValue = this.value;
    filterValue = filterFns[filterValue] || filterValue;
    $grid.isotope({
        filter: filterValue
    });
});
function disablebtn(){
    alert("This action is disabled in demo.");
}
$(function () {

    var filterList = {

        init: function () {

            $('#portfoliolist').mixItUp({
                selectors: {
                    target: '.portfolio',
                    filter: '.filter'
                },
                load: {
                    filter: '.1'
                }
            });

        }

    };


    filterList.init();

});


$(document).ready(function () {
    $("#content-slider").lightSlider({
        loop: true,
        keyPress: true
    });
    $('#image-gallery').lightSlider({
        gallery: true,
        item: 1,
        thumbItem: 9,
        slideMargin: 0,
        speed: 500,
        auto: true,
        loop: true,
        onSliderLoad: function () {
            $('#image-gallery').removeClass('cS-hidden');
        }
    });
});
$(document).ready(function () {

    $("#owl-demo").owlCarousel({
        navigation: true
    });

});

function changetab() {
    $(this).toggleClass("on");
    $("#menu").slideToggle();
}


$('.responsive').slick({
    dots: true,
    prevArrow: $('.prev'),
    nextArrow: $('.next'),
    infinite: false,
    speed: 300,
    rtl: rtl_slick(),
    slidesToShow: 7,
    slidesToScroll: 4,
    responsive: [{
            breakpoint: 1024,
            settings: {
                slidesToShow: 7,
                slidesToScroll: 3,
                infinite: true,
                dots: true
            }
        }, {
            breakpoint: 767,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
                infinite: true,
                dots: true
            }
        }, {
            breakpoint: 576,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 2
            }
        }, {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
            }
        }

    ]
});

function checkboth(val) {
    var pwd = $("input[name='password_reg']").val();
    if (val != pwd) {
        alert($("#pwdmatch").val());
        $("input[name='password_reg']").val("");
        $("input[name='con_password_reg']").val("");
    }
}

function checkbothpwd(val) {
    var npwd = $("input[name='npwd']").val();
    if (npwd != val) {
        alert($("#pwdmatch").val());
        $("input[name='npwd']").val("");
        $("input[name='rpwd']").val("");
    }
}

function checkcurrentpwd(val) {
    $.ajax({
        url: $("#path_site").val() + "/checkuserpassword" + "/" + val,
        success: function (data) {
            if (data == 0) {
                alert($("#error_cut_pwd").val());
                $("input[name='opwd']").val("");
            }
        }
    });
}

function cancelpwd() {
    $("input[name='npwd']").val("");
    $("input[name='rpwd']").val("");
    $("input[name='opwd']").val("");
}

function changepassword() {
    var npwd = $("input[name='npwd']").val();
    var opwd = $("input[name='opwd']").val();
    $.ajax({
        url: $("#path_site").val() + "/changeuserpwd",
        method: "GET",
        data: {
            npwd: npwd,
            opwd: opwd,
        },
        success: function (data) {
            $("input[name='npwd']").val("");
            $("input[name='rpwd']").val("");
            $("input[name='opwd']").val("");
            $('#contact').addClass('active');
            var txt = '<div class="col-sm-12"><div class="alert  alert-success alert-dismissible fade show" role="alert">Password Change Successfully<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div></div>';
            document.getElementById("msgres").innerHTML = txt;
        }
    });
}

function changecategory(val,id) {
    var total=$("#totalcategory").val();
    document.getElementById("category_div").style.display = "block";
    document.getElementById("main_content").style.display = "none";
    
    $.ajax({
        url: $("#path_site").val() + "/category" + "/" + val,
        method: "GET",
        success: function (result) {
             for(var i=0;i<total;i++){;
                $("#cat1"+i).removeClass('slick-slide active');
                $("#catdiv"+i).removeClass('slick-slide active');
            }
            $("#cat1"+id).addClass('slick-slide active');
            $("#catdiv"+id).addClass('slick-slide active');
            var res = JSON.parse(result);
            var data = res.item;
            var txt = "";
            console.log(data);
            txt=txt+'<div class="row">';
            var path = $("#path_site").val();
              for (var i = 0; i < data.length; i++) {
                 txt=txt+'<div class="portfolio 1 col-md-6 burger w3-container  w3-animate-zoom portfoliocat" data-cat="'+data[i]['id']+'" data-bound><div class="items"><div class="b-img"><a href="' + path + '/detailitem' + "/" + data[i]['id'] + '"><img src="' + path + '/public/upload/images/menu_item_icon' + "/" + data[i]['menu_image'] + '"></a></div><div class="bor"><div class="b-text"><h1><a href="' + path + '/detailitem' + "/" + data[i]['id'] + '">' + data[i]['menu_name'] + '</a></h1><p>' + data[i]['description'] + '</p></div><div class="price"><h1>' + $("#currency").val() + data[i]['price'] + '</h1><div class="cart"><a href="' + path + '/detailitem' + "/" + data[i]['id'] + '">' + $("#addcart").val() + '</a></div></div></div></div></div>';
              }
            txt=txt+'</div>';
            var cat = res.category;
           
            document.getElementById("category_div").innerHTML = txt;
        }
    });

}
$(document).ready(function () {
    $('a[href="/"]').removeClass('active');
    $('a[href="' + window.location.href + '"]').addClass('active');
});

function logout() {
    window.location.href = $("#path_site").val() + "/user_logout";
}

function rtl_slick() {
    if ($('body').hasClass("rtl")) {
        return true;
    } else {
        return false;
    }
}

function addtocart() {
    var item_id = $("#menu_name").val();
    var item=$("#item_id").val();
    var qty = $("#number").val();
    var price = $('#origin_price').val();
    var favorite = [];
    $.each($("input[name='interdient']:checked"), function () {
        favorite.push($(this).val());
    });
    var totalint = favorite.toString();
    $.ajax({
        url: $("#path_site").val() + "/addcartitem",
        method: "GET",
        data: {
            id: item_id,
            qty: qty,
            totalint: totalint,
            price: price
        },
        success: function (data) {

            document.getElementById("totalcart").innerHTML = data;
            window.location.href = $("#path_site").val() + "/detailitem" + '/' + item;
        }
    });

}

function increaseValue() {
    var value = parseInt(document.getElementById('number').value, 10);
    value = isNaN(value) ? 1 : value;
    value++;
    var getprice = $("#origin_price").val();
    document.getElementById('number').value = value;
    var finalvalues = parseInt(value) * parseFloat(getprice);
    document.getElementById('price').innerHTML = finalvalues.toFixed(2);
}

function decreaseValue() {
    var value = parseInt(document.getElementById('number').value, 10);
    value = isNaN(value) ? 1 : value;
    value < 1 ? value = 1 : '';
    if (value > 1) {
        value--;
    }
    var getprice = $("#origin_price").val();
    document.getElementById('number').value = value;
    var finalvalues = parseInt(value) * parseFloat(getprice);
    document.getElementById('price').innerHTML = finalvalues.toFixed(2);
}

function changemodel() {
    $('a[href="#tab1').removeClass('active');
    $('a[href="#tab2').addClass('active');
    $('#tab1').removeClass('active');
    $('#tab2').addClass('active');
    $("#myModal1").model("show");
}

function addtocartsingle(item_id) {
    var qty = 1;
    var totalint = 0;
    $.ajax({
        url: $("#path_site").val() + "/addcartitem",
        method: "GET",
        data: {
            id: item_id,
            qty: qty,
            totalint: totalint
        },
        success: function (data) {
            document.getElementById("totalcart").innerHTML = data;
            window.location.reload();
        }
    });
}

function shareonsoical(val, id) {
    $.ajax({
        url: $("#path_site").val() + "/sharesoicalmedia" + "/" + val + "/" + id,
        method: "GET",
        success: function (data) {
            if (val == 1) {
                window.open('https://www.facebook.com/sharer/sharer.php?u='+$("#path_site").val()+'/detailitem/' + id + '', '_blank');
                document.getElementById("facebook_share_id").innerHTML = data;
            }
            if (val == 2) {
                window.open('https://twitter.com/intent/tweet?text=my share text&amp;url='+$("#path_site").val()+'/detailitem/' + id + '', '_blank');
                document.getElementById("twitter_share_id").innerHTML = data;
            }

        }
    });
}

function checkcart() {
    var cartttotal = $("#carttotal").val();
    if (carttotal != 0) {
        window.location.href = $("#path_site").val() + "/cartdetails";
    }
}


function registeruser() {
    var name = $("input[name='name']").val();
    var phone = $("input[name='phone_reg']").val();
    var password = $("input[name='password_reg']").val();
    var conn = $("input[name='con_password_reg']").val();
    if (name != "" && phone != "" && password != "" && conn != "") {
        $.ajax({
            url: $("#path_site").val() + "/userregister",
            method: "GET",
            data: {
                name: name,
                phone: phone,
                password: password
            },
            success: function (data) {
                if (data == 1) {
                    $("input[name='name']").val("");
                    $("input[name='phone_reg']").val("");
                    $("input[name='password_reg']").val("");
                    document.getElementById("reg_success_msg").style.display = "block";
                    document.getElementById("reg_error_msg").style.display = "none";
                } else {
                    document.getElementById("reg_error_msg").innerHTML = "";
                    document.getElementById("reg_error_msg").style.display = "block";
                    document.getElementById("reg_success_msg").style.display = "none";
                }
            }
        });
    } else {
        document.getElementById("reg_error_msg").style.display = "block";
        document.getElementById("reg_success_msg").style.display = "none";
        document.getElementById("reg_error_msg").innerHTML = $("#reg_error").val();

    }
}

function checkdata(val) {
    var pwd = $("input[name='password_reg']").val();
    if (val != pwd) {
        alert($("#pwdmatch").val());
        $("input[name='password_reg']").val("");
        $("input[name='con_password_reg']").val("");
    }
}

function loginaccount() {
    var phone = $("input[name='phone']").val();
    var password = $("input[name='password']").val();
    if ($("input[name='rem_me']").prop("checked") == true) {
        var rem_me = 1;
    } else {
        var rem_me = 0;
    }

    if (phone != "" && password != "") {
        $.ajax({
            url: $("#path_site").val() + "/userlogin",
            method: "GET",
            data: {
                phone: phone,
                password: password,
                rem_me: rem_me
            },
            success: function (data) {
                if (data == 1) {
                    var url1 = window.location.href;
                    var url2 = $("#path_site").val() + "/home";
                    var n = url1.localeCompare(url2);
                    console.log(n);
                    if (n == 0) {
                        window.location.href = $("#path_site").val() + "/myaccount";
                    } else {
                        window.location.reload();
                    }

                } else {
                    document.getElementById("login_error_msg").innerHTML = $("#login_error").val();
                    document.getElementById("login_error_msg").style.display = "block";
                }
            }
        });
    } else {
        document.getElementById("login_error_msg").innerHTML = $("#required_field").val();
        document.getElementById("login_error_msg").style.display = "block";
    }
}

function checkloginaccount() {
    var phone = $("input[name='phone_check']").val();
    var password = $("input[name='password_check']").val();
    if ($("input[name='rem_me_check']").prop("checked") == true) {
        var rem_me = 1;
    } else {
        var rem_me = 0;
    }

    if (phone != "" && password != "") {
        $.ajax({
            url: $("#path_site").val() + "/userlogin",
            method: "GET",
            data: {
                phone: phone,
                password: password,
                rem_me: rem_me
            },
            success: function (data) {
                if (data == 1) {
                    var url1 = window.location.href;
                    var url2 = $("#path_site").val() + "/home";
                    var n = url1.localeCompare(url2);
                    console.log(n);
                    if (n == 0) {
                        window.location.href = $("#path_site").val() + "/myaccount";
                    } else {
                        window.location.reload();
                    }

                } else {
                    document.getElementById("check_login_error_msg").innerHTML = $("#login_error").val();
                    document.getElementById("check_login_error_msg").style.display = "block";
                }
            }
        });
    } else {
        document.getElementById("check_login_error_msg").innerHTML = $("#required_field").val();
        document.getElementById("check_login_error_msg").style.display = "block";
    }
}

function forgotmodel() {
    document.getElementById('forgotbody').style.display = "block";
    document.getElementById('loginmodel').style.display = "none";
}

function loginmodel() {
    document.getElementById('forgotbody').style.display = "none";
    document.getElementById('loginmodel').style.display = "block";
}

function forgotaccount() {
    var phone = $("input[name='phone_for']").val();
    if (phone != "") {
        $.ajax({
            url: $("#path_site").val() + "/forgotpassword",
            method: "GET",
            data: {
                phone: phone
            },
            success: function (data) {
                console.log(data);
                if (data == 1) {
                    document.getElementById("for_success_msg").style.display = "block";
                    document.getElementById("for_error_msg").style.display = "none";
                } else {
                    document.getElementById("for_error_msg").innerHTML = $("#forgot_error").val();
                    document.getElementById("for_error_msg").style.display = "block";
                    document.getElementById("for_success_msg").style.display = "none";
                }
            }
        });
    } else {
        document.getElementById("for_error_msg").innerHTML = $("#forgot_error_2").val();
        document.getElementById("for_error_msg").style.display = "block";
        document.getElementById("for_success_msg").style.display = "none";

    }

}


function changeoptioncart(val) {
    if (val == 0) {
        document.getElementById("home1").checked = true;
        document.getElementById("home2").checked = false;
    }
    if (val == 1) {
        document.getElementById("home2").checked = true;
        document.getElementById("home1").checked = false;
    }
}

function addqty(id, iqty) {
    var qty = $("input[name='qty" + iqty + "']").val();
    var nqty = parseInt(qty) + 1;
    var price = document.getElementById("price_pro" + id).innerHTML;
    $("input[name='qty" + iqty + "']").val(nqty);
    $.ajax({
        url: $("#path_site").val() + "/cartqtyupdate" + "/" + id + "/" + nqty + "/1",
        data: {},
        success: function (data) {
            var pricedata = parseFloat(nqty) * parseFloat(price);
            document.getElementById("producttotal" + id).innerHTML = pricedata.toFixed(2);
            document.getElementById("finaltotal").innerHTML = data.finaltotal;
            document.getElementById("subtotal").innerHTML = data.subtotal;
        }
    });

}

function minusqty(id, iqty) {
    var qty = $("input[name='qty" + iqty + "']").val();
    var nqty = parseInt(qty) - 1;
    var price = document.getElementById("price_pro" + id).innerHTML;
    $("input[name='qty" + iqty + "']").val(nqty);
    if (nqty != 0) {
        $.ajax({
            url: $("#path_site").val() + "/cartqtyupdate" + "/" + id + "/" + nqty + "/0",
            data: {},
            success: function (data) {
                var pricedata = parseFloat(nqty) * parseFloat(price);
                document.getElementById("producttotal" + id).innerHTML = pricedata.toFixed(2);
                document.getElementById("finaltotal").innerHTML = data.finaltotal;
                document.getElementById("subtotal").innerHTML = data.subtotal;
            }
        });
    } else {
        $.ajax({
            url: $("#path_site").val() + "/deletecartitem" + "/" + id,
            data: {},
            success: function (data) {
                window.location.reload();
            }
        });
    }

}


function changebutton(val) {
    if (val == "Cash" || val == "by Card") {
        document.getElementById("orderplace1").style.display = "block";
        document.getElementById("orderplacestrip").style.display = "none";
        document.getElementById("orderplacepaypal").style.display = "none";
        $("#pay1").addClass('activepayment');
        $("#pay2").removeClass('activepayment');
        $("#pay3").removeClass('activepayment');
        $("#order_payment_type_1").prop("checked", true);
        $("#order_payment_type_3").prop("checked", false);
        $("#order_payment_type_4").prop("checked", false);
    }
    if (val == "Stripe") {
        var totalprice = document.getElementById("finaltotal_order").innerHTML;
        $("script").each(function () {
            $(this).attr('data-amount', totalprice);
        })
        $("#phone_or").val($("#order_phone").val());
        $("#note_or").val($("#order_notes").val());
        $("#city_or").val($("#order_city").val());
        $("#payment_type_or").val("Stripe");
        $("#shipping_type_or").val($("input[name='shipping_type']").val());
        $('#total_price_or').val(totalprice);
        $('#subtotal_or').val(document.getElementById("subtotal_order").innerHTML);

        if ($("#phone_or").val() != "" && $("#city_or").val() != "") {
            if ($("#home1").prop("checked") == true) {
                var shipping_type = 0;
                $("#shipping_type_or").val(0);
                $("#address_or").val($("#us2-address").val());
                $("#lat_long_or").val($("#us2-lat").val() + "," + $("#us2-lon").val());
                $('#charage_or').val(document.getElementById("delivery_charges_order").innerHTML);
            } else if ($("#home2").prop("checked") == true) {
                var shipping_type = 1;
                $("#shipping_type_or").val(1);
            }

            if (shipping_type == 0 && $("#address_or").val() == "") {
                $("#order_payment_type_4").prop("checked", false);
                alert("{{__('messages.required_field')}}");
            } else {
                document.getElementById("orderplace1").style.display = "none";
                document.getElementById("orderplacestrip").style.display = "block";
                document.getElementById("orderplacepaypal").style.display = "none";
                $("#pay1").removeClass('activepayment');
                $("#pay2").removeClass('activepayment');
                $("#pay3").addClass('activepayment');
                $("#order_payment_type_1").prop("checked", false);
                $("#order_payment_type_3").prop("checked", false);
                $("#order_payment_type_4").prop("checked", true);
            }
        } else {
            $("#order_payment_type_4").prop("checked", false);
            alert($("#required_field").val());

        }

    }
    if (val == "Paypal") {
        var totalprice = document.getElementById("finaltotal_order").innerHTML;
        $("#phone_pal").val($("#order_phone").val());
        $("#note_pal").val($("#order_notes").val());
        $("#city_pal").val($("#order_city").val());
        $("#payment_type_pal").val("Paypal");
        $('#total_price_pal').val(totalprice);
        $('#subtotal_pal').val(document.getElementById("subtotal_order").innerHTML);
        if ($("#phone_pal").val() != "" && $("#city_pal").val() != "") {
            document.getElementById("orderplace1").style.display = "none";
            document.getElementById("orderplacestrip").style.display = "none";
            document.getElementById("orderplacepaypal").style.display = "block";
            $("#pay1").removeClass('activepayment');
            $("#pay2").addClass('activepayment');
            $("#pay3").removeClass('activepayment');
            $("#order_payment_type_1").prop("checked", false);
            $("#order_payment_type_3").prop("checked", true);
            $("#order_payment_type_4").prop("checked", false);
            if ($("#home1").prop("checked") == true) {
                var shipping_type = 0;
                $("#shipping_type_pal").val(0);
                $("#address_pal").val($("#us2-address").val());
                $("#lat_long_pal").val($("#us2-lat").val() + "," + $("#us2-lon").val());
                $('#charage_pal').val(document.getElementById("delivery_charges_order").innerHTML);
            } else if ($("#home2").prop("checked") == true) {
                var shipping_type = 1;
                $("#shipping_type_pal").val(1);
            }

        } else {
            $("#order_payment_type_3").prop("checked", false);
            alert($("#required_field").val());

        }

    }
}

function changeoption(val) {
    var subtotal = $("#subtotalorder").val();
    var discharges = $("#delivery_charges").val();
    if (val == 0) {
        document.getElementById("home1").checked = true;
        document.getElementById("home2").checked = false;
        document.getElementById("maporder").style.display = "block";
        document.getElementById("addressorder").style.display = "block";
        document.getElementById("dcorder").style.display = "block";
        document.getElementById("finaltotal_order").innerHTML = parseFloat(subtotal) + parseFloat(discharges);
    }
    if (val == 1) {
        document.getElementById("home2").checked = true;
        document.getElementById("home1").checked = false;
        document.getElementById("maporder").style.display = "none";
        document.getElementById("addressorder").style.display = "none";
        document.getElementById("dcorder").style.display = "none";
        document.getElementById("finaltotal_order").innerHTML = parseFloat(subtotal);
    }
}

function orderplace() {
    var phone = $("#order_phone").val();
    var note = $("#order_notes").val();
    var city = $("#order_city").val();
    var payment_type = 'Cash';
    var totalprice = document.getElementById("finaltotal_order").innerHTML;
    var subtotal = document.getElementById("subtotal_order").innerHTML;
    var charge = document.getElementById("delivery_charges_order").innerHTML;
    var typedata = "";
 //   console.log($("#us2-address").val());

    if ($("#home1").prop("checked") == true) {
        var shipping_type = 0;
        var address = $("#us2-address").val();
        var latlong = $("#us2-lat").val() + "," + $("#us2-lon").val();
    }
    if ($("#home2").prop("checked") == true) {
        var shipping_type = 1;
        var address ="";
        var latlong ="";
    } 

    if (phone != "" && city != "" && payment_type != "") {
        $.ajax({
            url: $("#path_site").val() + "/placeorder",
            method: "GET",
            data: {
                phone: phone,
                note: note,
                city: city,
                address:address,
                payment_type: payment_type,
                shipping_type: shipping_type,
                totalprice: totalprice,
                subtotal: subtotal,
                charge: charge,
                latlong:latlong
            },
            success: function (data1) {
                console.log(data1);
                if (data1 != 0) {
                    window.location.href = $("#path_site").val() + "/viewdetails" + "/" + data1;
                }
            }
        });
    } else {
        document.getElementById("orderplace1").style.display = "none";
        document.getElementById("orderplacestrip").style.display = "none";
        document.getElementById("orderplacepaypal").style.display = "none";
        $("#pay1").removeClass('activepayment');
        $("#pay2").removeClass('activepayment');
        $("#pay3").removeClass('activepayment');
        $("#order_payment_type_1").prop("checked", false);
        $("#order_payment_type_3").prop("checked", false);
        $("#order_payment_type_4").prop("checked", false);
        alert($("#required_field").val());
    }
}


function addprice(price, iqty) {
    if ($("#checkbox-" + iqty).prop("checked") == true) {
        console.log("checked");
        var origin_price = $("#origin_price").val();
        var menu_new_price = parseFloat(origin_price) + parseFloat(price);
        $("#origin_price").val(menu_new_price.toFixed(2));
        var pricedata = menu_new_price * parseFloat($('#number').val());
        document.getElementById("price").innerHTML = pricedata.toFixed(2);
        console.log(menu_new_price);
    } else if ($("#checkbox-" + iqty).prop("checked") == false) {
        console.log("unchecked");
        var origin_price = $("#origin_price").val();
        var menu_new_price = parseFloat(origin_price) - parseFloat(price);
        $("#origin_price").val(menu_new_price.toFixed(2));
        var pricedata = menu_new_price * parseFloat($('#number').val());
        document.getElementById("price").innerHTML = pricedata.toFixed(2);
        console.log(menu_new_price);
    }


}
$('#us2').locationpicker({
    location: {
        latitude: 21.2284231,
        longitude: 72.896816
    },
    radius: 300,
    inputBinding: {
        latitudeInput: $('#us2-lat'),
        longitudeInput: $('#us2-lon'),
        radiusInput: $('#us2-radius'),
        locationNameInput: $('#us2-address')
    },
    enableAutocomplete: true
});