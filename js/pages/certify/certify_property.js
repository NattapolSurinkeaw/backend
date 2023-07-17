
var cp_id = 0;
var elementActive = "";
var elementSelect = "";


function clearProperty() {
    let color = 'blue';
    $("#nameShow").css('color', color);
    $("#titleShow").css('color', color);
    $("#scoreShow").css('color', color);
    $("#dayShow").css('color', color);
    $("#monthShow").css('color', color);
    $("#yearShow").css('color', color);
    $("#image1").removeClass('border-2 border-blue-500')
    $("#image2").removeClass('border-2 border-blue-500')
}

function setProperty(_name, _size, _weight, _y, _x) {
    $('#nameInput').val(_name);
    $('#fontsizeInput').val(parseInt(_size));
    $('#fontweightInput').val(parseInt(_weight));
    $('#YInput').val(parseInt(_y));
    $('#XInput').val(parseInt(_x));
}

function removePropDisabled(){
    $('#nameInput').prop('disabled', false);
    $('#fontsizeInput').prop('disabled', false);
    $('#fontweightInput').prop('disabled', false);
    $('#YInput').prop('disabled', false);
    $('#XInput').prop('disabled', false);
}

function changeWeightToHeight_Image(_type){
    if(_type == 'image'){
        $('#fontweightInput').prop('min',false);
        $('#fontweightInput').prop('max',false);
        $('#fontweightInput').prop('step',false);
        $('#weightText').text('Height');
        $('#sizeText').text('Width');
        $('#weightExten').text('px');
    }else{
        $('#fontweightInput').prop('min',true);
        $('#fontweightInput').attr('min',100);
        $('#fontweightInput').prop('max',true);
        $('#fontweightInput').attr('max','900');
        $('#fontweightInput').prop('step',true);
        $('#fontweightInput').attr('step','100');
        $('#weightText').text('Weight');
        $('#sizeText').text('Size');
        $('#weightExten').text('(100-900)');
    }
}

function setData(_this, _type, _title) {
    clearProperty()

    if(_type == "image1" || _type == "image2"){
        setProperty(_title, _this.css('width'), _this.css('height'), _this.css('top'), _this.css('left'))
        changeWeightToHeight_Image('image')
    }else{
        setProperty(_this.text().trim(), _this.css('fontSize'), _this.css('fontWeight'), _this.css('top'), _this.css('left'))
        changeWeightToHeight_Image('no')
    }
    
    $('#cp_id').val((_this.data('id') == undefined) ? 0 : _this.data('id'));
    $('#cp_type').val(_type);
    $('#settingPropertyName').text(_title);
    $('#btnSave').removeClass('cursor-not-allowed opacity-50');

    _this.css('color', 'red');
    elementActive = _this.attr('id');
    elementSelect = $('#' + elementActive)[0];


    removePropDisabled(); //ลบ Prop disabled
}


$(function () {
    clearProperty()
    document.querySelector('#statusSaveWrapper').style.setProperty('top','50px','important');
    setTimeout(() => {
        document.querySelector('#statusSaveWrapper').style.setProperty('top','-150px','important');
    }, 5000);

    $("#nameShow").draggable({
        drag: function (e) {
            setData($(this), "name", "ชื่อ");
            saveTempPropertyTest()
        }
    });
    $("#titleShow").draggable({
        drag: function (e) {
            setData($(this), "title", "หัวข้อ");
            saveTempPropertyTest()
        }
    });
    $("#scoreShow").draggable({
        drag: function (e) {
            setData($(this), "score", "คะแนน");
            saveTempPropertyTest()
        }
    });
    $("#dayShow").draggable({
        drag: function (e) {
            setData($(this), "day", "วันที่");
            saveTempPropertyTest()
        }
    });
    $("#monthShow").draggable({
        drag: function (e) {
            setData($(this), "month", "เดือน");
            saveTempPropertyTest()
        }
    });
    $("#yearShow").draggable({
        drag: function (e) {
            setData($(this), "year", "ปี");
            saveTempPropertyTest()
        }
    });
    $("#image1").draggable({
        drag: function (e) {
            setData($(this), "image1", "รูปที่2");
            $(this).addClass('border-2 border-blue-500')
            saveTempPropertyTest()
            // clearProperty()
            // $(this).addClass('border-2 border-blue-500')
            // let data = {
            //     action: "saveCertifyPropertyImage",
            //     y: parseInt($(this).css('top')),
            //     x: parseInt($(this).css('left')),
            //     cp_id: $(this).data('id'),

            // }
            // $.ajax({
            //     url: "ajax/ajax.certify.php",
            //     type: "post",
            //     dataType: "json",
            //     data: data,
            //     success: function (data) {
            //         console.log(data)

            //     }
            // });
        }
    });
    $("#image2").draggable({
        drag: function (e) {
            setData($(this), "image2", "รูปที่2");
            $(this).addClass('border-2 border-blue-500')
            saveTempPropertyTest()

            // clearProperty()
            // $(this).addClass('border-2 border-blue-500')
            // let data = {
            //     action: "saveCertifyPropertyImage",
            //     y: parseInt($(this).css('top')),
            //     x: parseInt($(this).css('left')),
            //     cp_id: $(this).data('id'),

            // }
            // $.ajax({
            //     url: "ajax/ajax.certify.php",
            //     type: "post",
            //     dataType: "json",
            //     data: data,
            //     success: function (data) {
            //         console.log(data)

            //     }
            // });
        }
    });

    

    $("#nameShow").click(function () { setData($(this), "name", "ชื่อ"); });
    $("#titleShow").click(function () { setData($(this), "title", "หัวข้อ"); });
    $("#scoreShow").click(function () { setData($(this), "score", "คะแนน"); });
    $("#dayShow").click(function () { setData($(this), "day", "วันที่"); });
    $("#monthShow").click(function () { setData($(this), "month", "เดือน"); });
    $("#yearShow").click(function () { setData($(this), "year", "ปี"); });
    $("#image1").click(function(){
        setData($(this), "image1", "รูปที่1");
        $(this).addClass('border-2 border-blue-500')
    });
    $("#image2").click(function(){
        setData($(this), "image2", "รูปที่2");
        $(this).addClass('border-2 border-blue-500')
    });
    
});




