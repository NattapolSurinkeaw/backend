let carTable;
$(function () {

    carTable = $('#cars-grid').DataTable({
        "language": { "url": 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/Thai.json' },
        "scrollX": true,
        "pageLength": 10,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "ajax/ajax.car_price.php",
            data: { action: "get_carList" },
            type: "post"
        },
        "columnDefs": [{
            targets: [0, 6],
            orderable: false,
        }],
        "order": [[5, "desc"]]
    });


    // ลบข้อมูล car type
    $(document).on('click', '._clickable', function () {
        let checkbox = $(this).find('.checkbox-car-color');
        if ($(checkbox).hasClass('active')) {
            $(checkbox).prop('checked', false);
        } else {
            $(checkbox).prop('checked', true);
        }
        $(checkbox).toggleClass('active');
        $(this).toggleClass('active');
    });


    //คลิกแก้ไขประเภทรถ
    $(document).on('click', '.edit_car', function () {

        let id = $(this).data('id');
        $.ajax({
            type: "POST",
            url: "ajax/ajax.car_price.php",
            dataType: 'json',
            data: {
                action: "get_car",
                id: id
            },
            success: function (response) {
                $('#edit_car_type').val(response.car_type_id);
                $('#edit_car_brand').val(response.car_brand_id);
                $('#edit_car_detail').val(response.car_model);
                $('#edit_car_price').val(response.car_model_price);
                $('#edit_car_id').val(response.car_model_id);
                $('#edit_car_status').val(response.car_modal_status);

                $('#modal-editcar').modal('toggle');
            }
        });
    });

    //validations add
    $("#form-add-car").validate({
        invalidHandler: function (form, validator) {
            $(validator.errorList[0].element).focus();
        },
        errorElement: "label",
        ignore: ".ignore",
        rules: {
            'color': {
                required: true
            }
        },
        errorPlacement: function (error, element) {
            let name = element.attr('name');
            $('.' + name + '-error').show();
        },
        highlight: function (element, errorClass, validClass) {
            $(element).closest(".form-group").addClass("has-error");
        },
        unhighlight: function (element, errorClass, validClass) {
            $('.' + $(element).attr('name') + '-error').hide();
            $(element).closest(".form-group").removeClass("has-error");
        }
    });
        //validations edit
        $("#form-edit-car").validate({
            invalidHandler: function (form, validator) {
                $(validator.errorList[0].element).focus();
            },
            errorElement: "label",
            ignore: ".ignore",
            rules: {
                'color': {
                    required: true
                }
            },
            errorPlacement: function (error, element) {
                let name = element.attr('name');
                $('.' + name + '-error').show();
            },
            highlight: function (element, errorClass, validClass) {
                $(element).closest(".form-group").addClass("has-error");
            },
            unhighlight: function (element, errorClass, validClass) {
                $('.' + $(element).attr('name') + '-error').hide();
                $(element).closest(".form-group").removeClass("has-error");
            }
        });

    //คลิกบันทึกประเภทรถ
    $(document).on('click', '#save-add-car', function () {

        if (!$("#form-add-car").valid()) {
            return false;
        }

        let data = {
            action: "add_car",
            cartype: $('#car_type').val(),
            car_brand: $('#car_brand').val(),
            car_detail: $('#car_detail').val(),
            car_price: $('#car_price1').val()
        }
        _add(data);
        $('#modal-add-carprice').modal('toggle');
    });

    $('#modal-add-carprice').on('hide.bs.modal', function (e) {
        if (e.namespace == 'bs.modal') {
            $('#form-add-car').trigger("reset");
        }
      }); 

    //คลิกบันทึกแก้ไขประเภทรถ
    $(document).on('click', '#save-edit-car', function () {
       
        if (!$("#form-edit-car").valid()) {
            return false;
        }

        let data = $('#form-edit-car').serializeArray();
        _edit(data);
        $('#modal-editcar').modal('toggle');
    });

    $('#modal-editcar').on('hide.bs.modal', function (e) {
        if (e.namespace == 'bs.modal') {
            $('#form-edit-car').trigger("reset");
        }
      }); 

    $(document).on('click', '.delete-car', function () {
        var data = {
            action: "delete_car",
            id: $(this).data("id")
        };
        $.confirm({
            title: 'ยืนยัน?',
            content: 'คุณต้องการลบข้อมูลรถใช่หรือไม่?',
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

});

//เพิ่มข้อมูล
function _add(dataSet) {
    $.ajax({
        type: "POST",
        url: "ajax/ajax.car_price.php?v=3",
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
                                carTable.ajax.reload(null, false);
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

    var url = "ajax/ajax.car_price.php",
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
                                carTable.ajax.reload(null, false);
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
        url: "ajax/ajax.car_price.php",
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
                                carTable.ajax.reload(null, false);
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

