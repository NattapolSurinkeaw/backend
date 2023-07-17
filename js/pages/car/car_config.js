
$(function () {

    $('.disableEnter').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) { 
          e.preventDefault();
          return false;
        }
      });
      
    // ลบข้อมูล car type
    $(document).on('click', '.delete_car_type', function () {
        var data = {
            action: "delete_car_type",
            id: $(this).data("id")
        };
        $.confirm({
            title: 'ยืนยัน?',
            content: 'คุณต้องการลบประเภทรถใช่หรือไม่?',
            theme: 'material',
            icon: 'fa fa-warning',
            type: 'red',
            draggable: false,
            buttons: {
                confirm: {
                    btnClass: 'btn-red',
                    text: 'ยืนยัน',
                    action: function () {
                        _delete(data);
                    }
                },
                formCancel: {
                    text: 'ยกเลิก'
                }
            }
        });
    });


    //คลิกแก้ไขประเภทรถ
    $(document).on('click', '.edit_car_type', function () {

        let car_typeId = $(this).data('id');
        $.ajax({
            type: "POST",
            url: "ajax/ajax.car_config.php",
            dataType: 'json',
            data: {
                action: "get_cartype",
                id: car_typeId
            },
            success: function (response) {
                $('#edit-cartype').val(response.car_type);
                $('#edit-cartype-id').val(response.car_type_id);
                $('#modal-edit-cartype').modal('toggle');

            }
        });
    });

    //คลิกบันทึกประเภทรถ
    $(document).on('click', '#save-add-cartype', function () {
        let data = {
            action: "add_cartype",
            cartype: $('#add-cartype').val()
        }
        _add(data);
    });

    //คลิกบันทึกแก้ไขประเภทรถ
    $(document).on('click', '#save-edit-cartype', function () {
        let dataSet = {
            action: "update_cartype",
            cartype: $('#edit-cartype').val(),
            id: $('#edit-cartype-id').val(),

        }
        _edit(dataSet);
    });


    //============ CAR BRAND ==================

    // ลบข้อมูล car brand
    $(document).on('click', '.delete_car_brand', function () {
        var data = {
            action: "delete_carbrand",
            id: $(this).data("id")
        };
        $.confirm({
            title: 'ยืนยัน?',
            content: 'คุณต้องการลบยี่ห้อรถใช่หรือไม่?',
            theme: 'material',
            icon: 'fa fa-warning',
            type: 'red',
            draggable: false,
            buttons: {
                confirm: {
                    btnClass: 'btn-red',
                    text: 'ยืนยัน',
                    action: function () {
                        _delete(data);
                    }
                },
                formCancel: {
                    text: 'ยกเลิก'
                }
            }
        });
    });

    //คลิกบันทึกยี่ห้อรถยนต์
    $(document).on('click', '#save-add-brandtype', function () {
        let data = {
            action: "add_carbrand",
            car_brand: $('#add-brandtype').val(),
            car_brand_link: $('#add-website').val(),
        }
        _add(data);
    });

    //คลิกแก้ไขยี่ห้อรถ
    $(document).on('click', '.edit_car_brand', function () {
        let id = $(this).data('id');
        $.ajax({
            type: "POST",
            url: "ajax/ajax.car_config.php",
            dataType: 'json',
            data: {
                action: "get_carbrand",
                id: id
            },
            success: function (response) {
                $('#edit-brandtype-id').val(response.car_brand_id);
                $('#edit-brandtype').val(response.car_brand);
                $('#edit-website').val(response.car_brand_link);
                $('#modal-edit-brandtype').modal('toggle');
            }
        });
    });

    //คลิกบันทึกแก้ไขยี่ห้อรถ
    $(document).on('click', '#save-edit-brandtype', function () {
        let dataSet = {
            action: "update_carbrand",
            car_brand: $('#edit-brandtype').val(),
            car_brand_link: $('#edit-website').val(),
            id:$('#edit-brandtype-id').val(),

        }
        _edit(dataSet);
    });


    //============ CAR COLOR ==================

    // ลบข้อมูล car brand
    $(document).on('click', '.delete_car_color', function () {
        var data = {
            action: "delete_color",
            id: $(this).data("id")
        };
        $.confirm({
            title: 'ยืนยัน?',
            content: 'คุณต้องการลบสีรถยนต์ใช่หรือไม่?',
            theme: 'material',
            icon: 'fa fa-warning',
            type: 'red',
            draggable: false,
            buttons: {
                confirm: {
                    btnClass: 'btn-red',
                    text: 'ยืนยัน',
                    action: function () {
                        _delete(data);
                    }
                },
                formCancel: {
                    text: 'ยกเลิก'
                }
            }
        });
    });

    //คลิกบันทึกยี่ห้อรถยนต์
    $(document).on('click', '#save-add-color', function () {
        let data = {
            action: "add_color",
            color: $('#add-color').val()
        }
        _add(data);
    });

    //คลิกแก้ไขยี่ห้อรถ
    $(document).on('click', '.edit_car_color', function () {
        let id = $(this).data('id');
        $.ajax({
            type: "POST",
            url: "ajax/ajax.car_config.php",
            dataType: 'json',
            data: {
                action: "get_color",
                id: id
            },
            success: function (response) {
                $('#edit-color-id').val(response.car_color_id);
                $('#edit-color').val(response.car_color);
                $('#modal-edit-color').modal('toggle');
            }
        });
    });

    //คลิกบันทึกแก้ไขยี่ห้อรถ
    $(document).on('click', '#save-edit-color', function () {
        let dataSet = {
            action: "update_color",
            color: $('#edit-color').val(),
            id:$('#edit-color-id').val()
        }
        _edit(dataSet);
    });
    
});

