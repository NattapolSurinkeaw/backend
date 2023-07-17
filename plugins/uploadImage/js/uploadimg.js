/*****************************************
 * jquery script upload/preview images
 ****************************************/
var formdata = false;
if(window.FormData){
   formdata = new FormData();
   formdata.append('numpreviewimg', 0);
}

var defaults = {
    preview: false,
    resize: false,
    multiple: false,
    width: '',
    height: ''
};

$.fn.uploadImage = function (options) {
    var elements = $(this);

    defaults = $.extend({}, defaults, options);

    // event change file images
    elements.change(function () {
        fnUploadImg.change_file_image(elements);
    });
}

var fnUploadImg = {
    change_file_image: function(elements){ // change file image
        var files = elements[0].files,
            filesLength = files.length,
            previewlength = $(".col-img-preview").length+1,
            preview = "."+elements.data('preview');

        for (var i = 0; i < filesLength; i++){
            var file = files[i],
                numImg = parseInt(i+1);

            if(file != null){
                if(!!file.type.match(/image.*/)){
                    if(formdata){
                        var img_index = parseInt(formdata.get('numpreviewimg'))+1;
                        if(defaults['multiple'] == true){
                            formdata.append("images"+img_index, file);
                        }else{
                            formdata.delete("images"+img_index);
                            formdata.append("images"+img_index, file);
                        }

          							if(defaults['preview'] == true){
          								fnUploadImg.load_blog_previewimg(img_index,preview);
          								fnUploadImg.load_preview_img(file, img_index);
          							}
                    }
                }
            }
        }

        var numpreviewimg = formdata.get("numpreviewimg")+1;
        formdata.delete("images[]");
        for(var i = 1; i <= numpreviewimg; i++){
            if(formdata.get("images" + i) !== null){
                formdata.append("images[]", formdata.get("images" + i));
            }
        }
    },
    load_blog_previewimg: function(img_index,preview){
        if(defaults['multiple'] == true){
            $(preview).append(
                '<div class="col-img-preview" id="col_img_preview_'+img_index+'" data-id="'+img_index+'">\
                    <img class="preview-img" id="preview_img_'+img_index+'"/>\
                    <a href="javascript:;" class="fa fa-trash" id="img_remove_'+img_index+'" data-id="'+img_index+'"></a>\
                </div>'
            );
        }
        else{
            $(preview).html(
                '<div class="col-img-preview" id="col_img_preview_1" data-id="1">\
                    <img class="preview-img" id="preview_img_1"/>\
                    <a href="javascript:;" class="fa fa-trash" id="img_remove_1" data-id="1"></a>\
                </div>'
            );
        }
    },
    load_preview_img: function(file, img_index){ // load preview image
        if(defaults['multiple'] == true){
            var output = $("#preview_img_"+img_index)[0];
            output.src = URL.createObjectURL(file);
            formdata.set('numpreviewimg', parseInt(formdata.get('numpreviewimg'))+1);
        }
        else{
            var output = $("#preview_img_1")[0];
            output.src = URL.createObjectURL(file);
        }
    }
}


/*****************************************
 * jquery event
 ****************************************/
// mouseover image preview
$(document).on('mouseover', '.col-img-preview', function(){
    var elementId = $(this).data('id');
    $("#preview_img_"+elementId).addClass("img-hover");
    $("#img_remove_"+elementId).show();
});

// mouseout image preview
$(document).on('mouseout', '.col-img-preview', function(){
    var elementId = $(this).data('id');
    $("#preview_img_"+elementId).removeClass("img-hover");
    $("#img_remove_"+elementId).hide();
});

// remove image preview
$(document).on('click', '.col-img-preview a', function(){
    var elementId = $(this).data('id');
    $("#col_img_preview_"+elementId).remove();
    formdata.delete("images"+elementId);

    formdata.delete("images[]");
    for(var i = 1; i <= formdata.get("numpreviewimg"); i++){
        if(formdata.get("images" + i) !== null){
            formdata.append("images[]", formdata.get("images" + i));
        }
    }
});

// mouseover image show
$(document).on('mouseover', '.col-img-show', function(){
    var elementId = $(this).data('id');
    $("#show_img_"+elementId).addClass("img-hover");
    $("#img_show_remove_"+elementId).show();
});

// mouseout image show
$(document).on('mouseout', '.col-img-show', function(){
    var elementId = $(this).data('id');
    $("#show_img_"+elementId).removeClass("img-hover");
    $("#img_show_remove_"+elementId).hide();
});
