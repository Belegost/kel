'use strict';

var windowHeight = document.documentElement.clientHeight;
var windowWidth = document.documentElement.clientWidth;

// Запускаем счетчик цифр, если он не был запущен до этого
var incrementDigits = function() {
    var selectors = [];
    return {
        init: function(selector, options) {
            if(selectors.indexOf(selector) == -1) {
                var options = options || false;
                var defaultOptions = { thousandSeparator: "", duration: 2000 };
                if(options){ for(var opt in defaultOptions)if(!options[opt])options[opt]=defaultOptions[opt] }
                else { options = defaultOptions; }
                $(selector).spincrement(options);
                selectors.push(selector);
            }
        }
    }
}();

function animatedBlocksParam(selector) {
    var section = document.querySelector(selector);
    return {
        section: section,
        sectionBg: section?document.querySelector(selector+'-bg'):false,
        sectionOffset: section?section.offsetTop:section
    }
}
function histogrammMenu(obj){
    var activeBorder = obj.querySelector('.active-border');
    obj.addEventListener('click', function(e) {
        if (e.target.tagName == 'SPAN') {
            activeBorder.style.marginLeft = e.target.offsetLeft+'px';
            activeBorder.style.width = e.target.offsetWidth+'px';
            obj.querySelector('.current').classList.remove('current');
            e.target.className = 'current';
        }
    });
}
function showVisible() {
    var elems = document.querySelectorAll('.animate-visible');
    for (var i = 0; i < elems.length; i++) {
        var elem = elems[i];
        if (isVisible(elem)) {
            elem.classList.remove('animate-visible')
        }
    }
}
function isVisible(elem) {
    var coords = elem.getBoundingClientRect();
    var topVisible = coords.top > 0 && coords.top < windowHeight;
    var bottomVisible = coords.bottom < windowHeight && coords.bottom > 0;
    return topVisible || bottomVisible;
}

$('input[type=text],input[type=password],input[type=tel],input[type=email],input[type=time],input[type=date],input[type=url], textarea').on({
        focus: function () {
            var fldst = $(this).closest('fieldset');
            fldst.removeClass('has_error');
            fldst.find('label, .field-border').addClass('focused');
        },
        blur: function () {
            var fldst = $(this).closest('fieldset');
            if(this.value=="")fldst.find('label, .field-border').removeClass('focused');
        }
    }
);
$('input[type=checkbox]').on({
        change: function () {
            $(this).closest('label').toggleClass('checked');
        }
    }
);
$('input[type=radio]').on({
        change: function () {
            var cname = this.name;
            var cbox = $(this);
            $('input[name='+cname+']').each(function(){
                $(this).prop('checked', false);
                $(this).closest('label').removeClass('checked');
            });
            cbox.prop('checked', true);
            $(this).closest('label').addClass('checked');
        }
    }
);
$('.inputfile' ).each( function() {
    var $input = $(this),
        $label = $input.next('label'),
        labelVal = $label.html();

    $input.on('change', function (e) {
        var fileName = '';

        if (this.files && this.files.length > 1)
            fileName = ( this.getAttribute('data-multiple-caption') || '' ).replace('{count}', this.files.length);
        else if (e.target.value)
            fileName = e.target.value.split('\\').pop();

        if (fileName)
            $label.find('span').html(fileName);
        else
            $label.html(labelVal);
    });

    // Firefox bug fix
    $input
        .on('focus', function () {
            $input.addClass('has-focus');
        })
        .on('blur', function () {
            $input.removeClass('has-focus');
        });
});
$('fieldset input').each(function(e) {
    var field = $(this).parent();
    if($(this).val() == '') {
        field.find('label, .field-border').removeClass('focused');
    } else {
        field.find('label, .field-border').addClass('focused');
    }
});
$('fieldset input[type=checkbox], fieldset input[type=radio]').each(function(e) {
    var label = $(this).parent();
    if($(this).prop('checked')) {
        label.addClass('checked');
    } else {
        label.removeClass('checked');
    }
});
$('fieldset select').customSelect();
$('.popup-toggle').on('click', function(e) {
    e.preventDefault();
    var popupShadow = $('.popup-shadow');
    var id = $(this).attr('data-target');

    $('.popup').removeClass('visible');
    popupShadow.fadeIn(300, function() {
        $('.popup.'+id).addClass('visible');
    });
});
$('.close-wrap').on('click', function() {
    var popupShadow = $('.popup-shadow');
    var id = $(this).attr('data-target');

    $('.popup').removeClass('visible');
    $('.popup').removeClass('visible');
    popupShadow.find('div').hide();
    popupShadow.fadeOut(300);
});

function displayPopupMsg(msg) {
    $('.popup-msg .popup-msg-info').html(msg);
    var popupShadow = $('.popup-shadow');
    $('.popup').removeClass('visible');
    popupShadow.fadeIn(300, function() {
        $('.popup.popup-msg').addClass('visible');
    });
}