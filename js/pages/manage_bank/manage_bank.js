let BankTable;

$(function () {
    //d.selectType = $('#selectMemberType option:selected').val()
    BankTable = $('#bank-grid').DataTable({
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "ajax": {   
            url: "ajax/ajax.bank.php",
            data: function (d) {
                d.action = "get_bank"
            },
            type: "post",
            error: function () {
            }
        },
        "columnDefs": [{
            targets: [0,1,2,3,4,5,6],
            orderable: false,
        }],
        "order": [[0, "asc"]],
        "pageLength": 50,
        "columns": [
            { "width": "5%" },
            { "width": "15%" },  
            { "width": "15%" },
            { "width": "15%" },
            { "width": "15%" },  
            { "width": "15%" }, 
             { "width": "20%" }
        ],
    });
});

function reloadTable() {

    BankTable.ajax.reload(null, false);

}





$("#add-datestart").flatpickr({

    "locale": 'th',

    // mode: "multiple",

    static: true,

    enableTime: true,

    dateFormat: "Y-m-d H:i",

    minDate: "today",

    disable: ["2018-09-19", "2018-09-20", "2018-09-21", new Date(2019, 8, 23)],

    onChange: function (selectedDates, dateStr, instance) {

        // let dateStart = flatpickr.parseDate(dateStr, "Y-m-d").fp_incr(1);

        // setDateflatpickr_addDateEnd(dateStr)

    }

});

$("#add-dateend").flatpickr({

    "locale": 'th',

    // mode: "multiple",

    static: true,

    enableTime: true,

    dateFormat: "Y-m-d H:i",

    minDate: "today",

    disable: ["2018-09-19", "2018-09-20", "2018-09-21", new Date(2019, 8, 23)],

});

// function setDateflatpickr_addDateEnd(_mindate) {

//     $("#add-dateend").flatpickr({

//         "locale": 'th',

//         static: true,

//         enableTime: true,

//         dateFormat: "Y-m-d H:i",

//         minDate: _mindate,

//         disable: ["2019-09-19", "2019-09-20", "2019-09-21", new Date(2019, 8, 23)],

//     });

// }





/**

 * บันทึก New SaveBank

 */

function SaveBank(e = window.event) {

    e.preventDefault();

    let data = {
        image: $('#inputFileImg').val().trim(),
        name:$('#add-name').val(),
        account:$('#add-bank').val(),
        number:$('#add-number').val(),
        action: "save_bank"
    }

    if (data.image.length == 0 || data.name.length == 0 || data.number.length == 0) {

        $.confirm({
            title: 'Warning',
            content: `Please fill out all information.`,
            theme: 'modern',
            icon: 'fa fa-times',
            type: 'red',
            draggable: false,
            backgroundDismiss: true,
            buttons: {
                confirm: {
                    text: 'OK',
                    btnClass: 'btn-red',
                    action: function () {
                    }
                }
            }
        });
        return false;
    }


    $.ajax({
        url: "ajax/ajax.bank.php",
        type: "POST",
        dataType: "json",
        data: data,
        success: function (response) {
            
            if (response.message == "OK") {
                $('#inputID').val(response.insert_id)
                $('#formUploadImg').submit();
            }
        }
    })
}





/**

 * กดปุ่ม แก้ไข เพื่อดูและแก้ไข

 */

function editBank(e = window.event, _id) {

    $.ajax({

        url: "ajax/ajax.bank.php",

        type: "POST",

        dataType: "json",

        data: { action: "getBankById", id: _id },

        success: function (response) {

            

            $('#edit-name').val(response.result.name)
            $('#edit-bank').val(response.result.account)
            $('#edit-number').val(response.result.number)
            

            $('#edit-img-handle-upload-image').attr('src',response.result.img)

            $('#edit-id').val(response.result.id)



            $('#EditFacilities').modal('show')

        }

    })

}





/**

 * ยืนยันการแก้ไขข้อมูล

 */

