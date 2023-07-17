$(function () {
  $('#order-grid-table').DataTable( {
    "scrollX": true,
    "processing": true,
    "serverSide": true,
    "ajax": {
        url: "ajax/ajax.orders.php",
        data: {action:"getorder"},
        type: "post",
        error: function(){
          $(".employee-grid-error").html("");
          $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
          $("#employee-grid_processing").css("display","none"); 
        }
    },
    "columnDefs": [{
      targets: [6],
      orderable: false,
    }],
    "order": [[ 2, "desc" ]]
  });
});

$(document).on('click', '.getorderdetail', function(){
  //console.log( $(this).data('id') );
  get_order_detail($(this).data('id'));
});

function get_order_detail(id) {  

  var shippingstatus = '';
  var payingstatus = '';
  var selected = '';
  var tableorderdetail = '';

  console.log(id);

  $.ajax({
    type:"POST",
    url:url_ajax_request + "ajax/ajax.orders.php",
    data:{action:"getorderdetail",
          id: id},
    beforeSend: function() {
      
    },
    success:function(msg){
      var obj = jQuery.parseJSON(msg);
      console.log(obj);
      //left
      $(".service_id").html(obj[0][0]['id']);

      var car_cate = obj[0][0]['car_cate'];
      $(".car_cate").html( /*obj[0][0]['car_cate'] +*/ " " +obj[2][car_cate]['cate_name']);

      var car_brand = obj[0][0]['car_brand'];
      $(".car_brand").html(/*obj[0][0]['car_brand'] + */" " +obj[3][car_brand]['cate_name']);

      var car_subcate = obj[0][0]['car_subcate'];
      $(".car_subcate").html("รหัสรถ " +obj[0][0]['car_subcate'] + " " +obj[4][car_subcate]['title']);

      var color = obj[0][0]['color'];
      $(".color").html( obj[5][color]['tag_name'] );


      $(".showroom").html(obj[0][0]['showroom']+" "+obj[0][0]['showroom_room'] );
      $(".banking").html(obj[0][0]['banking']+" "+obj[0][0]['bankingname']+" "+obj[0][0]['bankingname_number'] );
      
      //middle basicdata
      $(".fullname").html(obj[0][0]['fullname']);
      $(".oldfullname").html(obj[0][0]['oldfullname']);
      $(".birthdate").html(obj[0][0]['birthdate']);
      $(".age").html(obj[0][0]['age']);
      $(".cc").html(obj[0][0]['cc']);
      $(".sc").html(obj[0][0]['sc'] );
      $(".citizennumber").html(obj[0][0]['citizennumber']);
      $(".outdate").html(obj[0][0]['outdate']);

      $(".marry_status").html(obj[0][0]['marry_status']);
      $(".manychild").html(obj[0][0]['manychild']);
      $(".fullname_wh").html(obj[0][0]['frontname_wh']+" "+obj[0][0]['fullname_wh']);
      $(".age_wh").html(obj[0][0]['age_wh']);
      $(".job_wh").html(obj[0][0]['job_wh']);
      $(".position_wh").html(obj[0][0]['position_wh']);
      $(".jobname_wh").html(obj[0][0]['jobname_wh']);
      $(".phonejob_wh").html(obj[0][0]['phonejob_wh']);
      $(".phonehome_wh").html(obj[0][0]['phonehome_wh']);
      $(".phonemobile_wh").html(obj[0][0]['phonemobile_wh']);
      $(".saraly_wh").html(obj[0][0]['saraly_wh']);
      $(".morejob_wh").html(obj[0][0]['morejob_wh']);
      $(".morejobposition_wh").html(obj[0][0]['morejobposition_wh']);
      $(".morejobtype_wh").html(obj[0][0]['morejobtype_wh']);
      $(".morejobsaraly_wh").html(obj[0][0]['morejobsaraly_wh']);

      //ที่อยู่ที่ทำงาน
      $(".work_address").html(obj[0][0]['work_address']);
      $(".work_type").html(obj[0][0]['work_type']);
      $(".work_job").html(obj[0][0]['work_job']);
      $(".work_position").html(obj[0][0]['work_position']);
      $(".work_class").html(obj[0][0]['work_class']);
      $(".work_years").html(obj[0][0]['work_years']);
      $(".work_address_number").html(obj[0][0]['work_address_number']);
      $(".work_address_moo").html(obj[0][0]['work_address_moo']);
      $(".work_address_mooban").html(obj[0][0]['work_address_mooban']);
      $(".work_address_soi").html(obj[0][0]['work_address_soi']);
      $(".work_address_road").html(obj[0][0]['work_address_road']);
      $(".work_address_tumbol").html(obj[0][0]['work_address_tumbol']);
      $(".work_address_aumphor").html(obj[0][0]['work_address_aumphor']);
      $(".work_address_country").html(obj[0][0]['work_address_country']);
      $(".work_address_zip").html(obj[0][0]['work_address_zip']);
      $(".work_address_phone").html(obj[0][0]['work_address_phone']);
      $(".work_saraly").html(obj[0][0]['work_saraly']);
      $(".work_more_job").html(obj[0][0]['work_more_job']);
      $(".work_more_position").html(obj[0][0]['work_more_position']);
      $(".work_more_type").html(obj[0][0]['work_more_type']);
      $(".work_more_saraly").html(obj[0][0]['work_more_saraly']);
      $(".work_more_another").html(obj[0][0]['work_more_another']);

      //ที่อยู่ตามทะเบียนบ้าน
      $(".address_number").html(obj[0][0]['address_number']);
      $(".address_mootee").html(obj[0][0]['address_mootee']);
      $(".address_mooban").html(obj[0][0]['address_mooban']);
      $(".address_soi").html(obj[0][0]['address_soi']);
      $(".address_road").html(obj[0][0]['address_road']);
      $(".address_tumbol").html(obj[0][0]['address_tumbol']);
      $(".address_aumphor").html(obj[0][0]['address_aumphor']);
      $(".address_country").html(obj[0][0]['address_country']);
      $(".address_zip").html(obj[0][0]['address_zip']);
      $(".address_phone").html(obj[0][0]['address_phone']);
      $(".address_mobile").html(obj[0][0]['address_mobile']);
      $(".home_years").html(obj[0][0]['home_years']);
      $(".houseowner").html(obj[0][0]['houseowner']);

      //ที่อยู่ปัจจุบัน
      $(".current_address_number").html(obj[0][0]['current_address_number']);
      $(".current_address_mootee").html(obj[0][0]['current_address_mootee']);
      $(".current_address_mooban").html(obj[0][0]['current_address_mooban']);
      $(".current_address_soi").html(obj[0][0]['current_address_soi']);
      $(".current_address_road").html(obj[0][0]['current_address_road']);
      $(".current_address_tumbol").html(obj[0][0]['current_address_tumbol']);
      $(".current_address_aumphor").html(obj[0][0]['current_address_aumphor']);
      $(".current_address_country").html(obj[0][0]['current_address_country']);
      $(".current_address_zip").html(obj[0][0]['current_address_zip']);
      $(".current_address_phone").html(obj[0][0]['current_address_phone']);
      $(".current_address_mobile").html(obj[0][0]['current_address_mobile']);
      $(".current_home_years").html(obj[0][0]['current_home_years']);

      $(".sendto_number").html(obj[0][0]['sendto_number']);
      $(".sendto_mootee").html(obj[0][0]['sendto_mootee']);
      $(".sendto_mooban").html(obj[0][0]['sendto_mooban']);
      $(".sendto_soi").html(obj[0][0]['sendto_soi']);
      $(".sendto_road").html(obj[0][0]['sendto_road']);
      $(".sendto_tumbol").html(obj[0][0]['sendto_tumbol']);
      $(".sendto_aumphor").html(obj[0][0]['sendto_aumphor']);
      $(".sendto_country").html(obj[0][0]['sendto_country']);
      $(".sendto_zip").html(obj[0][0]['sendto_zip']);
      $(".sendto_phone").html(obj[0][0]['sendto_phone']);
      $(".sendto_mobile").html(obj[0][0]['sendto_mobile']);
      $(".sendto_home_years").html(obj[0][0]['sendto_home_year']);

      $(".creditcard").html(obj[0][0]['creditcard']+" ผ่อนเดือนละ "+obj[0][0]['creditcard_per_month']);
      $(".car").html(obj[0][0]['car']+" ผ่อนเดือนละ "+obj[0][0]['car_per_month']);
      $(".motocy").html(obj[0][0]['motocy']+" ผ่อนเดือนละ "+obj[0][0]['motocy_per_month']);
      $(".havehome").html(obj[0][0]['havehome']+" ผ่อนเดือนละ "+obj[0][0]['havehome_per_month']);
      $(".sinc").html(obj[0][0]['sinc']+" ผ่อนเดือนละ "+obj[0][0]['sinc_per_month']);
      
    }
  });
}

