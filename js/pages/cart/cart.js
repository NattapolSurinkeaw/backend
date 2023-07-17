let OrderGeneralTable;
let OrderHospitalTable;

$(function () {
    //ตาราง ตะกร้าสินค้า (สมาชิกทั่วไป)
    OrderGeneralTable = $('#cart-general-grid').DataTable({
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "ajax/ajax.cart.php",
            data: function(d){
                d.action = "get_order_general",
                d.selectOrder = $('#selectOrderType option:selected').val()
            },
            type: "post",
            error: function () {
             
            }
        },
        "columnDefs": [{
            targets: [0,2,3,4,7,8],
            orderable: false,
        }],
        "order": [[1, "asc"]],  
        "pageLength": 50,
    });

    //ตาราง ตะกร้าสินค้า (Hospital)
    OrderHospitalTable = $('#cart-hospital-grid').DataTable({
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "ajax/ajax.cart.php",
            data: function(d){
                d.action = "get_order_hospital",
                d.selectOrder = $('#selectOrderTypeH option:selected').val()
            },
            type: "post",
            error: function () {
             
            }
        },
        "columnDefs": [{
            targets: [0,2,3,4,7,8],
            orderable: false,
        }],
        "order": [[1, "asc"]],  
        "pageLength": 50,
    });

});

$('#selectOrderType').on('change',function(e){
    reloadTable()
})
$('#selectOrderTypeH').on('change',function(e){
    reloadTableH()
})

function showOrderGeneral(e,_orderID){
    $('.btn-editDataOrderGeneral').hide();
    $.ajax({
        url:"ajax/ajax.cart.php",
        type:"post",
        dataType:"json",
        data:{action:"getOrderGeneralByOrderId",order_id:_orderID},
        success:function(data){
            console.log(data)
            
            $('#modal-general-date').text(`รายการประจำวันที่ ${data.resTotal.create_date}`);
            $('#modal-general-order_id').text(`OrderID:  ${data.resTotal.order_id}`);
            $('#modal-general-member_name').text(`ชื่อ ${data.resTotal.member_name}`);
            $('#model-orderGeneral-show-body').html(`${data.resDetail}`);
            $('#model-orderGeneral-show').show();

            $('#generalPrint').attr('href',data.href)
            reloadTable()
        }
    })
}
function showOrderHospital(e,_orderID){

    $.ajax({
        url:"ajax/ajax.cart.php",
        type:"post",
        dataType:"json",
        data:{action:"getOrderHospitalByOrderId",order_id:_orderID},
        success:function(data){
            console.log(data)
            
            $('#modal-hospital-date').text(`รายการประจำวันที่ ${data.resTotal.create_date}`);
            $('#modal-hospital-order_id').text(`OrderID:  ${data.resTotal.order_id}`);
            $('#modal-hospital-member_name').text(`ชื่อ ${data.resTotal.member_name}`);
            $('#model-orderHospital-show-body').html(`${data.resDetail}`);
            $('#model-orderHospital-show').show();

            
            $('#HospitalPrint').attr('href',data.href)
            reloadTableH()
        }
    })
}
//เมื่อกดปุ่มแก้ไข 
function editOrderGeneral(e,_orderID){
    $('.btn-editDataOrderGeneral').show();
    $.ajax({
        url:"ajax/ajax.cart.php",
        type:"post",
        dataType:"json",
        data:{action:"getOrderGeneralByOrderId",order_id:_orderID,option:"edit"},
        success:function(data){
            console.log(data)
            
            $('#modal-general-date').text(`รายการประจำวันที่ ${data.resTotal.create_date}`);
            $('#modal-general-order_id').text(`OrderID:  ${data.resTotal.order_id}`);
            $('#modal-general-member_name').text(`ชื่อ ${data.resTotal.member_name}`);
            $('#model-orderGeneral-show-body').html(`${data.resDetail}`);
            $('#model-orderGeneral-show').show();
            $('.btn-editDataOrderGeneral').data('orderid',data.resTotal.order_id);

            
            reloadTable()
            
        }
    })
}

