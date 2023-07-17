$(function () {
    console.log('aboutus_video')
    getAboutusVideo()
})

function getAboutusVideo() {
    $.ajax({
        url: "ajax/ajax.aboutus.php",
        type: "post",
        dataType: "json",
        data: { action: "getAboutusVideo" },
        success: function (data) {
            // console.log(data)
            for (let i in data.result) {

                $('#video-' + i).val(data.result[i]['video'])
            }
        }
    })
}

/**
 * เล่น วีดีโอ
 * @param {*} e event
 * @param {*} _number เลขลำดับ
 */
function previewVideo(e, _number) {
    e = e || window.event;
    e.preventDefault();

    try{
        let video = $('#video-' + _number).val().trim();
        let s = video.split("/")
        $('#previewVideo').html(`
            <iframe style="width:100%;height:350px;" 
                    src="https://www.youtube.com/embed/${s[3]}" 
                    frameborder="0" 
                    allow="accelerometer; 
                    autoplay="1"; 
                    encrypted-media; 
                    gyroscope; 
                    picture-in-picture" 
                    allowfullscreen>
            </iframe>
        `);
    }catch(err){
        $.confirm({
            title: 'แจ้งเตือน',
            content: 'ไม่สามารถแสดง วีดีโอได้',
            theme: 'modern',
            icon: 'fa fa-times',
            type: 'red',
            typeAnimated: true,
            buttons: {
              tryAgain: {
                text: 'ตกลง',
                btnClass: 'btn-red',
                action: function () {
                    // getAboutusVideo()
                }
              }
            }
          });
    }
}

/**
 * บันทึก วีดีโอ
 * @param {*} e event
 * @param {*} _number เลขลำดับ
 */
function saveVideo(e, _number) {
    e = e || window.event;
    e.preventDefault();

    let video = $('#video-' + _number).val().trim();

    if (video.length < 1) {
        console.log('Show Error กรุณาใส่ข้อมูล')
        return false;
    }


    $.ajax({
        url: "ajax/ajax.aboutus.php",
        type: "post",
        dataType: "json",
        data: { action: "saveAboutusVideo", video, number: _number },
        success: function (data) {
            if(data.result.message == "OK"){
                $.confirm({
                    title: 'สำเร็จ',
                    content: 'บันทึกวีดีโอสำเร็จ',
                    theme: 'modern',
                    icon: 'fa fa-check',
                    type: 'green',
                    typeAnimated: true,
                    buttons: {
                      tryAgain: {
                        text: 'ตกลง',
                        btnClass: 'btn-green',
                        action: function () {
                            getAboutusVideo()
                        }
                      }
                    }
                  });
            }else if(data.result.message == "ERR"){
                $.confirm({
                    title: 'แจ้งเตือน',
                    content: 'ลิ้งวีดีโอไม่ถูกต้อง ไม่สามารถบันทึกได้',
                    theme: 'modern',
                    icon: 'fa fa-times',
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                      tryAgain: {
                        text: 'ตกลง',
                        btnClass: 'btn-red',
                        action: function () {
                            // getAboutusVideo()
                        }
                      }
                    }
                  });
            }
        }
    })
}

/**
 * ลบ วีดีโอ
 * @param {*} e event
 * @param {*} _number เลขลำดับ
 */
function delVideo(e, _number) {
    e = e || window.event;
    e.preventDefault();

    let video = $('#video-' + _number).val().trim();

    if (video.length < 1) {
        console.log('sssssss');
        return false;
    }

    $.ajax({
        url: "ajax/ajax.aboutus.php",
        type: "post",
        dataType: "json",
        data: { action: "delAboutusVideo", number: _number },
        success: function (data) {
            if(data.result.message == "OK"){
                $.confirm({
                    title: 'สำเร็จ',
                    content: 'ลบวีดีโอสำเร็จ',
                    theme: 'modern',
                    icon: 'fa fa-check',
                    type: 'green',
                    typeAnimated: true,
                    buttons: {
                      tryAgain: {
                        text: 'ตกลง',
                        btnClass: 'btn-green',
                        action: function () {
                            getAboutusVideo()
                        }
                      }
                    }
                  });
            }

        }
    })
}
