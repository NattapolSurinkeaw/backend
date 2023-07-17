 
var agentTableForm ='';
$(function () {  
 
   agentTableForm = $('#admin-grid').DataTable({
    
    "scrollX": true,
    "processing": true,
    "serverSide": true,
    "ajax": {
        url: "ajax/ajax.agent.php",
        data: {action:"get_salesList"},
        type: "post",
        error: function(){					
          $(".employee-grid-error").html("");
          $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
          $("#employee-grid_processing").css("display","none"); 
        } 
    },
    "pageLength": 25,
    "columnDefs": [{
      targets: [0,1,2,3,4],
      orderable: false,
    }],
    // "order": [[ 2, "asc" ]]
  });
   
  // $("section.newForm .btnAgentAction .btnDeleteAgent[data-id='0']").css("display","none");
  $('.agentpage section.newForm .dataTables_scrollBody  button.btn.btn-sm.btnDeleteAgent[data-id="0"]').css("background","blue");
 
});


 

$(document).on('click', '.edit-admin', function(){

  var userId = $(this).data("id");
  $.ajax({
    type:"POST",
    url:"ajax/ajax.agent.php",
    data:{action:"getuser",
          id:userId},
    beforeSend: function() {
      $('.edit-display-error').hide();
      $("#edit-display-group").removeClass("has-error");
      $('.edit-email-error').hide();
      $("#edit-email-group").removeClass("has-error");
      $('input[name=language]').attr('checked',false);
    },
    success:function(msg){
      var obj = jQuery.parseJSON(msg);
      console.log(obj);
      var language = obj["0"].language.split(",");

      document.getElementById('edit-member-id').value = obj["0"].member_id;
      document.getElementById('edit-display-name').value = obj["0"].display_name;
      document.getElementById('edit-email').value = obj["0"].email;
      document.getElementById('current-email').value = obj["0"].email;
      document.getElementById('type-id-'+obj["0"].member_type).selected = true;
      document.getElementById('status-id-'+obj["0"].status_user).selected = true;

      for (var i = 0; i < language.length; i++) {
        $('#lang-'+language[i]).attr('checked',true);
      }
    }
  });
});

$(document).on('click', '.delete-admin', function(){
  var data = {
    action: "deleteuser",
    id: $(this).data("id")
  };
  $.confirm({
    title: 'Are you sure?',
    content: 'you want to delete this account.',
    theme: 'material',
    icon: 'fa fa-warning',
    type: 'red',
    draggable: false,
    buttons: {
      confirm:  {
        text: 'Yes, delete it!',
        btnClass: 'btn-red',
        action: function(){
          delete_user(data);
        }
      },
      formCancel: {
        text: 'Cancel',
        cancel: function () {}  
      }
    }
  });
});

$("#save-edit-user").on("click",function() {
  validate_edit_user();
});

$("#reset-password").on("click",function() {
  var data = {
    action: "resetpass",
    email: $('#current-email').val()
  };
  $.confirm({
    title: 'Reset password?',
    content: '',
    theme: 'material',
    icon: 'fa fa-warning',
    type: 'red',
    draggable: false,
    buttons: {
      confirm:  {
        text: 'OK',
        btnClass: 'btn-red',
        action: function(){
          reset_password(data);
        }
      },
      formCancel: {
        text: 'Cancel',
        cancel: function () {}  
      }
    }
  });
});

function reset_password(data) {
  var url = "ajax/ajax.agent.php",
      dataSet = data;
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function(data){
      var obj = jQuery.parseJSON(data);
      // console.log(obj);
      if (obj['message'] === "success") {
        $.confirm({
          title: 'Reset password',
          content: 'Password has been reset Please check in register email.',
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
              //  location.reload();
              }
            }
          },
          backgroundDismiss: function(){
            //location.reload();
          }
        });
      }
    }
  });
}

function delete_user(data) {

  var url = "ajax/ajax.agent.php",
      dataSet = data;
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function(data){
      var obj = jQuery.parseJSON(data);
      // console.log(obj);
      if (obj['message'] === "OK") {
        $.confirm({
          title: 'Deleted!',
          content: 'Account was successfully deleted!',
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
                agentTableForm.ajax.reload( null,false);
              }
            }
          },
          backgroundDismiss: function(){
            agentTableForm.ajax.reload( null,false);
          }
        });
      }
    }
  });
}

