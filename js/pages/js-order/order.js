$(function () {
  $('#admin-grid').DataTable( {
    "scrollX": true,
    "processing": true,
    "serverSide": true,
    "ajax": {
        url: "ajax/ajax.order.php",
        data: {action:"getorder"},
        type: "post",
        error: function(){
          $(".admin-grid-error").html("");
          $("#admin-grid").append('<tbody class="admin-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
          $("#admin-grid_processing").css("display","none"); 
        }
    },
    "columnDefs": [{
      targets: [7],
      orderable: false,
    }],
    "order": [[ 6, "desc" ]]
  });
});

$(document).on('click', '.view-order', function(){
  var userId = $(this).data("id");
  $.ajax({
    type:"POST",
    url:"ajax/ajax.order.php",
    data:{action:"getorderdetail",
          id:userId},
    beforeSend: function() {
      $("#order_id").val('');
      $("#name").val('');
      $("#phone").val('');
      $("#email").val('');
      $("#vehicle_type").val('');
      $("#location_route").val('');
      $("#message").val('');
      $("#fight_number").val('');
      $("#order_date").val('');
    },
    success:function(msg){
      var obj = jQuery.parseJSON(msg);
      console.log(obj);
      
      $("#order_id").val(obj['0'].order_id);
      $("#name").val(obj['0'].name);
      $("#phone").val(obj['0'].phone);
      $("#email").val(obj['0'].email);
      $("#vehicle_type").val(obj['0'].vehicle);
      $("#location_route").val(obj['0'].location_route);
      $("#message").val(obj['0'].message);
      $("#fight_number").val(obj['0'].fight_number);
      $("#order_date").val(obj['0'].new_date);
    }
  });
});

$(document).on('click', '.delete-order', function(){
  var data = {
    action: "deleteorder",
    id: $(this).data("id")
  };
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
  var url = "ajax/ajax.order.php",
      dataSet = data;
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function(data){
      var obj = jQuery.parseJSON(data);
      // console.log(obj);
      if (obj['message'] === "OK") {
        $.confirm({
          title: 'Deleted!',
          content: 'Order was successfully deleted!',
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
      }
    }
  });
}