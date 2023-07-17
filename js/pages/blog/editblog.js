// เริมต้นทำงาน jquery หน้าแก้ไข content
//ใส่ event คลิกปุ้มแก้ไข content
var start_initEdit = false;
var checkAlertEdit;
$(document).ready(function() {
    $(".edit-content").on("click", load_edit);
});

/* @initEdit  ฟังก์ชั่นเตียมข้อมูลสำหรับแก้ไขโดยจะทำงานครั้งแรกที่คลิกปุ่ม edit  */
function initEdit() {

    $('#blog-category-tree').on('changed.jstree', function(evt, data) {
        if (data.action == 'deselect_node') {
            if (data.selected.length <= 0) {
                $('.box-content-cate-edit').addClass("error");
            }
        } else {
            $('.box-content-cate-edit').removeClass("error");
        }
    });

    //ใส่ event ปุ้มบันทึก edit ให้ทำการเรียกใช้ฟังก์ชั่น validate_edit_content
    $("#save-edit").on("click", function() {

        checkAlertEdit = 0;
        $('#edit-category').val($('#blog-category-tree').jstree('get_selected').join());

        $('#imgmoreId-edit').val($(".id_imgmore-edit").map(function() {
            return $(this).data("id");
        }).get());

        if ($("#form-edit-content").valid()) {
            var url = url_ajax_request + "ajax/ajax.blog.php";
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                data: new FormData($('#form-edit-content')[0]),
                contentType: false,
                processData: false,
                success: function(obj) {

                    if (obj.data.message === "OK") {

                        location.reload();

                        // if ($('#edit-images-content-hidden').val().length > 0) {
                        //     if (formdata.getAll("images[]").length !== 0) {
                        //         uploadimages(obj.id, "uploadimgcontent");
                        //     }
                        // } else {
                        //     location.reload();
                        // }

                    } else {

                        $.confirm({
                            title: '',
                            content: LANG_LABEL.urlisuse,
                            type: 'red',
                            typeAnimated: true,
                            buttons: {
                                tryAgain: {
                                    text: LANG_LABEL.close,
                                    btnClass: 'btn-red'
                                }
                            }
                        });

                    }
                }
            });
        }

    });

    //ใส่ event ปุ้ม reset หน้า edit เพื่อเคลียร์ค่าที่กรอกไว้ในฟอร์มออก
    $("#reset-edit").on("click", function() {
        resetFormEdit();
    });


    $("#imgInp-left-edit").change(function() {
        readURL(this, 'blah-left-edit');
    });

    $("#imgInp-right-edit").change(function() {
        readURL(this, 'blah-right-edit');
    });



    //validation form add
    $.validator.messages.required = LANG_LABEL.input_warning_title;
    $("#form-edit-content").validate({
        focusInvalid: false,
        onfocusout: false,
        invalidHandler: function(form, validator) {
            if (!validator.numberOfInvalids()) return;
            $('#scrollbar-add').animate({
                scrollTop: $(validator.errorList[0].element).offset().top - 200
            }, 2000);
            $(validator.errorList[0].element).focus();
        },
        ignore: ".ignore",
        rules: {
            'edit_content': {
                required: function() {
                    CKEDITOR.instances.edit_content.updateElement();
                },
                minlength: 10
            },
            'edit-slug': {
                required: true,
                remote: {
                    url: 'ajax/ajax.php',
                    type: "post",
                    async: false,
                    data: {
                        action: 'checkUrl',
                        old_slug: function() {
                            return $('#current-url').val()
                        },
                        'slug': function() {
                            $('#edit-slug').val($('#edit-slug').val().replace(/[^a-zA-Z0-9ก-๙_-]/g, '-'));
                            return $('#edit-slug').val();
                        }
                    }
                }
            }
        },
        messages: {
            'add-images-content-hidden': {
                required: LANG_LABEL.selectimage
            }, //เลือกรูป
            'edit-slug': {
                remote: LANG_LABEL.urlisuse // url ถูกใช้งานแล้ว
            },
            'edit-category': {
                required: LANG_LABEL.selectcategory
            }
        },
        errorPlacement: function(error, element) {
            if (checkAlertEdit == 0) {
                $.confirm({
                    title: '',
                    content: error.text(), //แสดงข้อความเตือน
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: LANG_LABEL.close,
                            btnClass: 'btn-red'
                        }
                    }
                });
            }
            checkAlertEdit = 1;
        },
        highlight: function(element, errorClass, validClass) {
            if ($(element).hasClass('edit-category')) {
                $('.box-content-cate-edit').addClass("error");
            } else {
                $(element).closest(".form-group").addClass("has-error");
            }
        },
        unhighlight: function(element, errorClass, validClass) {
            if ($(element).hasClass('edit-category')) {
                $('.box-content-cate-edit').removeClass("error");
            } else {
                $(element).closest(".form-group").removeClass("has-error");
            }
        }
    });

    //โหลดไรบลารีสำหรับ ckeditor
    // editor content
    CKEDITOR.replace('edit_content', {
        filebrowserUploadUrl: "/backend/plugins/ckeditor/filemanager/connectors/php/upload.php?Type=File",
        filebrowserImageUploadUrl: "/backend/plugins/ckeditor/filemanager/connectors/php/upload.php?Type=Image",
        filebrowserFlashUploadUrl: "/backend/plugins/ckeditor/filemanager/connectors/php/upload.php?Type=Flash",
        height: 400,
        language: backend_language
    });

    //โหลดฟังก์ชั่นอัพโหลดรูป
    $("#edit-images-content").uploadImage({
        preview: true
    });


    //ใส่ event เมื่อมีการเปลี่ยนรูป
    $("#edit-images-content").on("change", function() {
        if (formdata.getAll("images[]").length !== 0) {
            var img = formdata.getAll("images[]")["0"].name;
            $('#edit-images-content-hidden').val(img);
            $(".form-edit-images").removeClass("has-error");
            $(".edit-images-error").css("display", "none");
        }
    });

    //สั่ง event แสดงโพสเซสอัพรูปที่หลายละหลายรูป และสั่งให้ฟังก์ชั่นอัพโหลดทำงาน
    $('#prog-edit').progressbar({
        value: 0
    });

    $("#edit-more-images").on("change", function(event) {
        files = event.target.files;
        var data = new FormData();
        data.delete("images[]");
        $.each(files, function(key, value) {
            data.append("images[]", ("images" + (key + 1), value));
        });

        data.append('action', 'uploadmoreimgcontent');
        data.append('id', $('#edit-content-id').val());
        $.ajax({
            url: "ajax/ajax.blog.php",
            type: "POST",
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function(event) {
                $('#prog-edit').progressbar({
                    value: 0
                });
                $('#overlay-edit-more-img').css('display', 'block');
                // console.log(event);
            },
            progress: function(e) {
                if (e.lengthComputable) {
                    var pct = (e.loaded / e.total) * 100;
                    // console.log(pct);
                    $('#prog-edit')
                        .progressbar('option', 'value', pct)
                        .children('.ui-progressbar-value')
                        .html(pct.toPrecision(3) + '%');
                } else {
                    // console.warn('Content Length not reported!');
                }
            },
            success: function(msg) {
                var obj = jQuery.parseJSON(msg),
                    img_list = '';
                //not fix 
                for (i = 0; i < obj.length; i++) {
                    img_list += '\
                    <div class="blog-show-image">\
                        <div class="iconimg id_imgmore-edit" id="img-delete" data-id="' + obj[i].image_id + '" data-name="' + obj[i].image_link + '">\
                        <i class="fa fa-times" alt="delete"></i>\
                        </div>\
                        <div id="image-preview">\
                        <div class="col-img-preview">\
                            <img class="preview-img" src="' + root_url + obj[i].image_link + '">\
                        </div>\
                        </div>\
                    </div>';
                }
                $("#show-img-more").append(img_list);
            },
            complete: function() {
                $('#prog-edit').progressbar({
                    value: 0
                });
                $('#overlay-edit-more-img').css('display', 'none');
            }
        });
    });


    // datepicker
    $('#date-display').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        language: 'th',
        todayHighlight: true
    }).on('changeDate', function(e) {
        $('#date-display-hidden').val(e.format('yyyy-mm-dd'));
    });

    //timepicker
    $("#time-display").timepicker({
        defaultTime: false,
        showInputs: false,
        minuteStep: 1,
        showMeridian: false
    });

    $('#modalEditContent').on('hide.bs.modal', function(e) {
        if (e.namespace == 'bs.modal') {
            resetFormEdit();

            /*
            var myDiv = document.getElementById('scrollbar-edit');
            myDiv.scrollTop = 0;

            deleteImageDraft();
            $(".form-edit-title").removeClass("has-error");
            $(".edit-title-error").css("display", "none");

            $(".form-edit-description").removeClass("has-error");
            $(".edit-description-error").css("display", "none");

            $(".form-edit-slug").removeClass("has-error");
            $(".edit-slug-error").css("display", "none");

            $('#show-video').html('');*/
        }
    });


    $(document).on('click', '#img-delete', function() {
        var id = $(this).data("id"),
            filename = $(this).data("name"),
            postId = $("#edit-content-id").val(),
            that = $(this);
        $.ajax({
            type: "POST",
            url: "ajax/ajax.blog.php",
            data: {
                action: "deleteimagecontent",
                id: id,
                filename: filename,
                postId: postId
            },
            beforeSend: function() {},
            success: function(msg) {
                //remove by this
                $(that).closest('.blog-show-image').remove();
            }
        });
    });


    //ฟังก์ชั่นสำหรับค้นหา Tag ในหน้า Edit
    $("#edit-search-tag").on("keyup", function() {
        if ($(this).val().length >= 1) {
            searchTag($(this).val());
        } else {
            document.getElementById('searchtagresult').innerHTML = '';
        }
    });

    $("#edit-add-tag").on("keyup", function(event) {
        if (event.keyCode == 13) {
            if ($(this).val().length >= 1) {
                addTagEdit($(this).val());
                document.getElementById('edit-add-tag').value = '';
            }
        }
    });

    $(document).on('click', '.sent-tag', function() {
        var data = {
            id: $(this).data("id"),
            text: $(this).data("text")
        }
        var resource = [];
        $('.checkbox-tag :input').each(function() {
            resource.push({
                id: $(this).val()
            });
        });
        // console.log(resource);
        for (var i = 0; i < resource.length; i++) {
            if (resource[i].id == data.id) {
                $(".checkbox-tag :input[value='" + data.id + "']").prop('checked', true);
                return false;
            }
        }
        sendtagtobox(data);
    });
}
// จบฟังก์ชั่น initEdit


