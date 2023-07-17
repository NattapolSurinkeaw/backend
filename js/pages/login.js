$("#formlogin").submit(function(e) {
    e.preventDefault(); 
    var username = $('#user'),
        password = $('#password'),
        usererror = $('.user-error'),
        passworderror = $('.password-error');

    if (username.val().length < 1) {
        usererror.text("Enter an email.");
        usererror.show();
        username.addClass("error");
        username.focus();
        return false;
    } else if ((username.val().indexOf('@') < 0) || (username.val().indexOf('.') < 0)) {
        usererror.text("Invalid email address.");
        usererror.show();
        username.addClass("error");
        username.focus();
        return false;
    } else {
        usererror.hide();
        username.removeClass("error");
    }

    if (password.val().length < 1) {
        passworderror.text("Enter a password.");
        passworderror.show();
        password.addClass("error");
        password.focus();
        return false;
    } else if (password.val().length < 8) {
        passworderror.text("Please lengthen this text to 8 characters.");
        passworderror.show();
        password.addClass("error");
        password.focus();
        return false;
    } else {
        passworderror.hide();
        password.removeClass("error");
    }

    var data = {
        action: "login",
        username: username.val(),
        password: password.val(), 
        tokenReCaptcha : tokenderr
    };

    login(data);
});

$("#formforgot").submit(function(e) {
    e.preventDefault();
    var email = $('#email-editor-forgot'),
        emailerror = $('.email-forgot-error');

    if (email.val().length < 1) {
        emailerror.text("Enter an email.");
        emailerror.show();
        email.addClass("error");
        email.focus();
        return false;
    } else if ((email.val().indexOf('@') < 0) || (email.val().indexOf('.') < 0)) {
        emailerror.text("Invalid email address."); 
        emailerror.show();
        email.addClass("error"); 
        email.focus(); 
        return false; 
    } else { 
        emailerror.hide();
        email.removeClass("error");
    } 
 
    var data = {
        action: "resetpass", 
        email: email.val(),
        tokenReCaptcha : tokenderr
    };
    resetpass(data);
});

$("#formregis").submit(function(e) { 
    e.preventDefault(); 
    var email = $('#regis-email'),  
        password = $('#regis-pass'), 
        phone = $('#regis-phone'), 
        display = $('#regis-display'),  
        emailerror = $('.regis-email-error'), 
        passworderror = $('.regis-pass-error'), 
        displayerror = $('.regis-display-error'); 

    if (email.val().length < 1) { 
        emailerror.text("Enter an email."); 
        emailerror.show(); 
        email.addClass("error"); 
        email.focus();  
        return false; 
    } else if ((email.val().indexOf('@') < 0) || (email.val().indexOf('.') < 0)) { 
        emailerror.text("Invalid email address."); 
        emailerror.show();  
        email.addClass("error"); 
        email.focus(); 
        return false;  
    } else {  
        emailerror.hide(); 
        email.removeClass("error"); 
    }

    if (password.val().length < 1) { 
        passworderror.text("Enter a password.");
        passworderror.show();
        password.addClass("error");
        password.focus();
        return false;
    } else if (password.val().length < 8) {
        passworderror.text("Please lengthen this text to 8 characters.");
        passworderror.show();
        password.addClass("error");
        password.focus();
        return false;
    } else {
        passworderror.hide();
        password.removeClass("error");
    }

    if (display.val().length < 1) {
        displayerror.text("Enter a display name.");
        displayerror.show();
        display.addClass("error");
        display.focus();
        return false;
    } else {
        displayerror.hide();
        display.removeClass("error");
    }

    var data = {
        action: "register",
        email: email.val(),
        password: password.val(),
        phone: phone.val(),
        display: display.val(),
        tokenReCaptcha : tokenderr
    };
    register(data);
});

$(document).on('click', '.forgot_or_regis', function() {
    $(".blog_form_editor_regis").find("input").val("");
});

$(document).on('click', '.forgot_or_regis.active', function() {
    var key = $(this).find("a").attr("href");

    $(".blog_form_editor_regis").find("input").removeClass("error");
    $(".blog_form_editor_regis").find(".text-error").hide();

    $(this).removeClass("active");
    if (key === "#forgot_pw") {
        $("#forgot_pw").removeClass("active in");

    } else if (key === "#editor_regis") {
        $("#editor_regis").removeClass("active in");
    }
});

function login(data) {
    var url = "ajax/ajax.login.php",
        dataSet = data;
    $.ajax({
        type: "POST",
        url: url,
        data: dataSet,
        dataType: "json",
        success: function(data) {
            console.log(data)
            document.location.href = "/backend";
        }
    });
}

function register(data) {
    var url = "/backend/ajax/ajax.login.php",
        dataSet = data;
    $.ajax({
        type: "POST",
        url: url,
        data: dataSet,
        success: function(data) {
            var obj = jQuery.parseJSON(data);
            // console.log(obj);
            if (obj['message'] === "success") {
                $.confirm({
                    title: obj['title'],
                    content: obj['text'],
                    theme: 'material',
                    icon: 'fa fa-check-circle',
                    type: 'darkgreen',
                    draggable: false,
                    backgroundDismiss: true,
                    buttons: {
                        confirm: {
                            text: 'OK',
                            btnClass: 'btn-darkgreen',
                            action: function() {
                                location.reload();
                            }
                        }
                    },
                    backgroundDismiss: function() {
                        location.reload();
                    }
                });
            } else {
                $.confirm({
                    title: obj['title'],
                    content: obj['text'],
                    theme: 'material',
                    icon: 'fa fa-times-circle',
                    type: 'red',
                    draggable: false,
                    backgroundDismiss: true,
                    buttons: {
                        confirm: {
                            text: 'OK',
                            btnClass: 'btn-red',
                            action: function() {
                                document.getElementById("regis-email").value = "";
                            }
                        }
                    },
                    backgroundDismiss: function() {
                        document.getElementById("regis-email").value = "";
                    }
                });
            }
        }
    });
}

function resetpass(data) {
    var url = "/backend/ajax/ajax.login.php",
        dataSet = data;
    $.ajax({
        type: "POST",
        url: url,
        data: dataSet,
        success: function(data) {
            var obj = jQuery.parseJSON(data),
                title = '',
                icon = '';
            // console.log(obj);
            if (obj['message'] === "success") {
                title = 'Success';
                icon = 'fa fa-check-circle';
                $.confirm({
                    title: title,
                    content: obj['text'],
                    theme: 'material',
                    icon: icon,
                    type: 'darkblue',
                    draggable: false,
                    backgroundDismiss: true,
                    buttons: {
                        confirm: {
                            text: 'OK',
                            btnClass: 'btn-darkblue',
                            action: function() {
                                location.reload();
                            }
                        }
                    },
                    backgroundDismiss: function() {
                        location.reload();
                    }
                });
            } else {
                title = 'Not Success';
                icon = 'fa fa-times-circle';
                $.confirm({
                    title: title,
                    content: obj['text'],
                    theme: 'material',
                    icon: icon,
                    type: 'darkblue',
                    draggable: false,
                    backgroundDismiss: true,
                    buttons: {
                        confirm: {
                            text: 'OK',
                            btnClass: 'btn-darkblue',
                            action: function() {
                                document.getElementById("email-editor-forgot").value = "";
                            }
                        }
                    },
                    backgroundDismiss: function() {
                        document.getElementById("email-editor-forgot").value = "";
                    }
                });
            }
        }
    });
}