//เมื่อกดปุ่มแก้ไข
function editOrderHospital(e,_orderID){
    $('.btn-editDataOrderHospital').show();
    $.ajax({
        url:"ajax/ajax.cart.php",
        type:"post",
        dataType:"json",
        data:{action:"getOrderHospitalByOrderId",order_id:_orderID,option:"edit"},
        success:function(data){
            console.log(data)
            
            $('#modal-hospital-date').text(`รายการประจำวันที่ ${data.resTotal.create_date}`);
            $('#modal-hospital-order_id').text(`OrderID:  ${data.resTotal.order_id}`);
            $('#modal-hospital-member_name').text(`ชื่อ ${data.resTotal.member_name}`);
            $('#model-orderHospital-show-body').html(`${data.resDetail}`);
            $('#model-orderHospital-show').show();
            reloadTableH()
        }
    })
}

function delOrderGeneral(e,_orderID){

    $.confirm({
        title: 'แจ้งเตือน',
        content: 'ยืนยันการลบ Order '+_orderID+' คุณจะไม่สามารถกู้คืนได้',
        theme: 'modern',
        icon: 'fa fa-warning',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: 'ตกลง',
                btnClass: 'btn-green',
                action: function () {
                    $.ajax({
                        url:"ajax/ajax.cart.php",
                        type:"post",
                        dataType:"json",
                        data:{action:"delOrderGeneralByOrderId",order_id:_orderID},
                        success:function(data){
                            console.log(data)
                
                            if(data.message == "OK"){
                                $.confirm({
                                    title: 'เรียบร้อย',
                                    content: 'ลบ Order '+_orderID+' เรียบร้อย',
                                    theme: 'modern',
                                    icon: 'fa fa-trash',
                                    type: 'green',
                                    typeAnimated: true,
                                    buttons: {
                                        confirm: {
                                            text: 'ตกลง',
                                            btnClass: 'btn-green',
                                            action: function () {
                                                reloadTable()
                                            }
                                        },
                                    }
                                });
                            }
                        }
                    })
                }
            },
            formCancel: {
                text: 'ยกเลิก',
                btnClass: 'btn-red',
                cancel: function () { }
            }
        }
    });
    
}
function delOrderHospital(e,_orderID){

    $.confirm({
        title: 'แจ้งเตือน',
        content: 'ยืนยันการลบ Order '+_orderID+' คุณจะไม่สามารถกู้คืนได้',
        theme: 'modern',
        icon: 'fa fa-warning',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: 'ตกลง',
                btnClass: 'btn-green',
                action: function () {
                    $.ajax({
                        url:"ajax/ajax.cart.php",
                        type:"post",
                        dataType:"json",
                        data:{action:"delOrderHospitalByOrderId",order_id:_orderID},
                        success:function(data){
                            console.log(data)
                
                            if(data.message == "OK"){
                                $.confirm({
                                    title: 'เรียบร้อย',
                                    content: 'ลบ Order '+_orderID+' เรียบร้อย',
                                    theme: 'modern',
                                    icon: 'fa fa-trash',
                                    type: 'green',
                                    typeAnimated: true,
                                    buttons: {
                                        confirm: {
                                            text: 'ตกลง',
                                            btnClass: 'btn-green',
                                            action: function () {
                                                reloadTableH();
                                            }
                                        },
                                    }
                                });
                            }
                        }
                    })
                }
            },
            formCancel: {
                text: 'ยกเลิก',
                btnClass: 'btn-red',
                cancel: function () { }
            }
        }
    });
    
}


function closeModal(e){
    e = e || window.event;
    e.preventDefault();
    $('#model-orderGeneral-show').hide();
    $('#model-orderHospital-show').hide();

    $('.btn-editDataOrderGeneral').hide();
}

