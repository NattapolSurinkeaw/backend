$(function() {
    //checkStatusOrder();
    // checkStatusOrderCrash();
    // setInterval((e) => {
    //     checkStatusOrder();
    //     checkStatusOrderCrash();
    // },5000)

});

/**
 * check Status Order จะแจ้งเตือนถ้ามี Order เข้ามา
 */
function checkStatusOrder() {
    $.ajax({
        url: url_ajax_request + "ajax/ajax.cart.php",
        type: "post",
        dataType: "json",
        data: { action: "checkStatusOrder" },
        success: function(data) {
            console.log('checkStatusOrder')
            showStatus('#statusOrderAll', data.sum)
            showStatus('#statusOrderGeneral', data.general)
            showStatus('#statusOrderHospital', data.hospital)
        }
    })
}

function showStatus(_id, _data) {
    if (_data == 0) {
        $(_id).text(_data)
        $(_id).hide()
    } else {
        $(_id).text(_data)
        $(_id).show()
    }
}

function checkStatusOrderCrash() {
    $.ajax({
        url: url_ajax_request + "ajax/ajax.cart.php",
        type: "post",
        dataType: "json",
        data: { action: "checkStatusOrderCrash" },
        success: function(data) {
            showStatus('#statusOrderCrash', data.countSum)
            showStatus('#statusOrderGeneralCrash', data.countG)
            showStatus('#statusOrderHospitalCrash', data.countH)
        }
    })
}