// $("#edit-video").on("change", function(){ 
//   console.log($(this).val());
// });

// $("#edit-video").on("keyup", function(){
//   console.log($(this).val());
//   $('#show-video').html('\
//     <div class="box box-tag">\
//       <div class="box-body">\
//         <div class="form-group" style="margin: 0 auto; width: 400px;">\
//           <div class="videoWrapper">\
//             <iframe width="560" height="349" src="https://www.youtube.com/embed/'+$(this).val()+'" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>\
//           </div>\
//         </div>\
//       </div>\
//     </div>');
// }); 


function deleteImageDraft() {
    $.ajax({
        type: "POST",
        url: "ajax/ajax.blog.php",
        data: {
            action: "deleteimagedraft"
        }
    });
}

var contentCk = '';

function load_edit() {

    //สั่งโหลดข้อมูลเตรียมพร้อมสำหรับแก้ไข 
    //จะเช็ดก่อนว่ามีการสั่ง initEdit หรือยัง
    if (!start_initEdit) {
        initEdit();
        start_initEdit = true;
    }

    // ดึงค่าจากปุ่มถ้า content ภาษานั้นได้เพิ่มแล้ว  type จะเป็น edit แต่ถ้ายังไม่เพิ่ม type จะเป็น add
    var contentId = $(this).data("id"),
        submitType = $(this).data("type");
    console.log(submitType);
    $('#prog').progressbar({
        value: 0
    });

    $.ajax({
        type: "POST",
        url: "ajax/ajax.blog.php",
        dataType: 'json',
        data: {
            action: "getcontent",
            id: contentId
        },
        beforeSend: function() {
            resetFormEdit();
        },
        success: function(obj) {

            var tag_list = '',
                img_list = '';
            //เลือกค่าเดิม category 
            $.jstree.reference('#blog-category-tree').select_node(obj.category.split(','));

            $('#blah-left-edit').attr('src', root_url + obj.thumbnail);
            $('#blah-right-edit').attr('src', root_url + obj.thumbnail2);

            $('#submit-type').val(submitType);
            //content ถูกเพิ่ม default แล้วแต่จะเพิ่มภาษาอื่น
            if (submitType == 'add') {
                console.log(obj.date_created);
                $('#edit-images-content-hidden').val(obj.thumbnail);
                $('#date-created').val(obj.date_created);
            }

            $('#edit-content-id').val(obj.id);
            $('#edit-title').val(obj.title);
            $('#edit-keyword').val(obj.keyword);
            $('#edit-description').val(obj.description);
            $('#edit-slug').val(obj.slug);
            $('#current-url').val(obj.slug);

            $('#edit-tech-status').val(obj.status_type)

            $('#edit-h1').val(obj.h1);
            $('#edit-h2').val(obj.h2);

            $('#edit-freetag').val(obj.freetag);
            $('#edit-h1').val(obj.h1);
            $('#edit-h2').val(obj.h2);

            $('#edit-video').val(obj.video);
            $('#edit-topic').val(obj.topic);
            $('#edit-priority').val(obj.priority);
            // if (obj.vdo) {
            //   if (obj.vdo['type'] == "youtube") {
            //     $('#show-video').html(obj.vdo['embed']);
            //   } else if (obj.vdo['type'] == "facebook") {
            //     $('#show-video').html(obj.vdo['embed']);
            //     FB.XFBML.parse(document.getElementById('show-video'));
            //   }
            // }

            contentCk = obj.content;

            document.getElementById('edit-display-' + obj.display).selected = true;
            document.getElementById('edit-pin-' + obj.pin).selected = true;

            if (obj.tag != '') {
                document.getElementById('edit-blog-tag').style.display = 'block';
                for (var i = 0; i < obj.tag.length; i++) {
                    tag_list += '\
            <div class="checkbox checkbox-tag">\
              <label>\
                <input type="checkbox" name="edit-tag[]" value="' + obj.tag[i].tag_id + '"  checked>\
                ' + obj.tag[i].tag_name + '\
              </label>\
            </div>';
                }
                $('#edit-blog-tag').html(tag_list);
            }

            if (typeof obj.images !== 'undefined') {
                for (i = 0; i < obj.images.length; i++) {
                    img_list += '\
            <div class="blog-show-image">\
              <div class="iconimg id_imgmore-edit" id="img-delete" data-id="' + obj.images[i].image_id + '" data-name="' + obj.images[i].image_link + '">\
                <i class="fa fa-times" alt="delete"></i>\
              </div>\
              <div id="image-preview">\
                <div class="col-img-preview">\
                  <img class="preview-img" \
                  src="' + site_url + 'classes/thumb-generator/thumb.php?src=' + root_url + obj.images[i].image_link + '&size=150x150">\
                </div>\
              </div>\
            </div>';
                }
            }
            $("#show-img-more").html(img_list);

            if (!isNaN(new Date(obj.date_display).getTime())) {
                $('#date-display').datepicker('setDate', new Date(obj.date_display));
                $('#time-display').val(formatTime(new Date(obj.date_display)));
            }
        },
        complete: function(msg) {
            $('#modalEditContent').modal('toggle');
            setTimeout(function() {
                CKEDITOR.instances['edit_content'].setData(contentCk, submitaftersetdata);
            }, 1000);
        }
    });
}