function validate_edit_user() {
  var userid = $('#edit-member-id'),
      display = $('#edit-display-name'),
      email = $('#edit-email'),
      type = $('#edit-user-type'),
      status = $('#edit-user-status'),
      language = '',

      emailerror = $('.edit-email-error'),
      displayerror = $('.edit-display-error');

  if (display.val().length < 1) {
    displayerror.text("Enter a display name.");
    displayerror.show();
    $("#edit-display-group").addClass("has-error");
    display.focus();
    return false;
  }else {
    displayerror.hide();
    $("#edit-display-group").removeClass("has-error");
  }

  if (email.val().length < 1) {
    emailerror.text("Enter an email.");
    emailerror.show();
    $("#edit-email-group").addClass("has-error");
    email.focus();
    return false;
  }else if ((email.val().indexOf('@') < 0) || (email.val().indexOf('.') < 0)) {
    emailerror.text("Invalid email address.");
    emailerror.show();
    $("#edit-email-group").addClass("has-error");
    email.focus();
    return false;
  }else {
    emailerror.hide();
    $("#edit-email-group").removeClass("has-error");
  }

  for (var i = 0; i < $("input[name=language]:checked").length; i++) {
    language += ","+$("input[name=language]:checked")[i].value;
  }
  if (language) language = language.substring(1);

  var data = {
    action: "edituser",
    id: userid.val(),
    display: display.val(),
    email: email.val(),
    currentEmail: $('#current-email').val(),
    type: type.val(),
    status: status.val(),
    language: language
  };
  // console.log(data);
  edit_user(data)
}

function edit_user(data) {
  var url = "ajax/ajax.agent.php",
      dataSet = data;
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function(data){
      var obj = jQuery.parseJSON(data);
      // console.log(obj);
      if(obj['message'] === "success"){
        

      }else if (obj['message'] === "email_already_exists") {
        $('.edit-email-error').text("This email already exists.");
        $('.edit-email-error').show();
        $("#edit-email-group").addClass("has-error");
        $('#edit-email').focus();

      }else if (obj['message'] === "not_success") {
        $.confirm({
          title: obj['title'],
          content: obj['text'],
          theme: 'material',
          icon: 'fa fa-times-circle',
          type: 'red',
          draggable: false,
          backgroundDismiss: true,
          buttons: {
              confirm:  {
                  text: 'OK',
                  btnClass: 'btn-red',
                  action: function(){
                    $("#modal-admin").modal("hide");
                  }
              }
          },
          backgroundDismiss: function(){
            $("#modal-admin").modal("hide");
          }
        });
      }
    }
  });
}

//set agent form
function  formAgentActionOn(data){ 

	$('.newFormAction').show();
	$('.agentBoxTitle').text(data['title']); 	 
	if(data.type === 'add'){
		$('.btnSaveAgent').addClass('add');
		if( $('.btnSaveAgent').hasClass('edit') ){
			$('.btnSaveAgent').removeClass('edit');			
		}
	}else{
		$('.btnSaveAgent').addClass('edit');
		$('.btnSaveAgent').data('id',data.id);
		if( $('.btnSaveAgent').hasClass('add') ){
				$('.btnSaveAgent').removeClass('add');			
		}
  } 
  
}
 
//open agent form 
$('.agentpage').on('click','.addagentForm',function(){
  $('#sortable').text('');
  switchAddEditAgentBank();
  resetSaleForm();

	data = { 
		type: 'add',
		title: 'เพิ่มตัวแทนจำหน่าย'
	}
	formAgentActionOn(data);
	
});

//get agent data to show
$('.agentpage').on('click','.btnEditAgent',function(){
  $('.blog-preview-add').html('')
 	data = { 
		type: 'edit',
		id: $(this).data('id'),
		title: 'แก้ไขข้อมูลตัวแทน'
  }

	formAgentActionOn(data); 
	getAgentEditData(data);
});


//save edit agent data
$('.agentpage').on('click','.btnSaveAgent.add',function(){ 
   validateAgentForm('addAgent');
   $('.btnEditMoreBank').trigger('click');
});
//save edit agent data
$('.agentpage').on('click','.btnSaveAgent.edit',function(){
  validateAgentForm('editAgent',$(this).data('id'));
  $('.btnEditMoreBank').trigger('click');
});