$("#save-order").on("click",function() {
  var orderId = $("#order-id").val(),
      shippingstatus = $('#shippingstatus'),
      paymentstatus = $('#paymentstatus');

  var data = {
    action: "editorderstatus",
    orderId: orderId,
    shippingstatus: shippingstatus.val(),
    paymentstatus: paymentstatus.val()
  };
   //console.log(data);
  edit_order_status(data);
});



function edit_order_status(data) {
  var url = "ajax/ajax.orders.php",
      dataSet = data;
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function(msg){
      console.log(msg);
      var obj = jQuery.parseJSON(msg);
      // console.log(obj);
      if(obj['message'] === "success"){
        location.reload();

      }else if (obj['message'] === "not_success") {
        $.confirm({
          title: 'บันทึกไม่สำเร็จ',
          content: 'กรุณาลองใหม่',
          theme: 'material',
          icon: 'fa fa-times-circle',
          type: 'red',
          draggable: false,
          backgroundDismiss: true,
          buttons: {
              confirm:  {
                  text: 'OK',
                  btnClass: 'btn-red',
                  action: function(){
                    $("#modal-admin").modal("hide");
                  }
              }
          },
          backgroundDismiss: function(){
            $("#modal-admin").modal("hide");
          }
        });
      }

    }
  });
}