function submitaftersetdata() {
    this.updateElement();
    console.log("updated");
}

function searchTag(key) {
    $.ajax({
        type: "POST",
        url: "ajax/ajax.blog.php",
        data: {
            action: "searchtag",
            key: key
        },
        beforeSend: function() {

        },
        success: function(msg) {
            if (msg) {
                var obj = jQuery.parseJSON(msg);
                var doc = "";
                // console.log(obj);
                for (var i = 0; i < obj.length; i++) {
                    doc += '<div class="sent-tag" data-id="' + obj[i].tag_id + '" data-text="' + obj[i].tag_name + '">' + obj[i].tag_name + '</div>';
                }
                document.getElementById('searchtagresult').innerHTML = doc;
            }
        }
    });
}

function addTagEdit(key) {
    $.ajax({
        type: "POST",
        url: "ajax/ajax.blog.php",
        data: {
            action: "addtag",
            key: key
        },
        success: function(msg) {
            var obj = jQuery.parseJSON(msg);
            // console.log(obj);
            if (obj.data != "exist") {
                var data = {
                    id: obj.data.insert_id,
                    text: key
                }
                sendtagtobox(data);
            } else {
                $.confirm({
                    title: window.location.hostname + ' says :',
                    content: 'This tag is already exist.',
                    theme: 'my-theme',
                    icon: 'fa fa-warning',
                    type: 'darkgreen',
                    draggable: false,
                    backgroundDismiss: true,
                    buttons: {
                        confirm: {
                            text: 'OK',
                            btnClass: 'btn-darkgreen',
                        }
                    }
                });
            }
        }
    });
}