function validateAgentForm(act,id){

	data = {
		username: $('.txt_username').val().trim(), 
		name: $('.txt_name').val().trim(), 		
		email: $('.txt_email').val().trim(),
    phone: $('.txt_phone').val().trim(),
    facebook: $('.txt_facebook').val().trim(), 
    fbid: $('.fbid').val().trim(), 		
    instagram: $('.txt_instagram').val().trim(), 	
		line: $('.txt_line').val().trim(),
		moreBank: $( "#sortable" ).sortable( "toArray"),
		action: act
  }
  permis= 'YES';
  if(data.username == ''){
      $('.txt_username').css('border-color','red');
      permis = 'NO';
  }else{
    $('.txt_username').css('border-color','#d2d6de');
  }

  if(data.name == ''){
    $('.txt_name').css('border-color','red');
    permis = 'NO';
  }else{
    $('.txt_name').css('border-color','#d2d6de');
  }

  if(data.email == ''){
    $('.txt_email').css('border-color','red');
    permis = 'NO';
  }else{
  $('.txt_email').css('border-color','#d2d6de');
  }

  if(data.phone == ''){
    $('.txt_phone').css('border-color','red');
    permis = 'NO';
  }else{
    $('.txt_phone').css('border-color','#d2d6de');
  }
  
  //conntect to ajax
  if(permis != 'NO'){
    if(act != 'addAgent'){ 
      data.id = id;
      editAgentData(data);
      
    }else{
      getAgentAddData(data);
    }
  }
 
}

 

//close tab agent form  
$('.agentpage').on('click','.agentFormClose',function(){
  $('.newFormAction').hide(); 
  $('.blog-preview-add').html('')
});
//delete Agent data
$('.agentpage').on('click','.btnDeleteAgent ',function(){
 
  checkDelAgent($(this).data('id'))
  
 
});

function checkDelAgent(id){
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
       
          deleteAgentData(id);
          resetSaleForm();
          $('.newFormAction').hide();
          
        }
      },
      formCancel: {
        text: 'Cancel',
        cancel: function () {}  
      }
    }
    });
}


function getAgentAddData(data){
	$.ajax({
		url:'ajax/ajax.agent.php',
		type:'POST',
		dataType:'json', 
		data: data,
		success:  function(msg){
			checkActionAlert(msg['saleman'],'add');
			updateImageThumb(msg['saleman']['insert_id']);
      resetSaleForm();
      // agentTableForm.ajax.reload( null,false);
		}
	}); 
}

function updateImageThumb(id){
 
	if ($('#add-images-Agent-hidden').val().length > 0) {
		if(formdata.getAll("images[]").length !== 0){
			uploadimages(id, "uploadimgAgent");
		}
	}else{
    agentTableForm.ajax.reload( null,false);

	}
}

function checkActionAlert(msg,action){	

  if(action == 'add'){
      title = 'เพิ่มข้อมูลสำเร็จ!';
      content = 'The agent was adding successfully!';
  }
  if(action == 'edit'){
    title = 'แก้ไขข้อมูลสำเร็จ!';
    content = 'The agent was editing successfully!';
}

	if (msg['message']  === "OK") {
		$.confirm({
			title,
			content,
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
					 
					}
				}
			},
			backgroundDismiss: function(){
		 
			}
		}); 

	}
}



function getAgentEditData(data){
	
  data.action = "getAgentEdit";
 
	$.ajax({
		url:'ajax/ajax.agent.php',
		type:'POST',
		dataType:'json',
		data: data,
		success:  function(msg){
        //clear sortable before append (stacking break)
        $('#sortable').text(''); 
        //show edit data
        $('.txt_username').val(msg['saleuser']);
        $('.txt_name').val(msg['salename']);
        $('.txt_email').val(msg['saleemail']);
        $('.txt_phone').val(msg['salephone']);
        $('.txt_facebook').val(msg['facebook']);
        $('.fbid').val(msg['fbid']);
        $('.txt_instagram').val(msg['instagram']);
        $('.txt_line').val(msg['saleline']);
        $('#sortable').append(msg['bank']);
        $('.blog-preview-add').append(msg['saleimage']);
        $('#add-images-Agent-hidden').val(msg['saleimage']);
        $('#preview_img').attr('src',msg['saleimage']);
        $('.titleSort').show();
 
		}

	});
}

function editAgentData(data){
 
	
	$.ajax({
		url:'ajax/ajax.agent.php',
		type:'POST',
		dataType:'json',
		data: data,
		success:  function(msg){
      checkActionAlert(msg['editAgent'],'edit');
			updateImageThumb(msg['id']);
      resetSaleForm();
      $('#sortable').text('');
      $('.newFormAction').hide();
      agentTableForm.ajax.reload( null,false);
      
		}
	});

}
function deleteAgentData(id){
  data= {
    id,
    action: 'delAgent'
  }

	$.ajax({
		url:'ajax/ajax.agent.php',
		type:'POST',
		dataType:'json',
		data: data,
		success:  function(){

      agentTableForm.ajax.reload( null,false);
		}
	});
}
$( function() {
	$( "#sortable" ).sortable();
	$( "#sortable" ).disableSelection();
});