//#=========================== Upload Image Zone

$('#formUploadImg').on('submit', function (e) {
    e.preventDefault();
    let formData = new FormData($(this)[0]);
    formData.append("action", "uploadImgCert")
    $.ajax({
        url: "ajax/ajax.certify.php",
        type: "post",
        dataType: "json",
        data: formData,
        processData: false, //Not to process data
        contentType: false, //Not to set contentType
        beforeSend: function () {
            console.log('Load Start')
            $('.wrapper-pop').addClass('pop-active');
        },
        success: function (data) {
            console.log(data);
            if (data.message == "OK") {

                $.confirm({
                    title: 'สำเร็จ',
                    content: 'อัพโหลดรูปภาพสำเร็จ',
                    theme: 'modern',
                    icon: 'fa fa-check',
                    type: 'green',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'ตกลง',
                            btnClass: 'btn-green',
                            action: function () {
                                location.reload();
                            }
                        }
                    }
                });
            }

        },
        xhr: function () {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function (evt) {
                if (evt.lengthComputable) {
                    var pct = (evt.loaded / evt.total) * 100;
                    console.log('p1 => ' + pct.toPrecision(3))
                    $('.loadper').text(`${parseInt(pct)} %`)
                }
            }, false);

            xhr.addEventListener("progress", function (evt) {
                if (evt.lengthComputable) {
                    var pct = (evt.loaded / evt.total) * 100;
                    console.log('p2 => ' + pct.toPrecision(3))
                }
            }, false);

            return xhr;
        },
        complete: function () {
            console.log('Load End')
            $('.wrapper-pop').removeClass('pop-active');

        }
    });
})

$('#img-handle-upload-image').on('click', function (e) {
    e.preventDefault();
    $('#inputFileImg').click();
})

$('#inputFileImg').on('change', function (e) {
    $('.showFileNameImg').text($(this).val())
    readURL('#img-handle-upload-image', this);
});



$('#formUploadImg2').on('submit', function (e) {
    e.preventDefault();
    let formData = new FormData($(this)[0]);
    formData.append("action", "uploadImgCert")
    $.ajax({
        url: "ajax/ajax.certify.php",
        type: "post",
        dataType: "json",
        data: formData,
        processData: false, //Not to process data
        contentType: false, //Not to set contentType
        beforeSend: function () {
            console.log('Load Start')
            $('.wrapper-pop').addClass('pop-active');
        },
        success: function (data) {
            console.log(data);
            if (data.message == "OK") {

                $.confirm({
                    title: 'สำเร็จ',
                    content: 'อัพโหลดรูปภาพสำเร็จ',
                    theme: 'modern',
                    icon: 'fa fa-check',
                    type: 'green',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'ตกลง',
                            btnClass: 'btn-green',
                            action: function () {
                                location.reload();
                            }
                        }
                    }
                });
            }

        },
        xhr: function () {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function (evt) {
                if (evt.lengthComputable) {
                    var pct = (evt.loaded / evt.total) * 100;
                    console.log('p1 => ' + pct.toPrecision(3))
                    $('.loadper').text(`${parseInt(pct)} %`)
                }
            }, false);

            xhr.addEventListener("progress", function (evt) {
                if (evt.lengthComputable) {
                    var pct = (evt.loaded / evt.total) * 100;
                    console.log('p2 => ' + pct.toPrecision(3))
                }
            }, false);

            return xhr;
        },
        complete: function () {
            console.log('Load End')
            $('.wrapper-pop').removeClass('pop-active');

        }
    });
})

