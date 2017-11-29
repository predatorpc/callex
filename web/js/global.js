// Ввод цифр;
$(document).on("keypress", "input.number", function(e) {
    var charCode = (e.which) ? e.which : event.keyCode;
    if (charCode != 8 && (charCode < 48 || charCode > 57)) return false;
    return true;
});
// Ввод номера телефона;
$(document).on("keypress", "input.phone", function(e) {
    var charCode = (e.which) ? e.which : event.keyCode;
    if (charCode != 8 && charCode != 43 && (charCode < 48 || charCode > 57)) return false;
    return true;
});

// +7
$(document) .on('focus','input.phone',function() {
    $(this).parents('.form-group').siblings('.pl').show();
    $(this).siblings('.pl').show();
    $(this).css("padding-left","22px");
}).on('blur','input.phone',function() {
    if($(this).val() == '') {
        $(this).parents('.form-group').siblings('.pl').hide();
        $(this).siblings('.pl').hide();
        $(this).css("padding-left","12px");
    }
});

// супер сетка;
$(window).load(function () {
    $('.js-grid').masonry({
       // columnWidth: 200,
        itemSelector: '.item-grid',

    });
   //
});



// Отправка форм глобальный;
$(document).on('beforeSubmit','.js-form-yii2', function (event) {
    return false;
}).on('ajaxBeforeSend','.js-form-yii2', function (event, jqXHR, textStatus) {
    $('.btn-pay').button('loading');
    loader('show');
    console.log('loading');
}).on('ajaxComplete','.js-form-yii2', function (event, jqXHR, textStatus) {
    var data = jqXHR.responseText;
    data = json_string(data);
    if(data.status == 'success') {
        alert_messages(data.message, 1);
    }else if(data.status == 'error') {
        alert_messages(data.message,2);
    }
    loader('hide');
    return false;
});







// Преолдер;
function loader(type) {
    if(type == 'show') {
        $('body').append('<div class="loader"><span></span><span></span><span></span><span></span></div>');
    }
    if(type == 'hide') {
        $('.loader').remove();
    }
}
// Форма;

// Уведомления:
function alert_messages (text,status,options) {
    var status_element;
    switch (status) {
        case 1: status_element = 'alert-success'; break; // Успех;
        case 2: status_element = 'alert-danger'; break; // Ошибка:
        case 3: status_element = 'alert-warning'; break; // Внимание;
        default : status_element = 'alert-info'; // Инфо;
    }


    // Показываем увдемоления;
    $(".alert__fix").fadeIn(200).addClass(status_element);
    $(".alert__fix .messages").html(text);
    if(options) return false;
    // Закрываем через 3 сек;
    setTimeout(function(){
        $(".alert__fix").fadeOut(600).removeClass(status_element);
        $(".alert__fix .messages").html('');
    },5000);

    return false;
}



// Число из строки;
function in_number(string) {
    var temp = string.replace(/\D/g,'');
    return parseInt(temp);
}

// Проверка на пустоту;
function issetJs(variable) {
    return (typeof(variable) != "undefined" && variable !== null);
}

// Проверка на пустоту;
function emptyJs( mixed_var ) {
    return ( mixed_var === "" || mixed_var === 0   || mixed_var === "0" || mixed_var === null  || mixed_var === false  ||  ( is_array(mixed_var) && mixed_var.length === 0 ) );
}

// Из JSON в в строку;
function json_string(data) {
    try {
        return $.parseJSON(data);
    } catch (e) {
        return false;
    }
}


console.log('global OK');