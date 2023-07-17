function reloadTable(){
    dataTableForm.ajax.reload(null, false);
}
$(function(){
    dataTableForm = $('#admin-grid').DataTable({    
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: site_url +"ajax/ajax.reviews.php",
            data: {action:"get_reviews"},
            type: "post",
            error: function(){					
              $(".employee-grid-error").html("");
              $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
              $("#employee-grid_processing").css("display","none"); 
            } 
        }, 
        "columnDefs": [{ 
            targets: [2],
            orderable: false,
        }], 
        "order": [
            [0, "asc"]
        ],
        "columns": [
            { "width": "10%", "targets": 0 },
            { "width": "40%", "targets": 1 },
            { "width": "20%", "targets": 2 },
            { "width": "10%", "targets": 3 },
            { "width": "20%", "targets": 4 },
          ],
        "pageLength": 50,
    });   
});

 
function editCategory(event,_id){
    let param = {
        action: 'prepare_edit',
        id: _id
    }
    $.ajax({
        url: site_url+"ajax/ajax.reviews.php",
        type: 'POST',
        dataType: 'json',
        data: { action: 'prepare_edit',
                id: _id },
        success: function(response){
            prepareEdit_category(response);
        }
    });
}
async function prepareEdit_category(response){
        const { value: accept } = await Swal.fire({ 
            customClass: {
                container: 'swal-cate-approve',
                header: 'my-header-style',
            },
            title: 'แก้ไขรีวิว', 
            inputPlaceholder: 'Type of System',
            showCancelButton: true,
            confirmButtonText:'แก้ไข', 
            cancelButtonText:'ยกเลิก', 
            html: response['html'],
            focusConfirm: false,
            input: 'checkbox',
            inputValue: 1,
            inputValidator: (result) => { 
                if($(".page_reviews .txt_title").val().length < 1){
                   return 'ระบุชื่อรีวิว'
                }
                let param = { 
                    action:"update_reviews" 
                   ,id: response['rev_id'] 
                   ,image: $(".page_reviews #add-images-content-hidden").val()
                   ,title: $(".page_reviews .txt_title").val()
                   ,desc: $(".page_reviews .txt_desc").val()
                   ,priority: $(".page_reviews .txt_priority").val()
                 }  
                 swalUpdateNumbCategory(param);  
            },   
        }); 
}

function swalUpdateNumbCategory(param){
    $.ajax({
        url: 'ajax/ajax.reviews.php',
        type: 'POST',
        dataType: 'json',
        data: param,
        success: function(response){
           
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

           reloadTable();

        },
        error: function(){
            console.log('error')
        }
    })
}
function delReviews(event,_id){
    let details = $(".page_reviews .del_catenumb[data-id='"+_id+"']").data('name');
    Swal.fire({
        title: 'Are you sure?',
        text: "คุณต้องการลบรีวิว "+details+"!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ยืนยันการลบ!',
        cancelButtonText: 'ยกเลิก',
        
      }).then((result) => {
        if (result.value) {
            $.ajax({
                url: site_url + "ajax/ajax.reviews.php",
                type: "POST",
                dataType: 'json',
                data: { action: 'delete_reviews_by_id',id: _id },
                success: function(response){
                    if(response['message'] == "OK"){
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'ลบรายการสำเร็จ',
                            showConfirmButton: false,
                            timer: 1000
                          })
                    } else {
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'ลบรายการไม่สำเร็จ',
                            showConfirmButton: false,
                            timer: 1000
                          })
                    }
                   reloadTable();
                }
            })
        }
      }) 
}   

//Toggle Switch
$('.switch').on('click', (event) => {
    let _this = event.target;
    _this.closest('.toggle-switch').classList.toggle('ts-active')
    let status = _this.closest('.toggle-switch').classList.value.split(" ").find((e) => e === "ts-active")
    if (status == "ts-active") {
        $('.reviewspage').val('yes')
    } else {
        $('.reviewspage').val('no')
    }
}) 

$(".page_reviews").on("click",".btnPin .switch",function(event){
    let _this = event.target;
    _this.closest('.toggle-switch').classList.toggle('ts-active')
    let status = _this.closest('.toggle-switch').classList.value.split(" ").find((e) => e === "ts-active")
    if (status == "ts-active") {
        $('.reviews_status').val('yes')
        _pin = 'yes';
    } else {
        $('.reviews_status').val('no')
        _pin = 'no';
    }
    let _id = $(this).data('id');
    switchPin_reviews(_id,_pin);
})

function switchPin_reviews(_id,_pin){
    let param = { 
        action: 'update_pin_reviews',
        id: _id,
        pin: _pin
    }
    $.ajax({
        url: site_url +"ajax/ajax.reviews.php",
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
    });
}

$(".page_reviews ").on("change",'#add-images-content',function () { 
    let file = this.files[0];
    if (file.length !== 0) { 
      var img = file.name;
      $('#add-images-content-hidden').val(img);
      $(".form-add-images").removeClass("has-error");
      $(".add-images-error").css("display", "none"); 
      editPreviewImage(file,'uploadImage_reviews');
    }
});

function editPreviewImage(file,action) {
    let formdata = new FormData();
    formdata.append("action", action);
    formdata.append("images[]", file);
    $.ajax({
      url:  "ajax/ajax.reviews.php",
      type: 'POST',
      data: formdata,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function (response) { 
        let thumbnail = '<div class="col-img-preview" id="col_img_preview_1" data-id="1"><img class="preview-img" id="preview_img_1" src="https://'+location.host+'/'+response[0]+'"></div>';
        $(".page_reviews .image-label").css("display","none")
        $(".page_reviews .blog-preview-add").html(thumbnail);
        $(".page_reviews #add-images-content-hidden").val(response[0]);
      }
    });
  }
 
  function prepare_reviews(){
    $.ajax({
        url: site_url+'ajax/ajax.reviews.php',
        type: 'POST',
        dataType: 'json',
        data: {action: 'prepare__add_reviews' },
        success: function(response){
            add_reviews(response);
        
        }
    });
  }

  async function add_reviews(response){
    const { value: accept } = await Swal.fire({ 
        customClass: {
            container: 'swal-category-numb',
            header: 'my-header-style',
        },
        title: 'เพิ่มรีวิวสินค้า', 
        inputPlaceholder: 'Type of System',
        showCancelButton: true,
        confirmButtonText:'เพิ่ม', 
        cancelButtonText:'ยกเลิก', 
        html: response['html'],
        focusConfirm: false,
        input: 'checkbox',
        inputValue: 1,
        inputValidator: (result) => { 
            if($(".page_reviews .txt_title").val().length < 1){
                return 'ระบุชื่อรีวิว'
             }
             let param = { 
                 action:"insert_reviews" 
                ,image: $(".page_reviews #add-images-content-hidden").val()
                ,title: $(".page_reviews .txt_title").val()
                ,desc: $(".page_reviews .txt_desc").val()
                ,priority: $(".page_reviews .txt_priority").val()
              }  
              swalUpdateNumbCategory(param); 
        },   
    }); 
  }
 
 