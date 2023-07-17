// editor content 
let cateTable;
let productTable;
let replyTable;
let cate_id; 
let btn_set; 
$(function() { 
    cateTable = $('#product-cate-grid').DataTable({ 
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "ajax/ajax.manage_products.php",
            data: function(d){   
                d.action = "get_categoryProduct";
                d.id = cate_id;
            }, 
            type: "post",
            error: function() { 
            }
        },
        "columnDefs": [{
            targets: [1,5],
            orderable: false,
        }], 
        "order": [
            [0, "asc"]
        ],
        "pageLength": 50,
    }); 

    productTable = $('#product-grid').DataTable({ 
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "ajax/ajax.manage_products.php",
            data: function(d){   
                d.action = "get_products";
                d.id = cate_id;
            }, 
            type: "post",
            error: function() { 
            }
        },
        "columnDefs": [{
            targets: [1,3,4,8],
            orderable: false,
        }],
        "order": [
            [1, "asc"]
        ],
        "pageLength": 50,
    }); 
});

$(".content").on("click",".btnBackToCategory",function(){
    if( $(this).hasClass('active')){ return false; }  
    cateTable.ajax.reload(null, false); 
    $(".content .btnBackToCategory").addClass("active");  
    $("#cate_products_table").addClass("active");   
    $("#products_table").removeClass("active"); 
    $(".btnAddCategory").addClass('active');
    $(".btnAddProduct").removeClass('active'); 
}); 

function viewProductByCateId(_id) {
    cate_id = _id; 
    productTable.ajax.reload(null, false);
    $("#cate_products_table").removeClass("active");    
    $("#products_table").addClass("active");     
    $(".content .btnBackToCategory").removeClass("active");  
    $(".btn_add_action.product").removeClass("active"); 
    $(".btnAddCategory").removeClass('active'); 
    $(".btnAddProduct").addClass('active');
}

function reloadTable() { 
    cateTable.ajax.reload(null, false); 
    productTable.ajax.reload(null, false); 
} 
function reloadTableReply() { 
    replyTable.ajax.reload(null, false); 
} 

function PopupCenter(pageURL, title, w, h) {
    var left = (screen.width / 2) - (w / 2);
    var top = (screen.height / 2) - (h / 2);
    var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
    return targetWin;
}

//Toggle Switch
$('.switch').on('click', (event) => {
    let _this = event.target;
    _this.closest('.toggle-switch').classList.toggle('ts-active')

    let status = _this.closest('.toggle-switch').classList.value.split(" ").find((e) => e === "ts-active")
    if (status == "ts-active") {
        $('#product_cate_status').val('yes')
    } else {
        $('#product_cate_status').val('no')
    }
}) 

//ยืนยันการเพิ่มหมวดหมู่
$('#add_product_cate').on('click', function() { 
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
        'action': 'add_product_cate',
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
                uploadimages(data.insert_id, "uploadimgcontent");
            }
        }
    })
})


// ฟังชั่น Upload รูปภาพเด้อ
// function uploadimages(id, action) {
//     formdata.append("action", action);
//     formdata.append("id", id);

//     $.ajax({
//         url: "ajax/ajax.manage_products.php",
//         type: 'POST',
//         data: formdata,
//         processData: false,
//         contentType: false,
//         beforeSend: function() {
//             console.log('Load Start')
//             $('.wrapper-pop').addClass('pop-active');
//         },
//         success: function(obj) {

//             $.confirm({
//                 title: 'สำเร็จ',
//                 content: 'เพิ่มหมวดหมู่สำเร็จ',
//                 theme: 'modern',
//                 icon: 'fa fa-check',
//                 type: 'green',
//                 typeAnimated: true,
//                 buttons: {
//                     tryAgain: {
//                         text: 'ตกลง',
//                         btnClass: 'btn-green',
//                         action: function() {
//                             location.reload();
//                             reloadTable();
//                             // clearFormAddProductCate()
//                         }
//                     }
//                 }
//             });

//         },
//         complete: function() {
//             console.log('Load End')
//             $('.wrapper-pop').removeClass('pop-active');
//         },
//         xhr: function() {
//             var xhr = new window.XMLHttpRequest();
//             xhr.upload.addEventListener("progress", function(evt) {
//                 if (evt.lengthComputable) {
//                     var pct = (evt.loaded / evt.total) * 100;
//                     console.log('p1 => ' + pct.toPrecision(3))
//                     $('.loadper').text(`${parseInt(pct)} %`)
//                 }
//             }, false);

//             xhr.addEventListener("progress", function(evt) {
//                 if (evt.lengthComputable) {
//                     var pct = (evt.loaded / evt.total) * 100;
//                     console.log('p2 => ' + pct.toPrecision(3))
//                 }
//             }, false);

//             return xhr;
//         }
//     });
// }

