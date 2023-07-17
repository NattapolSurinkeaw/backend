$(function(){
    grade_grid = $('#grades-grid').DataTable({ 
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "ajax/ajax.grade.php",
            data: function(d){   
                d.action = "get_grades";
            }, 
            type: "post",
            error: function() { 
            }
        },
        "columnDefs": [{ 
            targets: [1,2],
            orderable: false,
        }], 
        "order": [
            [0, "asc"]
        ],
        "pageLength": 50,
    });  
});

$(".page_predicts  #grades-tables").on("keyup",".txt_desc",function(e){
    let keycode = e.keyCode;
    if(keycode == 13){
        param = {
            value: $(this).val(),
            id: $(this).data('id'),
            name: 'description'
        }
        grades_update_prepare(param);
    }
});
$(".page_predicts  #grades-tables").on("keyup",".txt_max",function(e){
    let keycode = e.keyCode;
    if(keycode == 13){
        param = {
            value: $(this).val(),
            id: $(this).data('id'),
            name: 'max'
        }
        grades_update_prepare(param);
    }
});
$(".page_predicts  #grades-tables").on("keyup",".txt_min",function(e){
    let keycode = e.keyCode;
    if(keycode == 13){
        param = {
            value: $(this).val(),
            id: $(this).data('id'),
            name: 'min'
        }
        grades_update_prepare(param);
    }
});

function grades_update_prepare(param){
    param.action = "update_grades";
    $.ajax({
        url: site_url+"ajax/ajax.grade.php",
        type: "POST",
        dataType: "json",
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
     
        }
    });
}