$('.addMoreBank').on('click',function(){
  switchAddEditAgentBank();
  $('.btnAddBank').hide();

	 
	document.getElementById('bankSec').style.display ="block";
  // document.getElementsByClassName('addMoreBank')[0].style.display = "none";
  $('.txt_bankName').val('');
	$('.txt_bankId').val('');
	$("#bankSaleSlc").val(0);
 
});

//agent ADD section
//ปุ่มกดเพิ่มธนาคาร
$('#bankSec').on('click','.btnAddMoreBank',function(){
 
	data = {
		bankType: $('#bankSaleSlc option:selected').data('name'),
		accName: $.trim($('.txt_bankName').val()),
		bankId: $.trim($('.txt_bankId').val()),
	  action: 'addMoreBank',
	}		
	status = 'A';
	
	if( data.accName == ''){ 
		status = 'B';
		$('.txt_bankName').css('border-color','red');
	}else if(data.bankId == ''){
		status = 'B';
		$('.txt_bankId').css('border-color','red');
	}else if(data.bankType == undefined){
		status = 'B';
		$('#bankSaleSlc').css('border-color','red');
	}else{
		status = 'A';
		$('.txt_bankName').css('border-color','#d2d6de');
		$('.txt_bankId').css('border-color','#d2d6de');
		$('#bankSaleSlc').css('border-color','#d2d6de');
	}
 
	if(status != 'B'){
		$.ajax({
			url: 'ajax/ajax.agent.php',
			type:'post',
			dataType: 'json',
			data: data,
			success: function(msg){
				 $('#sortable').append(msg['data']);
				 $('.titleSort').show();
				 $('.titleSort').addClass('active');
				 $('.txt_bankName').val('');
				 $('.txt_bankId').val('');
				 $("#bankSaleSlc").val(0);
			}
		});
	}	

});


$('.btnSaveAgent').on('click',function(){

	updateMoreSaleBank();
});

$('.btnResetAgent').on('click',function(){
  $('#sortable').text('');
	resetSaleForm();
});

function updateMoreSaleBank(){
	var bankAgentSort = $( "#sortable" ).sortable( "toArray" );
}
 
function resetMorebank(){
  $('.txt_bankName').val('');
	$('.txt_bankId').val('');
	$("#bankSaleSlc").val(0);
}

function resetSaleForm(){
	$('.txt_username').val('');
  $('.txt_name').val('');
  $('.txt_instagram').val('');
  $('.txt_facebook').val('');
  $('.fbid').val('');
	$('.txt_email').val('');
	$('.txt_phone').val('');
	$('.txt_line').val('');
	$('.txt_bankName').val('');
	$('.txt_bankId').val('');
	$("#bankSaleSlc").val(0);
	$('#add-images-Agent-hidden').val('');
	formdata.delete('images[]');
	$('#preview_img_1').attr('src','');
	$('.titleSort').hide();

	if($('.titleSort').hasClass('active')){

 		data = {		
			agentBankId: $( "#sortable" ).sortable( "toArray" ),
			action: 'resetAgentForm'
		}
		$.ajax({
			url:'ajax/ajax.agent.php',
			type: 'post',
			dataType: 'json',
			data: data,
			success: function(msg){			
				if(msg['res']['status'] == '200'){
					$('ul.listBankSale').text('');
					$('.titleSort').removeClass('active');
				}	
			
			}
		});
	}


}

/* upload images */
$("#add-images-Agent").uploadImage({
		preview: true	
});

$("#add-images-Agent").on("change", function(){ 
  if(formdata.getAll("images[]").length !== 0){
    var img = formdata.getAll("images[]")["0"].name;
    
    $(".form-add-images").removeClass("has-error");
		$(".add-images-error").css("display","none");
		$('#add-images-Agent-hidden').val(img);	
	}
});


function uploadimages(cateId,action) {
  formdata.append("action", action);
  formdata.append("id", cateId);
  $.ajax({
    url: url_ajax_request + "ajax/ajax.agent.php",
    type: 'POST',
		data: formdata,
		dataType: 'json',
    processData: false,
    contentType: false,
    success: function(msg){
   
      if(msg['message'] === "OK"){
        agentTableForm.ajax.reload( null,false);
        $('.newFormAction').hide();

      }
    }
  });
}

 

// add_content(data);

// function add_content(data) {
//   var url = url_ajax_request + "ajax/ajax.agent.php",
//             dataSet = data;
//   $.ajax({
//     type: "POST",
//     url: url,
//     data: dataSet,
//     success: function(msg){
      