function reloadTable() {
    OrderGeneralTable.ajax.reload(null, false);
}
function reloadTableH() {
    OrderHospitalTable.ajax.reload(null, false);
}

function PopupCenter(pageURL, title,w,h) {
    var left = (screen.width/2)-(w/2);
    var top = (screen.height/2)-(h/2);
    var targetWin = window.open (pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
    return targetWin;
} 

// select option order status general
function changeOrderStatusGeneral(e){
    e = e || window.event;
    e.preventDefault();
    let _this = e.target;
    let value = _this[_this.selectedIndex].value;
    
    if(value == 0){
        e.target.style.color = 'black';        
    }else if (value == 1){
        e.target.style.color = 'mediumseagreen';        
    }else if (value == 2){
        e.target.style.color = 'red';        
    }
}

// save order status general
function editDataOrderGeneral(e){
    e = e || window.event;
    e.preventDefault();

    let Datalen = $('.orderStatusGeneral').length;
    let dataStatus = [];
    let dataID   = [];

    if(Datalen >= 1){
        let data = $('.orderStatusGeneral option:selected')
        let data_id = $('.orderStatusGeneral')
        for(let i=0;i<Datalen;i++){ 
            dataStatus.push(data[i].value) 
            dataID.push(data_id[i].getAttribute('data-id')) 
        }
        
    }
    
    let _data = {
        action: "editOrderStatusGeneral",
        id : dataID,
        status : dataStatus
    }

    $.ajax({
        url:"ajax/ajax.cart.php",
        type:"post",
        dataType:"json",
        data:_data,
        success:function(data){
            console.log(data)
            $('#model-orderGeneral-show').hide();
            $.confirm({
                title: 'เรียบร้อย',
                content: 'แก้ไขข้อมูลเรียบร้อย',
                theme: 'modern',
                icon: 'fa fa-check',
                type: 'green',
                typeAnimated: true,
                buttons: {
                    confirm: {
                        text: 'ตกลง',
                        btnClass: 'btn-green',
                        action: function () {
                            reloadTable()
                        }
                    },
                }
            });
        }
    })
}

// select option order status hospital ยังไม่เสร็จ
function changeOrderStatusHospital(e){
    e = e || window.event;
    e.preventDefault();
    let _this = e.target;
    let value = _this[_this.selectedIndex].value;
    
    if(value == 0){
        e.target.style.color = 'black';        
    }else if (value == 1){
        e.target.style.color = 'mediumseagreen';        
    }else if (value == 2){
        e.target.style.color = 'red';        
    }
}

function editDataOrderHospital(e){
    e = e || window.event;
    e.preventDefault();

    e = e || window.event;
    e.preventDefault();

    let Datalen = $('.orderStatusHospital').length;
    let dataStatus = [];
    let dataID   = [];

    if(Datalen >= 1){
        let data = $('.orderStatusHospital option:selected')
        let data_id = $('.orderStatusHospital')
        for(let i=0;i<Datalen;i++){ 
            dataStatus.push(data[i].value) 
            dataID.push(data_id[i].getAttribute('data-id')) 
        }
        
    }
    
    let _data = {
        action: "editOrderStatusHospital",
        id : dataID,
        status : dataStatus
    }

    $.ajax({
        url:"ajax/ajax.cart.php",
        type:"post",
        dataType:"json",
        data:_data,
        success:function(data){
            console.log(data)
            $('#model-orderHospital-show').hide();
            $.confirm({
                title: 'เรียบร้อย',
                content: 'แก้ไขข้อมูลเรียบร้อย',
                theme: 'modern',
                icon: 'fa fa-check',
                type: 'green',
                typeAnimated: true,
                buttons: {
                    confirm: {
                        text: 'ตกลง',
                        btnClass: 'btn-green',
                        action: function () {
                            reloadTableH()
                        }
                    },
                }
            });
        }
    })
}