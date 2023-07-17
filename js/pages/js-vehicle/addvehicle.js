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

$.ajax({
  type:"POST",
  url:"ajax/ajax.vehicle.php",
  data:{action:"getcategorycontent"},
  beforeSend: function() {

  },
  success:function(msg){
    var obj = jQuery.parseJSON(msg);

    $("#add-blog-category-tree").jstree({ 
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
      $("#add-blog-category-tree").jstree('open_all');
    });

  }
});

$('#modalAddContent').on('hide.bs.modal', function (e) {
  if (e.namespace == 'bs.modal') {
    var myDiv = document.getElementById('scrollbar-add');
    myDiv.scrollTop = 0;

    $('#add-blog-category-tree').jstree("deselect_all");
    document.getElementById("form-add-content").reset();
    $("#add-date-display").datepicker('setDate','');

    $(".blog-preview-add").html("");
    $("#add-images-content-hidden").val("");

    $(".form-add-images").removeClass("has-error");
    $(".add-images-error").css("display","none");

    $(".form-add-title").removeClass("has-error");
    $(".add-title-error").css("display","none");

    $(".form-add-price").removeClass("has-error");
    $(".add-price-error").css("display","none");

    $(".form-add-description").removeClass("has-error");
    $(".add-description-error").css("display","none");

    $(".form-add-location-from").removeClass("has-error");
    $(".add-location-from-error").css("display","none");

    $(".form-add-location-to").removeClass("has-error");
    $(".add-location-to-error").css("display","none");
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
  var url = url_ajax_request + "ajax/ajax.vehicle.php",
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
      description = $("#add-description"),
      price = $('#add-price'),
      content = "",
      locationfrom = $("#add-location-from"),
      locationto = $("#add-location-to"),
      dateDisplay = $("#add-date-display"),
      display = $("#add-display"),
      pin = $("#add-pin"),
      images = $('#add-images-content-hidden'),
      category = $('#add-blog-category-tree').jstree().get_selected("full"),
      cateid = "",
      dataTime = "";


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

  //validate price
  if (price.val().length < 1) {
    price.focus();
    $(".form-add-price").addClass("has-error");
    $(".add-price-error").css("display","block");
    return false;
  } else {
    $(".form-add-price").removeClass("has-error");
    $(".add-price-error").css("display","none");
  }

  //validate location from
  if (locationfrom.val() == 0) {
    locationfrom.focus();
    $(".form-add-location-from").addClass("has-error");
    $(".add-location-from-error").css("display","block");
    return false;
  } else {
    $(".form-add-location-from").removeClass("has-error");
    $(".add-location-from-error").css("display","none");
  }

  //validate location to
  if (locationto.val() == 0) {
    locationto.focus();
    $(".form-add-location-to").addClass("has-error");
    $(".add-location-to-error").css("display","block");
    return false;
  } else {
    $(".form-add-location-to").removeClass("has-error");
    $(".add-location-to-error").css("display","none");
  }

  //validate location from to
  if (locationto.val() == locationfrom.val()) {
    locationfrom.focus();
    $(".form-add-location-from").addClass("has-error");

    locationto.focus();
    $(".form-add-location-to").addClass("has-error");
    return false;
  } else {
    $(".form-add-location-from").removeClass("has-error");

    $(".form-add-location-to").removeClass("has-error");
  }

  var data = {
      action: "addcontent",
      cateid: cateid,
      title: title.val(),
      description: description.val(),
      price: price.val(),
      locationfrom: locationfrom.val(),
      locationto: locationto.val(),
      dateDisplay: dataTime,
      display: display.val(),
      pin: pin.val()
  };

  add_content(data);
  // console.log(data);
}