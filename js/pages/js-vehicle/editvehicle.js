// upload images
$("#edit-images-content").uploadImage({
  preview: true
});

$("#edit-images-content").on("change", function(){ 
  if(formdata.getAll("images[]").length !== 0){
    var img = formdata.getAll("images[]")["0"].name;
    $('#edit-images-content-hidden').val(img);
    $(".form-edit-images").removeClass("has-error");
    $(".edit-images-error").css("display","none");
  }
});


$(document).on('click', '#img-delete', function(){
  var id = $(this).data("id"),
      filename = $(this).data("name"),
      postId = $("#edit-content-id").val();
  $.ajax({
    type:"POST",
    url:"ajax/ajax.vehicle.php",
    data:{
      action:"deleteimagecontent",
      id: id,
      filename: filename,
      postId: postId
    },
    beforeSend: function() {

    },
    success:function(msg){
      var obj = jQuery.parseJSON(msg),
          img_list = '';
      // console.log(obj);
      if (obj.images != "no_image") {
        for(i=0 ; i < obj.images.length ; i++){
          img_list += '\
          <div class="blog-show-image">\
            <div class="iconimg" id="img-delete" data-id="'+obj.images[i].image_id+'" data-name="'+obj.images[i].image_link+'">\
              <i class="fa fa-times" alt="delete"></i>\
            </div>\
            <div id="image-preview">\
              <div class="col-img-preview">\
                <img class="preview-img" src="'+root_url+obj.images[i].image_link+'">\
              </div>\
            </div>\
          </div>';
        }
        $("#show-img-more").html(img_list);
      }else {
        $("#show-img-more").html("");
      }
    }
  });
});

// datepicker
$('#date-display').datepicker({
  format: 'dd/mm/yyyy',
  autoclose: true,
  language: 'th',
  todayHighlight: true
}).on('changeDate', function(e) {
  $('#date-display-hidden').val(e.format('yyyy-mm-dd'));
});

//timepicker
$("#time-display").timepicker({
  defaultTime: false,
  showInputs: false,
  minuteStep: 1,
  showMeridian: false
});

$.ajax({
  type:"POST",
  url:"ajax/ajax.vehicle.php",
  data:{action:"getcategorycontent"},
  beforeSend: function() {

  },
  success:function(msg){
    var obj = jQuery.parseJSON(msg);

    $("#blog-category-tree").jstree({ 
      "core" : {
        "data" : obj,
        "check_callback" : true
      },
      "checkbox" : {
        "keep_selected_style" : false,
        "three_state": false,
        "cascade" : "up"
      },
      "plugins" : [ "wholerow", "checkbox" ]
    }).bind('loaded.jstree', function() {
      $("#blog-category-tree").jstree('open_all');
    });

  }
});

$('#modalEditContent').on('hide.bs.modal', function (e) {
  if (e.namespace == 'bs.modal') {
    var myDiv = document.getElementById('scrollbar-edit');
    myDiv.scrollTop = 0;

    deleteImageDraft();
    $(".form-edit-title").removeClass("has-error");
    $(".edit-title-error").css("display","none");

    $(".form-edit-price").removeClass("has-error");
    $(".edit-price-error").css("display","none");

    $(".form-edit-description").removeClass("has-error");
    $(".edit-description-error").css("display","none");

    $(".form-edit-location-from").removeClass("has-error");
    $(".edit-location-from-error").css("display","none");
  }
});

function deleteImageDraft() {
  $.ajax({
    type:"POST",
    url:"ajax/ajax.vehicle.php",
    data:{action:"deleteimagedraft"}
  });
}

$(".edit-content").on("click", function(){  
  var contentId = $(this).data("id"),
      submitType = $(this).data("type");
  $('#prog').progressbar({ value: 0 });
  $.ajax({
    type:"POST",
    url:"ajax/ajax.vehicle.php",
    data:{action:"getcontent",
          id:contentId},
    beforeSend: function() {
      $('#blog-category-tree').jstree("deselect_all");
      document.getElementById("form-edit-content").reset();

      $("#date-display").datepicker('setDate','');

      document.getElementById('edit-location-from-0').selected = true;
      document.getElementById('edit-location-to-0').selected = true;

      // $('#show-video').html('');
    },
    success:function(msg){
      var obj = jQuery.parseJSON(msg);
      // console.log(obj);

      var cate_list = obj.data["0"].category.split(',');
      var tag_list = '',
          img_list = '';
      for(j=1 ; j < cate_list.length-1 ; j++){
        $.jstree.reference('#blog-category-tree').select_node(cate_list[j]);
      }

      $(".blog-preview-edit").html('\
      <div class="col-img-preview">\
        <img class="preview-img" \
        src="'+site_url+'classes/thumb-generator/thumb.php?src='+root_url+obj.data["0"].thumbnail+'&size=150x150">\
      </div>');

      $('#submit-type').val(submitType);
      if (submitType == 'add') {
        $('#edit-images-content-hidden').val(obj.data["0"].thumbnail);
        $('#date-created').val(obj.data["0"].date_created);
      }

      $('#edit-content-id').val(obj.data["0"].id);
      $('#edit-title').val(obj.data["0"].title);
      $('#edit-description').val(obj.data["0"].description);
      $('#edit-price').val(obj.data["0"].saleprice);

      document.getElementById('edit-location-from-'+obj.data["0"].h1).selected = true;
      document.getElementById('edit-location-to-'+obj.data["0"].h2).selected = true;

      document.getElementById('edit-display-'+obj.data["0"].display).selected = true;
      document.getElementById('edit-pin-'+obj.data["0"].pin).selected = true;

      if ( !isNaN( new Date(obj.data["0"].date_display).getTime() ) ) {
        $('#date-display').datepicker('setDate', new Date(obj.data["0"].date_display));
        $('#time-display').val(formatTime(new Date(obj.data["0"].date_display)));
      }
    }
  });
});

