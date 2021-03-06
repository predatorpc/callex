
    console.log('desktop v2.0 S');
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
                    var res = JSON.parse(data);
                    if(res.status=='success'){
                        alert_messages(res.message,1);
                    }
                    else{
                        alert_messages(res.message,2);
                    }
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
            if($('select#action').val() == ''){
                alert_messages('Выберите действие',3);
                return false;

            }
            $.ajax({
                type: "POST",
                url: "/desktop/client-card",
                data: $(this).serialize(),
                success: function(data) {
                    var res = JSON.parse(data);
                    if(res.status=='success'){
                        alert_messages(res.message,1);
                    }
                    else{
                        alert_messages(res.message,2);
                    }
                    // $('ul#comments_list').prepend('<li>'+data+'</li>');
                    // $('form#comments textarea').val('');
                    // $('input[name="comment_send"]').val(1);
                },
            });
        }
        return false;
    });

    $(document).on('click','.js-feedback-trainer',function(){
        var input = $('#FeedbackTrainer').serialize();
        var trainer_id = $('.feedbackTrainerSelect').val();

        if($('#FeedbackTrainer input.feedbackTrainer').val().length == 0 || $('#FeedbackTrainer .feedbackTrainerSelect').val().length == 0) {
            alert('Не все поля заполнены');
            return false;
        }
            loader('show');
            $.ajax({
                type: "POST",
                url: "/desktop/client-card",
                data: input,
                success: function(data) {
                    console.log(data);
                    var res = JSON.parse(data);
                    if(res.status=='success'){
                        alert_messages(res.message,1);
                        //TODO:добавить нового тренера в историю и обновить истории и очистить поля
                        $(".feedbackTrainer").val('');
                        // Обновляем контент комент;
                        $.post('/desktop/feedback-trainer-comments',{f_comments:true,trainer_id: trainer_id ,client_id:$("input.client_id").val()},function(response) {
                            $(".content-feedback").html(response);
                            masonry_item();
                        });

                    }
                    else{
                        alert_messages(res.message,2);
                        $(".feedbackTrainer").val('');
                    }
                    loader('hide');
                },
            });

        return false;
    });

    /*
    $(document).on('change','.feedbackTrainerSelect',function() {
        loader('show');
        $.post('/desktop/feedback-trainer-comments',{f_comments:true,trainer_id:$(this).val(),client_id:$("input.client_id").val()},function(response) {
             $(".content-feedback").html(response);
             masonry_item();
             loader('hide');
        });
         return false;
    });*/

    //  Редактировать поле;
    $(document).on('click','.js-trainers-comments-update',function() {
        var comment_id = $(this).parents('.item').data('comment_id');
        var feedback = $(this).siblings('.feedback').val();

        loader('show');
        //
        $.post('/desktop/trainers-comments-update',{update_comments:true,comment_id:comment_id,feedback:feedback},function(response) {
            $(".content-feedback").html(response);
            masonry_item();
            alert_messages('Успешно сохранено',1);
            loader('hide');
        });
    });


    // Delete;
    $(document).on('click','.js-trainers-comments-delete',function() {
        var comment_id = $(this).parent('.item').data('comment_id');
        loader('show');
        //
        $.post('/desktop/trainers-comments-delete',{delete_comments:true,comment_id:comment_id},function(response) {
            $(".content-feedback").html(response);
            masonry_item();
            alert_messages('Успешно удалено',1);
            loader('hide');
        });
    });

    //
    $(document).on('click','.js-comments-text-show',function() {
         $(this).siblings('.content-text').toggle();
         masonry_item();
    });

    var phoneGlob = '';
    function call(phone, user){
        if(phoneGlob!=phone){
            phoneGlob = phone;
            console.log('call');
            $.post('/phone/index', {
                    'phone': phone,
                    'user':user});
        }
        else{
         console.log('call cancel');
        }
        //console.log($(this).attr('class'));
        //if($(this).attr('rel')==='false'){
            //$(this).attr('rel', 'true');
            //console.log($(this).attr('calldone'));

        //}
        return false;


    }

    $(document).on("click", ".infoItem", function(e) {
        //меняем чек бокс на выключено и пишеи history
        var element = $(this);

        // Тут мы блокируем
        element.prop({"disabled":true});
        if($(this).filter('disabled').length > 0) return false;

            loader('show');
            $.ajax({
                url: '/desktop/client-change-info',
                type: "post",
                data: {
                    'ClientInfoLinksId':$(this).attr('clientInfoLink'),
                    'ClientInfoLinksClientId':$(this).attr('client'),
                },
                success: function (data) {
                    //console.log(data);
                    $(".clients_info").html(data);
                    loader('hide');
                   // var resp = JSON.parse(data);

                    /*
                    if(resp.status=='true'){
                        if(resp.newval==1){
                            console.log('Adin');
                            console.log($('#checkBox').find('span').attr('class'));
                            element.children('.glyphicon glyphicon-ok').attr('class', '').addClass('greenText');
                        }
                        else if(resp.newval==0){
                            console.log('Dva');
                            console.log(element.children('.glyphicon glyphicon-ok'));
                            $(this).children('span').removeClass('greenText').removeClass('greyText').addClass('greyText');
                        }
                        else{
                            console.log('tree');
                            console.log($(this).children('span').attr('class'));
                            $(this).children('span').attr( 'class', '').addClass(currnetClass);
                        }
                    }
                    else{
                        $(this).children('span').attr( 'class', '').addClass(currnetClass);

                    }*/
                    element.prop({"disabled":false});

                    //вывести сообщение
                }
            });

        return false;


    });


    $(document).on("click", ".oldClientInfo", function(e) {
        console.log('oldClientInfo click');
        $(this).prop({"disabled":false});
        var tokenName = $('meta[name=csrf-param]').attr("content");
        var token = $('meta[name=csrf-token]').attr("content");

        $.ajax({
            url: '/desktop/client-old-info',
            type: "post",
            data: {
                //'TournamentLinks[card_id]':$(this).attr('cardID'),
                'ClientId':$(this).attr('client'),
                'ClientInfoId':$(this).attr('infoItem'),
                '_csrf':token
            },
            success: function (data) {
                //console.log(data);
            }
        });
        console.log('change');
    });


$(document).ready(function () {


   // alert_messages('Test Alert');

});




    console.log('desktop OK');

