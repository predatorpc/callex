
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
                    alert(res.message);

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
                alert('Выберите действие');
                return false;

            }
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
        var currnetClass=$(this).children('span').attr('class');
        // Тут мы блокируем
        element.prop({"disabled":true});
        if($(this).filter('disabled').length > 0){
            return false;
        }
        else{
            $.ajax({
                url: '/desktop/client-change-info',
                type: "post",
                data: {
                    'ClientInfoLinksId':$(this).attr('clientInfoLink'),
                    'ClientInfoLinksClientId':$(this).attr('client'),
                },
                success: function (data) {
                    console.log(data);
                    var resp = JSON.parse(data);


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

                    }
                    element.prop({"disabled":false});

                    //вывести сообщение
                }
            });
        }

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
                console.log(data);

                //var resp = JSON.parse(data);
                // if(parseInt(resp.tournamentLinkId)>0){
                //     // setTimeout(function() {
                //     //     $('#savedStatus'+resp.tournamentLinkId).empty();
                //     // },3000);
                // }
                // else{
                //     alert(resp.message);
                // }
            }
        });
        console.log('change');
    });




    console.log('desktop OK');

