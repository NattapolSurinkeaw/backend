// Add Category //
$("#add-images-cate").uploadImage({
  preview: true
});

$("#add-images-cate").on("change", function(){ 
  if(formdata.getAll("images[]").length !== 0){
    var img = formdata.getAll("images[]")["0"].name;
    $('#add-images-cate-hidden').val(img);
    $(".form-add-images").removeClass("has-error");
    $(".add-images-error").css("display","none");
  }
});

$("#add-name").on("keyup", function(){ 
  $(".form-add-name").removeClass("has-error");
  $(".add-name-error").css("display","none");
});

$("#add-title").on("keyup", function(){ 
  $(".form-add-title").removeClass("has-error");
  $(".add-title-error").css("display","none");
});

$("#add-slug").on("keyup", function(){ 
  $(".form-add-slug").removeClass("has-error");
  $(".add-slug-error").css("display","none");
});

$('#modalAddCategory').on('hidden.bs.modal', function (e) {
  if (e.namespace == 'bs.modal') {
    var myDiv = document.getElementById('scrollbar');
    myDiv.scrollTop = 0;

    $(".form-add-images").removeClass("has-error");
    $(".add-images-error").css("display","none");

    $(".form-add-name").removeClass("has-error");
    $(".add-name-error").css("display","none");

    $(".form-add-title").removeClass("has-error");
    $(".add-title-error").css("display","none");

    $(".form-add-slug").removeClass("has-error");
    $(".add-slug-error").css("display","none");

    $('#add-images-cate-hidden').val("");
    $('.blog-preview-add').html("");
    document.getElementById("add-cate-0").checked = true;
    document.getElementById("form-add-category").reset();
  }
});

$("#save-add-category").on("click", function(){ 
  validate_add_category();
});

function add_category(data) {
  var url = url_ajax_request + "ajax/ajax.car_brand.php",
            dataSet = data;
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function(msg){
      var obj = jQuery.parseJSON(msg);
      if(obj.data['message'] === "OK"){
          if ($('#add-images-cate-hidden').val().length > 0) {
            if(formdata.getAll("images[]").length !== 0){
              uploadimages(obj.id, "uploadimgcate");
            }
          }else{
            location.reload();
          }
      }else if(obj.data['message'] === "url_already_exists"){
        validate_add_category(obj.data['message']);
      }
    }
  });
}

function validate_add_category(data) {
  var parentId = $('input[name="parent-id-add"]:checked'),
      images = $('#add-images-cate-hidden'),
      name = $("#add-name"),
      title = $("#add-title"),
      keyword = $("#add-keyword"),
      description = $("#add-description"),
      slug = $("#add-slug"),
      topic = $("#add-topic"),
      freetag = $("#add-freetag"),
      h1 = $("#add-h1"),
      h2 = $("#add-h2"),
      display = $("#add-display"),
      priority = $("#add-priority"),
      categoryUrl = slug.val().trim().replace(/[^a-zA-Z0-9ก-๙_-]/g,'-');

  //validate images
  if (images.val().length < 1) {
    $(".form-add-images").addClass("has-error");
    $(".add-images-error").css("display","block");
    return false;
  } else {
    $(".form-add-images").removeClass("has-error");
    $(".add-images-error").css("display","none");
  }

  //validate name
  if (name.val().length < 1) {
    name.focus();
    $(".form-add-name").addClass("has-error");
    $(".add-name-error").css("display","block");
    return false;
  } else {
    $(".form-add-name").removeClass("has-error");
    $(".add-name-error").css("display","none");
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

  var data = {
      action: "addcategory",
      parentId: parentId.val(),
      name: name.val(),
      title: title.val(),
      keyword: keyword.val(),
      description: description.val(),
      slug: categoryUrl,
      topic: topic.val(),
      freetag: freetag.val(),
      h1: h1.val(),
      h2: h2.val(),
      display: display.val(),
      priority: priority.val()
  };
  add_category(data);
}