function EditSaveBank(event) {

    let data = {

        image: $('#edit-inputFileImg').val().trim(),
        name: $('#edit-name').val().trim(),
        account: $('#edit-bank').val().trim(),
        number: $('#edit-number').val().trim(),
        action: "editBank",

        id: $('#edit-id').val(),

    }



    $.ajax({

        url: "ajax/ajax.bank.php",

        type: "POST",

        dataType: "json",

        data: data,

        success: function (response) {

            

            if (response.message == "OK") {

                if(data.image.length != 0){

                    $('#edit-inputID').val(data.id);

                    $('#edit-formUploadImg').submit();

                }else{

                    $.confirm({

                        title: 'Success',

                        content: 'Successfully',

                        theme: 'modern',

                        icon: 'fa fa-check',

                        type: 'green',

                        draggable: false,

                        backgroundDismiss: true,

                        buttons: {

                            confirm: {

                                text: 'OK',

                                btnClass: 'btn-success',

                                action: function () {

                                    location.reload();

                                    reloadTable()

                                    $('#EditFacilities').modal('hide')

                                }

                            }

                        },

                        backgroundDismiss: function () {

                            //    location.reload();

                        }

                    });

                }

            }

        }

    })

}





/**

 * ลบข้อมูล member

 */

function delBank(e = window.event, _id) {

    $.confirm({

        title: 'Warning',

        content: 'delete a member',

        theme: 'modern',

        icon: 'fa fa-exclamation-circle',

        type: 'red',

        draggable: false,

        backgroundDismiss: true,

        buttons: {

            confirm: {

                text: 'OK',

                btnClass: 'btn-danger',

                action: function () {



                    $.ajax({

                        url: "ajax/ajax.bank.php",

                        type: "POST",

                        dataType: "json",

                        data: { action: "deleteBank", id: _id },

                        success: function (response) {



                            if (response.message == "OK") {

                                $.confirm({

                                    title: 'Success',

                                    content: 'Successfully',

                                    theme: 'modern',

                                    icon: 'fa fa-check',

                                    type: 'green',

                                    draggable: false,

                                    backgroundDismiss: true,

                                    buttons: {

                                        confirm: {

                                            text: 'OK',

                                            btnClass: 'btn-success',

                                            action: function () {

                                                //   location.reload();

                                                reloadTable()

                                            }

                                        }

                                    },

                                    backgroundDismiss: function () {

                                        location.reload();

                                    }

                                });

                            }

                        }

                    });



                }

            },

            cancel: {

                text: 'CANCEL',

            }

        },

        backgroundDismiss: function () {

            location.reload();

        }

    });

}



//Toggle Switch

$('.switch').on('click', (event) => {

    let _this = event.target;

    _this.closest('.toggle-switch').classList.toggle('ts-active')



    let status = _this.closest('.toggle-switch').classList.value.split(" ").find((e) => e === "ts-active")

    if (status == "ts-active") {

        console.log('if')

        $('#add-status').val('active')

        $('#edit-status').val('active')

    } else {

        console.log('else')

        $('#add-status').val('inactive')

        $('#edit-status').val('inactive')

    }

});



function PopupCenter(pageURL, title, w, h) {

    var left = (screen.width / 2) - (w / 2);

    var top = (screen.height / 2) - (h / 2);

    var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

    return targetWin;

}