//เพิ่มข้อมูล
function _add(dataSet) {
    $.ajax({
        type: "POST",
        url: "ajax/ajax.car_config.php?v=3",
        dataType: 'json',
        data: dataSet,
        success: function (response) {
            if (response.status == '200') {
                $.confirm({
                    title: 'สำเร็จ',
                    content: 'เพิ่มข้อมูลเรียบร้อยแล้ว',
                    theme: 'modern',
                    icon: 'fa fa-check',
                    type: 'darkgreen',
                    draggable: false,
                    backgroundDismiss: true,
                    buttons: {
                        confirm: {
                            text: 'ตกลง',
                            btnClass: 'btn-darkgreen',
                            action: function () {
                                location.reload();
                            }
                        }
                    },
                    backgroundDismiss: function () {
                        location.reload();
                    }
                });

            }
        }
    });
}

//ลบข้อมูล
function _delete(data) {

    var url = "ajax/ajax.car_config.php",
        dataSet = data;
    $.ajax({
        type: "POST",
        url: url,
        data: dataSet,
        dataType: 'json',
        success: function (response) {
            if (response.message === "OK") {
                $.confirm({
                    title: 'สำเร็จ',
                    content: 'ข้อมูลถูกลบแล้ว',
                    theme: 'modern',
                    icon: 'fa fa-check',
                    type: 'darkgreen',
                    draggable: false,
                    backgroundDismiss: true,
                    buttons: {
                        confirm: {
                            text: 'ตกลง',
                            btnClass: 'btn-darkgreen',
                            action: function () {
                                location.reload();
                            }
                        }
                    },
                    backgroundDismiss: function () {
                        location.reload();
                    }
                });
            }
        }
    });
}

//แก้ไขข้อมูล
function _edit(dataSet) {
    $.ajax({
        type: "POST",
        url: "ajax/ajax.car_config.php",
        dataType: 'json',
        data: dataSet,
        success: function (response) {
            if (response.status == '200') {
                $.confirm({
                    title: 'สำเร็จ',
                    content: 'ข้อมูลถูกแก้ไขแล้ว',
                    theme: 'modern',
                    icon: 'fa fa-check',
                    type: 'darkgreen',
                    draggable: false,
                    backgroundDismiss: true,
                    buttons: {
                        confirm: {
                            text: 'ตกลง',
                            btnClass: 'btn-darkgreen',
                            action: function () {
                                location.reload();
                            }
                        }
                    },
                    backgroundDismiss: function () {
                        location.reload();
                    }
                });

            }
        }
    });
}