function sendtagtobox(data) {
    document.getElementById('edit-blog-tag').style.display = 'block';
    document.getElementById('edit-blog-tag').innerHTML += '\
  <div class="checkbox checkbox-tag">\
    <label>\
      <input type="checkbox" name="edit-tag[]" value="' + data.id + '"  checked>\
      ' + data.text + '\
    </label>\
  </div>';
}

//Time
function formatDate(date) {
    var day = (date.getDate() < 10 ? '0' : '') + date.getDate();
    var monthIndex = ("0" + (date.getMonth() + 1)).slice(-2);
    var year = date.getFullYear();
    var hours = (date.getHours() < 10 ? '0' : '') + date.getHours();
    var minutes = (date.getMinutes() < 10 ? '0' : '') + date.getMinutes();
    date.get
    return year + '-' + monthIndex + '-' + day;
}

function formatTime(date) {
    var hours = (date.getHours() < 10 ? '0' : '') + date.getHours();
    var minutes = (date.getMinutes() < 10 ? '0' : '') + date.getMinutes();
    date.get
    return hours + ':' + minutes;
}


function uploadimages(id, action) {
    formdata.append("action", action);
    formdata.append("id", id);
    $.ajax({
        url: url_ajax_request + "ajax/ajax.blog.php",
        type: 'POST',
        data: formdata,
        processData: false,
        contentType: false,
        success: function(obj) {
            location.reload();
        },
        beforeSend: function() {
            console.log('Load Start')
            $('.wrapper-pop').addClass('pop-active');
        },
        complete: function() {
            console.log('Load End')
            $('.wrapper-pop').removeClass('pop-active');
        },
        xhr: function() {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function(evt) {
                if (evt.lengthComputable) {
                    var pct = (evt.loaded / evt.total) * 100;
                    console.log('p1 => ' + pct.toPrecision(3))
                    $('.loadper').text(`${parseInt(pct)} %`)
                }
            }, false);

            xhr.addEventListener("progress", function(evt) {
                if (evt.lengthComputable) {
                    var pct = (evt.loaded / evt.total) * 100;
                    console.log('p2 => ' + pct.toPrecision(3))
                }
            }, false);

            return xhr;
        }
    });
}

function resetFormEdit() {
    $('#blog-category-tree').jstree("deselect_all");
    document.getElementById("form-edit-content").reset();
    CKEDITOR.instances['edit_content'].setData('<span></span>');
    $("#date-display").datepicker('setDate', '');
    document.getElementById('searchtagresult').innerHTML = '';
    document.getElementById('edit-blog-tag').innerHTML = '';
    document.getElementById('edit-blog-tag').style.display = 'none';
}