//       var obj = jQuery.parseJSON(msg); 
//       if(obj.data['message'] === "OK"){
//           if ($('#add-images-content-hidden').val().length > 0) {
//             if(formdata.getAll("images[]").length !== 0){
//               uploadimages(obj.id, "uploadimgAgent");
//             }
//           }else{
//             agentTableForm.ajax.reload( null,false);
//           }
//       }else if(obj.data['message'] === "url_already_exists"){
//         validate_add_content(obj.data['message']);
// 			}
			
//     }
//   });
// }
//
$('#bankSec').on('click','.btnCancelMoreBank',function(){
  resetMorebank();
  switchAddEditAgentBank();
  $('#bankSec').hide();
  $('.btnAddBank').hide();
});


//กดเพิ่มเข้าสูโหมด เพิ่มบัญชีธนาคาร
$('.titleSort').on('click','.btnAddBank',function(){
  switchAddEditAgentBank();
  $('.btnAddBank').hide();
  resetMorebank();
  $('.btnEditMoreBank i').text(' เพิ่มบัญชี');
});

//edit more bank
$('#sortable').on('click','.editSaleBank',function(e){
 
  $('.btnAddBank').show();
 
  data = {
    id: $(this).data('id'),
    action: 'getMoreBankEdit'
  } 
  changeBtnAddToEditMoreBank(data.id); 
  $.ajax({
    url: 'ajax/ajax.agent.php',
    type: 'post',
    dataType: 'json',
    data: data,
    success: function(msg){
      if(msg['message'] != 'false'){
          $('#bankSec').show();
          $('.addMoreBank').text('แก้ไขบัญชีธนาคาร');          
          $('.addMoreBank').css('display','block');
          //let's show agent data to edit 
	        $('.txt_bankName').val(msg['name']);
	        $('.txt_bankId').val(msg['id']);
          $("#bankSaleSlc").val(msg['type']);

          $('.btnEditMoreBank i.fa-edit').text(' แก้ไข');
         
      }
    }
  });
  
});

//แก้ไขปุ่ม addmorebank ให้เป็นปุ่ม editmorebank
function changeBtnAddToEditMoreBank(id){
  $('.addMoreBank').addClass('editMoreBank');  
  $('.btnAddMoreBank').addClass('btnEditMoreBank');
  $('.btnAddMoreBank i').addClass('fa-edit');   
  $('.btnAddMoreBank i').removeClass('fa-plus');  
  $('.btnAddMoreBank').removeClass('btnAddMoreBank');  
  $('.btnEditMoreBank').data('id',id);  

}

//แก้ไขปุ่ม editmorebank ให้เป็นปุ่ม  addmorebank
function switchAddEditAgentBank(){
  $('.addMoreBank').removeClass('editMoreBank');  
  $('.btnEditMoreBank').addClass('btnAddMoreBank');  
  $('.btnEditMoreBank').removeClass('btnEditMoreBank');
  $('.btnAddMoreBank i').removeClass('fa-edit');   
  $('.btnAddMoreBank i').addClass('fa-plus');  
  $('.addMoreBank').text('เพิ่มบัญชีธนาคาร');   
  $('.btnAddMoreBank i.fa').text(' เพิ่มบัญชี');
}

//delete more bank  
$('#sortable').on('click','.delSaleBank',function(e){ 
    data = {
      id: $(this).data('id'),
       action: 'delMoreBank'
    }
    $.ajax({
      url: 'ajax/ajax.agent.php',
      type:'post',
      dataType:'json',
      data:data,
      success: function(msg){
     
        if(msg['res']['message'] == "OK"){
          id = '#'+data.id;
          $('#sortable '+id).remove();

         
        }
        if(msg['id'] == $('.btnEditMoreBank').data('id') ){
          resetMorebank();
        }
      }
    });

});

$('#bankSec').on('click','.btnEditMoreBank',function(e){

    data = {
     id: $('.btnEditMoreBank').data('id'),
     name: $('.txt_bankName').val(),
     bankid: $('.txt_bankId').val(),
     type: $("#bankSaleSlc option:selected").data('name'),
     action:'editMorebank'      
    }
    $.ajax({
      url: 'ajax/ajax.agent.php',
      type: 'POST',
      dataType: 'json',
      data: data,
      success: function(msg){

        checkActionbtnEditMoreBank(msg)

      }
    });
});


function checkActionbtnEditMoreBank(msg){	

	if (msg['moreBank']['message']  === "OK") {
		$.confirm({
			title: 'แก้ไขข้อมูลธนาคารสำเร็จ!',
			content: 'The agent bank were successful updated!',
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
            $('.btnAddBank').hide();          
            switchAddEditAgentBank();         
            resetMorebank();
          
					}
				}
			},
			backgroundDismiss: function(){
		 
			}
		}); 

	}
}
 