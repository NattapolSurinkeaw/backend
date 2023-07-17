$("#edit-website-images").uploadImage({
  preview: true
});

$("#edit-website-images").on("change", function(){ 
  if(formdata.getAll("images[]").length !== 0){
    var img = formdata.getAll("images[]")["0"].name;
    $('#edit-website-images-hidden').val(img);
  }
});

function validate_website_detail(data) {
  var id = $('#data-id'),
      title = $('#title'),
      keyword = $('#keyword'),
      description = $('#description'),
      language = $("#language"),
      images = $("#edit-website-images-hidden"),
      type = $("#data-type");



  var data = {
      action: "savewebsitedetail",
      id: id.val(),
      title: title.val(),
      keyword: keyword.val(),
      description: description.val(),
      language: language.val(),
      images: images.val(),
      type: type.val()
  };
  // console.log(data);
  edit_website_detail(data);
}

function edit_website_detail(data) {
  var url = url_ajax_request + "ajax/ajax.siteconfig.php",
            dataSet = data;
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function(msg){
      var obj = jQuery.parseJSON(msg);
      // console.log(obj);
  
      if(obj.data['message'] === "OK"){
          if ($('#edit-website-images-hidden').val().length > 0) {
            if(formdata.getAll("images[]").length !== 0){
              uploadimages(dataSet.id);
            }else{
              location.reload();
            }
          }else{
            location.reload();
            
          }
      }
    }
  });
}

function uploadimages(cateId) {
  formdata.append("action", "uploadimg");
  formdata.append("id", cateId);
  
  $.ajax({
    url: url_ajax_request + "ajax/ajax.siteconfig.php",
    type: 'POST',
    data: formdata,
    processData: false,
    contentType: false,
    success: function(msg){
      var obj = jQuery.parseJSON(msg);
      // console.log(obj);
      // return false;
      if(obj['message'] === "OK"){
        location.reload();
      }
    }
  });
}

$("#save-website-detail").on("click", function(){ 
  validate_website_detail();
});


//Change Url
var url = window.location.href;
var param = getAllUrlParams(url);
var pagetype = param.type;

if (pagetype) {
  $("#"+pagetype).addClass("active");
  $("#webinfo-box").show();

}else {
  $("#websiteconfig").addClass("active");
  $("#siteconfig-box").show();
}


$(".siteconfig-menu").on('click', function () {
  var url = "?"+$(this).data("id");
  ChangeUrl('', url);

});

//Web Info Edit
$(document).on('click', '#edit-web-info', function(){
  var id = $(this).data("id");
  var type = $(this).data("type");
  $.ajax({
    type:"POST",
    url:"ajax/ajax.siteconfig.php",
    data:{action:"getwebinfoedit",
          id:id},
    beforeSend: function() {
      $('#edit-info-title').val('');
      $('#edit-text-title').val('');
      $('#edit-info-link').val('');
      $('#edit-priority').val('');
      $('#edit-attribute').val('');

      $('#action-type').val('');
      $('#edit-info-type').val('');
      $('#edit-web-info-id').val('');
    },
    success:function(msg){
      // console.log(msg);
      var obj = jQuery.parseJSON(msg);
      // console.log(obj);

      $('#action-type').val(type);
      $('#edit-info-type').val(obj.info_type);
      $('#edit-web-info-id').val(obj.info_id);

      $('#edit-info-title').val(obj.info_title);
      $('#edit-text-title').val(obj.text_title);
      $('#edit-info-link').val(obj.info_link);
      $('#edit-priority').val(obj.priority);
      $('#edit-attribute').val(obj.attribute);

      document.getElementById('info-display-'+obj.info_display).selected = true;

    }
  });
});

function validate_web_info_edit(data) {
  var info_id = $('#edit-web-info-id'),
      info_type = $('#edit-info-type'),
      info_title = $('#edit-info-title'),
      text_title = $('#edit-text-title'),
      info_link = $('#edit-info-link'),
      priority = $("#edit-priority"),
      attribute = $("#edit-attribute"),
      info_display = $("#edit-info-display"),
      type = $("#action-type");



  var data = {
      action: "savewebinfoedit",
      info_id: info_id.val(),
      info_type : info_type.val(),
      info_title: info_title.val(),
      text_title: text_title.val(),
      info_link: info_link.val(),
      priority: priority.val(),
      attribute: attribute.val(),
      info_display: info_display.val(),
      type: type.val()
  };
  // console.log(data);
  edit_web_info(data);
}

function edit_web_info(data) {
  var url = url_ajax_request + "ajax/ajax.siteconfig.php",
            dataSet = data;
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function(msg){
      var obj = jQuery.parseJSON(msg);
      // console.log(obj);
      if(obj.data['message'] === "OK"){
        location.reload();
      }
    }
  });
}

$("#save-edit-web-info").on("click", function(){ 
  validate_web_info_edit();
});

//Web Info Add
function validate_web_info_add(data) {
  var info_type = $('#add-info-type'),
      info_title = $('#add-info-title'),
      text_title = $('#add-text-title'),
      info_link = $('#add-info-link'),
      priority = $("#add-priority"),
      attribute = $("#add-attribute"),
      info_display = $("#add-info-display");



  var data = {
      action: "savewebinfoadd",
      info_type : info_type.val(),
      info_title: info_title.val(),
      text_title: text_title.val(),
      info_link: info_link.val(),
      priority: priority.val(),
      attribute: attribute.val(),
      info_display: info_display.val()
  };
  console.log(data);
  add_web_info(data);
}

function add_web_info(data) {
  var url = url_ajax_request + "ajax/ajax.siteconfig.php",
            dataSet = data;
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function(msg){
      var obj = jQuery.parseJSON(msg);
      // console.log(obj);
      if(obj.data['message'] === "OK"){
        location.reload();
      }
    }
  });
}

$("#save-add-web-info").on("click", function(){ 
  validate_web_info_add();
});

//Web Info Delete
$(document).on('click', '#web-info-delete', function(){
  var id = $(this).data("id");
  
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
            url:"ajax/ajax.siteconfig.php",
            data:{action:"webinfodelete",
                  id: id},
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