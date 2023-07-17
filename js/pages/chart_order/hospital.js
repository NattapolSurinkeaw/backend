
$('#btnClickSearchMerberHospital').on('click', function (e) {

    $('#order-hospital-price-by-member-id').remove();
    $('#order-hospital-price-by-member-id-wrapper').append(`<canvas id="order-hospital-price-by-member-id" ></canvas>`);

    $('#order-hospital-qty-by-member-id').remove();
    $('#order-hospital-qty-by-member-id-wrapper').append(`<canvas id="order-hospital-qty-by-member-id" ></canvas>`);

    let _data = {
        action: "getOrder_ALL_By_member_id",
        memberID: $("#SelectMemberIDH option:selected").val(),
        start: $('#add-date-display-start-h').val(),
        end: $('#add-date-display-end-h').val(),
        type: 'hospital'
    }

    $.ajax({
        url: "ajax/ajax.chart_order.php",
        type: "post",
        dataType: "json",
        data: _data,
        success: function (data) {
            // console.log(data)

            if(data.Count > 8){
                $('#order-hospital-price-by-member-id-wrapper').removeClass('col-md-6');
                $('#order-hospital-price-by-member-id-wrapper').addClass('col-md-12');
            }else{
                $('#order-hospital-price-by-member-id-wrapper').removeClass('col-md-12');
                $('#order-hospital-price-by-member-id-wrapper').addClass('col-md-6');
            }

            var oh_member_id_price = document.getElementById('order-hospital-price-by-member-id').getContext('2d');
            var oh_member_id_qty = document.getElementById('order-hospital-qty-by-member-id').getContext('2d');
            ChartOrderByMemberId_(oh_member_id_price, "bar", data.Name, `ยอดขาย ${data.PriceTotal}`, data.PriceSum, `ยอดขาย ${data.PriceTotal} บาท`)
            ChartOrderByMemberId_(oh_member_id_qty, "doughnut", data.Name, `จำนวนที่ขายได้ ${data.QtyTotal}`, data.qty, `จำนวน ${data.QtyTotal} item`)
        }
    })
})