$('#img-handle-upload-image2').on('click', function (e) {
    e.preventDefault();
    $('#inputFileImg2').click();
})

$('#inputFileImg2').on('change', function (e) {
    $('.showFileNameImg2').text($(this).val())
    readURL('#img-handle-upload-image2', this);
});

//อ่านไฟล์รูปภาพ แบบ Preview
function readURL(_name, input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        console.log('xx')
        reader.onload = function (e) {
            $(_name).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}


let nameInput = document.querySelector('#nameInput');
let fontsizeInput = document.querySelector('#fontsizeInput');
let fontweightInput = document.querySelector('#fontweightInput');
let YInput = document.querySelector('#YInput');
let XInput = document.querySelector('#XInput');


nameInput.onkeyup = (e) => {
    elementSelect.textContent = e.target.value;
}

fontsizeInput.onchange = (e) => {
    if(elementSelect.getAttribute('id') == "image1" || elementSelect.getAttribute('id') == "image2"){
        elementSelect.style.width = e.target.value + 'px'
    }else{
        elementSelect.style.fontSize = e.target.value + 'px'
    }
    saveTempPropertyTest()
}
fontsizeInput.onkeyup = (e) => {
    if(elementSelect.getAttribute('id') == "image1" || elementSelect.getAttribute('id') == "image2"){
        elementSelect.style.width = e.target.value + 'px'
    }else{
        elementSelect.style.fontSize = e.target.value + 'px'
    }
    if(e.keyCode == 13 || e.which == 13){
        saveTempPropertyTest()
    }
}

fontweightInput.onchange = (e) => {
    if(elementSelect.getAttribute('id') == "image1" || elementSelect.getAttribute('id') == "image2"){
        elementSelect.style.height = e.target.value + 'px'
    }else{
        elementSelect.style.fontWeight = e.target.value;
    }
    saveTempPropertyTest()
}
fontweightInput.onkeyup = (e) => {
    if(elementSelect.getAttribute('id') == "image1" || elementSelect.getAttribute('id') == "image2"){
        elementSelect.style.height = e.target.value + 'px'
    }else{
        elementSelect.style.fontWeight = e.target.value;
    }
    if(e.keyCode == 13 || e.which == 13){
        saveTempPropertyTest()
    }
}

YInput.onchange = (e) => {
    elementSelect.style.top = e.target.value + "px";
    saveTempPropertyTest()
}
YInput.onkeyup = (e) => {
    elementSelect.style.top = e.target.value + "px";
    if(e.keyCode == 13 || e.which == 13){
        saveTempPropertyTest()
    }
}

XInput.onchange = (e) => {
    elementSelect.style.left = e.target.value + "px";
    saveTempPropertyTest()
}
XInput.onkeyup = (e) => {
    elementSelect.style.left = e.target.value + "px";
    if(e.keyCode == 13 || e.which == 13){
        saveTempPropertyTest()
    }
}



function saveCertifyProperty(e) {
    e = e || window.event;
    e.preventDefault();

    if ($('#cp_id').val() == '' || $('#cp_type').val() == '') {
        console.log('empty')
        console.log($('#cp_id').val())
        console.log($('#cp_type').val())
        return false;
    }


    let data = {
        name: $('#nameInput').val(),
        size: $('#fontsizeInput').val(),
        weight: $('#fontweightInput').val(),
        y: $('#YInput').val(),
        x: $('#XInput').val(),
        action: "saveCertifyProperty",
        ct_id: $('#ct_id').val(),
        cp_id: $('#cp_id').val(),
        cp_type: $('#cp_type').val(),
        status: $('#status').val(),
    }


    $.ajax({
        url: "ajax/ajax.certify.php",
        type: "post",
        dataType: "json",
        data: data,
        success: function (data) {
            console.log(data)

            if (data.message == "OK") {
                $.confirm({
                    title: 'สำเร็จ',
                    content: 'บันทึกข้อมูลสำเร็จ',
                    theme: 'modern',
                    icon: 'fa fa-check',
                    type: 'green',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'ตกลง',
                            btnClass: 'btn-green',
                            action: function () {
                                location.reload();
                            }
                        }
                    }
                });
            }
        }
    })

}


function saveTempPropertyTest(){
    let data = {
        name: $('#nameInput').val(),
        size: $('#fontsizeInput').val(),
        weight: $('#fontweightInput').val(),
        y: $('#YInput').val(),
        x: $('#XInput').val(),
        action: "saveTempPropertyTest",
        ct_id: $('#ct_id').val(),
        cp_id: $('#cp_id').val(),
        cp_type: $('#cp_type').val(),
        status: $('#status').val(),
    }


    $.ajax({
        url: "ajax/ajax.certify.php",
        type: "post",
        dataType: "json",
        data: data,
        success: function (data) {
            console.log(data)
        }
    })
}