$(function () {
  $('#subscribers-grid').DataTable( {
    "scrollX": true,
    "processing": true,
    "serverSide": true,
    "ajax": {
        url: "ajax/ajax.subscribers.php",
        data: {action:"getsubscribers"},
        type: "post",
        error: function(){
          $(".subscribers-grid-error").html("");
          $("#subscribers-grid").append('<tbody class="subscribers-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
          $("#subscribers-grid_processing").css("display","none"); 
        }
    },
    "columnDefs": [{
      targets: [4],
      orderable: false,
    }],
    "order": [[ 1, "desc" ]]
  });
});


$(document).on('click', '.delete-email', function(){
  var data = {
    action: "deleteemail",
    id: $(this).data("id")
  };
  $.confirm({
    title: 'Are you sure?',
    content: 'you want to delete this email.',
    theme: 'material',
    icon: 'fa fa-warning',
    type: 'red',
    draggable: false,
    buttons: {
      confirm:  {
        text: 'Yes, delete it!',
        btnClass: 'btn-red',
        action: function(){
          delete_email(data);
        }
      },
      formCancel: {
        text: 'Cancel',
        cancel: function () {}  
      }
    }
  });
});

function delete_email(data) {
  var url = "ajax/ajax.subscribers.php",
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
      }
    }
  });
}


$(function () {
  $('.mailbox-messages input[type="checkbox"]').iCheck({
    checkboxClass: 'icheckbox_flat',
    radioClass: 'iradio_flat'
  });

  $(".checkbox-toggle").click(function () {
    var clicks = $(this).data('clicks');
    if (clicks) {
      $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
      $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
    } else {
      $(".mailbox-messages input[type='checkbox']").iCheck("check");
      $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
    }
    $(this).data("clicks", !clicks);
  });
});


