let CertifyTable;
let CertifyReportTable;

$(function () {

    CertifyTable = $('#certify-grid').DataTable({
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "ajax/ajax.certify.php",
            data: {
                action: "get_certifyTableGrid"
            },
            type: "post",
            error: function () {

            }
        },
        "columnDefs": [{
            targets: [0, 2, 3, 4, 5, 6],
            orderable: false,
        }],
        "order": [
            [1, "asc"]
        ],
        "pageLength": 50,
    });

    CertifyReportTable = $('#certify-report-grid').DataTable({
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "ajax/ajax.certify.php",
            data: function(d){
                d.action = "get_certifyReportTableGrid",
                d.selectCertifyTitle = $('#selectCertifyTitle option:selected').val()
            },
            type: "post",
            error: function () {

            }
        },
        "columnDefs": [{
            targets: [0,1, 2, 3, 4, 5, 6,7,8],
            orderable: false,
        }],
        "order": [
            [1, "asc"]
        ],
        "pageLength": 50,
    });

});



function toggle_switch(e,_id){
    e = e || window.event;
    e.preventDefault();
    let _this = event.target;
    _this.closest('.toggle-switch').classList.toggle('ts-active')

    let status = _this.closest('.toggle-switch').classList.value.split(" ").find((e) => e === "ts-active")

    let _s = (status != undefined)?"open":"close";
    let _data = {
        action: "editStatusCertify",
        status: _s,
        ct_id:_id
    }
   
    $.ajax({
        url:"ajax/ajax.certify.php",
        type:"post",
        dataType:"json",
        data:_data,
        success:function(data){
            console.log(data)
            if(data.message == "OK"){
                $.confirm({
                    title: 'สำเร็จ',
                    content: 'เปลี่ยนข้อมูลสำเร็จ',
                    theme: 'modern',
                    icon: 'fa fa-check',
                    type: 'green',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'ตกลง',
                            btnClass: 'btn-green',
                            action: function () {
                                // location.reload();
                                // reloadTable();
                                // clearFormAddProductCate()
                            }
                        }
                    }
                });
            }
        }
    })
}

function reloadTable() {
    CertifyTable.ajax.reload(null, false);
}

function reloadTableReport() {
    CertifyReportTable.ajax.reload(null, false);
}


