var predicts_id = "";
function reloadTable(tables){
    if(tables == "cate"){
        predicts_cate.ajax.reload(null, false);
    }else if(tables == "subcate"){
        predicts_numb.ajax.reload(null, false);
    }else {
        predicts_cate.ajax.reload(null, false);
        predicts_numb.ajax.reload(null, false);
    }
}
$(function(){
    predicts_cate = $('#predicts-grid').DataTable({ 
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "ajax/ajax.predict_numb.php",
            data: function(d){   
                d.action = "get_category";
            }, 
            type: "post",
            error: function() { 
            }
        },
        "columnDefs": [{ 
            targets: [1,2,3,4],
            orderable: false,
        }], 
        "order": [
            [0, "asc"]
        ],
        "columns": [
            { "width": "10%", "targets": 0 },
            { "width": "40%", "targets": 1 },
            { "width": "10%", "targets": 2 },
            { "width": "10%", "targets": 3 },
            { "width": "30%", "targets": 4 },
          ],
        "pageLength": 50,
    });  
    
    predicts_numb = $('#predicts-numb-grid').DataTable({ 
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "ajax/ajax.predict_numb.php",
            data: function(d){   
                d.action = "get_numb_category";
                d.id = predicts_id;
            }, 
            type: "post", 
            error: function() { 
            }
        },
        initComplete: function(settings, json){
            console.log(json)
        },
        "columnDefs": [{ 
            targets: [1,2,3,4],
            orderable: false,
        }], 
        "order": [
            [0, "asc"]
        ],
        "pageLength": 50,
    }); 
});

function toggle_tables(){
    /* ถ้าตารางไหนแสดงผลอยู่ทำการซ่อน และตารางที่ซ่อนอยู่ทำการแสดงผล */
    if($(".page_predicts .btn-back").hasClass("active")){
        $(".page_predicts .toggle").removeClass("inactive");
        $(".page_predicts #predicts-numb-tables").addClass("inactive");
        $(".page_predicts .btn-back").removeClass("active");
        $(".page_predicts .addCategoryNumb").data('name',"prepare_add_numbcate")
    } else {
        $(".page_predicts .toggle").removeClass("inactive");
        $(".page_predicts #predicts-cate-tables").addClass("inactive");
        $(".page_predicts .btn-back").addClass("active");
        $(".page_predicts .addCategoryNumb").data('name',"prepare_add_subcate")
    }
}

$(".page_predicts").on("click",".btn-back.active",function(){
    toggle_tables();   
    reloadTable('cate');    
}); 
function showSubcategory(event,_id){
    predicts_id = _id;
    toggle_tables();
    reloadTable('subcate');    
    let details = $(".page_predicts .btn-edit-category[data-id='"+_id+"']").data('name');
    $(".page_predicts span.name-cate").html(details);
}

 
function editCategory(event,_id){
    let param = {
        action: 'prepare_edit',
        id: _id
    }
    $.ajax({
        url: site_url+"ajax/ajax.predict_numb.php",
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
            title: 'แก้ไขหมวดหมู่', 
            inputPlaceholder: 'Type of System',
            showCancelButton: true,
            confirmButtonText:'แก้ไข', 
            cancelButtonText:'ยกเลิก', 
            html: response['html'],
            focusConfirm: false,
            input: 'checkbox',
            inputValue: 1,
            inputValidator: (result) => { 
                if($(".page_predicts .txt_catename").val().length < 1){
                   return 'ระบุชื่อหมวดหมู่'
                }
                let param = { 
                    action:"update_numb_category" 
                   ,id: response['numbcate_id'] 
                   ,image: $(".page_predicts #add-images-content-hidden").val()
                   ,abv: $(".page_predicts .txt_abbrev").val()
                   ,name: $(".page_predicts .txt_catename").val()
                   ,color: $(".page_predicts #slc_color_numcate").val()
                   ,priority: $(".page_predicts .txt_priority").val()
                   ,wanted: $(".page_predicts #editWanted").val()
                   ,unwanted: $(".page_predicts #editUnwanted").val()
                 }  
                 swalUpdateNumbCategory(param);  
            },   
        }); 
}

