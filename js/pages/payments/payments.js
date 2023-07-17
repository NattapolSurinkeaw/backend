$(document).ready(function() {
    $("#single_1").fancybox({
        helpers: {
            title : {
                type : 'float'
            }
        }
    }); 
});

let paymentsTable; 
$(function() {
    //ตาราง Members
    paymentsTable = $('#members-grid').DataTable({
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "ajax/ajax.payments.php",
            data: function(d) { 
                d.action = "get_payments"; 
                if($("#slc_action").val() !== "all"){
                    d.method = $("#slc_action").val();
                }
                if($("#slc_status").val() !== "all"){
                    d.status_list = $("#slc_status").val();
                } 
            }, 
            type: "post",
            error: function() { 
       
            }
        },
        "columnDefs": [{
            targets: [1,3,5,7,8,10],
            orderable: false,
        }],
        "order": [
            [9, "DESC"]
        ],
        "pageLength": 50,
    });
});
$("#slc_action").on('change',function(){
    reloadTable();
})
$("#slc_status").on('change',function(){
    reloadTable();
})
$('#selectMemberType').on('change', function(e) {
    reloadTable();
})

/**
 * เมื่อกดปุ่ม +เพิ่มสมาชิก
 */
function openFormAddMember(e) {
    e = e || window.event;
    e.preventDefault();
    $('#formMembers').show();
    $('#add-member').show();
    $('#edit-member').hide();
    $('.m-id').hide();
    $('#formbtnpasswd').hide();
    $('#formlbpasswd').show();
    $('#forminputpasswd').show();
    clearFormMembers();
}

/**
 * เมื่อกดปุ่ม ยืนยันเพิ่มสมาชิก
 */
$('#add-member').on('click', function(e) {

    let member_type = $('#member-type').val();
    let member_name = $('#member-name').val();
    // let member_name_2 = $('#member-name2').val();
    let member_address = $('#member-address').val();
    let member_phone = $('#member-phone').val();
    let member_email = $('#member-email').val();
    // let member_email_sub = $('#member-email-sub').val();
    let member_password = $('#member-password').val();
    let member_status = $('#member-status').val();

    let _data = {
        // id: member_id,
        type: member_type,
        name: member_name,
        // name_2: member_name_2,
        address: member_address,
        phone: member_phone,
        email: member_email,
        // email_sub: member_email_sub,
        password: member_password,
        status: member_status,
        action: "addMembers"
    }

    $.ajax({
        url: "ajax/ajax.payments.php",
        type: "post",
        dataType: "json",
        data: _data,
        success: function(data) {

            if (data.message == "OK") {
                $.confirm({
                    title: 'สำเร็จ',
                    content: 'เพิ่มสมาชิกสำเร็จ',
                    theme: 'modern',
                    icon: 'fa fa-check',
                    type: 'green',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'ตกลง',
                            btnClass: 'btn-green',
                            action: function() {
                                clearFormMembers();
                                reloadTable()
                            }
                        }
                    }
                });
            } else {
                $.confirm({
                    title: 'แจ้งเตือน',
                    content: 'กรุณากรอกข้อมูลให้ครบ',
                    theme: 'modern',
                    icon: 'fa fa-times',
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'ตกลง',
                            btnClass: 'btn-red',
                            action: function() {}
                        }
                    }
                });
            }
        }
    })
});


/**
 * เช็คEmail ขณะ พิมพ์
 */
$('#member-email').on('keyup', function(e) {
    $.ajax({
        url: "ajax/ajax.payments.php",
        type: "post",
        dataType: "json",
        data: { action: "checkEmail", email: $(this).val() },
        success: function(data) {
            if (data.message == "OK") {
                $('#email_err').text('Email ถูกต้อง สามารถใช้งานได้')
                $('#email_err').css('color', 'mediumseagreen')
            } else if (data.message == "email_invalid") {
                $('#email_err').text('Email ไม่ถูกต้อง')
                $('#email_err').css('color', 'red')
            } else if (data.message == "email_used") {
                $('#email_err').text('Email นี้ไม่สามารถใช้งานได้ ')
                $('#email_err').css('color', 'red')
            }
        }
    })
});


/**
 * เมื่อกดปุ่ม ยืนยันแก้ไขสมาชิก
 */
