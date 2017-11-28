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

// Преолдер;

// Форма;