function swalUpdateNumbCategory(param){
    $.ajax({
        url: 'ajax/ajax.predict_numb.php',
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

            reloadTable("cate"); 

        },
        error: function(){
            console.log('error')
        }
    })
}
function delCategory(event,_id){
    let details = $(".page_predicts .del_catenumb[data-id='"+_id+"']").data('name');
    Swal.fire({
        title: 'Are you sure?',
        text: "คุณต้องการลบหมวดหมู่ "+details+"!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ยืนยันการลบ!',
        cancelButtonText: 'ยกเลิก',
        
      }).then((result) => {
        if (result.value) {
            $.ajax({
                url: site_url + "ajax/ajax.predict_numb.php",
                type: "POST",
                dataType: 'json',
                data: { action: 'delete_category_numb',id: _id },
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
                    reloadTable("cate"); 
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
        $('.predictCate').val('yes')
    } else {
        $('.predictCate').val('no')
    }
}) 

$(".page_predicts").on("click",".btnDisplay .switch",function(event){
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
        action: 'update_pin_numb_category',
        id: _id,
        pin: _display
    }
    $.ajax({
        url: "ajax/ajax.predict_numb.php",
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

$(".page_predicts ").on("change",'#add-images-content',function () { 
    let file = this.files[0];
    if (file.length !== 0) { 
      var img = file.name;
      $('#add-images-content-hidden').val(img);
      $(".form-add-images").removeClass("has-error");
      $(".add-images-error").css("display", "none"); 
      editPreviewImage(file,'uploadImageNumbCategory');
    }
});

function editPreviewImage(file,action) {
    let formdata = new FormData();
    formdata.append("action", action);
    formdata.append("images[]", file);
    $.ajax({
      url:  "ajax/ajax.predict_numb.php",
      type: 'POST',
      data: formdata,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function (response) { 
        let thumbnail = '<div class="col-img-preview" id="col_img_preview_1" data-id="1"><img class="preview-img" id="preview_img_1" src="https://'+location.host+'/'+response[0]+'"></div>';
        $(".page_predicts .image-label").css("display","none")
        $(".page_predicts .blog-preview-add").html(thumbnail);
        $(".page_predicts #add-images-content-hidden").val(response[0]);
      }
    });
  }

  $(".page_predicts").on("change","#slc_color_numcate",function(){
        let color = $(this).val();
        $(".page_predicts .sample_color").attr("src",root_url +color);
  });

  function add_category_numb(){
    let action = $(".addCategoryNumb").data('name');
    $.ajax({
        url: site_url+'ajax/ajax.predict_numb.php',
        type: 'POST',
        dataType: 'json',
        data: { action,id:predicts_id },
        success: function(response){
            if(action == "prepare_add_numbcate"){
                add_category_numb_swal(response);
            } else {
                add_numb_swal(response);
            }
        }
    });
  }

  async function add_category_numb_swal(response){
    const { value: accept } = await Swal.fire({ 
        customClass: {
            container: 'swal-category-numb',
            header: 'my-header-style',
        },
        title: 'เพิ่มหมวดหมู่การทำนาย', 
        inputPlaceholder: 'Type of System',
        showCancelButton: true,
        confirmButtonText:'เพิ่ม', 
        cancelButtonText:'ยกเลิก', 
        html: response['html'],
        focusConfirm: false,
        input: 'checkbox',
        inputValue: 1,
        inputValidator: (result) => { 
            if($(".page_predicts .txt_catename").val().length < 1){
                return 'ระบุชื่อหมวดหมู่'
             }
             let param = { 
                 action:"insert_numb_category" 
                ,image: $(".page_predicts #add-images-content-hidden").val()
                ,abv: $(".page_predicts .txt_abbrev").val()
                ,name: $(".page_predicts .txt_catename").val()
                ,color: $(".page_predicts #slc_color_numcate").val()
                ,priority: $(".page_predicts .txt_priority").val()
                ,wanted: $(".page_predicts .addWanted").val()
                ,unwanted: $(".page_predicts .addUnwanted").val()
              }  
              swalUpdateNumbCategory(param); 
        },   
    }); 
  }


  async function add_numb_swal(response){
    const { value: accept } = await Swal.fire({ 
        customClass: {
            container: 'swal-numb',
            header: 'my-header-style',
        },
        title: 'เพิ่มหมวดย่อยการทำนาย', 
        inputPlaceholder: 'Type of System',
        showCancelButton: true,
        confirmButtonText:'เพิ่ม', 
        cancelButtonText:'ยกเลิก', 
        html: response['html'],
        focusConfirm: false,
        input: 'checkbox',
        inputValue: 1,
        inputValidator: (result) => { 
            let param = { 
                action:"insert_numb_subcategory" 
               ,cate_id: predicts_id
               ,name: $(".page_predicts .txt_number").val()
               ,wanted: $(".page_predicts .txt_wanted").val()
               ,unwanted: $(".page_predicts .txt_unwanted").val()
               ,priority: $(".page_predicts .txt_priority").val()
             }  

             let duplicate = chkOverlapful_less(param.wanted,param.unwanted);
             if(duplicate != ""){
                 return "หมายเลข "+duplicate+" ต้องไม่ซ้ำกัน" 
             } 
             if($(".page_predicts .txt_number").val().length < 1){
                return 'ระบุชื่อหมวดย่อย'
             }
             insert_subcategory_numb(param); 
        },   
    }); 
  }
  function insert_subcategory_numb(param){
    $.ajax({
        url: 'ajax/ajax.predict_numb.php',
        type: 'POST',
        dataType: 'json',
        data: param,
        success: function(response){
            if(response['message'] == "OK"){
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'เพิ่มข้อมูลสำเร็จ',
                    showConfirmButton: false,
                    timer: 1000
                  })
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'เพิ่มข้อมูลไม่สำเร็จ',
                    showConfirmButton: false,
                    timer: 1000
                  })
            }
            reloadTable('subcate');
        }
    });
  }



  function chkOverlapful_less(_needful,_needless){
  
    needful = (_needful).split(',');
    needless = (_needless).split(',');
    var dupOverLap =[];
    var dupOverLap_msg = "";
    $.each( needful, function( key, value ) {
      if(!!~jQuery.inArray(value, needless)){
        if(jQuery.inArray(value, dupOverLap)){
            dupOverLap_msg += (dupOverLap_msg != "")? ","+value:value;
        }
      }
    }); 
    
    if(dupOverLap_msg != ""){   
      $('.page_predicts .txt_unwanted').addClass("failed");  
      $('.page_predicts .txt_wanted').addClass("failed");
      return dupOverLap_msg; 
    }else{   
      $('.page_predicts .txt_unwanted').removeClass("failed");
      $('.page_predicts .txt_wanted').removeClass("failed"); 
    } 
    return dupOverLap_msg; 
  }
  
function del_predictnumb(event,_id){
    let details = $(".page_predicts .del_catenumb[data-id='"+_id+"']").data('name');
    Swal.fire({
        title: 'Are you sure?',
        text: "คุณต้องการลบหมวดย่อย "+details+"!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ยืนยันการลบ!',
        cancelButtonText: 'ยกเลิก',
        
      }).then((result) => {
        if (result.value) {
            $.ajax({
                url: site_url + "ajax/ajax.predict_numb.php",
                type: "POST",
                dataType: 'json',
                data: {action:'delete_predict_numb',id: _id},
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
                    reloadTable("subcate"); 
                }
            })
        }
      }) 
}   

function prepareEdit_predictnumb(even,_id){ 
    $.ajax({
        url: site_url+'ajax/ajax.predict_numb.php',
        type: 'POST',
        dataType: 'json',
        data: { action:"prepare_edit_subcate",id:_id },
        success: function(response){
            if(response['id'] > 0){
                edit_predicts_numb(response)
            }else {
                console.log('failed id: '+_id)
            }
        }
    });
}

async function edit_predicts_numb(response){
    const { value: accept } = await Swal.fire({ 
        customClass: {
            container: 'swal-numb',
            header: 'my-header-style',
        },
        title: 'แก้ไขหมวดหมู่', 
        inputPlaceholder: 'Type of System',
        showCancelButton: true,
        confirmButtonText:'แก้ไข', 
        cancelButtonText:'ยกเลิก', 
        html: response['html'],
        focusConfirm: false,
        input: 'checkbox',
        inputValue: 1,
        inputValidator: (result) => { 
            let param = { 
                action:"update_predict_numb" 
               ,cate_id: response['cate_id'] 
               ,id: response['id'] 
               ,name: $(".page_predicts .txt_number").val()
               ,wanted: $(".page_predicts .txt_wanted").val()
               ,unwanted: $(".page_predicts .txt_unwanted").val()
               ,priority: $(".page_predicts .txt_priority").val()
             }  
            let duplicate = chkOverlapful_less(param.wanted,param.unwanted);
            if(duplicate != ""){
                return "หมายเลข "+duplicate+" ต้องไม่ซ้ำกัน" 
            } 
            if($(".page_predicts .txt_number").val().length < 1){
               return 'ระบุชื่อหมวดย่อย'
            }
            swalUpdateNumbPredict(param);  
        },   
    }); 
}

function swalUpdateNumbPredict(param){
    $.ajax({
        url: 'ajax/ajax.predict_numb.php',
        type: 'POST',
        dataType: 'json',
        data: param,
        success: function(response){
            if(response['message'] == "OK"){
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'แก้ไขข้อมูลสำเร็จ',
                    showConfirmButton: false,
                    timer: 1000
                  })
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'แก้ไขข้อมูลไม่สำเร็จ',
                    showConfirmButton: false,
                    timer: 1000
                  })
            }
            reloadTable('subcate');
        }
    });
}



testFunction();

function testFunction(){
    $.ajax({
        url: 'ajax/ajax.predict_numb.php',
        type: 'POST',
        dataType: 'json',
        data: {action: "testFunction"},
        success: function(response){
            
            console.log(response);
 

        },
        error: function(){
            console.log('error')
        }
    })
}