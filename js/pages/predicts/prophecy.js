function reloadTable() {
    prophecy.ajax.reload(null, false);
}
$(function(){
    prophecy = $('#prophecy-grid').DataTable({ 
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: site_url+ "ajax/ajax.predict_prophecy.php",
            data: function(d){   
                d.action = "get_prophecy";
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
            { "width": "10%", "targets": 0 },
            { "width": "20%", "targets": 1 },
            { "width": "40%", "targets": 2 },
            { "width": "10%", "targets": 3 },
            { "width": "10%", "targets": 4 },
          ],
        "pageLength": 50,
    }); 
});

function add_prophecy(){
    $.ajax({
        url: site_url+'ajax/ajax.predict_prophecy.php',
        type: 'POST',
        dataType: 'json',
        data: { action:'add_prophecy' },
        success: function(response){
            add_prophecy_swal(response);
        }
    });
}

async function add_prophecy_swal(response){
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
                action:"insert_prophecy" 
               ,title: $(".page_predicts .txt_title").val() 
               ,desc: $(".page_predicts .txt_desc").val()
               ,number: $(".page_predicts .txt_number").val()
               ,percentile: $(".page_predicts .txt_percent").val()
             }  
             if($(".page_predicts .txt_number").val().length < 1){
                return 'กรุณากรอกหมายเลข'
             }
             if($(".page_predicts .txt_percent").val().length < 1 ){
                return 'กรุณากรอกเปอร์เซ็นต์'
             } 
             insert_prophecy(param); 
        },   
    }); 
  }
function insert_prophecy(param){
    $.ajax({
        url: site_url+'ajax/ajax.predict_prophecy.php',
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


function prepareDel_prophecy(event,_id,_numb){
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
                url: site_url + "ajax/ajax.predict_prophecy.php",
                type: "POST",
                dataType: 'json',
                data: {action:'delete_prophecy',id: _id},
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


function prepareEdit_prophecy(even,_id){
    $.ajax({
        url: site_url+'ajax/ajax.predict_prophecy.php',
        type: 'POST',
        dataType: 'json',
        data: { action:"prepare_edit_prophecy",id:_id },
        success: function(response){
            edit_prophecy(response)
        }
    });
}
async function edit_prophecy(response){
    const { value: accept } = await Swal.fire({ 
        customClass: {
            container: 'swal-prophecy',
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
                action:"update_prophecy" 
               ,id: response['id']
               ,title: $(".page_predicts .txt_title").val() 
               ,desc: $(".page_predicts .txt_desc").val()
               ,number: $(".page_predicts .txt_number").val()
               ,percentile: $(".page_predicts .txt_percent").val()
             }  
             if($(".page_predicts .txt_number").val().length < 1){
                return 'กรุณากรอกหมายเลข'
             }
             if($(".page_predicts .txt_percent").val().length < 1 ){
                return 'กรุณากรอกเปอร์เซ็นต์'
             } 
            swalUpdateProphecy(param);  
        },   
    }); 
}

function swalUpdateProphecy(param){
    $.ajax({
        url: site_url+'ajax/ajax.predict_prophecy.php',
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