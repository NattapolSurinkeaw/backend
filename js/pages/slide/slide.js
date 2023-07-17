$(document).ready(function() {
  $('.fancybox').fancybox({
    wrapCSS: 'fancybox-custom',
    padding: 10,
    closeBtn: false,
    helpers : {
      title : {
        type: 'outside'
      }
    }
  });
});

$('#edit-ad-date-display').datepicker({
  format: 'dd/mm/yyyy',
  autoclose: true,
  language: 'th',
  todayHighlight: true
}).on('changeDate', function(e) {
  $('#edit-input-date-display').val(e.format('yyyy-mm-dd'));
});

$('#edit-ad-date-hidden').datepicker({
  format: 'dd/mm/yyyy',
  autoclose: true,
  language: 'th',
  todayHighlight: true
}).on('changeDate', function(e) {
  $('#edit-input-date-hidden').val(e.format('yyyy-mm-dd'));
});


$('#modalEditContent').on('hide.bs.modal', function (e) {
  if (e.namespace == 'bs.modal') {
    var myDiv = document.getElementById('scrollbar');
    myDiv.scrollTop = 0;
  }
});


// upload images
$("#edit-images-ads").uploadImage({
    preview: true
});

$("#reset-edit").on("click", function(){ 
  
});

$("#edit-images-ads").on("change", function(){ 
  if(formdata.getAll("images[]").length !== 0){
    var img = formdata.getAll("images[]")["0"].name;
    $('#edit-images-ads-hidden').val(img);
  }
});


$(".edit-slide").on("click", function(){ 
  var adsId = $(this).data("id");
  var type = $(this).data("type");
  getAds(adsId,type);
});

