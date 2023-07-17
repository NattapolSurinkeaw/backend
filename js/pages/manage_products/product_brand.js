let Table;
$(function() {

    Table = $('#product-cate-grid').DataTable({
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "ajax/ajax.manage_products.php",
            data: { action: "get_BrandProduct" },
            type: "post",
            error: function() {

            }
        },
        "columnDefs": [{
            targets: [0, 3, 5],
            orderable: false,
        }],
        "order": [
            [1, "asc"]
        ],
        "pageLength": 50,
    });

});

function reloadTable() {
    Table.ajax.reload(null, false);
}

//Toggle Switch
$('.switch').on('click', (event) => {
    let _this = event.target;
    _this.closest('.toggle-switch').classList.toggle('ts-active')

    let status = _this.closest('.toggle-switch').classList.value.split(" ").find((e) => e === "ts-active")
    if (status == "ts-active") {
        $('#product_brand_status').val('yes')
    } else {
        $('#product_brand_status').val('no')
    }
})


$('#add_product_brand').on('click', function() {

    if ($('#product_brand_name').val().trim().length == 0 ||
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
                    action: function() {}
                }
            }
        });
        return false;
    }

    let data = {
        'action': 'add_product_bran',
        'name': $('#product_brand_name').val().trim(),
        'status': $('#product_brand_status').val().trim(),
        'priority': $('#product_brand_priority').val().trim()
    }

    $.ajax({
        type: 'POST',
        url: 'ajax/ajax.manage_products.php',
        dataType: 'json',
        data: data,
        success: function(data) {
            // console.log(data);
            // return false;
            if (formdata.getAll("images[]").length !== 0) {
                uploadimages(data.insert_id, "uploadimgbrand");
            }
        }
    })
})


// ฟังชั่น Upload รูปภาพเด้อ
function uploadimages(id, action) {
    formdata.append("action", action);
    formdata.append("id", id);

    $.ajax({
        url: "ajax/ajax.manage_products.php",
        type: 'POST',
        data: formdata,
        processData: false,
        contentType: false,
        beforeSend: function() {
            console.log('Load Start')
            $('.wrapper-pop').addClass('pop-active');
        },
        success: function(obj) {

            $.confirm({
                title: 'สำเร็จ',
                content: 'เพิ่ม Brand สำเร็จ',
                theme: 'modern',
                icon: 'fa fa-check',
                type: 'green',
                typeAnimated: true,
                buttons: {
                    tryAgain: {
                        text: 'ตกลง',
                        btnClass: 'btn-green',
                        action: function() {
                            location.reload();
                            // reloadTable();
                            // clearFormAddProductCate()
                        }
                    }
                }
            });

        },
        complete: function() {
            console.log('Load End')
            $('.wrapper-pop').removeClass('pop-active');
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
        }
    });
}

// upload images
$("#add-images-content").uploadImage({
    preview: true
});
$("#add-images-content").on("change", function() {
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
}).on('changeDate', function(e) {
    $('#add-date-display-hidden').val(e.format('yyyy-mm-dd'));
});

//timepicker
$("#add-time-display").timepicker({
    defaultTime: false,
    showInputs: false,
    minuteStep: 1,
    showMeridian: false
});

// Show Form เพิ่มข้อมูล หมวดหมู่สินค้า
function showFormAddProductBrand() {

    $.ajax({
        url: "ajax/ajax.manage_products.php",
        type: "post",
        dataType: "json",
        data: { action: "getMaxPriorityBrand" },
        success: function(data) {

            if (data.message == "OK") {
                $('#product_brand_priority').val(data.priority);
                $('#form-add-product-cate').show();
                $('#add_product_brand').show();
                $('#edit_product_brand').hide();
                $('#product_brand_name').val('');
                $('#product_brand_create').val('');
                $('#product_brand_update').val('');
                $('.toggle-switch').removeClass('ts-active')
                $('.preview-img').remove();
                $('.ve_product_brand').hide();
            }
        }
    })
}

// ฟังชั่นView Product Cate
function viewProductCateById(_id) {
    $.ajax({
        type: "POST",
        url: "ajax/ajax.manage_products.php",
        dataType: 'json',
        data: { action: "viewProductBrand", id: _id },
        success: function(data) {
            // console.log(data)
            $('#form-add-product-cate').show();
            $('#product_brand_name').val(data.product_bn_name);
            $('#product_brand_create').val(data.product_bn_create);
            $('#product_brand_update').val(data.product_bn_update);
            $('.ve_product_brand').show();
 
            if (data.product_bn_display == "yes") {
                $('.toggle-switch').addClass('ts-active')
            } else {
                $('.toggle-switch').removeClass('ts-active')
            }
            $('#product_brand_status').val(data.product_bn_display)

            $('.blog-preview-add').html(`
                <div class="col-img-preview">
                <img class="preview-img" src="/${data.product_bn_img}">
                </div>
            `)

            $('#add_product_brand').hide();
            $('#edit_product_brand').hide();
        }
    })
}

// ฟังชั่นEdit Product Cate
function editProductCateById(_id) {
    $.ajax({
        type: "POST",
        url: "ajax/ajax.manage_products.php",
        dataType: 'json',
        data: { action: "viewProductBrand", id: _id },
        success: function(data) {
            // console.log(data)
            $('#form-add-product-cate').show();
            $('#product_brand_name').val(data.name);
            $('#product_brand_create').val(data.date_create);
            $('#product_brand_update').val(data.date_update);
            $('#product_brand_priority').val(data.priority);
            $('.ve_product_brand').show();

            if (data.display == "yes") {
                $('.toggle-switch').addClass('ts-active')
            } else {
                $('.toggle-switch').removeClass('ts-active')
            }
            $('#product_brand_status').val(data.display)

            $('.blog-preview-add').html(`
                <div class="col-img-preview">
                <img class="preview-img" src="/${data.img}">
                </div>
            `)

            $('#add_product_brand').hide();
            $('#edit_product_brand').show();

            $('#edit_product_brand_id').val(data.id)
        }
    })
}

//กด แก้ไขหมวดหมู่
$('#edit_product_brand').on('click', function() {
    editSaveProductCateById();
})


// ฟังชั่นEdit Product Cate
function editSaveProductCateById() {

    let edit_id = $('#edit_product_brand_id').val().trim();
    let edit_name = $('#product_brand_name').val().trim();
    let edit_status = $('#product_brand_status').val().trim();
    let edit_priority = $('#product_brand_priority').val().trim();

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
                    action: function() {}
                }
            }
        });
        return false;
    }

    let data = {
        'action': "editProducBrand",
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
        success: function(data) {

            if (data.message == "OK") {
                uploadimages(edit_id, "uploadimgbrand");
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

// ฟังชั่นลบ Product Cate
function deleteProductCateById(_id) {

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
                        url: "ajax/ajax.manage_products.php",
                        dataType: 'json',
                        data: { action: "deleteProductCate", id: _id },
                        success: function(data) {

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

// Clear Form หมวดหมู่สินค้า
function clearFormAddProductCate() {

    $('.toggle-switch').removeClass('ts-active');
    $('#product_brand_name').val('');
    $('#add-images-content-hidden').val('');
    $('.preview-img').remove();
    $('#product_brand_status').val('no');
    $('.ve_product_brand').hide();
    $('#edit_product_brand').hide();
    $('#add_product_brand').show();
    // formdata.delete("images[]");
}