$(document).on('click', '.delete-order', function(){

  var data = {
    action: "deleteorder",
    id: $(this).data("id")
  };
  //console.log(data);
  $.confirm({
    title: 'Are you sure?',
    content: 'you want to delete this order.',
    theme: 'material',
    icon: 'fa fa-warning',
    type: 'red',
    draggable: false,
    buttons: {
      confirm:  {
        text: 'Yes, delete it!',
        btnClass: 'btn-red',
        action: function(){
          delete_order(data);
        }
      },
      formCancel: {
        text: 'Cancel',
        cancel: function () {}  
      }
    }
  });
});

function delete_order(data) { 
  var url = "ajax/ajax.orders.php",
      dataSet = data;
      //console.log(dataSet.id);
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function(msg){
      var obj = jQuery.parseJSON(msg);
       console.log(obj[0]+" / "+dataSet.id);
      //if (obj[0] === dataSet.id) {
        $.confirm({
          title: 'Deleted!',
          content: 'Account was successfully deleted!',
          theme: 'modern',
          icon: 'fa fa-check',
          type: 'darkgreen',
          draggable: false,
          backgroundDismiss: true,
          buttons: {
            confirm:  {
              text: 'OK',
              btnClass: 'btn-darkgreen',
              action: function(){
                location.reload();
              }
            }
          },
          backgroundDismiss: function(){
            location.reload();
          }
        });
      //}
    }
  });
}

$('#print-link-detail').on('click', function() {

  $("#order-detail").print({
    stylesheet : site_url+"css/print/style-booking-print.css",
  });

});

$('#print-link-slip').on('click', function() {

  $("#showslip").print({
    stylesheet : site_url+"css/print/style-booking-print.css",
  });

});