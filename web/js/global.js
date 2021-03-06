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

    masonry_item();

    $('#myTabList a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
        masonry_item();
    })
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

$(document).on('click','.js-tag',function(){
    var text = $(this).text();
    $('.js-tag').removeClass('text-success');
    $(this).addClass('text-success');
    $('.js-text-add').html('');
    $('.js-text-add').html(text);
    return false;
});
// Выбор время;
$(document).on('click','.js-select-action',function(){
    var item = $(this).val();
    if(item == 7) {
      $('.times-content').fadeIn(200);
    }else{
      $('.times-content').fadeOut(200);
    }
    masonry_item();
    return false;
});

// Динамичные блоки;
function masonry_item() {
    $('.js-grid').masonry({
        // columnWidth: 200,
        itemSelector: '.item-grid',
    });
}



// Открытие модальное окно url - string; tittle - string; objPost - obj ,idModal -индификатор;
function window_pay(url,title,objPost,size) {
    var modalContainer = $('#window_pay');

    if(title){
        modalContainer.find(".modal-title").text(title);
    }else{
        modalContainer.find(".modal-title").text('');
    }
    // Размер модальное окно;
    if(size == 'lg') {
        modalContainer.find('.modal-dialog').addClass('modal-lg').removeClass('modal-min');
    }else if(size == 'sm'){
        modalContainer.find('.modal-dialog').addClass('modal-sm').removeClass('modal-min');
    }else{
        modalContainer.find('.modal-dialog').addClass('modal-min').removeClass('modal-sm modal-lg');
    }


    //Если нет объекта по умол. пустой;
    if(!issetJs(objPost)) objPost = {};
    loader('show');
    modalContainer.find('.modal-body').html('');
    $.ajax({
        url: '/' + url,
        type: "POST",
        data: objPost,
        async: false,
        success: function (data) {
            modalContainer.find('.modal-body').html(data);
            modalContainer.modal('show');
            loader('hide');
        }
    });

    return false;
}

// Преолдер;
function loader(type) {
    if(type == 'show') {
        $('.wrap').css('opacity','0.5');
        $('body').append('<div class="loader"><span></span><span></span><span></span><span></span></div>');
    }
    if(type == 'hide') {
        $('.wrap').css('opacity','1');
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