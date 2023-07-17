let programsTable;

$(function () {
    console.log('Start Programs')
    programsTable = $('#programs-grid').DataTable({
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "ajax/ajax.programsNew.php",
            data: { action: "get_programsTable" },
            type: "post",
            error: function () {
                asdas
            }
        },
        "columnDefs": [{
            targets: [0, 1, 2, 3, 4, 5, 6, 7],
            orderable: false,
        }],
        "order": [
            [1, "asc"]
        ],
        "pageLength": 50,
    });

});

function reloadTable() {
    programsTable.ajax.reload(null, false);
}

function clearDataModalForm() {
    $('#Modaltitle').text('เพิ่มข้อมูล');
    $('#name').val('');
    $('#url').val('');
    $('.blog-preview-add').html(``)
}

function OPenFormAdd(e) {
    e = e || window.event;
    e.preventDefault();
    clearDataModalForm();
    $('#ModalPrograms').modal('toggle')
    $('#Modaltitle').data('type', 'add');
}

function editPrograms(e, _id) {
    e = e || window.event;
    e.preventDefault();
    clearDataModalForm();
    $.ajax({
        url: "ajax/ajax.programsNew.php",
        type: "post",
        dataType: "json",
        data: { action: "getProgramsById", id: _id },
        success: function (data) {
            console.log(data.res)
            if (data.message == "OK") {
                $('#Modaltitle').text('แก้ไขข้อมูล');
                $('#name').val(data.res['title']);
                $('#url').val(data.res['short_url']);
                $('#idEdit').val(data.res['id']);
                $('.blog-preview-add').html(`
                    <div class="col-img-preview">
                    <img class="preview-img" src="/${data.res['thumbnail']}">
                    </div>
                `)
                $('#ModalPrograms').modal('toggle')
                $('#Modaltitle').data('type', 'edit');
            }
        }
    })
}

function delProgramsById(e, _id) {
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
                        url: "ajax/ajax.programsNew.php",
                        dataType: 'json',
                        data: { action: "delProgramsById", id: _id },
                        success: function (data) {

                            if (data.message == "OK") {
                                $.confirm({
                                    title: 'สำเร็จ',
                                    content: 'ลบหมวดหมู่สำเร็จ',
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

function PopupCenter(pageURL, title, w, h) {
    var left = (screen.width / 2) - (w / 2);
    var top = (screen.height / 2) - (h / 2);
    var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
    return targetWin;
}


//ยืนยันการเพิ่มหมวดหมู่
$('#addPrograms').on('click', function () {

    let type = $('#Modaltitle').data('type');
    if (type == "edit") {
        if ($('#name').val().trim().length == 0 ||
            $('#url').val().trim().length == 0
        ) {
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
                        action: function () { }
                    }
                }
            });
            return false;
        }

        var data = {
            'action': 'edit_programs',
            'name': $('#name').val().trim(),
            'url': $('#url').val().trim(),
            'id': $('#idEdit').val().trim(),
        }

    } else {
        if ($('#name').val().trim().length == 0 ||
            $('#url').val().trim().length == 0 ||
            formdata.getAll("images[]").length == 0 ||
            $('#add-images-content-hidden').val().trim().length == 0
        ) {
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
                        action: function () { }
                    }
                }
            });
            return false;
        }
        var data = {
            'action': 'add_programs',
            'name': $('#name').val().trim(),
            'url': $('#url').val().trim(),
        }
    }


    $.ajax({
        type: 'POST',
        url: 'ajax/ajax.programsNew.php',
        dataType: 'json',
        data: data,
        success: function (data) {
            console.log(data);
            if (data.res.message == "OK") {
                // if (formdata.getAll("images[]").length !== 0) {
                    uploadimages(data.insert_id, "uploadimgcontent");
                // }
            }

        }
    })
})


// ฟังชั่น Upload รูปภาพเด้อ
function uploadimages(id, action) {
    formdata.append("action", action);
    formdata.append("id", id);

    $.ajax({
        url: "ajax/ajax.programsNew.php",
        type: 'POST',
        data: formdata,
        processData: false,
        contentType: false,
        beforeSend: function () {
            console.log('Load Start')
            $('.wrapper-pop').addClass('pop-active');
        },
        success: function (obj) {

            $.confirm({
                title: 'สำเร็จ',
                content: 'เพิ่มหมวดหมู่สำเร็จ',
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

        },
        complete: function () {
            console.log('Load End')
            $('.wrapper-pop').removeClass('pop-active');
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
        }
    });
}

// upload images
$("#add-images-content").uploadImage({
    preview: true
});
$("#add-images-content").on("change", function () {
    if (formdata.getAll("images[]").length !== 0) {
        console.log('Test')
        var img = formdata.getAll("images[]")["0"].name;
        $('#add-images-content-hidden').val(img);
        $(".form-add-images").removeClass("has-error");
        $(".add-images-error").css("display", "none");
    }
});



$('#add-date-display').datepicker({
    format: 'dd/mm/yyyy',
    autoclose: true,
    language: 'th',
    todayHighlight: true
}).on('changeDate', function (e) {
    $('#add-date-display-hidden').val(e.format('yyyy-mm-dd'));
});

//timepicker
$("#add-time-display").timepicker({
    defaultTime: false,
    showInputs: false,
    minuteStep: 1,
    showMeridian: false
});

// ฟังชั่นEdit Product Cate
function editSaveProductCateById() {

    let edit_id = $('#edit_product_cate_id').val().trim();
    let edit_name = $('#product_cate_name').val().trim();
    let edit_status = $('#product_cate_status').val().trim();
    let edit_priority = $('#product_cate_priority').val().trim();

    if (edit_name.length == 0) {
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
                    action: function () { }
                }
            }
        });
        return false;
    }

    let data = {
        'action': "editProductCate",
        'id': edit_id,
        'name': edit_name,
        'status': edit_status,
        'priority': edit_priority
    }


    $.ajax({
        type: "POST",
        url: "ajax/ajax.manage_products.php",
        dataType: 'json',
        data: data,
        success: function (data) {

            if (data.message == "OK") {
                uploadimages(edit_id, "uploadimgcontent");
            }

            // if(data.message == "OK"){
            //   $.confirm({
            //     title: 'สำเร็จ',
            //     content: 'แก้ไขหมวดหมู่สำเร็จ',
            //     theme: 'modern',
            //     icon: 'fa fa-check',
            //     type: 'green',
            //     typeAnimated: true,
            //     buttons: {
            //       tryAgain: {
            //         text: 'ตกลง',
            //         btnClass: 'btn-green',
            //         action: function () {
            //           uploadimages(edit_id, "uploadimgcontent");
            //           reloadTable();
            //           clearFormAddProductCate()
            //         }
            //       }
            //     }
            //   });
            // }

        }
    })
}
