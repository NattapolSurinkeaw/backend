//////////////////////////// Web Info Type ////////////////////////////////
$(document).on('click', '#web-info-type-edit', function(){
  var id = $(this).data("id");
  var type = $(this).data("type");
  $.ajax({
    type:"POST",
    url:"ajax/ajax.setting.php",
    data:{action:"getwebinfotype",
          id:id},
    beforeSend: function() {
      $('#edit-web-info-type-id').val('');
      $('#current-info-type').val('');
      $('#edit-info-type').val('');
      $('#edit-info-title').val('');
    },
    success:function(msg){
      var obj = jQuery.parseJSON(msg);
      // console.log(obj);
      $('#edit-web-info-type-id').val(obj.id);
      $('#current-info-type').val(obj.info_type);

      $('#edit-info-type').val(obj.info_type);
      $('#edit-info-title').val(obj.info_title);

      $('#action-type').val(type);
    }
  });
});

function validate_web_info_type_edit() {
  var id = $('#edit-web-info-type-id'),
      type = $('#action-type'),
      info_type = $('#edit-info-type'),
      info_title = $('#edit-info-title'),
      current_info_type = $('#current-info-type');

  var data = {
    action: "editwebinfotype",
    id: id.val(),
    type: type.val(),
    info_type: info_type.val(),
    info_title: info_title.val(),
    current_info_type: current_info_type.val()
  };
  // console.log(data);
  web_info_type_edit(data);
}

function web_info_type_edit(data) {
  var url = url_ajax_request + "ajax/ajax.setting.php",
      dataSet = data;
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function(data){
      var obj = jQuery.parseJSON(data);
      // console.log(obj);
      if (obj.data["message"] == "OK") {
        location.reload();
      }
      
    }
  });
}

$("#save-edit-web-info-type").on("click",function() {
  validate_web_info_type_edit();
});


// Add 
function validate_web_info_type_add() {
  var info_type = $('#add-info-type'),
      info_title = $('#add-info-title');

  var data = {
    action: "addwebinfotype",
    info_type: info_type.val(),
    info_title: info_title.val()
  };
  // console.log(data);
  web_info_type_add(data);
}

function web_info_type_add(data) {
  var url = url_ajax_request + "ajax/ajax.setting.php",
      dataSet = data;
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function(data){
      var obj = jQuery.parseJSON(data);
      // console.log(obj);
      if (obj.data["message"] == "OK") {
        location.reload();
      }
      
    }
  });
}

$("#save-add-web-info-type").on("click",function() {
  validate_web_info_type_add();
});

$(document).on('click', '#web-info-type-delete', function(){
  var id = $(this).data("id");
  var type = $(this).data("type");
  
  $.confirm({
    title: 'Are you sure?',
    content: 'you want to delete this data.',
    theme: 'material',
    icon: 'fa fa-warning',
    type: 'red',
    draggable: false,
    buttons: {
      confirm:  {
        text: 'Yes, delete it!',
        btnClass: 'btn-red',
        action: function(){
          $.ajax({
            type:"POST",
            url:"ajax/ajax.setting.php",
            data:{action:"webinfotypedelete",
                  id: id,
                  type: type},
            success:function(msg){
              var obj = jQuery.parseJSON(msg);
              
              if (obj["message"] == "OK") {
                location.reload();
              }else {
                console.log(obj);
              }
              
            }
          });
        }
      },
      formCancel: {
        text: 'Cancel',
        cancel: function () {}  
      }
    }
  });
});


//////////////////////////// Feature ////////////////////////////////
$(".edit-feature").on("click",function() {
  var featureId = $(this).data("id");
  var data = {
    action: "editfeature",
    id: featureId,
    status: $("#feature-status-" + featureId).val()
  };
  edit_feature(data);
});

function edit_feature(data) {
  var url = url_ajax_request + "ajax/ajax.setting.php",
      dataSet = data;
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function(data){
      var obj = jQuery.parseJSON(data);
      // console.log(obj);
      if (obj.message === 'OK') {
        $.confirm({
            theme: 'modern',
            type: 'green',
            icon: 'fa fa-check',
            title: 'บันทึกสำเร็จ',
            content: '',
            buttons: {
                somethingElse: {
                    text: 'ตกลง',
                    keys: ['enter']
                }
            }
        });
      }else {
        $.confirm({
            theme: 'modern',
            type: 'red',
            icon: 'fa fa-times',
            title: 'บันทึกข้อมูลไม่สำเร็จ',
            content: 'กรุณาลองใหม่อีกครั้ง',
            buttons: {
                somethingElse: {
                    text: 'ตกลง',
                    keys: ['enter']
                }
            }
        });
      }
      
    }
  });
}

//////////////////////////// Language ////////////////////////////////

//Language Add
$('#modal-add-language').on('hidden.bs.modal', function (e) {
  if (e.namespace == 'bs.modal') {
    $("#add-language-display").val("");
    $("#add-language-name").val("");
  }
});

$("#save-add-language").on("click", function(){
  var data = {
    action: "addlangusge",
    name: $("#add-language-name").val(),
    display: $("#add-language-display").val()
  };
  add_language(data);
});

function add_language(data) {
  var url = url_ajax_request + "ajax/ajax.setting.php",
      dataSet = data;
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function(data){
      var obj = jQuery.parseJSON(data);
      // console.log(obj);
      if (obj.message === 'OK') {
        $.confirm({
            theme: 'modern',
            type: 'green',
            icon: 'fa fa-check',
            title: 'บันทึกสำเร็จ',
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
      }else {
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
      }
      
    }
  });
}

