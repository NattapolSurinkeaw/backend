function view_network(){
  $.ajax({
    type: 'POST',
    url: 'ajax/ajax.manage_network.php',
    dataType: 'json',
    success:function(data){
      // console.log(data)

      data.forEach(datas => {
        $('#boxber-network #table-network tbody').append(`
                                                      <tr>
                                                        <td>${datas.network_id}</td>
                                                        <td>${datas.network_name}</td>
                                                        <td class="text-center"><img src="https://berhoro.com/${datas.thumbnail}" style="width:100px;height:50px;object-fit: contain;"></td>
                                                        <td class="text-center">
                                                          <button class="btn btn-warning" data-toggle="modal" data-target="#modal-network-edit" onclick="edit(${datas.network_id})">แก้ไข</button>
                                                          
                                                        </td>
                                                      </tr>
                                                  `)
      });
    }
  })
}

function network_insert(){
  let file = $('form[name="add-network"] input[name="add_file_network"]').prop("files")[0]
  let name = $('form[name="add-network"] input[name="add_name_network"]').val()
  let action = "add"

  form_data = new FormData()
  form_data.append('images[]', file)
  form_data.append('name', name)
  form_data.append('action', action)

  // console.log(form_data.get('action'))

  $.ajax({
    type: 'POST',
    url: 'ajax/ajax.manage_network.php',
    data:form_data,
    cache: false,
    contentType: false,
    processData: false,
    success:function(data){
      if(data != "false"){
        location.reload()
      }
      else{
        console.log("error")
      }
    },
  })
}

function network_delete(id){
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-success',
      cancelButton: 'btn btn-danger'
    },
    buttonsStyling: false
  })
  
  swalWithBootstrapButtons.fire({
    title: 'ต้องการลบหรือไม่?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'ยืนยันลบ',
    cancelButtonText: 'ยกเลิก',
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      let data = {
        "id":id,
        "action":"delete"
      }
    
      $.ajax({
        type: 'POST',
        url: 'ajax/ajax.manage_network.php',
        data:data,
        dataType: 'json',
        success:function(data){
          // console.log(data)
          location.reload()
        },
      })
    }
  })
}

function edit(id){
  setTimeout(() => {
    const data = {
      "id":id,
      "action":"edit"
    }

    $.ajax({
      type: 'POST',
      url: 'ajax/ajax.manage_network.php',
      data:data,
      dataType: 'json',
      success:function(data){
        // console.log(data[0])
        $('input[name="network_id"]').val(data[0].network_id)
        $('input[name="edit_name_network"]').val(data[0].network_name)
        $('#preview_pic_network').attr("src",`https://berhoro.com/${data[0].thumbnail}`)
        $('#network_status').val(data[0].display)

        // console.log($('#network_status').val())
        if($('#network_status').val() == "no"){
          document.querySelector('.toggle-switch').classList.remove('ts-active')
        }
        else if($('#network_status').val() == "yes"){
          document.querySelector('.toggle-switch').classList.add('ts-active')
        }
      }
    })
  }, 100);
}

function network_update(){
  // console.log(status)

  let file = $('form[name="edit-network"] input[name="edit_file_network"]').prop("files")[0]
  let name = $('form[name="edit-network"] input[name="edit_name_network"]').val()
  let status = $('#network_status').val()
  let id = $('form[name="edit-network"] input[name="network_id"]').val()
  let action = "update"

  form_data = new FormData()
  form_data.append('images[]', file)
  form_data.append('id', id)
  form_data.append('name', name)
  form_data.append('status', status)
  form_data.append('action', action)

  $.ajax({
    type: 'POST',
    url: 'ajax/ajax.manage_network.php',
    data:form_data,
    cache: false,
    contentType: false,
    processData: false,
    success:function(data){
      if(data != "false"){
        location.reload()
      }
      else{
        console.log("error")
      }
    },
  })
}

////////////////////////////
view_network()


$('.toggle-switch.inTables').on('click',function(){
  // console.log(document.querySelector('.toggle-switch.inTables').classList.contains('ts-active'))
  if(document.querySelector('.toggle-switch.inTables').classList.contains('ts-active')){
    $('#network_status').val('yes')
  }
  else{
    $('#network_status').val('no')
  }
})