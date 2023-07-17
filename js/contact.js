var errMsg = '';
$('body').on('click', '.send_contact', function(e){
    errMsg = '';
    //console.log( ($('.contact_name').val()).length );
    if( ($('.name').val()).length < 3 ){
        errMsg += 'กรุณาใส่ชื่อ ,';
    }
    if( ($('.lastname').val()).length < 3 ){
        errMsg += 'กรุณาใส่นามสกุล ,';
    }
    if( ($('.email').val()).length < 3 ){
        errMsg += 'กรุณาใส่อีเมล ,';
    }
    if( ($('.phone').val()).length < 3 ){
        errMsg += 'กรุณาใส่เบอร์โทรศัพท์ ,';
    }
    if( ($('.budget').val()).length < 3 ){
        errMsg += 'กรุณาระบุงบประมาณของท่าน ,';
    }
    if( ($('.purpose').val()).length < 3 ){
        errMsg += 'กรุณาระบุราคาที่ต้องการ ,';
    }
    if( ($('.message').val()).length < 3 ){
        errMsg += 'กรุณาใส่ข้อความที่ต้องการติดต่อเรา';
    }
    if( errMsg.length > 2){
        $.confirm({
            title: 'กรุณากรอกข้อมูลให้ครบถ้วน',
            content: errMsg,
            type: 'red',
            typeAnimated: true,
            buttons: {
                tryAgain: {
                    text: 'Try again',
                    btnClass: 'btn-red',
                    action: function(){
                    }
                },
                close: function () {
                }
            }
        });
    }else{
        var dataSet = {
            action: 'send_contact',
            name:$('.name').val(),
            lastname:$('.lastname').val(),
            email:$('.email').val(),
            phone:$('.phone').val(),
            budget:$('.budget').val(),
            purpose:$('.purpose').val(),
            message:$('.message').val()
        };
        console.log(dataSet);
        //send_contact(dataSet);
    }
});

function send_contact(dataSet){
    $.ajax({
        type:"POST",
        url:"ajax/ajax.contact.php",
        data:dataSet,
        beforeSend: function(){
        },
        success:function(msg){
            obj = $.parseJSON(msg);
            console.log(obj[0]['message']);
            if( obj[0]['message'] == 'OK' ){
                $.confirm({
                    title: 'ส่งข้อความของคุณแล้ว',
                    content: 'WE GOT YOUR MESSGAE :D',
                    type: 'blue',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'OK',
                            btnClass: 'btn-info',
                            action: function(){
                                window.location.href = '';
                            }
                        },
                        close: function () {
                            window.location.href = '';
                        }
                    }
                });
            }else{
                $.confirm({
                    title: 'ส่งข้อความไม่สำเร็จ',
                    content: "WE CAN'T RECEIVE YOUR MESSAGE :( ",
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'OK',
                            btnClass: 'btn-red',
                            action: function(){
                            }
                        },
                        close: function () {
                        }
                    }
                });
            }
        }
    });
}


