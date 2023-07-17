// editor content
CKEDITOR.replace('edit-content', {
  filebrowserUploadUrl  :"/backend/plugins/ckeditor/filemanager/connectors/php/upload.php?Type=File",
  filebrowserImageUploadUrl : "/backend/plugins/ckeditor/filemanager/connectors/php/upload.php?Type=Image",
  filebrowserFlashUploadUrl : "/backend/plugins/ckeditor/filemanager/connectors/php/upload.php?Type=Flash",
  height: 400,
  language: 'th'
});

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


$('#prog-edit').progressbar({ value: 0 });

$("#edit-more-images").on("change", function(event){ 
  files = event.target.files;
  var data = new FormData();
  data.delete("images[]");
  $.each(files, function(key, value){
    data.append("images[]", ("images"+(key+1), value));
  });

  data.append('action', 'uploadmoreimgcontent');
  data.append('id', $('#edit-content-id').val());
  $.ajax({
    url: "ajax/ajax.rooms.php", 
    type: "POST",          
    data: data,
    contentType: false,
    cache: false,             
    processData:false, 
    beforeSend: function(event) {
      $('#prog-edit').progressbar({ value: 0 });
      $('#overlay-edit-more-img').css('display', 'block');
      // console.log(event);
    },  
    progress: function(e) {
      if(e.lengthComputable) {
          var pct = (e.loaded / e.total) * 100;
          // console.log(pct);
          $('#prog-edit')
              .progressbar('option', 'value', pct)
              .children('.ui-progressbar-value')
              .html(pct.toPrecision(3) + '%');
      } else {
          // console.warn('Content Length not reported!');
      }
    }, 
    success: function(msg){
      var obj = jQuery.parseJSON(msg),
          img_list = '';
        // console.log(obj);
        for(i=0 ; i < obj.length ; i++){
          img_list += '\
          <div class="blog-show-image">\
            <div class="iconimg" id="img-delete" data-id="'+obj[i].image_id+'" data-name="'+obj[i].image_link+'">\
              <i class="fa fa-times" alt="delete"></i>\
            </div>\
            <div id="image-preview">\
              <div class="col-img-preview">\
                <img class="preview-img" src="'+root_url+obj[i].image_link+'">\
              </div>\
            </div>\
          </div>';
        }
      $("#show-img-more").append(img_list);
    },
    complete: function(){
      $('#prog-edit').progressbar({ value: 0 });
      $('#overlay-edit-more-img').css('display', 'none');
    }
  });
});

