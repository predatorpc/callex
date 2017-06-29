
    $('#clients-call_status_id').change(function(){
        if($(this).val() == 2){
            $('div#next_call').show();
        }else{
            $('div#next_call').hide();
        }
        $('.btn.btn-success.client').prop("disabled", false)
    });
    $('.btn.btn-success.client').click(function(){
        if($('#clients-call_status_id').val() != 1 && $('#clients-call_status_id').val() != 6){
            if($('input[name="comment_send"]').val() != 1){
                alert('Оставьте комментарий');
                return false;
            }
        }
    });
    $('form#sms #sendsms').click(function(){
        if($(this).find('input').val() != ''){
            $.ajax({
                type: "POST",
                url: "/desktop/sms-send",
                data: $('form#sms').serialize(),
                success: function(data) {
                    alert(data);

                },
            });
        }
        return false;
    });
    $('form#sms #savesms').click(function(){
        if($(this).find('input').val() != ''){
            $.ajax({
                type: "POST",
                url: "/desktop/sms-save",
                data: $('form#sms').serialize(),
                success: function(data) {
                    alert(data);

                },
            });
        }
        return false;
    });
    $('form#comments').submit(function(){
        if($(this).find('textarea').val() != ''){
            $.ajax({
                type: "POST",
                url: "/desktop/add-comment",
                data: $(this).serialize(),
                success: function(data) {
                    $('ul#comments_list').prepend('<li>'+data+'</li>');
                    $('form#comments textarea').val('');
                    $('input[name="comment_send"]').val(1);
                },
            });
        }
        return false;
    });
    console.log('desktop.js OK');