// Add
$('#formUploadImg').on('submit', function (e) {
    e.preventDefault();
    let formData = new FormData($(this)[0]);
    formData.append("action", "uploadImg")
    $.ajax({
        url: "ajax/ajax.bank.php",
        type: "post",
        dataType: "json",
        data: formData,
        processData: false, //Not to process data
        contentType: false, //Not to set contentType
        beforeSend: function () {
            console.log('Load Start')
            $('.wrapper-pop').addClass('pop-active');
        },
        success: function (data) {
            if (data.message == "OK") {
                console.log(data);
                $.confirm({
                    title: 'Success',
                    content: 'Successfully',
                    theme: 'modern',
                    icon: 'fa fa-check',
                    type: 'green',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'ตกลง',
                            btnClass: 'btn-green',
                            action: function () {
                                location.reload();
                                reloadTable();
                                $('#CreateFacilities').modal('hide')
                            }
                        }
                    }
                });
            }
        },
        xhr: function () {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function (evt) {
                if (evt.lengthComputable) {
                    var pct = (evt.loaded / evt.total) * 100;
                    console.log('p1 => ' + pct.toPrecision(3))
                    $('.loadper').text(`${parseInt(pct)} %`)
                }
            }, false);
            xhr.addEventListener("progress", function (evt) {
                if (evt.lengthComputable) {
                    var pct = (evt.loaded / evt.total) * 100;
                    console.log('p2 => ' + pct.toPrecision(3))
                }
            }, false);
            return xhr;
        },
        complete: function () {
            console.log('Load End')
            $('.wrapper-pop').removeClass('pop-active');
        }
    });
})

$('.btn-click-upload-add').on('click', function (e) {
    e.preventDefault();
    $('#inputFileImg').click();
})

$('#img-handle-upload-image').on('click', function (e) {
    e.preventDefault();
    // $('#inputFileImg').click();
})
$('#inputFileImg').on('change', function (e) {
    $('.showFileNameImg').text($(this).val())
    readURL('#img-handle-upload-image', this);
});



// Edit

$('#edit-formUploadImg').on('submit', function (e) {
    e.preventDefault();
    let formData = new FormData($(this)[0]);
    formData.append("action", "uploadImg")
    $.ajax({
        url: "ajax/ajax.bank.php",
        type: "post",
        dataType: "json",
        data: formData,
        processData: false, //Not to process data
        contentType: false, //Not to set contentType
        beforeSend: function () {
            console.log('Load Start')
            $('.wrapper-pop').addClass('pop-active');
        },
        success: function (data) {
            if (data.message == "OK") {
                console.log(data);
                $.confirm({
                    title: 'Success',
                    content: 'Successfully',
                    theme: 'modern',
                    icon: 'fa fa-check',
                    type: 'green',
                    draggable: false,
                    backgroundDismiss: true,
                    buttons: {
                        confirm: {
                            text: 'OK',
                            btnClass: 'btn-success',
                            action: function () {
                                location.reload();
                                reloadTable()
                                $('#EditPromotion').modal('hide')
                            }
                        }
                    },
                    backgroundDismiss: function () {
                        //    location.reload();
                    }
                });
            }
        },

        xhr: function () {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function (evt) {
                if (evt.lengthComputable) {
                    var pct = (evt.loaded / evt.total) * 100;
                    console.log('p1 => ' + pct.toPrecision(3))
                    $('.loadper').text(`${parseInt(pct)} %`)
                }
            }, false);
            xhr.addEventListener("progress", function (evt) {
                if (evt.lengthComputable) {
                    var pct = (evt.loaded / evt.total) * 100;
                    console.log('p2 => ' + pct.toPrecision(3))
                }
            }, false);
            return xhr;
        },
        complete: function () {
            console.log('Load End')
            $('.wrapper-pop').removeClass('pop-active');
        }
    });
})

$('.btn-click-upload-edit').on('click', function (e) {
    e.preventDefault();
    $('#edit-inputFileImg').click();
})

$('#edit-img-handle-upload-image').on('click', function (e) {
    e.preventDefault();
    // $('#edit-inputFileImg').click();
})

$('#edit-inputFileImg').on('change', function (e) {
    $('.showFileNameImg').text($(this).val())
    readURL('#edit-img-handle-upload-image', this);
});





//อ่านไฟล์รูปภาพ แบบ Preview
function readURL(_name, input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        console.log('xx')
        reader.onload = function (e) {
            $(_name).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}