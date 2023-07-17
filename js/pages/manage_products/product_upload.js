$('.filesUploadExcel').on('click','.btnUpload',function(){
    $('.formSelectFile').click(); 
}); 

$('.filesUploadExcel').on('change','.formSelectFile',function(){   
  split = $(this).val().split("\\")
  $('.txtNameUpload').text(split[split.length-1]);
  $('.txtNameUpload').addClass('active');
 
  check = split[2].split('.');  
    if(check[1] != 'xlsm' && check[1] != 'xlsx' && check[1] != 'csv'){
          clearFileUpload();
          alert('ไฟล์ไม่ถูกต้อง');
    } 
});  
   
//กดอัพโหลดไฟล์ #submitButton 
$("#myform1").on("submit",function(e){   
    e.preventDefault();  
    // ปิดการใช้งาน submit ปกติ เพื่อใช้งานผ่าน ajax   
    // เตรียมข้อมูล form สำหรับส่งด้วย  FormData Object
    var formData = new FormData($(this)[0]);  
     //ส่งค่าแบบ POST ไปยังไฟล์ show_data.php รูปแบบ ajax แบบเต็ม
    if($('.txtNameUpload').hasClass('active')){
       $.confirm({
        title: 'Are you sure?',
        content: 'การดำเนินการลงสินค้าใหม่ ข้อมูลเบอร์ทั้งหมดจะถูกลบเพื่อเริ่มกระบวนการ',
        theme: 'material',
        icon: 'fa fa-warning',
        type: 'red',
        draggable: false,
        buttons: {
        confirm:  {
          text: 'Yes!',
          btnClass: 'btn-red',
          action: function(){
            $('.loader-box').addClass('activeLoader');
            $('.manage-product').addClass('hide');  
            $('body').addClass('waitAct');    
            $('section.newForm').hide();
            uploadNewFileProduct(formData);  
          }
        },
        formCancel: {
          text: 'Cancel',
          cancel: function () {}  
        }
       }
     });
    }else{
        Swal.fire(
            'เกิดข้อผิดพลาด',
            'ท่านยังไม่ได้เลือกไฟล์',
            'warning'
          )
    } 
});
let duplicate_number = "";
function uploadNewFileProduct(formData){
    $.ajax({
      url: 'ajax/ajax.uploadproductber.php',
      type: 'POST',   
      dataType:'json',    
      data: formData,
      async: true,
      cache: false,
      contentType: false,
      processData: false
      }).done(function(data){
        // console.log(data);
        duplicate_number = data['duplicate'];
        if(data['message'] != "OK"){
            Swal.fire({
             position: 'center-center',
             icon: 'error',
             title: 'Error!',
             text: data['message'],
             html: duplicate_number,
             confirmButtonText: "ตกลง"
            }).then((result) => {
              location.reload();
            });
        }else{ 

          $('body').removeClass('waitAct'); 
          $('section.newForm').show();
          clearFileUpload();
          updateDataAfterUpload();
        }
    });  
}   
function updateDataAfterUpload(){
    var data = {      
      action: 'updateDataUpload'
    } 
    $.ajax({
      url: 'ajax/ajax.uploadproductber.php',
      type: 'POST',
      dataType: 'json',
      data: data,
      success: function(msg){  
        confirmActionUploadFile(msg); 
      }
    });

} 
function confirmActionUploadFile(msg){
    $('.loader-box').removeClass('activeLoader');
    $('.manage-product').removeClass('hide');
    if(msg['message'] == "OK"){   
        Swal.fire({
            position: 'center-center',
            icon: 'success',
            title: 'อัพเดทข้อมูลสำเร็จแล้ว!',
            html: duplicate_number,
            showConfirmButton: true, 
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'ตกลง' 
      }).then((result) => {
            location.reload();
      });
         
    }else {
        Swal.fire({
            icon: 'error',
            title: 'Upload failed',
            text: 'การอัพโหลดไม่สำเร็จกรุณาลองใหม่อีกครั้ง!',
            html: duplicate_number,
            footer: '<a href>ติดต่อผู้ดูแลระบบ?</a>',
            showConfirmButton: true, 
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'ตกลง' 
        }).then((result) => {
            location.reload();
        });
    }
}  
function clearFileUpload(){
    $('.txtNameUpload').removeClass('active');
    $('.txtNameUpload').text('No file selected');
    $('.txtNameUpload').val('');
    $('body').removeClass('waitAct'); 
}
  
$(".page_manage_products ").on("change",'#add-images-content',function () { 
  let file = this.files[0];
  if (file.length !== 0) { 
    var img = file.name;
    $('#add-images-content-hidden').val(img);
    $(".form-add-images").removeClass("has-error");
    $(".add-images-error").css("display", "none"); 
    editPreviewImage(file,'uploadImageCategory');
  }
});

function editPreviewImage(file,action) {
  let formdata = new FormData();
  formdata.append("action", action);
  formdata.append("images[]", file);
  $.ajax({
    url:  "ajax/ajax.manage_products.php",
    type: 'POST',
    data: formdata,
    processData: false,
    contentType: false,
    dataType: 'json',
    success: function (response) {
      let thumbnail = '<div class="col-img-preview" id="col_img_preview_1" data-id="1"><img class="preview-img" id="preview_img_1" src="https://'+location.host+'/'+response[0]+'"></div>';
      $(".page_manage_products .image-label").css("display","none")
      $(".page_manage_products .blog-preview-add").html(thumbnail);
      $(".page_manage_products #add-images-content-hidden").val(response[0]);
    }
  });
}

$('.page_manage_products').on('click',".btnGetExcel",function(){
    $('.loader-box').addClass('activeLoader');
    $('.manage-product').addClass('hide');  
    $('body').addClass('waitAct');    
    $('section.newForm').hide();
    if( $('.body').hasClass('waitAct')){
        console.log('already click');
        return false;
    }

  let data = { 
    action: 'getExcelExport'
  }
  $.ajax({
    url: 'ajax/ajax.uploadproductber.php',
    type: 'POST',
    dataType:'json',
    data: data,
    success: function(data){ 
    $('.loader-box').removeClass('activeLoader');
    $('.manage-product').removeClass('hide');
    $('.loader-box').removeClass('activeLoader');
    $('.manage-product').removeClass('hide');
      window.location.href = data['src']; 

    } 
  });
});
