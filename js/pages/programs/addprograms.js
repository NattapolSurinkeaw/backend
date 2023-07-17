// เริมต้นทำงาน jquery หน้าเพิ่ม content
//ใส่ event คลิกปุ้มเพิ่ม content
var start_initAdd = false;
var checkAlert;
$(document).ready(function () {
  $(".bt-add-content").on("click", load_add);
});

//@initAdd  ฟังก์ชั่นเตียมข้อมูลสำหรับเพิ่มโดยจะทำงานครั้งแรกที่คลิกปุ่ม add 
function init_add() {

  $('#add-blog-category-tree').on('changed.jstree', function(evt, data) {
    if(data.action == 'deselect_node'){
      if(data.selected.length <= 0){
        $('.box-content-cate-add').addClass("error");
      }
    }else{
      $('.box-content-cate-add').removeClass("error");
    }
  });

  //validation form add
  $.validator.messages.required = LANG_LABEL.input_warning_title;
  $("#form-add-content").validate({
    focusInvalid: false,
    onfocusout: false,
    invalidHandler: function (form, validator) {
      if (!validator.numberOfInvalids()) return;
      $('#scrollbar-add').animate({
        scrollTop: $(validator.errorList[0].element).offset().top - 200
      }, 2000);
      $(validator.errorList[0].element).focus();
    },
    ignore: ".ignore",
    rules: {
      'add-images-content-hidden': {
        required: function () {
          if ($('#add-images-content-hidden').val().length < 1) { return true; }
          else { return false; }
        }
      },
      'add_content': {
        required: function () {
          CKEDITOR.instances.add_content.updateElement();
        },
        minlength: 10
      },
      'add-slug': {
        required: true,
        remote: {
          url: 'ajax/ajax.php',
          type: "post",
          async: false,
          data: {
            action: 'checkUrl',
            'slug': function () {
              $('#add-slug').val($('#add-slug').val().replace(/[^a-zA-Z0-9ก-๙_-]/g, '-'));
              return $('#add-slug').val();
            }
          }
        }
      }
    },
    messages: {
      'add-images-content-hidden': {
        required: LANG_LABEL.selectimage
      },//เลือกรูป
      'add-slug': {
        remote: LANG_LABEL.urlisuse // url ถูกใช้งานแล้ว
      },
      'add-category': {
        required: LANG_LABEL.selectcategory
      }
    },
    errorPlacement: function (error, element) {
      if (checkAlert == 0) {
        $.confirm({
          title: '',
          content: error.text(), //แสดงข้อความเตือน
          type: 'red',
          typeAnimated: true,
          buttons: {
            tryAgain: {
              text: LANG_LABEL.close,
              btnClass: 'btn-red'
            }
          }
        });
      }
      checkAlert = 1;
    },
    highlight: function (element, errorClass, validClass) {
      if ($(element).hasClass('add-category')) {
        $('.box-content-cate-add').addClass("error");
      } else {
        $(element).closest(".form-group").addClass("has-error");
      }
    },
    unhighlight: function (element, errorClass, validClass) {
      if ($(element).hasClass('add-category')) {
        $('.box-content-cate-add').removeClass("error");
      } else {
        $(element).closest(".form-group").removeClass("has-error");
      }
    }
  });

  // editor content
  CKEDITOR.replace('add_content', {
    filebrowserUploadUrl: "/backend/plugins/ckeditor/filemanager/connectors/php/upload.php?Type=File",
    filebrowserImageUploadUrl: "/backend/plugins/ckeditor/filemanager/connectors/php/upload.php?Type=Image",
    filebrowserFlashUploadUrl: "/backend/plugins/ckeditor/filemanager/connectors/php/upload.php?Type=Flash",
    height: 400,
    language: backend_language
  });

  // upload images
  $("#add-images-content").uploadImage({
    preview: true
  });

  $("#add-images-content").on("change", function () {
    if (formdata.getAll("images[]").length !== 0) {
      var img = formdata.getAll("images[]")["0"].name;
      $('#add-images-content-hidden').val(img);
      $(".form-add-images").removeClass("has-error");
      $(".add-images-error").css("display", "none");
    }
  });

  $('#prog-add').progressbar({ value: 0 });

  $("#add-more-images").on("change", function (event) {
    files = event.target.files;
    var data = new FormData();
    data.delete("images[]");
    $.each(files, function (key, value) {
      data.append("images[]", ("images" + (key + 1), value));
    });

    data.append('action', 'uploadmoreimgcontent');
    data.append('id', 0);
    $.ajax({
      url: "ajax/ajax.programs.php",
      type: "POST",
      data: data,
      contentType: false,
      cache: false,
      processData: false,
      beforeSend: function (event) {
        $('#prog-add').progressbar({ value: 0 });
        $('#overlay-add-more-img').css('display', 'block');
        // console.log(event);
      },
      progress: function (e) {
        if (e.lengthComputable) {
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
      success: function (msg) {
        var obj = jQuery.parseJSON(msg),
          img_list = '';
        // console.log(obj);
        for (i = 0; i < obj.length; i++) {
          img_list += '\
          <div class="blog-show-image">\
            <div class="iconimg id_imgmore" id="add-img-delete" data-id="'+ obj[i].image_id + '" data-name="' + obj[i].image_link + '">\
              <i class="fa fa-times" alt="delete"></i>\
            </div>\
            <div id="image-preview">\
              <div class="col-img-preview">\
                <img class="preview-img" src="'+ root_url + obj[i].image_link + '">\
              </div>\
            </div>\
          </div>';
        }
        $("#show-add-img-more").append(img_list);
      },
      complete: function () {
        $('#prog-add').progressbar({ value: 0 });
        $('#overlay-add-more-img').css('display', 'none');
      }
    });
  });

  $(document).on('click', '#add-img-delete', function () {
    var id = $(this).data("id"),
      filename = $(this).data("name"),
      postId = 0,
      that = $(this);
    $.ajax({
      type: "POST",
      url: "ajax/ajax.programs.php",
      data: {
        action: "deleteimagecontent",
        id: id,
        filename: filename,
        postId: postId
      },
      beforeSend: function () { },
      success: function (msg) {
        $(that).closest('.blog-show-image').remove();
      }
    });
  });

  // datepicker
  $('#add-date-display').datepicker({
    format: 'dd/mm/yyyy',
    autoclose: true,
    language: 'th',
    todayHighlight: true
  }).on('changeDate', function (e) {
    $('#add-date-display-hidden').val(e.format('yyyy-mm-dd'));
  });

  //timepicker
  $("#add-time-display").timepicker({
    defaultTime: false,
    showInputs: false,
    minuteStep: 1,
    showMeridian: false
  });

  $('#modalAddContent').on('hide.bs.modal', function (e) {
    if (e.namespace == 'bs.modal') {
      var myDiv = document.getElementById('scrollbar-edit');
      myDiv.scrollTop = 0;
      resetFormAdd();
    }
  }); 

  //Edit Tag
  $("#add-search-tag").on("keyup", function () {
    if ($(this).val().length >= 1) {
      searchTagAdd($(this).val());
    } else {
      document.getElementById('add-searchtagresult').innerHTML = '';
    }
  });

  $("#add-tag").on("keyup", function (event) {
    if (event.keyCode == 13) {
      if ($(this).val().length >= 1) {
        addTag($(this).val());
        document.getElementById('add-tag').value = '';
      }
    }
  });

  $(document).on('click', '.add-sent-tag', function () {
    var data = {
      id: $(this).data("id"),
      text: $(this).data("text")
    }
    var resource = [];
    $('.add-checkbox-tag :input').each(function () {
      resource.push({ id: $(this).val() });
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

  $("#reset-add").on("click", function () {
   // resetFormAdd();
  });

  $("#save-add").on("click", function () {
    checkAlert = 0;
    $('#add-category').val($('#add-blog-category-tree').jstree('get_selected').join());
    $('#imgmoreId').val($(".id_imgmore").map(function () { return $(this).data("id"); }).get());
    if ($("#form-add-content").valid()) {
      var url = url_ajax_request + "ajax/ajax.programs.php";
      $.ajax({
        type: "POST",
        url: url,
        dataType:'json',
        data: $("#form-add-content").serializeArray(),
        success: function (obj) {
          if (obj.data['message'] === "OK") {
            if ($('#add-images-content-hidden').val().length > 0) {
              console.log(formdata);
              if (formdata.getAll("images[]").length !== 0) {
                uploadimages(obj.id, "uploadimgcontent");
              }
            } else {
              location.reload();
            }
          }else{
            $.confirm({
              title: '',
              content: LANG_LABEL.urlisuse, 
              type: 'red',
              typeAnimated: true,
              buttons: {
                tryAgain: {
                  text: LANG_LABEL.close,
                  btnClass: 'btn-red'
                }
              }
            });
          }
        }
      });
    }
  });

  $('#add-title').focusout(function () {
    if ($('#add-slug').val() == "") {
      $('#add-slug').val($(this).val().replace(/[^a-zA-Z0-9ก-๙_-]/g, '-'));
      $("#form-add-content").validate().element("#add-slug");
    }
  });

}
//จบการทำงาน init_add

function load_add() {
  //สั่งโหลดข้อมูลเตรียมพร้อมสำหรับเพิ่ม
  //จะเช็ดก่อนว่ามีการสั่ง initAdd หรือยัง
  if (!start_initAdd) {
    init_add();
    start_initAdd = true;
  }
  /* สั่ง popup add content ทำงาน */
  $('#modalAddContent').modal('toggle');
}
//จบการทำงานฟังก์ชั่น load_add

function resetFormAdd() {
  $('#add-blog-category-tree').jstree("deselect_all");
  document.getElementById("form-add-content").reset();
  CKEDITOR.instances['add_content'].setData('');
  $("#add-date-display").datepicker('setDate', '');
  document.getElementById('add-searchtagresult').innerHTML = '';
  document.getElementById('add-blog-tag').innerHTML = '';
  document.getElementById('add-blog-tag').style.display = 'none';

  $(".blog-preview-add").html('');
  $('.box-content-cate-add').removeClass('error');
  $("#show-add-img-more .blog-show-image").each(function () {
    $(this).remove();
  });
  $("#imgmoreId").val("");
  $("#add-category").val("");
  $("#form-add-content .has-error").each(function () {
    $(this).removeClass('has-error');
    $(this).find(".help-block").css("display", "none");
  });
}

function searchTagAdd(key) {
  $.ajax({
    type: "POST",
    url: "ajax/ajax.programs.php",
    data: {
      action: "searchtag",
      key: key
    },
    beforeSend: function () {

    },
    success: function (msg) {
      if (msg) {
        var obj = jQuery.parseJSON(msg);
        var doc = "";
        // console.log(obj);
        for (var i = 0; i < obj.length; i++) {
          doc += '<div class="add-sent-tag" data-id="' + obj[i].tag_id + '" data-text="' + obj[i].tag_name + '">' + obj[i].tag_name + '</div>';
        }
        document.getElementById('add-searchtagresult').innerHTML = doc;
      }
    }
  });
}

function addTag(key) {
  $.ajax({
    type: "POST",
    url: "ajax/ajax.programs.php",
    data: {
      action: "addtag",
      key: key
    },
    success: function (msg) {
      var obj = jQuery.parseJSON(msg);
      // console.log(obj);
      if (obj.data != "exist") {
        var data = {
          id: obj.data.insert_id,
          text: key
        }
        sendtagtoboxAdd(data);
      } else {
        $.confirm({
          title: window.location.hostname + ' says :',
          content: 'This tag is already exist.',
          theme: 'my-theme',
          icon: 'fa fa-warning',
          type: 'darkgreen',
          draggable: false,
          backgroundDismiss: true,
          buttons: {
            confirm: {
              text: 'OK',
              btnClass: 'btn-darkgreen',
            }
          }
        });
      }
    }
  });
}

function sendtagtoboxAdd(data) {
  document.getElementById('add-blog-tag').style.display = 'block';
  document.getElementById('add-blog-tag').innerHTML += '\
  <div class="checkbox add-checkbox-tag">\
    <label>\
      <input type="checkbox" name="add-tag" value="' + data.id + '"  checked>\
      ' + data.text + '\
    </label>\
  </div>';
}