$('#edit-member').on('click', function(e) {
    let member_id = $('#member-id').val();
    let member_type = $('#member-type').val();
    let member_name = $('#member-name').val();
    let member_name_2 = $('#member-name2').val();
    let member_address = $('#member-address').val();
    let member_phone = $('#member-phone').val();
    let member_email = $('#member-email').val();
    let member_email_sub = $('#member-email-sub').val();
    // let member_password = $('#member-password').val();
    let member_status = $('#member-status').val();

    let _data = {
        id: member_id,
        type: member_type,
        name: member_name,
        name_2: member_name_2,
        address: member_address,
        phone: member_phone,
        email: member_email,
        email_sub: member_email_sub,
        // password: member_password,
        status: member_status,
        action: "editMembers"
    }

    $.ajax({
        url: "ajax/ajax.payments.php",
        type: "post",
        dataType: "json",
        data: _data,
        success: function(data) {

            if (data.message == "OK") {
                $.confirm({
                    title: 'สำเร็จ',
                    content: 'แก้ไขสมาชิกสำเร็จ',
                    theme: 'modern',
                    icon: 'fa fa-check',
                    type: 'green',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'ตกลง',
                            btnClass: 'btn-green',
                            action: function() {
                                clearFormMembers();
                                reloadTable()
                            }
                        }
                    }
                });
            } else {
                $.confirm({
                    title: 'แจ้งเตือน',
                    content: 'กรุณากรอกข้อมูลให้ครบ',
                    theme: 'modern',
                    icon: 'fa fa-times',
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'ตกลง',
                            btnClass: 'btn-red',
                            action: function() {}
                        }
                    }
                });
            }
        }
    })
});

/**
 * กดดู
 * @param {*} e 
 * @param {*} _id 
 */
function showMembers(e, _id) {
    e = e || window.event;
    e.preventDefault();
    console.log('showMembers ' + _id)
    getMemberById(_id)
    $('.m-id').show();
    $('#add-member').hide();
    $('#edit-member').hide();
    $('#email_err').text('');
    $('#formbtnpasswd').hide();
    $('#formlbpasswd').show();
    $('#forminputpasswd').show();
}

/**
 * กดแก้ไข
 * @param {*} e 
 * @param {*} _id 
 */
function editMembers(e, _id) {
    e = e || window.event;
    e.preventDefault();
    console.log('editMember: ' + _id);
    getMemberById(_id)
    $('#add-member').hide();
    $('#edit-member').show();
    $('.m-id').show();
    $('#email_err').text('');

    $('#formbtnpasswd').show();
    $('#formlbpasswd').hide();
    $('#forminputpasswd').hide();
    $('#memberIdEditPassword').val(_id)
}

/**
 * กดลบ
 * @param {*} e 
 * @param {*} _id 
 */
function delMembers(e, _id) {
    e = e || window.event;
    e.preventDefault();
    console.log('delMembers ' + _id)
    $.confirm({
        title: 'แจ้งเตือน',
        content: 'ยืนยันการลบสมาชิก',
        theme: 'modern',
        icon: 'fa fa-trash',
        type: 'green',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: 'ตกลง',
                btnClass: 'btn-green',
                action: function() {
                    $.ajax({
                        url: "ajax/ajax.payments.php",
                        type: "post",
                        dataType: "json",
                        data: { action: "deleteMembers", id: _id },
                        success: function(data) {
                            $.confirm({
                                title: 'สำเร็จ',
                                content: 'ลบสมาชิกสำเร็จ',
                                theme: 'modern',
                                icon: 'fa fa-check',
                                type: 'green',
                                typeAnimated: true,
                                buttons: {
                                    tryAgain: {
                                        text: 'ตกลง',
                                        btnClass: 'btn-green',
                                        action: function() {
                                            reloadTable()
                                        }
                                    }
                                }
                            });
                        }
                    })
                }
            },
            formCancel: {
                text: 'ยกเลิก',
                btnClass: 'btn-red',
                cancel: function() {}
            }
        }
    });
    $('#email_err').text('');
}

function editMemberPassword(e) {
    e = e || window.event;
    e.preventDefault();
    let passNew = $('#member-edit-password').val().trim();
    let id = $('#memberIdEditPassword').val().trim();

    let _data = {
        action: "editMemberPasswordNew",
        password: passNew,
        id: id
    }

    if (passNew.length < 1) {
        $('.member-edit-password-error').text(`กรุณากรอก รหัสผ่านด้วยครับ`);
        $('#member-edit-password').css('border-color', 'red');
        $('#member-edit-password').attr('placeholder', 'กรุณากรอกรหัสผ่านด้วยครับ');
        return false;
    }

    $.ajax({
        url: "ajax/ajax.payments.php",
        type: "post",
        dataType: "json",
        data: _data,
        success: function(data) {
            if (data.message == "OK") {
                $('#member-edit-password').val('');
                $('#closeFormMemberEditPassword').click()
                $.confirm({
                    title: 'สำเร็จ',
                    content: 'แก้ไขรหัสผ่านสมาชิกสำเร็จ',
                    theme: 'modern',
                    icon: 'fa fa-check',
                    type: 'green',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'ตกลง',
                            btnClass: 'btn-green',
                            action: function() {
                                reloadTable()
                            }
                        }
                    }
                });
            }
        }
    })
}


/**
 * getMemberById
 */