$('#formupload').on('submit', function (e) {
    e.preventDefault();
    let formData = new FormData($(this)[0]);
    formData.append("action", "uploadExcel")
    $.ajax({
        url: "ajax/ajax.certify.php",
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
            // console.log(data);
            switch (data.message) {
                case 'OK':
                    $.confirm({
                        title: 'สำเร็จ',
                        content: 'อัพโหลดสำเร็จ',
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
                                    // reloadTable();
                                    // clearFormAddProductCate()
                                }
                            }
                        }
                    });
                    break;
                case 'Error':
                    $titleError = "";
                    if (data.detail == "error_type") {
                        $titleError = "type ไม่ตรงตามที่กำหนด";
                    } else if (data.detail == "error_extension") {
                        $titleError = "นามสกุลไฟล์ไม่ตรงตามที่กำหนด กรุณาอัพ ไฟล์ xlsx , xlsm , xls";
                    } else if (data.detail == "Upload_Failed") {
                        $titleError = "Upload_Failed";
                    }

                    $.confirm({
                        title: 'ไม่สำเร็จ',
                        content: $titleError,
                        theme: 'modern',
                        icon: 'fa fa-times',
                        type: 'red',
                        typeAnimated: true,
                        buttons: {
                            tryAgain: {
                                text: 'ตกลง',
                                btnClass: 'btn-red',
                                action: function () {
                                    location.reload();
                                    // reloadTable();
                                    // clearFormAddProductCate()
                                }
                            }
                        }
                    })

                    break;
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

function openFormAddCerify(e) {
    e = e || window.event;
    e.preventDefault();
    $('#formUploadExcel').show();
    $('#formUploadImgWrap').hide();
}

$('#img-handle-upload').on('click', function (e) {
    e.preventDefault();
    $('#inputFile').click();
})

$('#inputFile').on('change', function (e) {
    let file = $(this).val();

    let filesplit = file.split('.');
    fileExtension = filesplit[filesplit.length - 1];
    // console.log(fileExtension)

    if (fileExtension != "xlsx" && fileExtension != "xlsm" && fileExtension != "xls") {

        $.confirm({
            title: 'แจ้งเตือน',
            content: 'นามสกุลไฟล์ไม่ตรงตามที่กำหนด กรุณาอัพ ไฟล์ xlsx , xlsm , xls',
            theme: 'modern',
            icon: 'fa fa-times',
            type: 'red',
            typeAnimated: true,
            buttons: {
                tryAgain: {
                    text: 'ตกลง',
                    btnClass: 'btn-red',
                    action: function () {
                        // return false;
                        // reloadTable();
                        // clearFormAddProductCate()
                    }
                }
            }
        })
    } else {
        $('.showFileName').text($(this).val())
        $('#img-handle-upload').attr('src', '/upload/excel/excel.png')
    }

})


function openFormAddImage(e) {
    e = e || window.event;
    e.preventDefault();
    $('#formUploadImgWrap').show();
    $('#formUploadExcel').hide();

    $('#img-handle-upload-image').attr('src','/upload/excel/upload.png');
    $('.titleLink').text('');
    $('.linkImg').val()
    $('.linkImg').hide()
    $('.previewImg').hide()

}

$('#formUploadImg').on('submit', function (e) {
    e.preventDefault();
    let formData = new FormData($(this)[0]);
    formData.append("action", "uploadImg")
    $.ajax({
        url: "ajax/ajax.certify.php",
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
            console.log(data);
            if (data.message == "OK") {

                $('.linkImg').val(data.urlimg);
                $('.linkImg').show();
                $('.titleLink').text('Copy link ด้านล่าง ไปใส่ใน Excel');
                $('.previewImg').attr('href', data.urlimg);
                $('.previewImg').show();
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

$('#img-handle-upload-image').on('click', function (e) {
    e.preventDefault();
    $('#inputFileImg').click();
})

$('#inputFileImg').on('change', function (e) {
    $('.showFileNameImg').text($(this).val())
    readURL('#img-handle-upload-image', this);
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


function delCertifyByCT_ID(e, ct_id) {
    e = e || window.event;
    e.preventDefault();

    let _data = {
        action: "deleteCertify",
        ct_id: ct_id
    }

    $.confirm({
        title: 'แจ้งเตือน',
        content: 'ยืนยันการลบ',
        theme: 'modern',
        icon: 'fa fa-exclamation-triangle',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: 'ตกลง',
                btnClass: 'btn-green',
                action: function () {

                    //*-------------
                    $.ajax({
                        type: "POST",
                        url: "ajax/ajax.certify.php",
                        dataType: 'json',
                        data: _data,
                        success: function (data) {

                            if (data.message == "OK") {
                                $.confirm({
                                    title: 'สำเร็จ',
                                    content: 'ลบข้อมูลสำเร็จ',
                                    theme: 'modern',
                                    icon: 'fa fa-check',
                                    type: 'green',
                                    typeAnimated: true,
                                    buttons: {
                                        tryAgain: {
                                            text: 'ตกลง',
                                            btnClass: 'btn-green',
                                            action: function () {
                                                reloadTable();
                                            }
                                        }
                                    }
                                });
                            } else {
                                $.confirm({
                                    title: 'แจ้งเตือน',
                                    content: 'ไม่สามารถลบข้อมูลนี้ได้',
                                    theme: 'modern',
                                    icon: 'fa fa-times',
                                    type: 'red',
                                    typeAnimated: true,
                                    buttons: {
                                        tryAgain: {
                                            text: 'ตกลง',
                                            btnClass: 'btn-red',
                                            action: function () {

                                            }
                                        }
                                    }
                                });
                            }


                        }
                    })

                    //*-------------

                }
            },
            formCancel: {
                text: 'ยกเลิก',
                btnClass: 'btn-red',
                cancel: function () { }
            }
        }
    });

}


//โชว์ข้อสอบรายบุลคน หน้า Report
function showCertifyLogByLog_id(e,log_id){
    e = e || window.event;
    e.preventDefault();
    $('#model-show-certify-by-logid').show();
    $.ajax({
        url:"ajax/ajax.certify.php",
        type:"post",
        dataType:"json",
        data:{action:"getCertifyLogBy_logID",log_id:log_id},
        success:function(data){
            console.log(data)
            if(data.message == "OK"){
                $('#model-show-certify-by-logid-body').html(data.res);
                $('.modal-title').html(data.title);
                $('.modal-member-name').html(`ชื่อ ${data.memberName}`);
                $('.modal-date').html(`เวลา ${data.date}`);
                $('.modal-point').html(`คะแนนที่ได้ ${data.point} คะแนน`);
            }
        }
    })
}


//ลบข้อสอบรายบุลคน หน้า Report
function delCertifyLogByLog_id(e,log_id){
    e = e || window.event;
    e.preventDefault();


    $.confirm({
        title: 'แจ้งเตือน',
        content: 'ยืนยันการลบ',
        theme: 'modern',
        icon: 'fa fa-exclamation-triangle',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: 'ตกลง',
                btnClass: 'btn-green',
                action: function () {

                    //*-------------
                    $.ajax({
                        type: "POST",
                        url: "ajax/ajax.certify.php",
                        dataType: 'json',
                        data: {action:"deleteCertifyLogBy_logID",log_id:log_id},
                        success: function (data) {

                            if (data.message == "OK") {
                                $.confirm({
                                    title: 'สำเร็จ',
                                    content: 'ลบข้อมูลสำเร็จ',
                                    theme: 'modern',
                                    icon: 'fa fa-check',
                                    type: 'green',
                                    typeAnimated: true,
                                    buttons: {
                                        tryAgain: {
                                            text: 'ตกลง',
                                            btnClass: 'btn-green',
                                            action: function () {
                                                reloadTable();
                                            }
                                        }
                                    }
                                });
                            } else {
                                $.confirm({
                                    title: 'แจ้งเตือน',
                                    content: 'ไม่สามารถลบข้อมูลนี้ได้',
                                    theme: 'modern',
                                    icon: 'fa fa-times',
                                    type: 'red',
                                    typeAnimated: true,
                                    buttons: {
                                        tryAgain: {
                                            text: 'ตกลง',
                                            btnClass: 'btn-red',
                                            action: function () {

                                            }
                                        }
                                    }
                                });
                            }


                        }
                    })

                    //*-------------

                }
            },
            formCancel: {
                text: 'ยกเลิก',
                btnClass: 'btn-red',
                cancel: function () { }
            }
        }
    });
}


function clearModalCertify(e){
    e = e || window.event;
    e.preventDefault();

    document.querySelector('#model-show-certify-by-logid').style.display='';
    $('#model-show-certify-by-logid-body').html(``);
    $('.modal-title').html(``);
}


$('.selectCertifyTitle').on('change',function(e){
    console.log( $(this).val() )
    if( $(this).val() == "" ){
        console.log('Empty');
        return false;
    }
    console.log('Has Data => ' + $(this).val());
    reloadTableReport();
});


function savePercentScore(e,_id){
    // console.log( $('.percent-score[data-id="id-'+_id+'"]').val() )
    let _data = {
        action:"updatePercentScore",
        id:_id,
        percent_score:$('.percent-score[data-id="id-'+_id+'"]').val()
    }
    $.ajax({
        type: "POST",
        url: "ajax/ajax.certify.php",
        dataType: 'json',
        data: _data,
        success: function (data) {

            if (data.message == "OK") {
                $.confirm({
                    title: 'สำเร็จ',
                    content: 'Update data success',
                    theme: 'modern',
                    icon: 'fa fa-check',
                    type: 'green',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'ตกลง',
                            btnClass: 'btn-green',
                            action: function () {
                                reloadTable();
                            }
                        }
                    }
                });
            }
            if (data.message == "Error") {
                $.confirm({
                    title: 'แจ้งเตือน',
                    content: 'Error',
                    theme: 'modern',
                    icon: 'fa fa-times',
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'ตกลง',
                            btnClass: 'btn-green',
                            action: function () {
                                reloadTable();
                            }
                        }
                    }
                });
            }
        }
    })
}
function sendEmailCertify(e,_log){

    e = e || window.event;
    e.preventDefault();
    
    let _data = {
        action:"sendEmailCertify",
        log:_log,
    }

    $.ajax({
        type: "POST",
        url: "ajax/ajax.certify.php",
        dataType: 'json',
        data: _data,
        success: function (data) {

            console.log(data)
            // if (data.message == "OK") {
            //     $.confirm({
            //         title: 'สำเร็จ',
            //         content: 'Update data success',
            //         theme: 'modern',
            //         icon: 'fa fa-check',
            //         type: 'green',
            //         typeAnimated: true,
            //         buttons: {
            //             tryAgain: {
            //                 text: 'ตกลง',
            //                 btnClass: 'btn-green',
            //                 action: function () {
            //                     reloadTable();
            //                 }
            //             }
            //         }
            //     });
            // }
            // if (data.message == "Error") {
            //     $.confirm({
            //         title: 'แจ้งเตือน',
            //         content: 'Error',
            //         theme: 'modern',
            //         icon: 'fa fa-times',
            //         type: 'red',
            //         typeAnimated: true,
            //         buttons: {
            //             tryAgain: {
            //                 text: 'ตกลง',
            //                 btnClass: 'btn-green',
            //                 action: function () {
            //                     reloadTable();
            //                 }
            //             }
            //         }
            //     });
            // }

        }
    })
}