// upload images
$("#add-images-content").uploadImage({
    preview: true
});
$("#add-images-content").on("change", function() {
    if (formdata.getAll("images[]").length !== 0) {
      
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
function showFormAddProductCate() { 
    $.ajax({
        url: "ajax/ajax.manage_products.php",
        type: "post",
        dataType: "json",
        data: { action: "getMaxPriorityCategoryProduct" },
        success: function(data) {

            if (data.message == "OK") {
                btnActionToggleCateAndSubcate();
                $('#product_cate_priority').val(data.priority);
                $("#form-add-product-cate .txt_label_addedit").html("เพิ่มหมวดหมู่สินค้า");
                $('#form-add-product-cate').show();
                $('#add_product_cate').show(); 
                $('#product_cate_name').val('');
                $('#product_cate_create').val('');
                $('#product_cate_update').val('');
                $('.toggle-switch').removeClass('ts-active')
                $('.preview-img').remove();
                $('.ve_product_cate').hide();
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
        data: { action: "viewProductCate", id: _id },
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
                </div> `)

            btnActionToggleCateAndSubcate(); 
         
        }
    })
}
  
// ฟังชั่นEdit Product Cate
function editProductCateById(_id) { 
    $.ajax({
        type: "POST",
        url: "ajax/ajax.manage_products.php",
        dataType: 'json',
        data: { action: "viewProductCate", id: _id },
        success: function(response) { 
           let _type = (response['status'] == "yes")?'no':'default'; 
           console.log('ok');
           editCategory_BlogStyleByType(_type,response); 


           CKEDITOR.replace('seo-content', {
               filebrowserUploadUrl  :site_url+"backend/plugins/ckeditor/filemanager/connectors/php/upload.php?Type=File",
               filebrowserImageUploadUrl : site_url+"backend/plugins/ckeditor/filemanager/connectors/php/upload.php?Type=Image",
               filebrowserFlashUploadUrl : site_url+"backend/plugins/ckeditor/filemanager/connectors/php/upload.php?Type=Flash",
               height: 400,
               language: 'th'
             });
        }
    });
} 

function checkNeedlessOverLap(needless){
    needlessNumb = (needless).split(',');
    validNeedLess = [];
    lessOverLap = [];
    if(needlessNumb != ''){ 
      $.each( needlessNumb, function( key, value ) {
        value =  value.trim();    
        if(!!~jQuery.inArray(value, validNeedLess)){
            /* ซ่้ำแล้วทำส่วนนี้ */
            if(jQuery.inArray(value, lessOverLap)){
              lessOverLap.push(value);
            }
     
        }else{
          validNeedLess[key] = value;         
        }
      });  
    }
    if(lessOverLap.length !== 0){
      return lessOverLap;
    }
  }
function checkNeedfulOverLap(needful){
    /* check value */
    needfulNumb = (needful).split(',');
    validNeedFul = [];
    fulOverLap = [];
    if(needfulNumb != ''){ 
      $.each( needfulNumb, function( key, value ) {
        value =  value.trim();
    
        if(!!~jQuery.inArray(value, validNeedFul)){
         /* ซ่้ำแล้วทำส่วนนี้ */
         if(jQuery.inArray(value, fulOverLap)){
            fulOverLap.push(value);
          }
        }else{
          validNeedFul[key] = value;         
        }
      });  
    }
    if(fulOverLap.length !== 0){
        return fulOverLap;
    }
}

function chkOverlapful_less(_needful,_needless){
  
    needful = (_needful).split(',');
    needless = (_needless).split(',');
    var dupOverLap =[];
    var dupOverLap_msg = "";
    $.each( needful, function( key, value ) {
      if(!!~jQuery.inArray(value, needless)){
        if(jQuery.inArray(value, dupOverLap)){
            /* dupOverLap.push(value); */
            dupOverLap_msg += (dupOverLap_msg != "")? ","+value:value;
        }
      }
    }); 
    
    if(dupOverLap_msg != ""){   
      $('.page_manage_products .txt_needless').addClass("failed");  
      $('.page_manage_products .txt_needful').addClass("failed");
      return dupOverLap_msg; 
    }else{   
      $('.page_manage_products .txt_needless').removeClass("failed");
      $('.page_manage_products .txt_needful').removeClass("failed"); 
    } 
    return dupOverLap_msg; 
  }


async function editCategory_BlogStyleByType(_type,response){
    if(_type == "default"){
        const { value: accept } = await Swal.fire({ 
            customClass: {
                header: 'my-header-style',
                popup: 'my-productcate-style'
            },
            width: '600px',
            title: 'แก้ไขหมวดหมู่', 
            confirmButtonText:'ยืนยัน',
            showCancelButton: true, 
            html:  response['html'],
            focusConfirm: false,
            input: 'checkbox',
            inputValue: 1,
            didOpen: () => {
                $('#edit-ad-date-display').datepicker({
                    format: 'dd/mm/yyyy',
                    autoclose: true,
                    language: 'th',
                    todayHighlight: true
                }).on('changeDate', function(e) {
                    $('#edit-input-date-display').val(e.format('yyyy-mm-dd'));
                });
                $("#edit-time-display").timepicker({
                    defaultTime: false,
                    showInputs: false,
                    minuteStep: 1,
                    showMeridian: false
                });
                $('#edit-ad-date-hidden').datepicker({
                    format: 'dd/mm/yyyy',
                    autoclose: true,
                    language: 'th',
                    todayHighlight: true
                }).on('changeDate', function(e) {
                    $('#edit-input-date-hidden').val(e.format('yyyy-mm-dd'));
                });
                $("#edit-time-hidden").timepicker({
                    defaultTime: false,
                    showInputs: false,
                    minuteStep: 1,
                    showMeridian: false
                });
                if(response.discount_mode == 'yes'){
                    $('#cateDisCountOnly')[0].click();
                }

                $('#edit-ad-date-display').datepicker('setDate', new Date(response.discount_begin));
                $('#edit-time-display').val(formatTime(new Date(response.discount_begin)));
                $('#edit-ad-date-hidden').datepicker('setDate', new Date(response.discount_expire));
                $('#edit-time-hidden').val(formatTime(new Date(response.discount_expire)));
                
            },
            inputValidator: (result) => {
                var seodescription = $("#seo-description").val();
                if($(".txt_catename").val().length < 1){
                    return "ระบุชื่อหมวดหมู่"
                }
                if(seodescription.length < 70 || seodescription.length > 155 ){
                    return "ระบุ Meta Description มากกว่า 70 และไม่เกิน 155 ตัวอักษร"
                }
                if($(".seo_h1").val().length < 20 || $(".seo_h1").val().length > 70 ){
                    return "ระบุ H1 มากกว่า 20 และไม่เกิน 70 ตัวอักษร"
                }
                if(result){
                    let param = { action:"editCategory",
                      image: $(".page_manage_products #add-images-content-hidden").val(),
                      abv: $(".page_manage_products .txt_abbrev").val(),
                      id: response['bercate_id'],
                      name: $(".txt_catename").val(),
                      priority: $(".txt_priority").val(),
                      h1: $(".page_manage_products .seo_h1").val(),
                      h2: $(".page_manage_products .seo_h2").val(),
                      content: CKEDITOR.instances["seo-content"].getData() ,
                      meta_title: $(".page_manage_products #seo-title").val(),
                      meta_desc: $(".page_manage_products #seo-description").val(),
                      discount: $(".page_manage_products .txt_catediscount").val(),
                      status: response['status'],
                      discountBeginDate: $(".page_manage_products #edit-input-date-display").val(), 
                      discountBeginTime: $(".page_manage_products #edit-time-display").val(), 
                      discountExpireDate: $(".page_manage_products #edit-input-date-hidden").val(), 
                      discountExpireTime: $(".page_manage_products #edit-time-hidden").val(),
                      discountMode: ($(".page_manage_products #cateDisCountOnly")[0].checked)?"yes":"no"
                    }
                    if( $('#edit-ad-date-display').val() == ""){
                        param.discountBeginDate = ""
                        param.discountBeginTime = ""
                    }
                    if( $('#edit-ad-date-hidden').val() == ""){
                        param.discountExpireDate = ""
                        param.discountExpireTime = ""
                    }
                    swalUpdateCategory(param); 
                } 
            }, 
        });
    } else { 
        const { value: accept } = await Swal.fire({ 
            customClass: {
                header: 'my-header-style',
                popup: 'my-productcate-style'
            },
            title: 'แก้ไขหมวดหมู่', 
            inputPlaceholder: 'Type of System',
            showCancelButton: true,
            confirmButtonText:'แก้ไข',
            html: response['html'],
            focusConfirm: false,
            input: 'checkbox',
            inputValue: 1,
            didOpen: () => {
                $('#edit-ad-date-display').datepicker({
                    format: 'dd/mm/yyyy',
                    autoclose: true,
                    language: 'th',
                    todayHighlight: true
                }).on('changeDate', function(e) {
                    $('#edit-input-date-display').val(e.format('yyyy-mm-dd'));
                });
                $("#edit-time-display").timepicker({
                    defaultTime: false,
                    showInputs: false,
                    minuteStep: 1,
                    showMeridian: false
                });
                $('#edit-ad-date-hidden').datepicker({
                    format: 'dd/mm/yyyy',
                    autoclose: true,
                    language: 'th',
                    todayHighlight: true
                }).on('changeDate', function(e) {
                    $('#edit-input-date-hidden').val(e.format('yyyy-mm-dd'));
                });
                $("#edit-time-hidden").timepicker({
                    defaultTime: false,
                    showInputs: false,
                    minuteStep: 1,
                    showMeridian: false
                });
                if(response.discount_mode == 'yes'){
                    $('#cateDisCountOnly')[0].click();
                }

                $('#edit-ad-date-display').datepicker('setDate', new Date(response.discount_begin));
                $('#edit-time-display').val(formatTime(new Date(response.discount_begin)));
                $('#edit-ad-date-hidden').datepicker('setDate', new Date(response.discount_expire));
                $('#edit-time-hidden').val(formatTime(new Date(response.discount_expire)));
                
            },
            inputValidator: (result) => {
                let needful = $(".txt_needful").val();
                let needless = $(".txt_needless").val();
                if($(".txt_catename").val().length < 1){
                    $('.page_manage_products .txt_catename').addClass("failed");
                    return "ระบุชื่อหมวดหมู่"
                } else {
                    $('.page_manage_products .txt_catename').removeClass("failed");
                }
                
                if($("#seo-description").val().length < 70 || $("#seo-description").val().length >155){
                    $('.page_manage_products #seo-description').addClass("failed");
                    return "ระบุ Meta Description มากกว่า 70 และน้อยกว่า 155 ตัวอักษร"
                } else {
                    $('.page_manage_products #seo-description').removeClass("failed");
                }

                if($(".seo_h1").val().length < 20 || $(".seo_h1").val().length >70){
                    $('.page_manage_products .seo_h1').addClass("failed");
                    return "ระบุ H1 มากกว่า 20 และไม่เกิน 70 ตัวอักษร"
                } else {
                    $('.page_manage_products .seo_h1').removeClass("failed");
                }
            
                let chkful = checkNeedfulOverLap(needful);
                if( chkful !== undefined){ 
                    $('.page_manage_products .txt_needful').addClass("failed");
                    return "หมายเลข "+chkful+" ซ้ำกัน" 
                }else {
                    $('.page_manage_products .txt_needful').removeClass("failed");
                }
                let chkless = checkNeedlessOverLap(needless);  
                if( chkless !== undefined){ 
                    $('.page_manage_products .txt_needless').addClass("failed")
                    return "หมายเลข "+chkless+" ซ้ำกัน" 
                } else {
                    $('.page_manage_products .txt_needless').removeClass("failed")
                }
                let duplicate = chkOverlapful_less(needful,needless);
                if(duplicate != ""){
                    return "หมายเลข "+duplicate+" ซ้ำกัน" 
                } 
                if(result){  
                    let param = { 
                      action:"editCategory" ,
                      image: $(".page_manage_products #add-images-content-hidden").val(),
                      abv: $(".page_manage_products .txt_abbrev").val(),
                      id: response['bercate_id'],
                      name: $(".txt_catename").val(),
                      priority: $(".txt_priority").val(),
                      needful: $(".txt_needful").val(),
                      needless: $(".txt_needless").val(),
                      status: response['status'],
                      h1: $(".page_manage_products .seo_h1").val(),
                      h2: $(".page_manage_products .seo_h2").val(),
                      meta_title: $(".page_manage_products #seo-title").val(),
                      meta_desc: $(".page_manage_products #seo-description").val(),
                      content: CKEDITOR.instances["seo-content"].getData() ,
                      discount: $(".page_manage_products .txt_catediscount").val(),
                      discountBeginDate: $(".page_manage_products #edit-input-date-display").val(), 
                      discountBeginTime: $(".page_manage_products #edit-time-display").val(), 
                      discountExpireDate: $(".page_manage_products #edit-input-date-hidden").val(), 
                      discountExpireTime: $(".page_manage_products #edit-time-hidden").val(),
                      discountMode: ($(".page_manage_products #cateDisCountOnly")[0].checked)?"yes":"no"
                    }
                    if( $('#edit-ad-date-display').val() == ""){
                        param.discountBeginDate = ""
                        param.discountBeginTime = ""
                    }
                    if( $('#edit-ad-date-hidden').val() == ""){
                        param.discountExpireDate = ""
                        param.discountExpireTime = ""
                    }

                    swalUpdateCategory(param);
                } 

            }  
        })

    }
}

function formatTime(date) {
    if(date.getHours()) {
      var hours = (date.getHours() < 10 ? '0' : '') + date.getHours();
      var minutes = (date.getMinutes() < 10 ? '0' : '') + date.getMinutes();
      return hours + ':' + minutes;
    } else {
      return "";
    }
}

//Time
function formatDate(date) {
    var day = (date.getDate()<10?'0':'') + date.getDate();
    var monthIndex = ("0" + (date.getMonth() + 1)).slice(-2);
    var year = date.getFullYear();
    var hours = (date.getHours()<10?'0':'') + date.getHours();
    var minutes = (date.getMinutes()<10?'0':'') + date.getMinutes();
    date.get
    return year + '-' + monthIndex + '-' + day;
  }

function swalUpdateCategory(param){
    $.ajax({
        url: "ajax/ajax.manage_products.php",
        type: 'POST',
        dataType: 'json',
        data: param,
        success: function(response){
            reactionUpdate(response);
        }
    }); 
}

function reactionUpdate(param){
    
    if(param['message'] == "OK"){
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'อัพเดทข้อมูลสำเร็จแล้ว!',
            showConfirmButton: false,
            timer: 1000
        });
        reloadTable();
    } else {
        Swal.fire({
            icon: 'error',
            title: 'ไม่สำเร็จ',
            text: 'อัพเดทข้อมูลไม่สำเร็จ!' 
        })
    }
}

//กด แก้ไขหมวดหมู่
$('#edit_product_cate').on('click', function() {
    editSaveProductCateById();
});

// ฟังชั่นEdit Product Cate
function editSaveProductCateById() { 
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
        'action': "editProductCate",
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

 
function deleteProductById(_id) {
        $.confirm({
            title: 'แจ้งเตือน',
            content: 'ยืนยันการลบ',
            theme: 'modern',
            icon: 'fa fa-exclamation-triangle',
            type: 'red',
            typeAnimated: true,
            buttons: {
                confirm: {
                    text: 'ยืนยัน',
                    btnClass: 'me-btn-cancel',
                    action: function() {
                        confirmDeleteProduct(_id);
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
function confirmDeleteProduct(_id){
    $.ajax({
        type: "POST",
        url: "ajax/ajax.manage_products.php",
        dataType: 'json',
        data: { action: "deleteProductById", id: _id },
        success: function(data) { 
            $.confirm({
                title: 'สำเร็จ',
                content: 'ลบข้อมูลเสร็จสิ้นแล้ว!',
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
                text: 'ยืนยัน',
                btnClass: 'me-btn-cancel',
                action: function() {
                    confirmDeleteCategory(_id);
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

function confirmDeleteCategory(_id){
    $.ajax({
        type: "POST",
        url: "ajax/ajax.manage_products.php",
        dataType: 'json',
        data: { action: "deleteProductCate", id: _id },
        success: function(data) { 
            $.confirm({
                title: 'สำเร็จ',
                content: 'ลบข้อมูลเสร็จสิ้นแล้ว!',
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
}

// Clear Form หมวดหมู่สินค้า
function clearFormAddProductCate() { 
    $('.toggle-switch').removeClass('ts-active');
    $('#product_cate_name').val('');
    $('#add-images-content-hidden').val('');
    $('.preview-img').remove();
    $('#product_cate_status').val('no');
    $('.ve_product_cate').hide();
    $('#edit_product_cate').hide();
    $('#add_product_cate').show();
    // formdata.delete("images[]");
}

function btnActionToggleCateAndSubcate(){ 
    $('#add_product_cate').hide();
    $('#edit_product_cate').hide();
    $('#add_product_subcate').hide();
    $('#edit_product_subcate').hide();
} 

$("#exampleModal").on("click",".btnAddCate",function(){ 
    let param = {
        action: 'add_product_category',
        status: 'yes',
        name: 'เบอร์หมวดใหม่',
        display: 'yes',
        needful: '4,5,6',
        needless: '1,2,3' 
    }
    $.ajax({
        url: "ajax/ajax.manage_products.php",
        type: 'POST',
        dataType: 'json',
        data: param,
        success: function(repsonse){
            console.log(response);
        }
    }); 
});

$("#cate_products_table").on("click",".btnSystem .switch",function(event){  
    Swal.fire({
        title: 'Are you sure?',
        text: "คุณต้องการให้หมวดหมู่นี้ประมวลผลอัตโนมัติ!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ยืนยัน!'
      }).then((result) => {
        if (result.value) {
            let _this = event.target;
            _this.closest('.toggle-switch').classList.toggle('ts-active')
            let status = _this.closest('.toggle-switch').classList.value.split(" ").find((e) => e === "ts-active")
            if (status == "ts-active") {
                $('#cate_status').val('yes');
                _system = 'yes';
            } else {
                $('#cate_status').val('no');
                _system = 'no';
            }
            let _id = $(this).data('id');
            switchUpdateCateSystem(_id,_system);
        }
      }) 
});

function switchUpdateCateSystem(_id,_system){
    let param = {
        action:"update_category_system",
        system: _system,
        id: _id
    }
    $.ajax({
        url:"ajax/ajax.manage_products.php",
        type: "POST",
        dataType: "json",
        data: param,
        success: function(response){
            cateTable.ajax.reload(null, false); 
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1000,
                timerProgressBar: true,
                onOpen: (toast) => {
                  toast.addEventListener('mouseenter', Swal.stopTimer)
                  toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
              })
              
              Toast.fire({
                icon: 'success',
                title: 'Successfully'
              })
        }
    });
}


$("#cate_products_table").on("click",".btnDisplay .switch",function(event){
    let _this = event.target;
    _this.closest('.toggle-switch').classList.toggle('ts-active')
    let status = _this.closest('.toggle-switch').classList.value.split(" ").find((e) => e === "ts-active")
    if (status == "ts-active") {
        $('#cate_status').val('yes')
        _display = 'yes';
    } else {
        $('#cate_status').val('no')
        _display = 'no';
    }
    let _id = $(this).data('id');
  
    switchUpdateDisplayCate(_id,_display);
})
function switchUpdateDisplayCate(_id,_display){
    let param = { 
        action: 'updateCategoryDisplay',
        id: _id,
        display: _display
    }
    $.ajax({
        url: "ajax/ajax.manage_products.php",
        type: 'POST',
        dataType: 'json',
        data: param,
        success: function(response){
            Swal.fire({ 
                title: 'Waiting!',
                timer: 700,
                timerProgressBar: true,  
                onBeforeOpen: () => {
                    Swal.showLoading()
                    timerInterval = setInterval(() => {
                      const content = Swal.getContent()
                      if (content) {
                        const b = content.querySelector('b')
                        if (b) {
                          b.textContent = Swal.getTimerLeft()
                        }
                      }
                    }, 100)
                  },
            });
        }
    })
}

$("#cate_products_table").on("click",".btnPin .switch",function(event){
    let _this = event.target;
    _this.closest('.toggle-switch').classList.toggle('ts-active')
    let status = _this.closest('.toggle-switch').classList.value.split(" ").find((e) => e === "ts-active")
    if (status == "ts-active") {
        $('#cate_status').val('yes')
        _pin = 'yes';
    } else {
        $('#cate_status').val('no')
        _pin = 'no';
    }
    let _id = $(this).data('id');
  
    switchUpdatePINCate(_id,_pin);
})
function switchUpdatePINCate(_id,_pin){
    let param = { 
        action: 'updateCategoryPin',
        id: _id,
        pin: _pin
    }
    $.ajax({
        url: "ajax/ajax.manage_products.php",
        type: 'POST',
        dataType: 'json',
        data: param,
        success: function(response){
            Swal.fire({ 
                title: 'Waiting!',
                timer: 700,
                timerProgressBar: true,  
                onBeforeOpen: () => {
                    Swal.showLoading()
                    timerInterval = setInterval(() => {
                      const content = Swal.getContent()
                      if (content) {
                        const b = content.querySelector('b')
                        if (b) {
                          b.textContent = Swal.getTimerLeft()
                        }
                      }
                    }, 100)
                  },
            });
        }
    })
}


$(".product_category_page").on("click",".btnAddCategory ",function(){
    add_category();
});
async function add_category(){
    const { value: accept } = await Swal.fire({ 
        customClass: {
            header: 'my-header-style',
            popup: 'my-productcate-style'
        },
        title: 'เพิ่มหมวดหมู่',  
        showCancelButton: true,
        confirmButtonText:'เพิ่ม', 
        html:
          '<div class="me-swal-title">ชื่อหมวดหมู่:</div>'+
          '<input  class="swal2-input txt_catename" placeholder="ชื่อหมวดหมู่"  value="">'+
          '<div class="me-swal-title">เลขที่ต้องการ:</div>'+
          '<textarea  class="swal2-input input-area txt_needful" placeholder="เช่น 12,133,144,155"></textarea>'+
          '<div class="me-swal-title">เลขที่ไม่ต้องการ:</div>'+
          '<textarea  class="swal2-input input-area txt_needless" placeholder="เช่น 12,133,144,155"></textarea>'+
          '<div class="me-swal-title">ลำดับการแสดงผล:</div>'+
          '<input  class="swal2-input txt_priority " value="" placeholder="กรุณาใส่ตัวเลข">'+
          '<div class="switch-form add-category">'+
               '<div class="col-md-12 switch-btn btnAddDisplay">'+
                '<span >แสดงผลบนเว็บไซต์: </span>'+
                '<div class="toggle-switch ts-active">'+
                    '<span class="switch"></span>'+
                '</div>'+
                '<input type="hidden" class="form-control" id="add_category_display" value="yes">'+
                '</div>'+  
                 '<div class="col-md-12 switch-btn btnAddSystem">'+
                '<span >ระบบจัดหมวดหมู่ Auto: </span>'+
                '<div class="toggle-switch ts-active">'+
                    '<span class="switch"></span>'+
                '</div>'+
                '<input type="hidden" class="form-control" id="add_category_system" value="yes">'+
            '</div>'+ 
         '</div>',  
        focusConfirm: false,
        input: 'checkbox',
        inputValue: 1, 
        inputValidator: (result) => { 
            if(result){  
                let param = { 
                    action:"addCategory"  
                    ,name: $(".page_manage_products .txt_catename").val()
                    ,priority: $(".page_manage_products .txt_priority").val()
                    ,needful: $(".page_manage_products .txt_needful").val()
                    ,needless: $(".page_manage_products .txt_needless").val()
                    ,status: $(".page_manage_products #add_category_system").val()
                    ,display: $(".page_manage_products #add_category_display").val()
                }
                
                if(param.status == "yes"){
                    let needful = param.needful;
                    let needless = param.needless;
                    if($(".txt_catename").val().length < 1){
                        $('.page_manage_products .txt_catename').addClass("failed");
                        return "ระบุชื่อหมวดหมู่"
                    } else {
                        $('.page_manage_products .txt_catename').removeClass("failed");
                    }

                    if($("#seo-description").val().length < 70 || $("#seo-description").val().length > 155){
                        $('.page_manage_products #seo-description').addClass("failed");
                        return "ระบุ Meta Description มากกว่า 70 และน้อยกว่า 155 ตัวอักษร"
                    } else {
                        $('.page_manage_products #seo-description').removeClass("failed");
                    }

                    if($(".seo_h1").val().length < 20 || $(".seo_h1").val().length >70){
                        $('.page_manage_products .seo_h1').addClass("failed");
                        return "ระบุ H1 มากกว่า 20 และไม่เกิน 70 ตัวอักษร"
                    } else {
                        $('.page_manage_products .seo_h1').removeClass("failed");
                    }
                 
                    let chkful = checkNeedfulOverLap(needful);
                    if( chkful !== undefined){ 
                        $('.page_manage_products .txt_needful').addClass("failed");
                        return "หมายเลข "+chkful+" ซ้ำกัน" 
                    }else {
                        $('.page_manage_products .txt_needful').removeClass("failed");
                    }                    
                    let chkless = checkNeedlessOverLap(needless);  
                    if( chkless !== undefined){ 
                        $('.page_manage_products .txt_needless').addClass("failed")
                        return "หมายเลข "+chkless+" ซ้ำกัน" 
                    } else {
                        $('.page_manage_products .txt_needless').removeClass("failed")
                    }
                    let duplicate = chkOverlapful_less(needful,needless);
                    if(duplicate != ""){
                        return "เลขที่ต้องการและไม่ต้องการ ซ้ำกัน: "+duplicate;
                    }  
                }
                swalInsertCategory(param);
            } 
        }
    }); 
}

$('.page_manage_products').on("click",".btnAddSystem .switch",function(event){
    let _this = event.target;
    _this.closest('.toggle-switch').classList.toggle('ts-active')
    let status = _this.closest('.toggle-switch').classList.value.split(" ").find((e) => e === "ts-active")
    if (status == "ts-active") {
        $('#add_category_system').val('yes')
    } else {
        $('#add_category_system').val('no')
    }
});

$('.page_manage_products').on("click",".btnAddDisplay .switch",function(event){
    let _this = event.target;
    _this.closest('.toggle-switch').classList.toggle('ts-active')
    let status = _this.closest('.toggle-switch').classList.value.split(" ").find((e) => e === "ts-active")
    if (status == "ts-active") {
        $('#add_category_display').val('yes')
    } else {
        $('#add_category_display').val('no')
    }
});
 
function swalInsertCategory(param){
    $.ajax({
        url: "ajax/ajax.manage_products.php",
        type: "POST",
        dataType: "json",
        data: param,
        success: function(response){
            reactionUpdate(response);
        }
    })
}

function editProductCateApproveById(_id){
    $.ajax({
        type: "POST",
        url: "ajax/ajax.manage_products.php",
        dataType: 'json',
        data: { action: "viewProductCateMode", id: _id },
        success: function(response) { 
           let _type = (response['status'] == "yes")?'no':'default'; 
           editCategory_Approve(_type,response); 
           CKEDITOR.replace('seo-content', {
            filebrowserUploadUrl  :site_url+"backend/plugins/ckeditor/filemanager/connectors/php/upload.php?Type=File",
            filebrowserImageUploadUrl : site_url+"backend/plugins/ckeditor/filemanager/connectors/php/upload.php?Type=Image",
            filebrowserFlashUploadUrl : site_url+"backend/plugins/ckeditor/filemanager/connectors/php/upload.php?Type=Flash",
            height: 400,
            language: 'th'
          });
        }
    });
}
async function editCategory_Approve(_type,response){
    const { value: accept } = await Swal.fire({ 
        customClass: {
            container: 'swal-cate-approve',
            header: 'my-header-style',
        },
        width:'600px',
        title: 'แก้ไขหมวดหมู่', 
        inputPlaceholder: 'Type of System',
        showCancelButton: true,
        confirmButtonText:'แก้ไข', 
        html: response['html'],
        focusConfirm: false,
        input: 'checkbox',
        inputValue: 1,
        didOpen: () => {
            $('#edit-ad-date-display').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                language: 'th',
                todayHighlight: true
            }).on('changeDate', function(e) {
                $('#edit-input-date-display').val(e.format('yyyy-mm-dd'));
            });
            $("#edit-time-display").timepicker({
                defaultTime: false,
                showInputs: false,
                minuteStep: 1,
                showMeridian: false
            });
            $('#edit-ad-date-hidden').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                language: 'th',
                todayHighlight: true
            }).on('changeDate', function(e) {
                $('#edit-input-date-hidden').val(e.format('yyyy-mm-dd'));
            });
            $("#edit-time-hidden").timepicker({
                defaultTime: false,
                showInputs: false,
                minuteStep: 1,
                showMeridian: false
            });
            if(response.discount_mode == 'yes'){
                $('#cateDisCountOnly')[0].click();
            }

            $('#edit-ad-date-display').datepicker('setDate', new Date(response.discount_begin));
            $('#edit-time-display').val(formatTime(new Date(response.discount_begin)));
            $('#edit-ad-date-hidden').datepicker('setDate', new Date(response.discount_expire));
            $('#edit-time-hidden').val(formatTime(new Date(response.discount_expire)));
            
        },
        inputValidator: (result) => { 
            if($(".page_manage_products .txt_catename").val().length < 1){
                return 'ระบุชื่อหมวดหมู่'
            }
            if($(".page_manage_products #seo-description").val().length < 70 || $(".page_manage_products #seo-description").val().length >155 ){
                return "ระบุ Meta Description มากกว่า 70 และน้อยกว่า 155 ตัวอักษร"
            }
            if($(".page_manage_products .seo_h1").val().length < 20 || $(".page_manage_products .seo_h1").val().length > 70){
                return 'ระบุ H1 มากกว่า 20 และน้อยกว่า 70 ตัวอักษร'
            }
            if($(".page_manage_products #seo-title").val().length < 30 || $(".page_manage_products #seo-title").val().length > 60){
                return 'ระบุ Meta Title มากกว่า 30 และน้อยกว่า 60 ตัวอักษร'
            }
            let param = { 
               action:"editCategory" ,
               id: response['bercate_id'] ,
               image: $(".page_manage_products #add-images-content-hidden").val(),
               abv: $(".page_manage_products .txt_abbrev").val(),
               name: $(".page_manage_products .txt_catename").val(),
               priority: $(".page_manage_products .txt_priority").val(),
               h1: $(".page_manage_products .seo_h1").val(),
               h2: $(".page_manage_products .seo_h2").val(),
               content: CKEDITOR.instances["seo-content"].getData() ,
               meta_title: $(".page_manage_products #seo-title").val(),
               meta_desc: $(".page_manage_products #seo-description").val(),
               discount: $(".page_manage_products .txt_catediscount").val(),
               discountBeginDate: $(".page_manage_products #edit-input-date-display").val(), 
               discountBeginTime: $(".page_manage_products #edit-time-display").val(), 
               discountExpireDate: $(".page_manage_products #edit-input-date-hidden").val(), 
               discountExpireTime: $(".page_manage_products #edit-time-hidden").val(),
               discountMode: ($(".page_manage_products #cateDisCountOnly")[0].checked)?"yes":"no"
             }  
             if( $('#edit-ad-date-display').val() == ""){
                param.discountBeginDate = ""
                param.discountBeginTime = ""
            }
            if( $('#edit-ad-date-hidden').val() == ""){
                param.discountExpireDate = ""
                param.discountExpireTime = ""
            }
             swalUpdateCategory(param); 

        },   
    });  
  
}
 
$(".page_manage_products").on("click",".switch.approve",function(event){
    let _this = event.target;
    _this.closest('.toggle-switch').classList.toggle('ts-active')
    let status = _this.closest('.toggle-switch').classList.value.split(" ").find((e) => e === "ts-active")
    let display = (status == "ts-active")? "yes":"no";
    let id = $(this).data('id');
    updateCategoryApprove(id,display); 
});

function updateCategoryApprove(_id,display){
    let param = {
        id: _id,
        display,
        action: 'updateCategoryApprove'
    }
    $.ajax({
        url: 'ajax/ajax.manage_products.php',
        type: 'post',
        dataType: 'json',
        data: param,
        success: function(response){ 
        }
    })
}

$(".page_manage_products").on("click",".btnAddProduct",function(){
    $.ajax({
        url:"ajax/ajax.manage_products.php",
        type: "POST",
        dataType: 'json',
        data: { action: "product_config_add"},
        success: function(response){
            let _action = "add_product";
            product_config(response,_action); 
        }
    });
});
function btnEditProduct(_id){
    $.ajax({
        url:"ajax/ajax.manage_products.php",
        type: "POST",
        dataType: 'json',
        data: { action: "product_config_edit", id:_id },
        success: function(response){
            let _action = "edit_product";
            product_config(response,_action); 
        }
    });
};
  
async function product_config(response,_action){ 
    if(_action == "edit_product"){
        tag = {
            title: "แก้ไขสินค้า",
            confirm: "แก้ไข"
        } 
    } else {
        tag = {
            title: "เพิ่มสินค้า",
            confirm: "เพิ่ม"
        } 
    }
    const { value: accept } = await Swal.fire({ 
        width: 300,
        customClass: { 
            container: 'swal-add-product',
            header: 'my-header-style' 
        },
        title: tag['title'],  
        showCancelButton: true,
        confirmButtonText: tag['confirm'], 
        html: response['html'],  
        focusConfirm: false,
        input: 'checkbox',
        inputValue: 1,
        inputValidator: (result) => { 
            if ($(".page_manage_products .txt_tel").val().length != 10) {
                return 'หมายเลข10หลัก'
            }else {
                if(result){  
                    let param = {   
                        action: _action 
                        ,tel: $(".page_manage_products .txt_tel").val()
                        ,price: $(".page_manage_products .txt_price").val()
                        ,discount: $(".page_manage_products .txt_discount").val()
                        ,network: $(".page_manage_products .slc_network").val()
                        ,display: $(".page_manage_products #product_display").val()
                        ,pin: $(".page_manage_products #product_pin").val()
                        ,sold: $(".page_manage_products #product_sold").val()
                    }
                    if( _action =="edit_product"){ param.id = response['ber']['product_id']; }
                    insertOrUpdateProduct(param); 
                } 
            }  
        }, 
    });
}

function insertOrUpdateProduct(param){
    $.ajax({
        url: 'ajax/ajax.manage_products.php',
        type: "POST",
        dataType: "json",
        data: param,
        success: function(response){
            productTable.ajax.reload(null, false);
            if(response['message'] == "OK"){
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'ทำรายการสำเร็จ',
                    showConfirmButton: false,
                    timer: 1000
                  })
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'ทำรายการไม่สำเร็จ',
                    showConfirmButton: false,
                    timer: 1000
                  })
            }
        }
    })
}



$(".page_manage_products").on("keyup",".txt_tel",function(){
    // console.log($(this).length);
    if($(this).length == 10){
        calcNumbsum($(this).val());
    } 
});

function calcNumbsum(tel){
    var str = tel.trim(); 
    var numb = (""+str).split("");  
    var numbsum = 0;
    for(i=0; i<numb.length ; i++){ 
      numbsum = numbsum + parseInt(numb[i]);    
    }   
    // $('.productFormAction #numsum').html(numbsum);
    // $('.productFormAction #numsum').val(numbsum);
}

$(".page_manage_products").on("click",".btnProductDisplay .switch",function(){
    let _this = event.target;
    _this.closest('.toggle-switch').classList.toggle('ts-active')
    let status = _this.closest('.toggle-switch').classList.value.split(" ").find((e) => e === "ts-active")
    if (status == "ts-active") {
        $('#product_display').val('yes')
    } else {
        $('#product_display').val('no')
    }
});

$(".page_manage_products").on("click",".btnProductPin .switch",function(){
    let _this = event.target;
    _this.closest('.toggle-switch').classList.toggle('ts-active')
    let status = _this.closest('.toggle-switch').classList.value.split(" ").find((e) => e === "ts-active")
    if (status == "ts-active") {
        $('#product_pin').val('yes')
    } else {
        $('#product_pin').val('no')
    }
});

$(".page_manage_products").on("click",".btnProductSold .switch",function(){
    let _this = event.target;
    _this.closest('.toggle-switch').classList.toggle('ts-active')
    let status = _this.closest('.toggle-switch').classList.value.split(" ").find((e) => e === "ts-active")
    if (status == "ts-active") {
        $('#product_sold').val('yes')
    } else {
        $('#product_sold').val('no')
    }
});

$(".page_manage_products #cate_products_table").on("keyup","input[type='search']",function(){
    let numb = $(this).val();
    if(numb > 0 && numb.length > 4 ){
        $(this).val("");
        viewProductByCateId(0);
        $(".page_manage_products #products_table input[type='search']").val(numb);
        $(".page_manage_products #products_table input[type='search']").focus();
    }
});

function collapSeo(){
    $('.seo-block').toggle()
}

function gotoNetwork(){
    location.href = "https://berhoro.com/backend/?page=manage_products&subpage=product_network"
}