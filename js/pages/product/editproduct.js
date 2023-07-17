//$( document ).ready(function() {
$( window ).on( "load", function() {

// editor content
$('.loading_blacksc_start').fadeToggle();
CKEDITOR.replace('edit-content', {
  filebrowserUploadUrl  : "/backend/plugins/ckeditor/filemanager/connectors/php/upload.php?Type=File",
  filebrowserImageUploadUrl : "/backend/plugins/ckeditor/filemanager/connectors/php/upload.php?Type=Image",
  filebrowserFlashUploadUrl : "/backend/plugins/ckeditor/filemanager/connectors/php/upload.php?Type=Flash",
  height: 400,
  language: 'th'
});

/*
var theEditor;
console.log(site_url);
ClassicEditor
    .create( document.querySelector( '#edit-content' ), {
        ckfinder: {
          uploadUrl: '/backend/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json'
        }
    } )
    .then( editor => {
        theEditor = editor;
        console.log( theEditor );
    } )
    .catch( error => {
        console.error( error );
    } );
*/
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
    url: "ajax/ajax.product.php", 
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
    url:"ajax/ajax.product.php",
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
  format: 'yyyy-mm-dd',
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
  showSeconds: true,
  showMeridian: false
});

// datepicker
$('#date-expire').datepicker({
  format: 'yyyy-mm-dd',
  autoclose: true,
  language: 'th',
  todayHighlight: true
}).on('changeDate', function(e) {
  $('#date-expire-hidden').val(e.format('yyyy-mm-dd'));
});

//timepicker
$("#time-expire").timepicker({
  defaultTime: false,
  showInputs: false,
  minuteStep: 1,
  showSeconds: true,
  showMeridian: false
});

