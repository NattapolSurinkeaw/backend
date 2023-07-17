function reloadTable() {
    prophecy.ajax.reload(null, false);
}
$(function(){
    prophecy = $('#sumber-grid').DataTable({ 
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: site_url+ "ajax/ajax.predict_sumber.php",
            data: function(d){   
                d.action = "get_sumber";
            }, 
            type: "post",
            error: function() { 
            }
        },
        "columnDefs": [{ 
            targets: [1,3],
            orderable: false,
        }], 
        "order": [
            [0, "asc"]
        ],
        "columns": [
            { "width": "15%", "targets": 0 },
            { "width": "40%", "targets": 1 },
            { "width": "10%", "targets": 2 },
            { "width": "25%", "targets": 3 },
          ],
        "pageLength": 50,
    }); 
});

function add_sumber(){
    $.ajax({
        url: site_url+'ajax/ajax.predict_sumber.php',
        type: 'POST',
        dataType: 'json',
        data: { action:'add_sumber' },
        success: function(response){
            add_sumber_swal(response);
        }
    });
}

async function add_sumber_swal(response){
    const { value: accept } = await Swal.fire({ 
        customClass: {
            container: 'swal-numb',
            header: 'my-header-style',
        },
        title: 'เพิ่มหมายเลข', 
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
                action:"insert_sumber" 
               ,desc: $(".page_predicts .txt_desc").val()
               ,number: $(".page_predicts .txt_number").val()
             }  
             if($(".page_predicts .txt_number").val().length < 1){
                return 'กรุณากรอกหมายเลข'
             }
            
             insert_sumber(param); 
        },   
    }); 
  }
function insert_sumber(param){
    $.ajax({
        url: site_url+'ajax/ajax.predict_sumber.php',
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
            reloadTable();
        }
    });
  }


function prepareDel_sumber(event,_id,_numb){
    Swal.fire({
        title: 'Are you sure?',
        text: "คุณต้องการลบหมายเลข "+_numb+"!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ยืนยันการลบ!',
        cancelButtonText: 'ยกเลิก',
        
      }).then((result) => {
        if (result.value) {
            $.ajax({
                url: site_url + "ajax/ajax.predict_sumber.php",
                type: "POST",
                dataType: 'json',
                data: {action:'delete_sumber',id: _id},
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


function prepareEdit_sumber(even,_id){
    $.ajax({
        url: site_url+'ajax/ajax.predict_sumber.php',
        type: 'POST',
        dataType: 'json',
        data: { action:"prepare_edit_sumber",id:_id },
        success: function(response){
            edit_sumber(response)
        }
    });
}
async function edit_sumber(response){
    const { value: accept } = await Swal.fire({ 
        customClass: {
            container: 'swal-sumber',
            header: 'my-header-style',
        },
        title: 'แก้ไขข้อมูล', 
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
                action:"update_sumber" 
               ,id: response['id']
               ,desc: $(".page_predicts .txt_desc").val()
               ,number: $(".page_predicts .txt_number").val()
             }  
             if($(".page_predicts .txt_number").val().length < 1){
                return 'กรุณากรอกหมายเลข'
             }
           
            swalUpdateProphecy(param);  
        },   
    }); 
}

function swalUpdateProphecy(param){
    $.ajax({
        url: site_url+'ajax/ajax.predict_sumber.php',
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

$(".page_predicts").on("click",".btnPin .switch",function(event){
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
        url: "ajax/ajax.predict_sumber.php",
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