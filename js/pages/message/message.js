$(function(){
    getDataContact();
  });
  
  
  /**
   * ดึงข้อมูล Contact
   */
  function getDataContact(){
    $.ajax({
      url:"ajax/ajax.message.php",
      type:"post",
      dataType:"json",
      data:{action:"getMessage"},
      success:function(response){  
          $('#message-deposit').val(response[0]['message']);
          $('#message-withdraw').val(response[1]['message']); 
          $('#message-marquee').val(response[2]['message']); 
      }
    })
  }
  
  
  /**
   * บันทึก ข้อมูล Contact
   */
  function addDataContact(e){
    e = e || window.event;
    e.preventDefault(); 
     
    let marquee =  {
        id: $('#message-marquee').data('id'),
        value: $('#message-marquee').val().trim()
    }
    let deposit =  {
        id: $('#message-deposit').data('id'),
        value: $('#message-deposit').val().trim()
    }
    let withdraw =  {
        id: $('#message-withdraw').data('id'),
        value: $('#message-withdraw').val().trim()
    } 
    let array = {
        withdraw,
        deposit,
        marquee
    } 
    let _data = {
      action: "saveMessage",
      array
    } 
  
    $.ajax({
      url:"ajax/ajax.message.php",
      type:"post",
      dataType:"json",
      data:_data,
      success:function(data){ 
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
  
  
  function previewMap(e){
    e = e || window.event;
    e.preventDefault();
  
    console.log( $('#contact-map').val() )
  
    $('#model-map-show-body').html(`${$('#contact-map').val()}`);
    $('#model-preview-map').show();
  }
  
  function closeModal(e){
    e = e || window.event;
    e.preventDefault();
    $('#model-preview-map').hide();
  }