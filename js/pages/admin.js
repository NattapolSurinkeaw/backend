$(function () {
  $('#admin-grid').DataTable( {
    "scrollX": true,
    "processing": true,
    "serverSide": true,
    "ajax": {
        url: "ajax/ajax.admin.php",
        data: {action:"getalluser"},
        type: "post",
        error: function(){
          $(".employee-grid-error").html("");
          $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
          $("#employee-grid_processing").css("display","none"); 
        }
    },
    "columnDefs": [{
      targets: [6,7],
      orderable: false,
    }],
    "order": [[ 2, "asc" ]]
  });
});

$(document).on('click', '.edit-admin', function(){
  var userId = $(this).data("id");
  $.ajax({
    type:"POST",
    url:"ajax/ajax.admin.php",
    data:{action:"getuser",
          id:userId},
    beforeSend: function() {
      $('.edit-display-error').hide();
      $("#edit-display-group").removeClass("has-error");
      $('.edit-email-error').hide();
      $("#edit-email-group").removeClass("has-error");
      $('input[name=language]').attr('checked',false);
    },
    success:function(msg){
      var obj = jQuery.parseJSON(msg);

      var language = obj["0"].language.split(",");

      document.getElementById('edit-member-id').value = obj["0"].member_id;
      document.getElementById('edit-display-name').value = obj["0"].display_name;
      document.getElementById('edit-email').value = obj["0"].email;
      document.getElementById('current-email').value = obj["0"].email;
      document.getElementById('type-id-'+obj["0"].member_type).selected = true;
      document.getElementById('status-id-'+obj["0"].status_user).selected = true;

      for (var i = 0; i < language.length; i++) {
        $('#lang-'+language[i]).attr('checked',true);
      }
    }
  });
});

$(document).on('click', '.delete-admin', function(){
  var data = {
    action: "deleteuser",
    id: $(this).data("id")
  };
  $.confirm({
    title: 'Are you sure?',
    content: 'you want to delete this account.',
    theme: 'material',
    icon: 'fa fa-warning',
    type: 'red',
    draggable: false,
    buttons: {
      confirm:  {
        text: 'Yes, delete it!',
        btnClass: 'btn-red',
        action: function(){
          delete_user(data);
        }
      },
      formCancel: {
        text: 'Cancel',
        cancel: function () {}  
      }
    }
  });
});

$("#save-edit-user").on("click",function() {
  validate_edit_user();
});

$("#reset-password").on("click",function() {
  var data = {
    action: "resetpass",
    email: $('#current-email').val()
  };
  $.confirm({
    title: 'Reset password?',
    content: '',
    theme: 'material',
    icon: 'fa fa-warning',
    type: 'red',
    draggable: false,
    buttons: {
      confirm:  {
        text: 'OK',
        btnClass: 'btn-red',
        action: function(){
          reset_password(data);
        }
      },
      formCancel: {
        text: 'Cancel',
        cancel: function () {}  
      }
    }
  });
});

function reset_password(data) {
  var url = "ajax/ajax.admin.php",
      dataSet = data;
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function(data){
      var obj = jQuery.parseJSON(data);
      // console.log(obj);
      if (obj['message'] === "success") {
        $.confirm({
          title: 'Reset password',
          content: 'Password has been reset Please check in register email.',
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

function delete_user(data) {
  var url = "ajax/ajax.admin.php",
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

function validate_edit_user() {
  var userid = $('#edit-member-id'),
      display = $('#edit-display-name'),
      email = $('#edit-email'),
      type = $('#edit-user-type'),
      status = $('#edit-user-status'),
      language = '',

      emailerror = $('.edit-email-error'),
      displayerror = $('.edit-display-error');

  if (display.val().length < 1) {
    displayerror.text("Enter a display name.");
    displayerror.show();
    $("#edit-display-group").addClass("has-error");
    display.focus();
    return false;
  }else {
    displayerror.hide();
    $("#edit-display-group").removeClass("has-error");
  }

  if (email.val().length < 1) {
    emailerror.text("Enter an email.");
    emailerror.show();
    $("#edit-email-group").addClass("has-error");
    email.focus();
    return false;
  }else if ((email.val().indexOf('@') < 0) || (email.val().indexOf('.') < 0)) {
    emailerror.text("Invalid email address.");
    emailerror.show();
    $("#edit-email-group").addClass("has-error");
    email.focus();
    return false;
  }else {
    emailerror.hide();
    $("#edit-email-group").removeClass("has-error");
  }

  for (var i = 0; i < $("input[name=language]:checked").length; i++) {
    language += ","+$("input[name=language]:checked")[i].value;
  }
  if (language) language = language.substring(1);

  var data = {
    action: "edituser",
    id: userid.val(),
    display: display.val(),
    email: email.val(),
    currentEmail: $('#current-email').val(),
    type: type.val(),
    status: status.val(),
    language: language
  };
  // console.log(data);
  edit_user(data)
}

function edit_user(data) {
  var url = "ajax/ajax.admin.php",
      dataSet = data;
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function(data){
      var obj = jQuery.parseJSON(data);
      // console.log(obj);
      if(obj['message'] === "success"){
        location.reload();

      }else if (obj['message'] === "email_already_exists") {
        $('.edit-email-error').text("This email already exists.");
        $('.edit-email-error').show();
        $("#edit-email-group").addClass("has-error");
        $('#edit-email').focus();

      }else if (obj['message'] === "not_success") {
        $.confirm({
          title: obj['title'],
          content: obj['text'],
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