function deleteImageDraft() {
  $.ajax({
    type:"POST",
    url:"ajax/ajax.product.php",
    data:{action:"deleteimagedraft"},
    global:false
  });
}

  $.ajax({
    type:"POST",
    url:"ajax/ajax.product.php",
    data:{action:"getcategory_days"},
    global:false,
    beforeSend: function() {

    },
    success:function(msg){
      var obj = jQuery.parseJSON(msg);

      $("#edit-blog-category-days").jstree({ 
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
        $("#edit-blog-category-days").jstree('open_all');
      });

    }
  });

  $.ajax({
    type:"POST",
    url:"ajax/ajax.product.php",
    data:{action:"getcategory_power"},
    global:false,
    beforeSend: function() {

    },
    success:function(msg){
      var obj = jQuery.parseJSON(msg);

      $("#edit-blog-category-power").jstree({ 
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
        $("#edit-blog-category-power").jstree('open_all');
      });

    }
  });

  $.ajax({
    type:"POST",
    url:"ajax/ajax.product.php",
    data:{action:"getcategory_bermongkol"},
    global:false,
    beforeSend: function() {

    },
    success:function(msg){
      var obj = jQuery.parseJSON(msg);

      $("#edit-blog-category-bermongkol").jstree({ 
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
        $("#edit-blog-category-bermongkol").jstree('open_all');
      });

    }
  });

  $.ajax({
    type:"POST",
    url:"ajax/ajax.product.php",
    data:{action:"getcategory_promotion"},
    global:false,
    beforeSend: function() {

    },
    success:function(msg){
      var obj = jQuery.parseJSON(msg);

      $("#edit-blog-category-promotion").jstree({ 
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
        $("#edit-blog-category-promotion").jstree('open_all');
      });

    }
  });

  $.ajax({
    type:"POST",
    url:"ajax/ajax.product.php",
    data:{action:"getcategory_network"},
    global:false,
    beforeSend: function() {

    },
    success:function(msg){
      var obj = jQuery.parseJSON(msg);
      $("#edit-blog-category-network").jstree({ 
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
        $("#edit-blog-category-network").jstree('open_all');
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

    $(".form-edit-description").removeClass("has-error");
    $(".edit-description-error").css("display","none");

    $(".form-edit-slug").removeClass("has-error");
    $(".edit-slug-error").css("display","none");
  }
});

var contentCk = '';
$('body').on('click', '.edit-content', function(e){
//$(".edit-content").on("click", function(){  

  $('.loading_blacksc').show();

  var contentId = $(this).data("id"),
      submitType = $(this).data("type");
  $('#prog').progressbar({ value: 0 });

  $.ajax({
    type:"POST",
    url:"ajax/ajax.product.php",
    data:{action:"getcontent",
          id:contentId},
    beforeSend: function() {
      $("#edit-blog-category-days").jstree("deselect_all");
      $("#edit-blog-category-power").jstree("deselect_all");
      $("#edit-blog-category-promotion").jstree("deselect_all");
      $("#edit-blog-category-network").jstree("deselect_all");
      document.getElementById("form-edit-content").reset();
      CKEDITOR.instances['edit-content'].setData('<span></span>');

      $("#date-display").datepicker('setDate','');
      $("#date-expire").datepicker('setDate','');
      document.getElementById('searchtagresult').innerHTML='';
      document.getElementById('edit-blog-tag').innerHTML='';
      document.getElementById('edit-blog-tag').style.display='none';
    },
    success:function(msg){
      var obj = jQuery.parseJSON(msg);
      var expire = obj.data["0"].date_expire.split(' ');
      // console.log(expire['1']);
      // console.log(obj);

      var raw_cate_days = obj.data["0"].cate_days.split(',');
      var raw_cate_bermongkol = obj.data["0"].cate_bermongkol.split(',');
      var raw_cate_power = obj.data["0"].cate_power.split(',');
      var raw_cate_promotion = obj.data["0"].cate_promotion.split(',');
      var raw_cate_network = obj.data["0"].cate_network.split(',');

      var tag_list = '',
          img_list = '';
      //for( var twice = 0; twice < 2 ; twice++ ){

        for(var j=1 ; j < raw_cate_days.length-1 ; j++){
          $.jstree.reference("#edit-blog-category-days").select_node(raw_cate_days[j]);
          //console.log("raw_cate_days : "+raw_cate_days[j]);
        }

        for(var j=1 ; j < raw_cate_bermongkol.length-1 ; j++){
          $.jstree.reference("#edit-blog-category-bermongkol").select_node(raw_cate_bermongkol[j]);
          //console.log("raw_cate_days : "+raw_cate_days[j]);
        }
      
        for(var j=1 ; j < raw_cate_power.length-1 ; j++){
          $.jstree.reference("#edit-blog-category-power").select_node(raw_cate_power[j]);
          //console.log("raw_cate_power : "+raw_cate_power[j]);
        }
      
        for(var j=1 ; j < raw_cate_promotion.length-1 ; j++){
          $.jstree.reference("#edit-blog-category-promotion").select_node(raw_cate_promotion[j]);
          //console.log("raw_cate_promotion : "+raw_cate_promotion[j]);
        }
      
        for(var j=1 ; j < raw_cate_network.length-1 ; j++){
          $.jstree.reference("#edit-blog-category-network").select_node(raw_cate_network[j]);
          //console.log("raw_cate_network : "+raw_cate_network[j]);
        }
      
        $(".blog-preview-edit").html('\
        <div class="col-img-preview">\
          <img class="preview-img" src="'+root_url+obj.data["0"].thumbnail+'">\
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
        $('#edit-topic').val(obj.data["0"].topic);
      
        $('#edit-amount').val(obj.data["0"].amount);
        $('#edit-saleprice').val(obj.data["0"].saleprice);
        $('#edit-specialprice').val(obj.data["0"].specialprice);
        $('#edit-color_dot').val(obj.data["0"].color_dot);
        
        $('#edit-shownewdate').val(obj.data["0"].date_shownew);

        var result = obj.data["0"].date_created.split(' ');
        $('#date-display').val(result['0']);
        $('#time-display').val(result['1']);
        var expire = obj.data["0"].date_expire.split(' ');
        $('#date-expire').val(expire['0']);
        $('#time-expire').val(expire['1']);

        $('#edit-freetag').val(obj.data["0"].freetag);
        $('#edit-h1').val(obj.data["0"].h1);
        $('#edit-h2').val(obj.data["0"].h2);
      
        $('#edit-video').val(obj.data["0"].video);

        contentCk = obj.data["0"].content;
        //CKEDITOR.instances['edit-content'].setData(obj.data["0"].content);

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
                  <img class="preview-img" src="'+root_url+obj.images[i].image_link+'">\
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
        if ( !isNaN( new Date(obj.data["0"].date_expire).getTime() ) ) {
          $('#date-expire').datepicker('setDate', new Date(obj.data["0"].date_expire));
          $('#time-expire').val(formatTime(new Date(obj.data["0"].date_expire)));
        }
      //}
      //setTimeout(function(){ $('#modalEditContent').modal('toggle'); $('.loading_blacksc').hide(); },500);
     /* $('#modalEditContent').modal('toggle');
      $('.loading_blacksc').hide();*/

    },    
    complete:function(msg){
      //console.log("xx");
      
      setTimeout(function(){ 
        $('#modalEditContent').modal('toggle'); $('.loading_blacksc').hide(); 
        CKEDITOR.instances['edit-content'].setData(contentCk,submitaftersetdata); 
      },1000);
    }
  });
});

function submitaftersetdata() {
    this.updateElement();
    console.log("updated");
}

$( document ).ajaxComplete(function( event, request, settings ) {
  /*
  CKEDITOR.instances['edit-content'].setData( contentCk );
  console.log(  settings  );
  */
  /*if ( settings.data == "action=getcontent" ) {
    console.log(  settings);
  }*/
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
    url:"ajax/ajax.product.php",
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
    url:"ajax/ajax.product.php",
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
  var url = "ajax/ajax.product.php",
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
      amount = $("#edit-amount"),
      saleprice = $("#edit-saleprice"),
      specialprice = $("#edit-specialprice"),
      content = CKEDITOR.instances["edit-content"].getData(),
      video = $("#edit-video"),
      linkfb = $("#edit-link-fb"),
      linktw = $("#edit-link-tw"),
      linkig = $("#edit-link-ig"),
      dateDisplay = $("#date-display"),
      dateExpire = $("#date-expire"),
      display = $("#edit-display"),
      pin = $("#edit-pin"),
      raw_cate_days = $('#edit-blog-category-days').jstree().get_selected("full"),
      raw_cate_bermongkol = $('#edit-blog-category-bermongkol').jstree().get_selected("full"),
      raw_cate_power = $('#edit-blog-category-power').jstree().get_selected("full"),
      raw_cate_promotion = $('#edit-blog-category-promotion').jstree().get_selected("full"),
      raw_cate_network = $('#edit-blog-category-network').jstree().get_selected("full"),
      tag = $('.checkbox-tag input[type="checkbox"]:checked'),
      allTag = "",
      cate_days = "",
      cate_bermongkol = "",
      cate_power = "",
      cate_promotion = "",
      cate_network = "",
      color_dot = $("#edit-color_dot").val(),
      dataTime = ""
      ;

  if (tag.length > 0) {
    allTag += ",";
    for (var i = 0; i < tag.length; i++) {
      allTag += tag[i].value+",";
    }
  }

  if (raw_cate_days.length > 0) {
    cate_days += ",";
    for (var i = 0; i < raw_cate_days.length; i++) {
      cate_days += raw_cate_days[i].id+",";
    }
  }
  //  else {
  //   $.confirm({
  //     title: window.location.hostname + ' says :',
  //     content: 'you need to select วัน at least 1 category!',
  //     theme: 'my-theme',
  //     icon: 'fa fa-warning',
  //     type: 'darkgreen',
  //     draggable: false,
  //     backgroundDismiss: true,
  //     buttons: {
  //         confirm:  {
  //             text: 'OK',
  //             btnClass: 'btn-darkgreen',
  //         }
  //     }
  //   });
  //   return false;
  // }

  if (raw_cate_bermongkol.length > 0) {
    cate_bermongkol += ",";
    for (var i = 0; i < raw_cate_bermongkol.length; i++) {
      cate_bermongkol += raw_cate_bermongkol[i].id+",";
    }
  }
  //  else {
  //   $.confirm({
  //     title: window.location.hostname + ' says :',
  //     content: 'you need to select เบอร์มงคล at least 1 category!',
  //     theme: 'my-theme',
  //     icon: 'fa fa-warning',
  //     type: 'darkgreen',
  //     draggable: false,
  //     backgroundDismiss: true,
  //     buttons: {
  //         confirm:  {
  //             text: 'OK',
  //             btnClass: 'btn-darkgreen',
  //         }
  //     }
  //   });
  //   return false;
  // }

  if (raw_cate_power.length > 0) {
    cate_power += ",";
    for (var i = 0; i < raw_cate_power.length; i++) {
      cate_power += raw_cate_power[i].id+",";
      //console.log(roomcategory[i].id);
    }
  }
  //  else {
  //   $.confirm({
  //     title: window.location.hostname + ' says :',
  //     content: 'you need to select พลัง at least 1!',
  //     theme: 'my-theme',
  //     icon: 'fa fa-warning',
  //     type: 'darkgreen',
  //     draggable: false,
  //     backgroundDismiss: true,
  //     buttons: {
  //         confirm:  {
  //             text: 'OK',
  //             btnClass: 'btn-darkgreen',
  //         }
  //     }
  //   });
  //   return false;
  // }

  if (raw_cate_promotion.length > 0) {
    cate_promotion += ",";
    for (var i = 0; i < raw_cate_promotion.length; i++) {
      cate_promotion += raw_cate_promotion[i].id+",";
      //console.log(roomcategory[i].id);
    }
  }else {
    cate_promotion = '';
  }

  if (raw_cate_network.length > 0) {
    cate_network += ",";
    for (var i = 0; i < raw_cate_network.length; i++) {
      cate_network += raw_cate_network[i].id+",";
      //console.log(roomcategory[i].id);
    }
  }else {
    $.confirm({
      title: window.location.hostname + ' says :',
      content: 'you need to select เครือข่าย at least 1!',
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

  if (dateExpire.val() != "") {
    dataTimeExpire = $("#date-expire-hidden").val() + " " + $("#time-expire").val()
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
      cate_days: cate_days,
      cate_bermongkol: cate_bermongkol,
      cate_power: cate_power,
      cate_promotion: cate_promotion,
      cate_network: cate_network,
      color_dot:color_dot,
      title: title.val(),
      keyword: keyword.val(),
      description: description.val(),
      slug: contentUrl,
      currentUrl: currentUrl.val(),
      topic: topic.val(),
      freetag: (freetag.val() != null) ? freetag.val():"",
      h1: (h1.val() != null) ? h1.val():"",
      h2: (h2.val() != null) ? h2.val():"",
      amount: amount.val(),
      saleprice: saleprice.val(),
      specialprice: specialprice.val(),
      content: content,
      video: video.val(),
      linkfb: linkfb.val(),
      linktw: linktw.val(),
      linkig: linkig.val(),
      tag: allTag,
      dateDisplay: dataTime,
      dateExpire: dataTimeExpire,
      display: display.val(),
      pin: pin.val(),
      images: $("#edit-images-content-hidden").val(),
      created: $('#date-created').val(),
      submitType: $("#submit-type").val()
  };

  //console.log(data);
  edit_content(data);
}

$("#reset-edit").on("click", function(){ 
  $('#blog-category-tree').jstree("deselect_all");
  $('#blog-roomcategory-tree').jstree("deselect_all");
  document.getElementById("form-edit-content").reset();
  CKEDITOR.instances['edit-content'].setData('<span></span>');
  $("#date-display").datepicker('setDate','');
  $("#date-expire").datepicker('setDate','');
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
    url: url_ajax_request + "ajax/ajax.product.php",
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

$("#inc_shownewdate").on("click", function(){ 
  $.confirm({
    title: 'Are you sure?',
    content: 'คุณต้องการเพิ่มวันแสดงผล NEW อีก 30 วันหรือไม่ ?',
    theme: 'material',
    icon: 'fa fa-arrow-up',
    type: 'blue',
    draggable: false,
    buttons: {
      confirm:  {
        text: 'ตกลง',
        btnClass: 'btn-info',
        action: function(){
          var dataSet = { 
            id: $("#edit-content-id").val(),
            action: "inc_shownewdate"
          };
          inc_shownewdate(dataSet);
        }
      },
      formCancel: {
        text: 'ยกเลิก',
        cancel: function () {}  
      }
    }
  });
  //console.log( dataSet );
});

function inc_shownewdate(dataSet){
  $.ajax({
    type: "POST",
    url: "ajax/ajax.product.php",
    data: dataSet,
    success: function(data){
      var obj = jQuery.parseJSON(data);
       console.log(obj);
      if(obj[0]['message'] === "OK"){
        location.reload();
      }else{
        console.log('fail');
      }
    }
  });

}

});