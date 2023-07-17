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
  //console.log(id);
  var id = id;
  $.ajax({
    type:"POST",
    url:url_ajax_request + "ajax/ajax.orders.php",
    data:{action:"getorderdetail",
          id: id},
    beforeSend: function() {
      
    },
    success:function(msg){
      var obj = jQuery.parseJSON(msg);
      // console.log(obj);
      //left
      $("#order-id").val(id);

      $('.orderid span').html( obj[0]['id'] );
      $('.orderdate').html( 'สั่งซื้อวันที่ : ' + obj[2] + ' ( วัน / เดือน / ปี )' );
      $('.customerName').html( obj[0]['name'] );
      $('.customerAddress').html( obj[0]['address'] );
      $('.aumphor').html( obj[0]['district'] );
      $('.country').html( obj[0]['province'] );
      $('.zipcode').html( obj[0]['zipcode'] );
      $('.customerPhone').html( obj[0]['phone'] );
      $('.customerEmail').html( obj[0]['email'] );
      $('.customerMsg').html( obj[0]['msg'] );
      $('.customerLine').html( obj[0]['id_line'] );

      //items
      var items = '';
      var totalPrice = 0;
      var replace_ = '';

      for( var i = 0; i < Object.keys(obj[5]).length ; i++ ){
        items +='\
                    <tr>\
                      <td> <img src = "'+root_url+obj[5][ Object.keys(obj[5])[i] ]["thumbnail"]+'"> </td>\
                      <td>'+obj[5][ Object.keys(obj[5])[i] ]["name"]+'</td>\
                      <td>'+obj[5][ Object.keys(obj[5])[i] ]["quantity"]+'</td>\
                      <td>'+obj[5][ Object.keys(obj[5])[i] ]["price"]+'</td>\
                    </tr>';
        replace_ = obj[5][ Object.keys(obj[5])[i] ]["price"];            
        replace_ = replace_.replace("," , "");
        replace_ = replace_.replace(" " , "");
        totalPrice += parseFloat( replace_ );           
      }
      items += '<tr class="total">\
                  <td colspan="3"> ราคารวมสินค้า </td>\
                  <td> '+totalPrice.toLocaleString(undefined, {minimumFractionDigits: 2,maximumFractionDigits: 2})+' บาท </td>\
                </tr>';
      $('.table-order-data .table-order-detail').html( items );

      if( obj[0]['slipimg'] == '' ){ 
        $('.customerSlip').hide();
      }else{
        $('.customerSlip').show();
      }
      $('.payedDate').html( 'โอนวันที่ : ' + obj[3] + ' ( วัน / เดือน / ปี )' );
      $('.payedByDesc').html( obj[0]['payby'] );
      $('.slipImg img').attr("src", obj[4] );

      //select
      /*var status = '';
      var selected = '';
      for( var i = 0; i < obj[1].length ; i++ ){
        if( obj[0]['orders_status'] == obj[1][i]['status_id'] ){ selected = 'selected'; }else{ selected = ''; }
        status += '<option value="'+obj[1][i]['status_id']+'" '+selected+' >'+obj[1][i]['orders_desc']+'</option>';
      }
      $('#order_status').html( status );
*/
      $('#order_status option[value="'+obj[0]['orders_status']+'"]').attr('selected','selected');
      $('#modalViewOrder').modal('toggle');

    }
  });
}

$("#save-order").on("click",function() {
  var data = {
    action: "editorderstatus",
    orderId: $("#order-id").val(),
    orders_status: $("#order_status").val()
  };
  console.log(data);
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