function getMemberById(_id) {
    $.ajax({
        url: "ajax/ajax.payments.php",
        type: "post",
        dataType: "json",
        data: { action: "getMemberById", id: _id },
        success: function(data) {
            if (data.message == "OK") {
                console.log(data.result);
                $('#formMembers').show();
                $('#member-id').val(data.result.id);
                $('#member-type').val(data.result.type);
                $('#member-name').val(data.result.name);
                $('#member-name2').val(data.result.name_2);
                $('#member-address').val(data.result.address);
                $('#member-phone').val(data.result.phone);
                $('#member-email').val(data.result.email);
                $('#member-email-sub').val(data.result.email_sub);
                $('#member-password').val('**********');

                if (data.result.status == "active") {
                    $('.toggle-switch').addClass('ts-active')
                    $('#member-status').val('active');
                } else {
                    $('.toggle-switch').removeClass('ts-active')
                    $('#member-status').val('inactive');
                }
            } else {
                //Error
            }
        }
    })
}

/**
 * Clear ค่าที่อยู่ใน text input ให้เป็นค่าว่าง
 */
function clearFormMembers() {
    $('#member-id').val('');
    $('#member-type').val('');
    $('#member-name').val('');
    $('#member-name2').val('');
    $('#member-address').val('');
    $('#member-phone').val('');
    $('#member-email').val('');
    $('#member-email-sub').val('');
    $('#member-password').val('');

    $('.toggle-switch').removeClass('ts-active')
    $('#member-status').val('inactive');
}

//Toggle Switch
$('.switch').on('click', (event) => {
    let _this = event.target;
    _this.closest('.toggle-switch').classList.toggle('ts-active')

    let status = _this.closest('.toggle-switch').classList.value.split(" ").find((e) => e === "ts-active")
    if (status == "ts-active") {
        $('#member-status').val('active')
    } else {
        $('#member-status').val('inactive')
    }
})

function reloadTable() {
    paymentsTable.ajax.reload(null, false);
}

function PopupCenter(pageURL, title, w, h) {
    var left = (screen.width / 2) - (w / 2);
    var top = (screen.height / 2) - (h / 2);
    var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
    return targetWin;
}

function openFormAddImage(e) {
    e = e || window.event;
    e.preventDefault();


    $('#MemberUploadImage').modal('show')
    $('#img-handle-upload-image').attr('src', '/upload/excel/upload.png');
}


$('#formUploadImg').on('submit', function(e) {
    e.preventDefault();
    let formData = new FormData($(this)[0]);
    formData.append("action", "uploadImg")
    $.ajax({
        url: "ajax/ajax.payments.php",
        type: "post",
        dataType: "json",
        data: formData,
        processData: false, //Not to process data
        contentType: false, //Not to set contentType
        beforeSend: function() {
            console.log('Load Start')
            $('.wrapper-pop').addClass('pop-active');
        },
        success: function(data) {

            if (data.message == "OK") {
                console.log(data);
                $.confirm({
                    title: 'สำเร็จ',
                    content: 'อัพโหลดรูปภาพสำเร็จ',
                    theme: 'modern',
                    icon: 'fa fa-check',
                    type: 'green',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'ตกลง',
                            btnClass: 'btn-green',
                            action: function() {
                                // reloadTable();
                                $('#MemberUploadImage').modal('hide')
                            }
                        }
                    }
                });
            }

        },
        xhr: function() {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function(evt) {
                if (evt.lengthComputable) {
                    var pct = (evt.loaded / evt.total) * 100;
                    console.log('p1 => ' + pct.toPrecision(3))
                    $('.loadper').text(`${parseInt(pct)} %`)
                }
            }, false);

            xhr.addEventListener("progress", function(evt) {
                if (evt.lengthComputable) {
                    var pct = (evt.loaded / evt.total) * 100;
                    console.log('p2 => ' + pct.toPrecision(3))
                }
            }, false);

            return xhr;
        },
        complete: function() {
            console.log('Load End')
            $('.wrapper-pop').removeClass('pop-active');

        }
    });
})

$('#img-handle-upload-image').on('click', function(e) {
    e.preventDefault();
    $('#inputFileImg').click();
})

$('#inputFileImg').on('change', function(e) {
    $('.showFileNameImg').text($(this).val())
    readURL('#img-handle-upload-image', this);
});


//อ่านไฟล์รูปภาพ แบบ Preview
function readURL(_name, input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        console.log('xx')
        reader.onload = function(e) {
            $(_name).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$('.membersManagements').on('click','span',function(){
    if(!$(this).hasClass('active')){
        $('.membersManagements span').removeClass('active');
        $(this).addClass('active'); 
        let id =  $('#'+$(this).data('type')); 
        $(".mng-blog").removeClass('active');
        id.addClass('active');
    }
});


 
    