//Language Edit
$(".edit-language").on("click", function(){
  var url = url_ajax_request + "ajax/ajax.setting.php";
  var langId = $(this).data("id");
  $.ajax({
    type: "POST",
    url: url,
    data: {action: "getlangusge",
          id: langId},
    beforeSend: function() {
      $("#edit-language-id").val("");
      $("#edit-language-display").val("");
      $("#edit-language-name").val("");
    },
    success: function(data){
      var obj = jQuery.parseJSON(data);
      // console.log(obj);
      $("#edit-language-id").val(obj["0"].id);
      $("#edit-language-display").val(obj["0"].display_name);
      $("#edit-language-name").val(obj["0"].language);
    }
  });
});

$("#save-edit-language").on("click", function(){
  var data = {
    action: "editlangusge",
    id: $("#edit-language-id").val(),
    display: $("#edit-language-display").val()
  };
  edit_language(data);
});

function edit_language(data) {
  var url = url_ajax_request + "ajax/ajax.setting.php",
      dataSet = data;
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function(data){
      var obj = jQuery.parseJSON(data);
      // console.log(obj);
      if (obj.message === 'OK') {
        $.confirm({
            theme: 'modern',
            type: 'green',
            icon: 'fa fa-check',
            title: 'บันทึกสำเร็จ',
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
      }else {
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
      }
      
    }
  });
}

//Language Delete
$(".delete-language").on("click", function(){
  var data = {
    action: "deletelanguage",
    id: $(this).data("id")
  };
  $.confirm({
    title: 'Are you sure?',
    content: 'you want to delete this language.',
    theme: 'material',
    icon: 'fa fa-warning',
    type: 'red',
    draggable: false,
    buttons: {
      confirm:  {
        text: 'Yes, delete it!',
        btnClass: 'btn-red',
        action: function(){
          delete_language(data);
        }
      },
      formCancel: {
        text: 'Cancel',
        cancel: function () {}  
      }
    }
  });
});

function delete_language(data) {
  var url = url_ajax_request + "ajax/ajax.setting.php",
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
          content: 'Successfully Deleted!',
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

//////////////////////////// Ads Type ////////////////////////////////

//Add Ads Type
$('#modal-add-ads-type').on('hidden.bs.modal', function (e) {
  if (e.namespace == 'bs.modal') {
    $("#add-ads-position").val("");
    $("#add-ads-type").val("");
    $("#add-ads-dimension").val("");
  }
});

$("#save-add-ads-type").on("click", function(){
  var data = {
    action: "addadstype",
    position: $("#add-ads-position").val(),
    type: $("#add-ads-type").val(),
    dimension: $("#add-ads-dimension").val()
  };
  add_ads_type(data);
});

function add_ads_type(data) {
  var url = url_ajax_request + "ajax/ajax.setting.php",
      dataSet = data;
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function(data){
      var obj = jQuery.parseJSON(data);
      // console.log(obj);
      if (obj.message === 'OK') {
        $.confirm({
            theme: 'modern',
            type: 'green',
            icon: 'fa fa-check',
            title: 'บันทึกสำเร็จ',
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
      }else {
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
      }
      
    }
  });
}

//Edit Ads Type
$(".edit-ads-type").on("click", function(){
  var url = url_ajax_request + "ajax/ajax.setting.php";
  var adsTypeId = $(this).data("id");
  $.ajax({
    type: "POST",
    url: url,
    data: {action: "getadstype",
          id: adsTypeId},
    beforeSend: function() {
      $("#edit-ads-type-id").val("");
      $("#edit-ads-position").val("");
      $("#edit-type").val("");
      $("#edit-ads-dimension").val("");
    },
    success: function(msg){
      var obj = jQuery.parseJSON(msg);
      // console.log(obj);
      $("#edit-ads-type-id").val(obj["0"].id);
      $("#edit-ads-position").val(obj["0"].position);
      $("#edit-type").val(obj["0"].type);
      $("#edit-ads-dimension").val(obj["0"].dimension);
    }
  });
});

$("#save-edit-ads-type").on("click", function(){
  var data = {
    action: "editadstype",
    id: $("#edit-ads-type-id").val(),
    position: $("#edit-ads-position").val(),
    type: $("#edit-type").val(),
    dimension: $("#edit-ads-dimension").val()
  };
  edit_ads_type(data);
});

function edit_ads_type(data) {
  var url = url_ajax_request + "ajax/ajax.setting.php",
      dataSet = data;
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function(msg){
      var obj = jQuery.parseJSON(msg);
      // console.log(obj);
      if (obj.message === 'OK') {
        $.confirm({
            theme: 'modern',
            type: 'green',
            icon: 'fa fa-check',
            title: 'บันทึกสำเร็จ',
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
      }else {
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
      }
      
    }
  });
}

//Delete Ads Type
$(".delete-ads-type").on("click", function(){
  var data = {
    action: "deleteadstype",
    id: $(this).data("id")
  };
  $.confirm({
    title: 'Are you sure?',
    content: 'you want to delete this ads type.',
    theme: 'material',
    icon: 'fa fa-warning',
    type: 'red',
    draggable: false,
    buttons: {
      confirm:  {
        text: 'Yes, delete it!',
        btnClass: 'btn-red',
        action: function(){
          delete_ads_type(data);
        }
      },
      formCancel: {
        text: 'Cancel',
        cancel: function () {}  
      }
    }
  });
});

function delete_ads_type(data) {
  var url = url_ajax_request + "ajax/ajax.setting.php",
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
          content: 'Successfully Deleted!',
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