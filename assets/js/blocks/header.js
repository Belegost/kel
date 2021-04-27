

window.addEventListener('load', function() {
    $('.mobile-burger, .switch-sidebar-menu').on(
        'click',
        function() {
            $('body').toggleClass('minified');
        }
    );
    $('.mainwrap-overlay').on('click', function(e) {
        $('body').removeClass('minified');
    });
    if(windowWidth>1139) {
        $('body:not(".minified") header nav a, body:not(".minified") .sticky-header nav a').on(
            'click',
            function(e) {
                e.preventDefault();
                var mClass = $(this).hasClass('menu-crypto')?'crypto':$(this).hasClass('menu-products')?'products':'market';
                $('.smenu > div').hide();
                switch(mClass) {
                    case 'crypto':
                        $('.smenu > div.smenu-crypto').show();
                        break;
                    case 'products':
                        $('.smenu > div.smenu-products').show();
                        break;
                    case 'market':
                        $('.smenu > div.smenu-market').show();
                        break;
                }
                $('body').addClass('minified');
            }
        )
    }
    showVisible();
});
var popupBuyBtn = {};
$('.popup-product_buy-info').on('click', '.btn', function() {
    var btn = $(this);
    btn.prop('disabled', true);
    $.ajax({
        url: btn.data('link'),
        type: 'POST',
        data: popupBuyBtn,
        dataType: 'json',
        success: function (response) {
            if (response.status == 'success') {
                displayPopupMsg('<h4>Product Added!</h4>');
                setTimeout(function() {
                    window.location.href='/myproducts'
                }, 2000);
                btn.prop('disabled', false);
            } else {
                displayPopupMsg(response.message);
            }
        },
        complete: function () {
            btn.prop('disabled', false);
        }
    });
});
$('.popup-product_buy-qty input').on('change', function(){
    var cost = $('.popup-product_buy-cost').text();
        cost = cost.replace(/[\s\$,]/g, '');
    var qty = $(this).val();
    $('.popup-product_buy-total').text('$ '+(1*qty*cost));
    $('.popup-product_buy-info button').data('qty', qty);
});
var pageHeaderBg = document.querySelector('body:not(.homepage) .page-header-bg');
var sticky = $('.sticky-header');
var sticky_is_visible = 'hidden';
sticky.removeClass('visible');
var lastScrollTop = 0;
window.addEventListener('scroll', function () {
    var scrolled = window.pageYOffset || document.documentElement.scrollTop;
    if (pageHeaderBg&&windowWidth > 767) {
        pageHeaderBg.style.transform = "translateY(" + 0.25 * scrolled + "px)";
    }
    showVisible();
    var current_sticky = false;
    if(scrolled>250&&lastScrollTop>scrolled) current_sticky = true;
    if(current_sticky!=sticky_is_visible) {
        if(current_sticky) {
            sticky.addClass('visible');
        } else {
            $('.sticky-header .header-user-menu').slideUp(200);
            sticky.removeClass('visible');
        }
    }
    sticky_is_visible = current_sticky;
    lastScrollTop = scrolled;
});
$(document.forms.sign_in).on('submit', function (e) {
    e.stopPropagation();
    var formSignInData = new FormData(this);

    var username = $('#sign_in_username').val();
    var password = $('#sign_in_password').val();
    var keepsigned = $('#sign_in_keepsigned').prop('checked')?1:0;
    var btn = $(this).find('.btn');
    // if(username.length<3) {
    //     $('.popup-login-username .field-error').html('The username should be more than 3 symbols');
    //     $('.popup-login-username .field-border').addClass('focused');
    //     $('.popup-login-username').addClass('has_error');
    // } else if(password.length<8) {
    //     $('.popup-login-password .field-error').html('The length of the password should be more than 8 chars');
    //     $('.popup-login-password').addClass('has_error');
    // } else {

        $.ajax({
            url: "/signin",
            type: 'POST',
            data: formSignInData,
            cache: false,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status == 'success') {
                    location.href = "/dashboard"
                } else {
                    $('.popup-login-errors').html(response.message).removeClass('dn');
                }
            },
            complete: function() {
                btn.prop('disabled', false);
                username.val('');
                password.val('');
            }
        });
    // }

    return false;
});

$(document.forms.reset_password).on('submit', function (e) {
    e.stopPropagation();
    var formSignInData = new FormData(this);
    var btn = $('.popup-forgot .btn');
    btn.prop('disabled', true);
    $.ajax({
        url: "/password/reset",
        type: 'POST',
        data: formSignInData,
        cache: false,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.status == 'success') {
                $('div.popup-forgot').removeClass('visible');
                $('div.popup-msg-info').html(response.message);
                $('div.popup-msg').addClass('visible');
            } else {
                $('.popup-forgot-errors').html(response.message).removeClass('dn');
            }
        },
        complete: function () {
            btn.prop('disabled', false);
            $('#reset_password_for_username').val('');
        }
    });

    return false;
});
$(document).on('click', function(event) {
    if(!$(event.target).closest('.login').length) {
        if($(event.target).closest('.header-user-menu').css('display')!='none') {
            $('.header-user-menu').slideUp(300);
        }
    }
});
$('.show-user-menu').on('click', function() {
    $(this).parent().find('.header-user-menu').slideToggle(300);
});
$('.header-user-menu-switcher').on('click', function() {
    $(this).toggleClass('active');
});