//Edit Tag
$("#edit-search-tag").on("keyup", function(){
  if ($(this).val().length >= 1) {
    searchTag($(this).val());
  }else {
    document.getElementById('searchtagresult').innerHTML='';
  }
});

$("#edit-add-tag").on("keyup", function(event){
  if(event.keyCode == 13){
    if ($(this).val().length >= 1) {
      addTagEdit($(this).val());
      document.getElementById('edit-add-tag').value = '';
    }
  }
});

$(document).on('click', '.sent-tag', function(){
  var data = {
    id: $(this).data("id"),
    text: $(this).data("text")
  }
  var resource = [];
  $('.checkbox-tag :input').each(function() {
      resource.push({id:$(this).val()});
  });
  // console.log(resource);
  for (var i = 0; i < resource.length; i++) {
    if (resource[i].id == data.id) {
      $(".checkbox-tag :input[value='" + data.id + "']").prop('checked', true);
      return false;
    }
  }
  sendtagtobox(data);
});

function searchTag(key) {
  $.ajax({
    type:"POST",
    url:"ajax/ajax.vehicle.php",
    data:{action:"searchtag",
          key: key},
    beforeSend: function() {

    },
    success:function(msg){
      if (msg) {
        var obj = jQuery.parseJSON(msg);
        var doc="";
        // console.log(obj);
        for (var i = 0; i < obj.length; i++) {
          doc += '<div class="sent-tag" data-id="' + obj[i].tag_id + '" data-text="' + obj[i].tag_name + '">' + obj[i].tag_name + '</div>';
        }
        document.getElementById('searchtagresult').innerHTML=doc;
      }
    }
  });
}

function addTagEdit(key) {
  $.ajax({
    type:"POST",
    url:"ajax/ajax.vehicle.php",
    data:{action:"addtag",
          key: key},
    success:function(msg){
      var obj = jQuery.parseJSON(msg);
      // console.log(obj);
      if (obj.data != "exist") {
        var data = {
          id: obj.data.insert_id,
          text: key
        }
        sendtagtobox(data);
      }else {
        $.confirm({
            title: window.location.hostname + ' says :',
            content: 'This tag is already exist.',
            theme: 'my-theme',
            icon: 'fa fa-warning',
            type: 'darkgreen',
            draggable: false,
            backgroundDismiss: true,
            buttons: {
                confirm:  {
                    text: 'OK',
                    btnClass: 'btn-darkgreen',
                }
            }
        });
      }
    }
  });
}

function sendtagtobox(data){
  document.getElementById('edit-blog-tag').style.display='block';
  document.getElementById('edit-blog-tag').innerHTML += '\
  <div class="checkbox checkbox-tag">\
    <label>\
      <input type="checkbox" value="' + data.id + '"  checked>\
      ' + data.text + '\
    </label>\
  </div>';
}

//Time
function formatDate(date) {
  var day = (date.getDate()<10?'0':'') + date.getDate();
  var monthIndex = ("0" + (date.getMonth() + 1)).slice(-2);
  var year = date.getFullYear();
  var hours = (date.getHours()<10?'0':'') + date.getHours();
  var minutes = (date.getMinutes()<10?'0':'') + date.getMinutes();
  date.get
  return year + '-' + monthIndex + '-' + day;
}

function formatTime(date) {
  var hours = (date.getHours()<10?'0':'') + date.getHours();
  var minutes = (date.getMinutes()<10?'0':'') + date.getMinutes();
  date.get
  return hours + ':' + minutes;
}

