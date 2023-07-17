// upload images
$("#edit-images-content").uploadImage({
  preview: true
});

//change password
function validate_change_password(data) {
  	var newpass = $("#new-password"),
        confirmpass = $("#confirm-password"),
        currentpass = $("#current-password"),
        userId = $("#user-id"),

        passworderror = $('#password-error'),
        confirmPassworderror = $("#confirmPassword-error"),
        currentPassworderror = $("#currentPassword-error");

    //current password
    if (currentpass.val().length < 1) {
    	$("#form-currentPass-error").addClass("has-error");
      	currentPassworderror.html('<i class="fa fa-times-circle aria-hidden="true"></i> กรุณากรอกรหัสผ่านของคุณ.');
      	currentPassworderror.show();
      	currentpass.focus();
      	return false;

    }else if (currentpass.val().length < 8) {
    	$("#form-currentPass-error").addClass("has-error");
      	currentPassworderror.html('<i class="fa fa-times-circle aria-hidden="true"></i> รหัสผ่านอย่างน้อย 8 ตัวอักษร.');
      	currentPassworderror.show();
      	currentpass.focus();
      	return false;

    }else if (data === "password_is_incorrect") {
    	$("#form-currentPass-error").addClass("has-error");
        currentPassworderror.html('<i class="fa fa-times-circle aria-hidden="true"></i> รหัสผ่านของคุณไม่ถูกต้อง.');
        currentPassworderror.show();
        currentpass.focus();
        return false;

    }else {
    	$("#form-currentPass-error").removeClass("has-error");
      	currentPassworderror.hide();
    }

	//validate password
	if (newpass.val().length < 1) {
		$("#form-password-error").addClass("has-error");
		passworderror.html('<i class="fa fa-times-circle aria-hidden="true"></i> กรุณากรอกรหัสผ่านของคุณ.');
		passworderror.show();
		newpass.focus();
    return false;

	}else if (newpass.val().length < 8) {
		$("#form-password-error").addClass("has-error");
		passworderror.html('<i class="fa fa-times-circle aria-hidden="true"></i> รหัสผ่านอย่างน้อย 8 ตัวอักษร.');
		passworderror.show();
		newpass.focus();
		return false;

	}else {
		$("#form-password-error").removeClass("has-error");
		passworderror.hide();
	}

	if (newpass.val() !== confirmpass.val()) {
		$("#form-confirmPass-error").addClass("has-error");
		confirmPassworderror.html('<i class="fa fa-times-circle aria-hidden="true"></i> ยืนยันรหัสผ่านไม่ถูกต้อง.');
		confirmPassworderror.show();
		confirmpass.val("");
		confirmpass.focus();
		return false;

	}else {
		$("#form-confirmPass-error").removeClass("has-error");
		confirmPassworderror.hide();
	}

  	var data = {
  		action : "adminchangepass",
  		newpass : newpass.val(),
  		currentpass : currentpass.val(),
  		userId : userId.val()
  	};

  // console.log(data);
  save_change_password(data);
}

function save_change_password(data) {
  var url = url_ajax_request + "ajax/ajax.profile.php",
            dataSet = data;
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function(msg){
      var obj = jQuery.parseJSON(msg);
      // console.log(obj);
      if(obj['message'] === "success"){
        $.confirm({
          theme: 'modern',
          type: 'green',
          icon: 'fa fa-check',
          title: 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว',
          content: '',
          buttons: {
            somethingElse: {
              text: 'ตกลง',
              keys: ['enter'],
              action: function(){
                location.reload();
              }
            }
          }
        });
      }else if(obj['message'] === "not_success"){
        $.confirm({
          theme: 'modern',
          type: 'red',
          icon: 'fa fa-times',
          title: 'บันทึกข้อมูลไม่สำเร็จ',
          content: 'กรุณาลองใหม่อีกครั้ง',
          buttons: {
            somethingElse: {
              text: 'ตกลง',
              keys: ['enter'],
              action: function(){
                location.reload();
              }
            }
          }
        });
      }else if(obj['message'] === "password_is_incorrect"){
        validate_change_password(obj['message']);
      }
    }
  });
}

function edit_profile(data) {
  var url = url_ajax_request + "ajax/ajax.profile.php",
      dataSet = data;
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function(data){
      var obj = jQuery.parseJSON(data);
      console.log(obj); 
      
      if(obj.data['message'] === "OK"){
        if(formdata.getAll("images[]").length !== 0){
          uploadimages(dataSet.id, "uploadimgprofile");
        }else{
          location.reload();
        }
      }else if(obj.data === "email_already_exists"){
        validate_edit_profile(obj.data);
      }
    }
  });
}

function validate_edit_profile(data) {
	var userId = $("#user-id"),
      displayname = $("#display-name"),
      username = $("#username"),
      email = $("#email"),
      current_email = $("#current-email"),
      phone = $("#phone"),

      display_form = $("#form-display"),
      username_form = $("#form-username"),
      email_form = $("#form-email"),
      phone_form = $("#form-phone"),

      display_error = $("#display-error"),
      username_error = $("#username-error"),
      email_error = $("#email-error"),
      phone_error = $("#phone-error")
  ;

  //validate display name
  if (displayname.val().length < 1) {
    displayname.focus();
    display_form.addClass("has-error");
    display_error.show();
    return false;
  } else {
    display_form.removeClass("has-error");
    display_error.hide();
  }

  //validate email
  if (email.val().length < 1) {
    email_error.text("กรุณากรอกข้อมูลในช่องนี้");
    email_error.show();
    email_form.addClass("has-error");
    email.focus();
    return false;

  }else if ((email.val().indexOf('@') < 0) || (email.val().indexOf('.') < 0)) {
    email_error.text("อีเมลไม่ถูกต้อง");
    email_error.show();
    email_form.addClass("has-error");
    email.focus();
    return false;

  }else if (data === "email_already_exists") {
    email_error.text('อีเมลนี้มีอยู่ในระบบแล้ว');
    email_error.show();
    email_form.addClass("has-error");
    email.focus();
    return false;

  }else {
    email_error.hide();
    email_form.removeClass("has-error");
  }

  //validate phone
  if (phone.val().length < 5) {
    phone_error.text("กรุณากรอกข้อมูลในช่องนี้ให้ถูกต้อง");
    phone.focus();
    phone_form.addClass("has-error");
    phone_error.show();
    return false;

  } else {
    phone_form.removeClass("has-error");
    phone_error.hide();

  }

	var data = {
		action: "editprofile",
		id: userId.val(),
    displayname: displayname.val(),
    username: username.val(),
    email: email.val(),
    current_email: current_email.val(),
    phone: phone.val(),
    language_templete : $('#language_templete option:selected').val(),
  };

  // console.log(data);
	edit_profile(data);
}

function uploadimages(id,action) {
	formdata.append("action", action);
	formdata.append("id", id);
	$.ajax({
		url: url_ajax_request + "ajax/ajax.profile.php",
		type: 'POST',
		data: formdata,
		processData: false,
		contentType: false,
		success: function(msg){
      var obj = jQuery.parseJSON(msg);
      console.log('uploadimages');
      console.log(obj);
      // return false;
			if(obj['message'] === "OK"){
				location.reload();
			}
		}
	});
}

$("#save-edit-profile").on("click", function(){ 
  validate_edit_profile();
});

$("#change-password-save").on("click", function(){ 
  validate_change_password();
});