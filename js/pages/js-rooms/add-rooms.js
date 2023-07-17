// editor content
CKEDITOR.replace('add-content', {
  filebrowserUploadUrl  :"/backend/plugins/ckeditor/filemanager/connectors/php/upload.php?Type=File",
  filebrowserImageUploadUrl : "/backend/plugins/ckeditor/filemanager/connectors/php/upload.php?Type=Image",
  filebrowserFlashUploadUrl : "/backend/plugins/ckeditor/filemanager/connectors/php/upload.php?Type=Flash",
  height: 400,
  language: 'th'
});

// upload images
$("#add-images-content").uploadImage({
  preview: true
});

$("#add-images-content").on("change", function(){ 
  if(formdata.getAll("images[]").length !== 0){
    var img = formdata.getAll("images[]")["0"].name;
    $('#add-images-content-hidden').val(img);
    $(".form-add-images").removeClass("has-error");
    $(".add-images-error").css("display","none");
  }
});

$('#prog-add').progressbar({ value: 0 });

$("#add-more-images").on("change", function(event){ 
  files = event.target.files;
  var data = new FormData();
  data.delete("images[]");
  $.each(files, function(key, value){
    data.append("images[]", ("images"+(key+1), value));
  });

  data.append('action', 'uploadmoreimgcontent');
  data.append('id', 0);
  $.ajax({
    url: "ajax/ajax.rooms.php", 
    type: "POST",          
    data: data,
    contentType: false,
    cache: false,             
    processData:false, 
    beforeSend: function(event) {
      $('#prog-add').progressbar({ value: 0 });
      $('#overlay-add-more-img').css('display', 'block');
      // console.log(event);
    },  
    progress: function(e) {
      if(e.lengthComputable) {
          var pct = (e.loaded / e.total) * 100;
          // console.log(pct);
          $('#prog-add')
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
            <div class="iconimg" id="add-img-delete" data-id="'+obj[i].image_id+'" data-name="'+obj[i].image_link+'">\
              <i class="fa fa-times" alt="delete"></i>\
            </div>\
            <div id="image-preview">\
              <div class="col-img-preview">\
                <img class="preview-img" src="'+root_url+obj[i].image_link+'">\
              </div>\
            </div>\
          </div>';
        }
      $("#show-add-img-more").append(img_list);
    },
    complete: function(){
      $('#prog-add').progressbar({ value: 0 });
      $('#overlay-add-more-img').css('display', 'none');
    }
  });
});

$(document).on('click', '#add-img-delete', function(){
  var id = $(this).data("id"),
      filename = $(this).data("name"),
      postId = 0;
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
            <div class="iconimg" id="add-img-delete" data-id="'+obj.images[i].image_id+'" data-name="'+obj.images[i].image_link+'">\
              <i class="fa fa-times" alt="delete"></i>\
            </div>\
            <div id="image-preview">\
              <div class="col-img-preview">\
                <img class="preview-img" src="'+root_url+obj.images[i].image_link+'">\
              </div>\
            </div>\
          </div>';
        }
        $("#show-add-img-more").html(img_list);
      }else {
        $("#show-add-img-more").html("");
      }
    }
  });
});

// datepicker
$('#add-date-display').datepicker({
  format: 'dd/mm/yyyy',
  autoclose: true,
  language: 'th',
  todayHighlight: true
}).on('changeDate', function(e) {
  $('#add-date-display-hidden').val(e.format('yyyy-mm-dd'));
});

//timepicker
$("#add-time-display").timepicker({
  defaultTime: false,
  showInputs: false,
  minuteStep: 1,
  showMeridian: false
});

// $.ajax({
//   type:"POST",
//   url:"ajax/ajax.rooms.php",
//   data:{action:"getcategorycontent"},
//   beforeSend: function() {

//   },
//   success:function(msg){
//     var obj = jQuery.parseJSON(msg);

//     $("#add-blog-category-tree").jstree({ 
//       "core" : {
//         "data" : obj,
//         "check_callback" : true
//       },
//       "checkbox" : {
//         "keep_selected_style" : false,
//         "three_state": false,
//         "cascade" : "up"
//       },
//       "plugins" : [ "wholerow", "checkbox" ]
//     }).bind('loaded.jstree', function() {
//       $("#add-blog-category-tree").jstree('open_all');
//     });

//   }
// });

$('#modalAddContent').on('hide.bs.modal', function (e) {
  if (e.namespace == 'bs.modal') {
    var myDiv = document.getElementById('scrollbar-add');
    myDiv.scrollTop = 0;

    $("#show-add-img-more").html("");
    deleteImageDraft();

    $('#add-blog-category-tree').jstree("deselect_all");
    document.getElementById("form-add-content").reset();
    CKEDITOR.instances['add-content'].setData('');
    $("#add-date-display").datepicker('setDate','');

    document.getElementById('add-searchtagresult').innerHTML='';
    document.getElementById('add-blog-tag').innerHTML='';
    document.getElementById('add-blog-tag').style.display='none';

    $(".blog-preview-add").html("");
    $("#add-images-content-hidden").val("");

    $(".form-add-images").removeClass("has-error");
    $(".add-images-error").css("display","none");

    $(".form-add-title").removeClass("has-error");
    $(".add-title-error").css("display","none");

    $(".form-add-description").removeClass("has-error");
    $(".add-description-error").css("display","none");

    $(".form-add-slug").removeClass("has-error");
    $(".add-slug-error").css("display","none");
  }
});

//Edit Tag
$("#add-search-tag").on("keyup", function(){
  if ($(this).val().length >= 1) {
    searchTagAdd($(this).val());
  }else {
    document.getElementById('add-searchtagresult').innerHTML='';
  }
});

