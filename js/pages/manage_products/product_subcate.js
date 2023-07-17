// Show Form เพิ่มข้อมูล หมวดหมู่สินค้า
function showFormAddProductSubCate() { 
    $.ajax({
        url: "ajax/ajax.manage_products.php",
        type: "post",
        dataType: "json",
        data: { action: "getMaxPrioritySubCategoryProduct" },
        success: function(data) { 

            if (data.message == "OK") {
                btnActionToggleCateAndSubcate();
                $('#product_cate_priority').val(data.priority);
                $("#form-add-product-cate .txt_label_addedit").html("เพิ่มหมวดหมู่ย่อย");
                $('#form-add-product-cate').show();
                $('#add_product_subcate').show(); 
                $('#product_cate_name').val('');
                $('#product_cate_create').val('');
                $('#product_cate_update').val('');
                $('.toggle-switch').removeClass('ts-active')
                $('.preview-img').remove();
                $('.ve_product_cate').hide();
            }
   
            $(".product_category_page .btnAddCategory.subcate").show();
        }
    })
} 



//ยืนยันการเพิ่มหมวดหมู่
$('#add_product_subcate').on('click', function() {  

    if ($('#product_cate_name').val().trim().length == 0 ||
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
        'cateid': $(this).data('id'),
        'action': 'add_product_subcate',
        'name': $('#product_cate_name').val().trim(),
        'status': $('#product_cate_status').val().trim(), 
        'priority': $('#product_cate_priority').val().trim()
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
                uploadimages(data.insert_id, "uploadimgcontent_subcate");
            }
        }
    })
})
  
//กด แก้ไขหมวดหมู่
$('#edit_product_subcate').on('click', function() {
    editSaveProductSubCateById($(this).data('id'));
})

// ฟังชั่นView Product Cate
function viewProductSubCateById(_id) {
    $.ajax({
        type: "POST",
        url: "ajax/ajax.manage_products.php",
        dataType: 'json',
        data: { action: "viewProductSubCate", id: _id },
        success: function(data) {
            
            // console.log(data)
            $('#form-add-product-cate').show();
            $('#product_cate_name').val(data.id);
            $('#product_cate_create').val(data.date_create);
            $('#product_cate_update').val(data.date_update); 
            $('.ve_product_cate').show();

            if (data.display == "yes") {
                $('.toggle-switch').addClass('ts-active')
            } else {
                $('.toggle-switch').removeClass('ts-active')
            }
            $('#product_cate_status').val(data.display)

            $('.blog-preview-add').html(`
        <div class="col-img-preview">
          <img class="preview-img" src="/${data.img}">
        </div>
      `)

            $('#add_product_cate').hide();
            $('#edit_product_cate').hide();
        }
    })
} 

// ฟังชั่นEdit Product Cate
function editProductSubCateById(_id) {
 
    $.ajax({
        type: "POST",
        url: "ajax/ajax.manage_products.php",
        dataType: 'json',
        data: { action: "viewProductSubCate", id: _id },
        success: function(data) {
   
            // console.log(data)
            $('#form-add-product-cate').show();
            $('#product_cate_name').val(data.name);
            $('#product_cate_create').val(data.date_create);
            $('#product_cate_update').val(data.date_update);
 
           
            $('#product_cate_priority').val(data.priority);
            $('.ve_product_cate').show();

            if (data.display == "yes") {
                $('.toggle-switch').addClass('ts-active')
            } else {
                $('.toggle-switch').removeClass('ts-active')
            }
            $('#product_cate_status').val(data.display)

            $('.blog-preview-add').html(`
                <div class="col-img-preview">
                <img class="preview-img" src="/${data.img}">
                </div>
             `)
             
             btnActionToggleCateAndSubcate();
             $('#edit_product_subcate').show();
             


            $('#edit_product_id').val(data.id)
        }
    })
}
 
// ฟังชั่นEdit Product SubCate
function editSaveProductSubCateById(_id) {   
    let edit_id = $('#edit_product_id').val().trim();
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
                    action: function() {}
                }
            }
        });
        return false;
    }

    let data = { 
        'action': "editProductSubCate",
        'id': edit_id,
        'name': edit_name,
        'status': edit_status,
        'priority': edit_priority, 
    } 

    $.ajax({
        type: "POST",
        url: "ajax/ajax.manage_products.php",
        dataType: 'json',
        data: data,
        success: function(data) {

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

// ฟังชั่นลบ Product SubCate
function deleteProductSubCateById(_id) {

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
                        data: { action: "deleteProductSubCate", id: _id },
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
function clearFormAddProductSubCate() {

    $('.toggle-switch').removeClass('ts-active');
    $('#product_cate_name').val('');
    $('#add-images-content-hidden').val('');
    $('.preview-img').remove();
    $('#product_cate_status').val('no');
    $('.ve_product_cate').hide();
    $('#edit_product_subcate').hide();
    $('#add_product_subcate').show();
    // formdata.delete("images[]");
}