$(document).on('click', '#img-delete', function(){
  var id = $(this).data("id"),
      filename = $(this).data("name"),
      postId = $("#edit-content-id").val();
  $.ajax({
    type:"POST",
    url:"ajax/ajax.rooms.php",
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


$('#modalEditContent').on('hide.bs.modal', function (e) {
  if (e.namespace == 'bs.modal') {
    var myDiv = document.getElementById('scrollbar-edit');
    myDiv.scrollTop = 0;

    deleteImageDraft();
    $(".form-edit-title").removeClass("has-error");
    $(".edit-title-error").css("display","none");

    $(".form-edit-description").removeClass("has-error");
    $(".edit-description-error").css("display","none");

    $(".form-edit-slug").removeClass("has-error");
    $(".edit-slug-error").css("display","none");

    $('#show-video').html('');
  }
});

function deleteImageDraft() {
  $.ajax({
    type:"POST",
    url:"ajax/ajax.rooms.php",
    data:{action:"deleteimagedraft"}
  });
}

$(".edit-content").on("click", function(){  
  var contentId = $(this).data("id"),
      submitType = $(this).data("type");
  $('#prog').progressbar({ value: 0 });
  $.ajax({
    type:"POST",
    url:"ajax/ajax.rooms.php",
    data:{action:"getcontent",
          id:contentId},
    beforeSend: function() {
      $('#blog-category-tree').jstree("deselect_all");
      document.getElementById("form-edit-content").reset();
      CKEDITOR.instances['edit-content'].setData('');
      $("#date-display").datepicker('setDate','');
      document.getElementById('searchtagresult').innerHTML='';
      document.getElementById('edit-blog-tag').innerHTML='';
      document.getElementById('edit-blog-tag').style.display='none';
      // $('#show-video').html('');
    },
    success:function(msg){
      var obj = jQuery.parseJSON(msg);
      // console.log(obj);

      var tag_list = '',
          img_list = '';

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
      $('#edit-keyword').val(obj.data["0"].keyword);
      $('#edit-description').val(obj.data["0"].description);
      $('#edit-slug').val(obj.data["0"].slug);
      $('#current-url').val(obj.data["0"].slug);

      $('#edit-h1').val(obj.data["0"].h1);
      $('#edit-h2').val(obj.data["0"].h2);

      $('#edit-freetag').val(obj.data["0"].freetag);
      $('#edit-h1').val(obj.data["0"].h1);
      $('#edit-h2').val(obj.data["0"].h2);

      $('#edit-video').val(obj.data["0"].video);
      $('#edit-topic').val(obj.data["0"].topic);
      // if (obj.vdo) {
      //   if (obj.vdo['type'] == "youtube") {
      //     $('#show-video').html(obj.vdo['embed']);
      //   } else if (obj.vdo['type'] == "facebook") {
      //     $('#show-video').html(obj.vdo['embed']);
      //     FB.XFBML.parse(document.getElementById('show-video'));
      //   }
      // }

      CKEDITOR.instances['edit-content'].setData(obj.data["0"].content);

      document.getElementById('edit-display-'+obj.data["0"].display).selected = true;
      document.getElementById('edit-pin-'+obj.data["0"].pin).selected = true;

      if (obj.tag.length != 0) {
        document.getElementById('edit-blog-tag').style.display='block';
        for (var i = 0; i < obj.tag.length; i++) {
          tag_list += '\
          <div class="checkbox checkbox-tag">\
            <label>\
              <input type="checkbox" value="' + obj.tag[i]["0"].tag_id + '"  checked>\
              ' + obj.tag[i]["0"].tag_name + '\
            </label>\
          </div>';
        }
        $('#edit-blog-tag').html(tag_list);
      }

      if (obj.images != 'no_image') {
        for(i=0 ; i < obj.images.length ; i++){
          img_list += '\
          <div class="blog-show-image">\
            <div class="iconimg" id="img-delete" data-id="'+obj.images[i].image_id+'" data-name="'+obj.images[i].image_link+'">\
              <i class="fa fa-times" alt="delete"></i>\
            </div>\
            <div id="image-preview">\
              <div class="col-img-preview">\
                <img class="preview-img" \
                src="'+site_url+'classes/thumb-generator/thumb.php?src='+root_url+obj.images[i].image_link+'&size=150x150">\
              </div>\
            </div>\
          </div>';
        }
      }
      $("#show-img-more").html(img_list);

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
    url:"ajax/ajax.rooms.php",
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
    url:"ajax/ajax.rooms.php",
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
  var url = "ajax/ajax.rooms.php",
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
      keyword = $("#edit-keyword"),
      description = $("#edit-description"),
      slug = $("#edit-slug"),
      contentUrl = slug.val().trim().replace(/[^a-zA-Z0-9ก-๙_-]/g,'-');
      currentUrl = $('#current-url'),
      topic = $("#edit-topic"),
      freetag = $("#edit-freetag"),
      h1 = $("#edit-h1"),
      h2 = $("#edit-h2"),
      content = CKEDITOR.instances["edit-content"].getData(),
      video = $("#edit-video"),
      linkfb = $("#edit-link-fb"),
      linktw = $("#edit-link-tw"),
      linkig = $("#edit-link-ig"),
      dateDisplay = $("#date-display"),
      display = $("#edit-display"),
      pin = $("#edit-pin"),
      category = "",
      tag = $('.checkbox-tag input[type="checkbox"]:checked'),
      allTag = "",
      cateid = "",
      dataTime = ""
      ;

  if (tag.length > 0) {
    allTag += ",";
    for (var i = 0; i < tag.length; i++) {
      allTag += tag[i].value+",";
    }
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

  //validate description
  if (description.val().length < 1) {
    description.focus();
    $(".form-edit-description").addClass("has-error");
    $(".edit-description-error").css("display","block");
    return false;
  } else {
    $(".form-edit-description").removeClass("has-error");
    $(".edit-description-error").css("display","none");
  }

  //validate slug
  if (slug.val().length < 1) {
    slug.focus();
    $(".edit-slug-error").text("Please fill out this field.");
    $(".form-edit-slug").addClass("has-error");
    $(".edit-slug-error").css("display","block");
    return false;
  }else if (data === "url_already_exists") {
    slug.val("");
    slug.focus();
    $(".edit-slug-error").text("This url already exist.");
    $(".form-edit-slug").addClass("has-error");
    $(".edit-slug-error").css("display","block");
    return false;
  } else {
    $(".form-edit-slug").removeClass("has-error");
    $(".edit-slug-error").css("display","none");
  }

  var data = {
      action: "editcontent",
      id: id.val(),
      cateid: ",2,",
      title: title.val(),
      keyword: keyword.val(),
      description: description.val(),
      slug: contentUrl,
      currentUrl: currentUrl.val(),
      topic: (topic.val() != null) ? topic.val():"",
      freetag: (freetag.val() != null) ? freetag.val():"",
      h1: (h1.val() != null) ? h1.val():"",
      h2: (h2.val() != null) ? h2.val():"",
      content: content,
      video: video.val(),
      linkfb: linkfb.val(),
      linktw: linktw.val(),
      linkig: linkig.val(),
      tag: allTag,
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
    url: url_ajax_request + "ajax/ajax.rooms.php",
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