function uploadimages(adsId) {
  if(formdata.getAll("images[]").length !== 0){
    formdata.append("action", "uploadimgads");
    formdata.append("id", adsId);
    $.ajax({
        url: url_ajax_request + "ajax/ajax.slide.php",
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
}

function getAds(adsId,type) {
  $.ajax({
    type:"POST",
    url:"ajax/ajax.slide.php",
    data:{action:"getads",
          id:adsId},
    beforeSend: function() {
      document.getElementById("form-edit-ads").reset();
      $('#edit-images-ads-hidden').val("");
      $('#edit-ads-title').val("");
      $('#edit-ads-link').val("");
      $('.blog-preview-img').html("");
      $("#edit-ads-position option").attr('selected', false);
    },
    success:function(msg){
      var obj = jQuery.parseJSON(msg);
      console.log(obj.ad_position)
      // console.log(obj);
      $('#edit-ads-id').val(obj.ad_id);
      $('#submit-type').val(type);
      $('.blog-preview-img').html('\
                    <div class="col-img-preview" id="col_img_preview_edit" data-id="edit">\
                        <img class="preview-img" id="preview_img_edit" \
                        src="'+site_url+'classes/thumb-generator/thumb.php?src='+root_url+obj.ad_image+'&size=150x150" \
                        data-image="'+obj.ad_image+'">\
                    </div>');
      $('#edit-images-ads-hidden').val(obj.ad_image);
      $('#edit-ads-title').val(obj.ad_title);
      $('#edit-ads-link').val(obj.ad_link);
      $('#edit-ads-priority').val(obj.ad_priority);

      // document.getElementById('pos' + obj.ad_position).selected = true;
      document.getElementById('edit-ads-' + obj.ad_display).selected = true;


    //   $("#edit-ads-position option").filter(function() {
    //     return this.id == 'pos'+obj.ad_position; 
    //   }).attr('selected', true);

      $("#edit-ads-position").val(obj.ad_position)
      $('#edit-ad-date-display').datepicker('setDate', new Date(obj.ad_date_display));
      $('#edit-ad-date-hidden').datepicker('setDate', new Date(obj.ad_date_hidden));
    }
  });
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

function editads(data) {
  var url = url_ajax_request + "ajax/ajax.slide.php",
            adsId = data['id'],
            dataSet = data;
  $.ajax({
      type: "POST",
      url: url,
      data: dataSet,
      success: function(data){
          var obj = jQuery.parseJSON(data);
          if(obj['message'] === "OK"){
            if(formdata.getAll("images[]").length !== 0){
              uploadimages(adsId);
            }else{
              location.reload();
            }
          }
      }
  });
}

$("#save-edit-ads").on("click", function(){
  var id = $("#edit-ads-id"),
      type = $("#submit-type"),
      images = $('#edit-images-ads-hidden'),
      title = $("#edit-ads-title"),
      link = $("#edit-ads-link"),
      position = $("#edit-ads-position"),
      priority = $("#edit-ads-priority"),
      display = $("#edit-ads-display"),
      dateDisplay = $("#edit-input-date-display").val(),
      dateHidden = $("#edit-input-date-hidden").val();

  if ($("#edit-ad-date-display").val().length < 1) {
    dateDisplay = "0000-00-00";
  }

  if ($("#edit-ad-date-hidden").val().length < 1) {
    dateHidden = "0000-00-00";
  }

  var data = {
    action: "editads",
    id: id.val(),
    type: type.val(),
    images: images.val(),
    title: title.val(),
    link: link.val(),
    position: position.val(),
    priority: priority.val(),
    display: display.val(),
    dateDisplay: dateDisplay,
    dateHidden: dateHidden
  };

  editads(data);
});


//////////////////Add/////////////////////////////////
$('#add-ad-date-display').datepicker({
  format: 'dd/mm/yyyy',
  autoclose: true,
  language: 'th',
  todayHighlight: true
}).on('changeDate', function(e) {
  $('#add-input-date-display').val(e.format('yyyy-mm-dd'));
});

$('#add-ad-date-hidden').datepicker({
  format: 'dd/mm/yyyy',
  autoclose: true,
  language: 'th',
  todayHighlight: true
}).on('changeDate', function(e) {
  $('#add-input-date-hidden').val(e.format('yyyy-mm-dd'));
});

$('#modalAddContent').on('hide.bs.modal', function (e) {
  if (e.namespace == 'bs.modal') {
    var myDiv = document.getElementById('scrollbar');
    myDiv.scrollTop = 0;
  }
});

$("#add-images-ads").uploadImage({
    preview: true
});

$("#add-images-ads").on("change", function(){ 
  if(formdata.getAll("images[]").length !== 0){
    var img = formdata.getAll("images[]")["0"].name;
    $('#add-images-ads-hidden').val(img);
  }
});

$("#save-add-ads").on("click", function(){
  var title = $("#add-ads-title"),
      link = $("#add-ads-link"),
      position = $("#add-ads-position"),
      priority = $("#add-ads-priority"),
      display = $("#add-ads-display"),
      dateDisplay = $("#add-input-date-display").val(),
      dateHidden = $("#add-input-date-hidden").val();

  if ($("#add-ad-date-display").val().length < 1) {
    dateDisplay = "0000-00-00";
  }

  if ($("#add-ad-date-hidden").val().length < 1) {
    dateHidden = "0000-00-00";
  }

  if (display.val() == "no") {
    dateDisplay = "0000-00-00";
    dateHidden = "0000-00-00";
  }

  var data = {
    action: "addads",
    title: title.val(),
    link: link.val(),
    position: position.val(),
    priority: priority.val(),
    display: display.val(),
    dateDisplay: dateDisplay,
    dateHidden: dateHidden
  };
  addads(data);
  // console.log(data);
});

function addads(data) {
  var url = url_ajax_request + "ajax/ajax.slide.php",
            dataSet = data;
  $.ajax({
      type: "POST",
      url: url,
      data: dataSet,
      success: function(data){
          var obj = jQuery.parseJSON(data);
          // console.log(obj);
          if(obj.data['message'] === "OK"){
              if ($('#add-images-ads-hidden').val().length > 0) {
                if(formdata.getAll("images[]").length !== 0){
                  uploadimages(obj.id);
                }
              }else{
                location.reload();
              }
          }
      }
  });
}