$("#add-tag").on("keyup", function(event){
  if(event.keyCode == 13){
    if ($(this).val().length >= 1) {
      addTag($(this).val());
      document.getElementById('add-tag').value = '';
    }
  }
});

$(document).on('click', '.add-sent-tag', function(){
  var data = {
    id: $(this).data("id"),
    text: $(this).data("text")
  }
  var resource = [];
  $('.add-checkbox-tag :input').each(function() {
      resource.push({id:$(this).val()});
  });
  // console.log(resource);
  for (var i = 0; i < resource.length; i++) {
    if (resource[i].id == data.id) {
      $(".add-checkbox-tag :input[value='" + data.id + "']").prop('checked', true);
      return false;
    }
  }
  sendtagtoboxAdd(data);
});

function searchTagAdd(key) {
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
          doc += '<div class="add-sent-tag" data-id="' + obj[i].tag_id + '" data-text="' + obj[i].tag_name + '">' + obj[i].tag_name + '</div>';
        }
        document.getElementById('add-searchtagresult').innerHTML=doc;
      }
    }
  });
}

function addTag(key) {
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
        sendtagtoboxAdd(data);
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

function sendtagtoboxAdd(data){
  document.getElementById('add-blog-tag').style.display='block';
  document.getElementById('add-blog-tag').innerHTML += '\
  <div class="checkbox add-checkbox-tag">\
    <label>\
      <input type="checkbox" value="' + data.id + '"  checked>\
      ' + data.text + '\
    </label>\
  </div>';
}


$("#reset-add").on("click", function(){ 
  $('#add-blog-category-tree').jstree("deselect_all");
  document.getElementById("form-add-content").reset();
  CKEDITOR.instances['add-content'].setData('');
  $("#add-date-display").datepicker('setDate','');
  document.getElementById('add-searchtagresult').innerHTML='';
  document.getElementById('add-blog-tag').innerHTML='';
  document.getElementById('add-blog-tag').style.display='none';
});

$("#save-add").on("click", function(){ 
  validate_add_content();
});

function add_content(data) {
  var url = url_ajax_request + "ajax/ajax.rooms.php",
            dataSet = data;
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function(msg){
      var obj = jQuery.parseJSON(msg);
      // console.log(obj);
      if(obj.data['message'] === "OK"){
          if ($('#add-images-content-hidden').val().length > 0) {
            if(formdata.getAll("images[]").length !== 0){
              uploadimages(obj.id, "uploadimgcontent");
            }
          }else{
            location.reload();
          }
      }else if(obj.data['message'] === "url_already_exists"){
        validate_add_content(obj.data['message']);
      }
    }
  });
}

function validate_add_content(data) {
  var title = $("#add-title"),
      keyword = $("#add-keyword"),
      description = $("#add-description"),
      slug = $("#add-slug"),
      topic = $("#add-topic"),
      freetag = $("#add-freetag"),
      h1 = $("#add-h1"),
      h2 = $("#add-h2"),
      content = CKEDITOR.instances["add-content"].getData(),
      video = $("#add-video"),
      linkfb = $("#add-link-fb"),
      linktw = $("#add-link-tw"),
      linkig = $("#add-link-ig"),
      dateDisplay = $("#add-date-display"),
      display = $("#add-display"),
      pin = $("#add-pin"),
      images = $('#add-images-content-hidden'),
      category = '',
      tag = $('.add-checkbox-tag input[type="checkbox"]:checked'),
      allTag = "",
      cateid = "",
      dataTime = "",
      contentUrl = slug.val().trim().replace(/[^a-zA-Z0-9ก-๙_-]/g,'-');

  if (tag.length > 0) {
    allTag += ",";
    for (var i = 0; i < tag.length; i++) {
      allTag += tag[i].value+",";
    }
  }

  //validate images
  if (images.val().length < 1) {
    $(".form-add-images").addClass("has-error");
    $(".add-images-error").css("display","block");
    return false;
  } else {
    $(".form-add-images").removeClass("has-error");
    $(".add-images-error").css("display","none");
  }

  //validate title
  if (title.val().length < 1) {
    title.focus();
    $(".form-add-title").addClass("has-error");
    $(".add-title-error").css("display","block");
    return false;
  } else {
    $(".form-add-title").removeClass("has-error");
    $(".add-title-error").css("display","none");
  }

  //validate description
  if (description.val().length < 1) {
    description.focus();
    $(".form-add-description").addClass("has-error");
    $(".add-description-error").css("display","block");
    return false;
  } else {
    $(".form-add-description").removeClass("has-error");
    $(".add-description-error").css("display","none");
  }

  //validate slug
  if (slug.val().length < 1) {
    slug.focus();
    $(".add-slug-error").text("Please fill out this field.");
    $(".form-add-slug").addClass("has-error");
    $(".add-slug-error").css("display","block");
    return false;
  }else if (data === "url_already_exists") {
    slug.val("");
    slug.focus();
    $(".add-slug-error").text("This url already exist.");
    $(".form-add-slug").addClass("has-error");
    $(".add-slug-error").css("display","block");
    return false;
  } else {
    $(".form-add-slug").removeClass("has-error");
    $(".add-slug-error").css("display","none");
  }

  if (dateDisplay.val() != "") {
    dataTime = $("#add-date-display-hidden").val() + " " + $("#add-time-display").val()
  }

  var data = {
      action: "addcontent",
      cateid: ',2,',
      title: title.val(),
      keyword: keyword.val(),
      description: description.val(),
      slug: contentUrl,
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
      pin: pin.val()
  };

  add_content(data);
  // console.log(data);
}