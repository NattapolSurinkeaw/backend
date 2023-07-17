$(function(){
    getDataContact();
  });
  
  /**
   * ดึงข้อมูล Contact
   */
  function getDataContact(){
    $.ajax({
      url:"ajax/ajax.contact_sel.php",
      type:"post",
      dataType:"json",
      data:{action:"getContact"}, 
      success:function(data){
        console.log(data)
        if(data.message == "OK"){
          if(data.result.thumbnail != ""){
            $(".image-label").css('display','none');
            img = '<div class="col-img-preview" id="col_img_preview_1" data-id="1"><img class="preview-img" id="preview_img_1" src="https://'+location.host+'/'+data.result.thumbnail+'"></div>';
          } else {
            img = '';
          }
          $(".blog-preview-add").html(img)
          $('#logo-text').val(data.result.title)
          $('#contact-name').val(data.result.name)
          $('#contact-address').val(data.result.address)
          $('#contact-email').val(data.result.email)
          $('#contact-map').val(data.result.map)

          if(data.result.phone_display == 'yes'){
            $('#contact-phone-display').attr('checked', true)
          }
          if(data.result.facebook_display == 'yes'){
            $('#contact-facebook-display').attr('checked', true)
          }
          if(data.result.line_display == 'yes'){
            $('#contact-line-display').attr('checked', true)
          }
          

          $('#contact-phone').val(data.result.phone)
          $('#og-description').val(data.result.line_desc);
          $('#contact-lineId').val(data.result.line);
          $('#contact-lineId-href').attr('href','https://line.me/ti/p/~'+data.result.line);
          $('#contact-youtube').val(data.result.youtube)
          $('#contact-youtube-href').attr('href','https://youtube.com/'+data.result.youtube)
          $('#contact-facebook').val(data.result.facebook)
          $('#contact-facebook-href').attr('href','https://facebook.com/'+data.result.facebook)
          // $('#contact-twitter').val(data.result.twitter)
          // $('#contact-twitter-href').attr('href',data.result.twitter)
          $('#contact-instagram').val(data.result.ig)
          $('#contact-instagram-href').attr('href','https://instagram.com/'+data.result.ig)
  
          $('#manual-preidct').val(data.result.manual_predict)
          $('#footer-title').val(data.result.footer_title)
          $('#footer-txt').val(data.result.footer_desc)
        }
      }
    })
  }
  
  /**
   * บันทึก ข้อมูล Contact
   */
  function addDataContact(e){
    e = e || window.event;
    e.preventDefault();
    let log_text = $('#logo-text').val().trim();
    let name = $('#contact-name').val().trim();
    let address = $('#contact-address').val().trim();
    let phone = $('#contact-phone').val().trim();
    let email = $('#contact-email').val().trim();
    let map = $('#contact-map').val().trim();
    let line = $('#contact-lineId').val().trim();
    let youtube = $('#contact-youtube').val().trim();
    let facebook = $('#contact-facebook').val().trim();
    // let twitter = $('#contact-twitter').val().trim();
    let ig = $('#contact-instagram').val().trim();
    let predict = $('#manual-preidct').val().trim();
    let footer_title = $('#footer-title').val().trim();
    let footer_desc = $('#footer-txt').val().trim();
    let og_desc = $('#og-description').val().trim();
    if ($('#add-images-content-hidden').val().length > 0) {
      console.log(formdata);
      if (formdata.getAll("images[]").length !== 0) {
        uploadimages("uploadImageLogo");
      }
    } 
  
    let _data = {
      action: "saveContact",
      log_text,
      name,
      address,
      phone,
      email,
      map,
      youtube,
      facebook,
      ig,
      line,
      predict,
      footer_title,
      footer_desc ,
      og_desc
    }
  
    $.ajax({
      url:"ajax/ajax.contact_sel.php",
      type:"post",
      dataType:"json",
      data:_data,
      success:function(data){
        console.log(data)
          if(data.message == "OK"){
            getDataContact();
            $.confirm({
              title: 'บันทึกสำเร็จ',
              content: '',
              theme: 'modern',
              icon: 'fa fa-check',
              type: 'green',
              typeAnimated: true,
              buttons: {
                tryAgain: {
                  text: 'ตกลง',
                  btnClass: 'btn-green',
                  action: function () {
                  }
                }
              }
            });
          }
       }
    })
  }
  console.log(url_ajax_request);
  
  function previewMap(name){
    // e = e || window.event;
    // e.preventDefault(); 
    if(name == "map"){
      img = $('#contact-map').val();
      $('#model-preview-map .modal-title').html('Preview Map');
    } 
    if(name == "predict"){
      img = '<figure class="img-previews"><img src="'+url_ajax_request+'images/previews/manual-predict.png"></figure>';
      $('#model-preview-map .modal-title').html('Manual Prediction');
    } 
    if(name == "footer-title"){ 
      img = '<figure class="img-previews"><img src="'+url_ajax_request+'images/previews/footer-title.png"></figure>';
      $('#model-preview-map .modal-title').html('Footer Title');
    } 
    if(name == "footer-desc"){
      img = '<figure class="img-previews"><img src="'+url_ajax_request+'images/previews/footer-description.png"></figure>';
      $('#model-preview-map .modal-title').html('Footer Description');
    } 
    $('#model-map-show-body').html(img);
    $('#model-preview-map').show();
  
  }
  
  function closeModal(e){
    e = e || window.event;
    e.preventDefault();
    $('#model-preview-map').hide();
  }
  
   
  // upload images
  $("#add-images-content").uploadImage({
    preview: true
  });
  
  $("#add-images-content").on("change", function () {
    console.log(formdata.getAll("images[]"));
    if (formdata.getAll("images[]").length !== 0) {
        var img = formdata.getAll("images[]")["0"].name;
        $('#add-images-content-hidden').val(img);
        $(".image-label").css("display", "none");
    }
  });
    
  function uploadimages(action) {
    formdata.append("action", action);
    $.ajax({
      url: url_ajax_request + "ajax/ajax.contact_sel.php",
      type: 'POST',
      data: formdata,
      processData: false,
      contentType: false,
      success: function (obj) {
        // console.log(obj)
      }
    });
  }


  function toggleContactDisplay(e){ 
    console.log(e)
    $.ajax({
      url: url_ajax_request + "ajax/ajax.contact_sel.php",
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'updateContactDisplay',
        app: e.value,
        display: (e.checked)?'yes':'no'
      },
      success: function (response) {
        console.log(response)
      }
    });
  }