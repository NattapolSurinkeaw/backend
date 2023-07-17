$(".send-content").on("click",function(){
   $.ajax({
     url: "ajax/ajax.transmit.php", 
     type: 'POST',
     dataType: 'json',
     data: {action: "transmit_news",id: $(this).data("id")},
     success: function(response) {
       if(response['message'] == "OK"){ 
          Swal.fire({
            position: 'top-center',
            icon: 'success',
            title: 'ส่งข้อมูลข่าวสารสำเร็จแล้ว!',
            showConfirmButton: false,
            timer: 2000
          }).then(()=>{
            location.reload();
          });
       }else{ 
          Swal.fire({
            position: 'top-center',
            icon: 'error',
            title: 'ส่งข้อมูลข่าวสารไม่สำเร็จ!',
            showConfirmButton: false,
            timer: 2000
          }).then(()=>{
            location.reload();
          }); 
       }
     }
   })
});