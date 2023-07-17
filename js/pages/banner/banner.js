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
$("#edit-images-banner").uploadImage({
    preview: true
});

$("#reset-edit").on("click", function(){ 
  
});

$("#edit-images-banner").on("change", function(){ 
  if(formdata.getAll("images[]").length !== 0){
    var img = formdata.getAll("images[]")["0"].name;
    $('#edit-images-banner-hidden').val(img);
  }
});


$(".edit-banner").on("click", function(){ 
  var bannerId = $(this).data("id");
  var type = $(this).data("type");
  getAds(bannerId,type);
});

function uploadimages(bannerId) {
  if(formdata.getAll("images[]").length !== 0){
    formdata.append("action", "uploadimgbanner");
    formdata.append("id", bannerId);
    $.ajax({
        url: url_ajax_request + "ajax/ajax.banner.php",
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

function getAds(bannerId,type) {
  $.ajax({
    type:"POST",
    url:"ajax/ajax.banner.php",
    data:{action:"getbanner",
          id:bannerId},
    beforeSend: function() {
      document.getElementById("form-edit-banner").reset();
      $('#edit-images-banner-hidden').val("");
      $('#edit-banner-title').val("");
      $('#edit-banner-link').val("");
      $('.blog-preview-img').html("");
      $("#edit-banner-position option").attr('selected', false);
    },
    success:function(msg){
      var obj = jQuery.parseJSON(msg);
      // console.log(obj);
      $('#edit-banner-id').val(obj.ad_id);
      $('#submit-type').val(type);
      $('.blog-preview-img').html('\
                    <div class="col-img-preview" id="col_img_preview_edit" data-id="edit">\
                        <img class="preview-img" id="preview_img_edit" \
                        src="'+site_url+'classes/thumb-generator/thumb.php?src='+root_url+obj.ad_image+'&size=150x150" \
                        data-image="'+obj.ad_image+'">\
                    </div>');
      $('#edit-images-banner-hidden').val(obj.ad_image);
      $('#edit-banner-title').val(obj.ad_title);
      $('#edit-banner-link').val(obj.ad_link);
      $('#edit-banner-priority').val(obj.ad_priority);

      // document.getElementById('pos' + obj.ad_position).selected = true;
      document.getElementById('edit-banner-' + obj.ad_display).selected = true;


      $("#edit-banner-position option").filter(function() {
        return this.id == 'pos'+obj.ad_position; 
      }).attr('selected', true);

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

function editbanner(data) {
  var url = url_ajax_request + "ajax/ajax.banner.php",
            bannerId = data['id'],
            dataSet = data;
  $.ajax({
      type: "POST",
      url: url,
      data: dataSet,
      success: function(data){
          var obj = jQuery.parseJSON(data);
          if(obj['message'] === "OK"){
            if(formdata.getAll("images[]").length !== 0){
              uploadimages(bannerId);
            }else{
              location.reload();
            }
          }
      }
  });
}

$("#save-edit-banner").on("click", function(){
  var id = $("#edit-banner-id"),
      type = $("#submit-type"),
      images = $('#edit-images-banner-hidden'),
      title = $("#edit-banner-title"),
      link = $("#edit-banner-link"),
      position = $("#edit-banner-position"),
      priority = $("#edit-banner-priority"),
      display = $("#edit-banner-display"),
      dateDisplay = $("#edit-input-date-display").val(),
      dateHidden = $("#edit-input-date-hidden").val();

  if ($("#edit-ad-date-display").val().length < 1) {
    dateDisplay = "0000-00-00";
  }

  if ($("#edit-ad-date-hidden").val().length < 1) {
    dateHidden = "0000-00-00";
  }

  if (display.val() == "no") {
    dateDisplay = "0000-00-00";
    dateHidden = "0000-00-00";
  }

  var data = {
    action: "editbanner",
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

  editbanner(data);
});


//////////////////Add/////////////////////////////////
$('#add-ad-date-display').datepicker({
  format: 'dd/mm/yyyy',
  autoclose: true,
  language: 'th',
  todayHighlight: true
}).on('changeDate', function(e) {
  $('#edit-input-date-display').val(e.format('yyyy-mm-dd'));
});

$('#add-ad-date-hidden').datepicker({
  format: 'dd/mm/yyyy',
  autoclose: true,
  language: 'th',
  todayHighlight: true
}).on('changeDate', function(e) {
  $('#edit-input-date-hidden').val(e.format('yyyy-mm-dd'));
});

$('#modalAddContent').on('hide.bs.modal', function (e) {
  if (e.namespace == 'bs.modal') {
    var myDiv = document.getElementById('scrollbar');
    myDiv.scrollTop = 0;
  }
});

$("#add-images-banner").uploadImage({
    preview: true
});

$("#add-images-banner").on("change", function(){ 
  if(formdata.getAll("images[]").length !== 0){
    var img = formdata.getAll("images[]")["0"].name;
    $('#add-images-banner-hidden').val(img);
  }
});

$("#save-add-banner").on("click", function(){
  var title = $("#add-banner-title"),
      link = $("#add-banner-link"),
      position = $("#add-banner-position"),
      priority = $("#add-banner-priority"),
      display = $("#add-banner-display"),
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
    action: "addbanner",
    title: title.val(),
    link: link.val(),
    position: position.val(),
    priority: priority.val(),
    display: display.val(),
    dateDisplay: dateDisplay,
    dateHidden: dateHidden
  };
  addbanner(data);
  // console.log(data);
});

function addbanner(data) {
  var url = url_ajax_request + "ajax/ajax.banner.php",
            dataSet = data;
  $.ajax({
      type: "POST",
      url: url,
      data: dataSet,
      success: function(data){
          var obj = jQuery.parseJSON(data);
          // console.log(obj);
          if(obj.data['message'] === "OK"){
              if ($('#add-images-banner-hidden').val().length > 0) {
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
 

$('.btn-delete-banner').on('click',function(){

	var data = {	
		id: $(this).data('id'),
		action: 'deleteBanner' 
	};
	 

	  deleteContinue(data);

});


function deleteContinue(data){
	
$.confirm({
title: 'Are you sure?',
content: 'you want to delete this content.',
theme: 'material',
icon: 'fa fa-warning',
type: 'red',
draggable: false,
buttons: {
	confirm:  {
		text: 'Yes, delete it!',
		btnClass: 'btn-red',
		action: function(){
	 
			delete_content(data);
			
		}
	},
	formCancel: {
		text: 'Cancel',
		cancel: function () {}  
	}
}
});

}


function delete_content(data) {

dataSet = data;

$.ajax({
type: "POST",
url: "ajax/ajax.banner.php",
dataType: 'json',
data: dataSet,
success: function(data){
 

	if (data['res']['message'] === "OK") {
		$.confirm({
			title: 'Deleted!',
			content: 'Successfully Deleted!',
			theme: 'modern',
			icon: 'fa fa-check',
			type: 'darkgreen',
			draggable: false,
			backgroundDismiss: true,
			buttons: {
				confirm:  {
					text: 'OK',
					btnClass: 'btn-darkgreen',
					action: function(){
						location.reload();
					}
				}
			},
			backgroundDismiss: function(){
				location.reload();
			}
		});
	}
}
});
}
 