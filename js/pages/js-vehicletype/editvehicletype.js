// Edit Category //
$("#edit-images-cate").uploadImage({
  preview: true
});

$(document).on('click', '.btn-edit-category', function(){
  var data = {
      action: "getcategoryedit",
      id: $(this).data("id"),
      type: $(this).data("type")
  }
  get_edit_category(data);
});

$("#edit-images-cate").on("change", function(){ 
  if(formdata.getAll("images[]").length !== 0){
    var img = formdata.getAll("images[]")["0"].name;
    $('#edit-images-cate-hidden').val(img);
  }
});

$("#edit-name").on("keyup", function(){ 
  $(".form-edit-name").removeClass("has-error");
  $(".edit-name-error").css("display","none");
});

$("#edit-title").on("keyup", function(){ 
  $(".form-edit-title").removeClass("has-error");
  $(".edit-title-error").css("display","none");
});

$("#edit-slug").on("keyup", function(){ 
  $(".form-edit-slug").removeClass("has-error");
  $(".edit-slug-error").css("display","none");
});

$('#modalEditCategory').on('hidden.bs.modal', function (e) {
  if (e.namespace == 'bs.modal') {
    var myDiv = document.getElementById('scrollbar');
    myDiv.scrollTop = 0;

    $('#submit-type').val("");

    $(".form-edit-name").removeClass("has-error");
    $(".edit-name-error").css("display","none");

    $(".form-edit-title").removeClass("has-error");
    $(".edit-title-error").css("display","none");

    $(".form-edit-slug").removeClass("has-error");
    $(".edit-slug-error").css("display","none");

    $('#edit-images-cate-hidden').val("");
    $('.blog-preview-edit').html("");
    // document.getElementById("edit-cate-0").checked = true;
    document.getElementById("form-edit-category").reset();
  }
});

$("#save-edit-category").on("click", function(){ 
  validate_edit_category();
});

function edit_category(data) {
  var url = url_ajax_request + "ajax/ajax.vehicletype.php",
            dataSet = data;
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function(msg){
      var obj = jQuery.parseJSON(msg);
      // console.log(obj);
      if(obj.data['message'] === "OK"){
          if ($('#edit-images-cate-hidden').val().length > 0) {
            if(formdata.getAll("images[]").length !== 0){
              uploadimages(dataSet.cateId, "uploadimgcate");
            }else{
              location.reload();
            }
          }else{
            location.reload();
          }
      }else if(obj.data['message'] === "url_already_exists"){
        validate_edit_category(obj.data['message']);
      }
    }
  });
}

function get_edit_category(data) {
  var url = url_ajax_request + "ajax/ajax.vehicletype.php",
      dataSet = data;
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function(msg){
      var obj = jQuery.parseJSON(msg); 
      // console.log(obj);

      // document.getElementById('edit-cate-'+obj.parent_id).checked = true;

      $(".blog-preview-edit").html('\
      <div class="col-img-preview">\
        <img class="preview-img" \
        src="'+site_url+'classes/thumb-generator/thumb.php?src='+root_url+obj.thumbnail+'&size=150x150">\
      </div>');

      if (dataSet.type == 'add') {
        $('#edit-images-cate-hidden').val(obj.thumbnail);
      }

      $('#edit-category-id').val(obj.cate_id);
      $('#current-parent-id').val(obj.parent_id);
      $('#edit-name').val(obj.cate_name);
      $('#edit-title').val(obj.title);
      $('#edit-keyword').val(obj.keyword);
      $('#edit-description').val(obj.description);
      $('#edit-slug').val(obj.url);
      $('#current-url').val(obj.url);
      $('#edit-freetag').val(obj.freetag);
      $('#edit-seats').val(obj.seats);
      $('#edit-doors').val(obj.doors);
      $('#edit-airbags').val(obj.airbags);
      $('#edit-air').val(obj.air_conditioner);
      $('#edit-h1').val(obj.h1);
      $('#edit-h2').val(obj.h2);
      document.getElementById('edit-menu-'+obj.menu).selected = true;
      document.getElementById('edit-display-'+obj.display).selected = true;
      $('#edit-position').val(obj.position);
      $('#edit-priority').val(obj.priority);
      $('#current-priority').val(obj.priority);

      $('#submit-type').val(dataSet.type);
    }
  });
}

function validate_edit_category(data) {
  var cateId = $('#edit-category-id'),
      images = $('#edit-images-cate-hidden'),
      name = $("#edit-name"),
      title = $("#edit-title"),
      keyword = $("#edit-keyword"),
      description = $("#edit-description"),
      slug = $("#edit-slug"),
      currentUrl = $('#current-url'),
      seats = $("#edit-seats"),
      doors = $("#edit-doors"),
      airbags = $("#edit-airbags"),
      air = $("#edit-air"),
      menu = $("#edit-show-on-menu"),
      display = $("#edit-display"),
      position = $("#edit-position"),
      priority = $("#edit-priority"),
      currentPriority = $('#current-priority'),
      currentParentId = $('#current-parent-id'),
      categoryUrl = slug.val().trim().replace(/[^a-zA-Z0-9ก-๙_-]/g,'-');

  //validate name
  if (name.val().length < 1) {
    name.focus();
    $(".form-edit-name").addClass("has-error");
    $(".edit-name-error").css("display","block");
    return false;
  } else {
    $(".form-edit-name").removeClass("has-error");
    $(".edit-name-error").css("display","none");
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
      action: "editcategory",
      cateId: cateId.val(),
      parentId: "0",
      name: name.val(),
      title: title.val(),
      keyword: keyword.val(),
      description: description.val(),
      slug: categoryUrl,
      currentUrl: currentUrl.val(),
      topic: "",
      freetag: "",
      seats: seats.val(),
      doors: doors.val(),
      airbags: airbags.val(),
      air: air.val(),
      h1: "",
      h2: "",
      menu: menu.val(),
      display: display.val(),
      position: position.val(),
      priority: priority.val(),
      images: images.val(),
      currentPriority: currentPriority.val(),
      currentParentId: currentParentId.val(),
      submitType: $("#submit-type").val()
  };
  // console.log(data);
  edit_category(data);
}