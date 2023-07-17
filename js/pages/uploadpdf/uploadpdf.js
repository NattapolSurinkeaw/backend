let salesTable;

$(function() {

    salesTable = $('#UploadPdf-grid').DataTable({
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "ajax/ajax.uploadpdf.php",
            data: { action: "get_pdf" },
            type: "post",
            error: function() {

            }
        },
        "columnDefs": [{
            targets: [0, 1,2,3,4,5,6],
            orderable: false,
        }],
        "order": [
            [1, "asc"]
        ],
        "pageLength": 50,
    });

});

function reloadTable() {
    salesTable.ajax.reload(null, false);
}

function PopupCenter(pageURL, title, w, h) {
    var left = (screen.width / 2) - (w / 2);
    var top = (screen.height / 2) - (h / 2);
    var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
    return targetWin;
}

function showFormAddUploadPdf(e){
    e = e || window.event;
    e.preventDefault();
    $('#formUploadPdf').show();
    $('#edit-formUploadPdf').hide();
    // console.log('show Add');

    
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

    if (fileExtension != "pdf") {

        $.confirm({
            title: 'แจ้งเตือน',
            content: 'นามสกุลไฟล์ไม่ตรงตามที่กำหนด กรุณาอัพ ไฟล์ pdf',
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
        $('#img-handle-upload').attr('src', '/upload/pdf/pdf.png')
    }

})


$('#formupload').on('submit', function (e) {
    e.preventDefault();
    let formData = new FormData($(this)[0]);
    formData.append("action", "uploadExcel")
    formData.append("name", $('#pdfname').val())
    formData.append("category", $('#pdfcategory').val())
    $.ajax({
        url: "ajax/ajax.uploadpdf.php",
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
                        $titleError = "นามสกุลไฟล์ไม่ตรงตามที่กำหนด กรุณาอัพ ไฟล์ pdf";
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

function deletePDF(e,_id){
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
                action: function() {

                    //*-------------
                    $.ajax({
                        type: "POST",
                        url: "ajax/ajax.uploadpdf.php",
                        dataType: 'json',
                        data: { action: "deletePdf", id: _id },
                        success: function(data) {

                            console.log(data)
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
                                        action: function() {
                                            reloadTable();
                                        }
                                    }
                                }
                            });

                        }
                    })

                    //*-------------

                }
            },
            formCancel: {
                text: 'ยกเลิก',
                btnClass: 'btn-red',
                cancel: function() {}
            }
        }
    });
}

function editPDF(e,_id){
    e = e || window.event;
    e.preventDefault();

    $('#edit-pdfcategory').val('');
    $('#edit-pdfname').val('');
    $('#formUploadPdf').hide();
    $('#edit-formUploadPdf').show();

    

    $('html,body').animate({
        scrollTop: $("#edit-formUploadPdf").offset().top - 100
    }, 'slow');
    $('#edit-pdfcategory').focus();


    $.ajax({
        url:"ajax/ajax.uploadpdf.php",
        type:"post",
        dataType:"json",
        data:{action:"getById",id:_id},
        success:function(data){
            console.log(data)
            if(data.message == "OK"){
                $('#edit-pdfcategory').val(data.res.category);
                $('#edit-pdfname').val(data.res.name);
                $('#edit-pdfid').val(_id);
            }
        }
    })
}

$('#edit-formupload').on('submit', function (e) {
    e.preventDefault();
    let formData = new FormData($(this)[0]);
    formData.append("action", "edit_uploadExcel")
    formData.append("name", $('#edit-pdfname').val())
    formData.append("category", $('#edit-pdfcategory').val())
    formData.append("id", $('#edit-pdfid').val())


    $.ajax({
        url: "ajax/ajax.uploadpdf.php",
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

            if(data.message == "OK"){
                $.confirm({
                    title: 'สำเร็จ',
                    content: 'แก้ไขข้อมูลสำเร็จ',
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