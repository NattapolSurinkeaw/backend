$('#btnClickSearchMerberGeneral').on('click', function (e) {

    $('#order-general-price-by-member-id').remove();
    $('#order-general-price-by-member-id-wrapper').append(`<canvas id="order-general-price-by-member-id" ></canvas>`);

    $('#order-general-qty-by-member-id').remove();
    $('#order-general-qty-by-member-id-wrapper').append(`<canvas id="order-general-qty-by-member-id" ></canvas>`);

    let _data = {
        action: "getOrder_ALL_By_member_id",
        memberID: $("#SelectMemberIDG option:selected").val(),
        start: $('#add-date-display-start').val(),
        end: $('#add-date-display-end').val(),
    }


    $.ajax({
        url: "ajax/ajax.chart_order.php",
        type: "post",
        dataType: "json",
        data: _data,
        success: function (data) {
            // console.log(data)
            if(data.Count > 8){
                $('#order-general-price-by-member-id-wrapper').removeClass('col-md-6');
                $('#order-general-price-by-member-id-wrapper').addClass('col-md-12');
            }else{
                $('#order-general-price-by-member-id-wrapper').removeClass('col-md-12');
                $('#order-general-price-by-member-id-wrapper').addClass('col-md-6');
            }

            var og_member_id_price = document.getElementById('order-general-price-by-member-id').getContext('2d');
            var og_member_id_qty = document.getElementById('order-general-qty-by-member-id').getContext('2d');
            ChartOrderByMemberId_(og_member_id_price, "bar", data.Name, `ยอดขายของบุคคล ${data.PriceTotal}`, data.PriceSum, `ยอดขาย ${data.PriceTotal} บาท`)
            ChartOrderByMemberId_(og_member_id_qty, "doughnut", data.Name, `จำนวนที่ขายได้ ${data.QtyTotal}`, data.qty, `จำนวน ${data.QtyTotal} item`)
        }
    })
})