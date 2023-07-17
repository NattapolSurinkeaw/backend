let bookTable;
$(function () {

    bookTable = $('#book-grid').DataTable({
        "language": { "url": 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/Thai.json' },
        "scrollX": true,
        "pageLength": 10,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "ajax/ajax.car_book.php",
            data: { action: "get_bookList" },
            type: "post"
        },
        "columnDefs": [{
            targets: [0, 3],
            orderable: false,
        }],
        "order": [[1, "desc"]]
    });

  // datepicker
  $('#add-date-book').datepicker({
    format: 'dd/mm/yyyy',
    autoclose: true,
    language: 'th',
    todayHighlight: true
  }).on('changeDate', function (e) {
    $("#form-add-book").validate().element('#add-date-book');
  });


    // datepicker
  $('#edit-date-book').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        language: 'th',
        todayHighlight: true
      }).on('changeDate', function (e) {
        $("#form-add-book").validate().element('#add-date-book');
      });

 
    //คลิกแก้ไขประเภทรถ
    $(document).on('click', '.edit_book', function () {

        let id = $(this).data('id');
        $.ajax({
            type: "POST",
            url: "ajax/ajax.car_book.php",
            dataType: 'json',
            data: {
                action: "get_book",
                id: id
            },
            success: function (response) {
                $('#edit-date-book').val(response._date);
                $('#edit_detail').val(response.detail);
                $('#edit_id_book').val(response.id);
                $('#modal-editcar').modal('toggle');
            }
        });
    });

    //validations add
    $("#form-add-book").validate({
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
    $("#form-edit-book").validate({
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
    $(document).on('click', '#save-add-book', function () {

        if (!$("#form-add-book").valid()) {
            return false;
        }

        let data = {
            action: "add_book",
            date: $('#add-date-book').val(),
            detail: $('#detail_book').val()
        }
        _add(data);
        $('#modal-add-book').modal('toggle');
    });

    $('#modal-add-book').on('hide.bs.modal', function (e) {
        if (e.namespace == 'bs.modal') {
            $('#form-add-book').trigger("reset");
        }
      }); 

    //คลิกบันทึกแก้ไขประเภทรถ
    $(document).on('click', '#save-edit-car', function () {
       
        if (!$("#form-edit-book").valid()) {
            return false;
        }

        let data = $('#form-edit-book').serializeArray();
        _edit(data);
        $('#modal-editcar').modal('toggle');
    });

    $('#modal-editcar').on('hide.bs.modal', function (e) {
        if (e.namespace == 'bs.modal') {
            $('#form-edit-book').trigger("reset");
        }
      }); 

    $(document).on('click', '.delete_book', function () {
        var data = {
            action: "delete_book",
            id: $(this).data("id")
        };
        $.confirm({
            title: 'ยืนยัน?',
            content: 'คุณต้องการลบวันรับรถใช่หรือไม่?',
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
        url: "ajax/ajax.car_book.php?v=3",
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
                                bookTable.ajax.reload(null, false);
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

    var url = "ajax/ajax.car_book.php",
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
                                bookTable.ajax.reload(null, false);
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
        url: "ajax/ajax.car_book.php",
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
                                bookTable.ajax.reload(null, false);
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