function edit_content(data) {
  var url = "ajax/ajax.vehicle.php",
      dataSet = data;
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function(data){
      var obj = jQuery.parseJSON(data);
      // console.log(obj);
      if(obj.data['message'] === "OK"){
        if ($('#edit-images-content-hidden').val().length > 0) {
          if(formdata.getAll("images[]").length !== 0){
            uploadimages(dataSet.id, "uploadimgcontent");
          }else{
            location.reload();
          }
        }else{
          location.reload();
        }
      }else if(obj.data['message'] === "url_already_exists"){
        validate_edit_content(obj.data['message']);
      }
    }
  });
}

function validate_edit_content(data) {
  var id = $("#edit-content-id"),
      title = $("#edit-title"),
      description = $("#edit-description"),
      price = $("#edit-price"),
      content = "",
      locationfrom = $("#edit-location-from"),
      locationto = $("#edit-location-to"),
      dateDisplay = $("#date-display"),
      display = $("#edit-display"),
      pin = $("#edit-pin"),
      category = $('#blog-category-tree').jstree().get_selected("full"),
      cateid = "",
      dataTime = ""
      ;

  if (category.length > 0) {
    cateid += ",";
    for (var i = 0; i < category.length; i++) {
      cateid += category[i].id+",";
    }
  }else {
    $.confirm({
        title: window.location.hostname + ' says :',
        content: 'you need to select at least 1 category!',
        theme: 'my-theme',
        icon: 'fa fa-warning',
        type: 'darkgreen',
        draggable: false,
        backgroundDismiss: true,
        buttons: {
          confirm:  {
              text: 'OK',
              btnClass: 'btn-darkgreen',
          }
        }
    });
    return false;
  }

  //validate title
  if (title.val().length < 1) {
    title.focus();
    $(".form-edit-title").addClass("has-error");
    $(".edit-title-error").css("display","block");
    return false;
  } else {
    $(".form-edit-title").removeClass("has-error");
    $(".edit-title-error").css("display","none");
  }

  if (dateDisplay.val() != "") {
    dataTime = $("#date-display-hidden").val() + " " + $("#time-display").val()
  }

  //validate price
  if (price.val().length < 1) {
    price.focus();
    $(".form-edit-price").addClass("has-error");
    $(".edit-price-error").css("display","block");
    return false;
  } else {
    $(".form-edit-price").removeClass("has-error");
    $(".edit-price-error").css("display","none");
  }

  //validate location from
  if (locationfrom.val() == 0) {
    locationfrom.focus();
    $(".form-edit-location-from").addClass("has-error");
    $(".edit-location-from-error").css("display","block");
    return false;
  } else {
    $(".form-edit-location-from").removeClass("has-error");
    $(".edit-location-from-error").css("display","none");
  }

  //validate location to
  if (locationto.val() == 0) {
    locationto.focus();
    $(".form-edit-location-to").addClass("has-error");
    $(".edit-location-to-error").css("display","block");
    return false;
  } else {
    $(".form-edit-location-to").removeClass("has-error");
    $(".edit-location-to-error").css("display","none");
  }

  //validate location from to
  if (locationto.val() == locationfrom.val()) {
    locationfrom.focus();
    $(".form-edit-location-from").addClass("has-error");

    locationto.focus();
    $(".form-edit-location-to").addClass("has-error");
    return false;
  } else {
    $(".form-edit-location-from").removeClass("has-error");

    $(".form-edit-location-to").removeClass("has-error");
  }


  var data = {
      action: "editcontent",
      id: id.val(),
      cateid: cateid,
      title: title.val(),
      keyword: "",
      description: description.val(),
      slug: "",
      price: price.val(),
      content: content,
      locationfrom: locationfrom.val(),
      locationto: locationto.val(),
      dateDisplay: dataTime,
      display: display.val(),
      pin: pin.val(),
      images: $("#edit-images-content-hidden").val(),
      created: $('#date-created').val(),
      submitType: $("#submit-type").val()
  };

  // console.log(data);
  edit_content(data);
}

$("#reset-edit").on("click", function(){ 
  $('#blog-category-tree').jstree("deselect_all");
  document.getElementById("form-edit-content").reset();
  CKEDITOR.instances['edit-content'].setData('');
  $("#date-display").datepicker('setDate','');
  document.getElementById('searchtagresult').innerHTML='';
  document.getElementById('edit-blog-tag').innerHTML='';
  document.getElementById('edit-blog-tag').style.display='none';
});

$("#save-edit").on("click", function(){ 
  validate_edit_content();
});

function uploadimages(id,action) {
  formdata.append("action", action);
  formdata.append("id", id);
  $.ajax({
    url: url_ajax_request + "ajax/ajax.vehicle.php",
    type: 'POST',
    data: formdata,
    processData: false,
    contentType: false,
    success: function(msg){
      var obj = jQuery.parseJSON(msg);
      if(obj['message'] === "OK"){
        location.reload();
      }
    }
  });
}