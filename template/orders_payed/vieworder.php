<!-- Modal Add Category -->  
<div id="modalViewOrder" class="modal fade blog-content blog-content-lg" role="dialog">
  <input type = "hidden" class = "hidden_penging" value = "">
  <input type = "hidden" class = "hidden_paying" value = "">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-bars"></i> รายละเอียดการสั่งซื้อ</h4>
      </div>

      <div class="modal-body">
        <div class="row body-row-content">

          <div class="order-detail" id = "order-detail">
            <div class="row invoice-info">

              <div class = "row customer_order_detail">
                <div class = "header">
                  รายละเอียดลูกค้า
                </div>
                <div class = "bodyDesc">
                  <div class ="orderid">
                    รหัสสั่งซื้อ : <span> # </span>
                  </div>
                  <div class ="orderdate">
                    #
                  </div>
                  <div class ="name">
                    <span> ชื่อ : </span>  <span class = "customerName"> # </span>
                  </div>
                  <div class ="address">
                    <span> ที่อยู่ : </span> <span class= "customerAddress"> # </span>
                  </div>
                  <div class ="district">
                    <span> อำเภอ / เขต : </span> <span class= "aumphor"> # </span>
                  </div>
                  <div class ="district">
                    <span> จังหวัด : </span> <span class= "country"> # </span>
                  </div>
                  <div class ="district">
                    <span> รหัสไปรษณีย์ : </span> <span class= "zipcode"> # </span>
                  </div>
                  <div class ="phone">
                    <span> เบอร์โทรศัพท์ : </span> <span class= "customerPhone"> # </span>
                  </div>
                  <div class ="email">
                    <span> อีเมล : </span> <span class= "customerEmail"> # </span>
                  </div>
                  <div class ="line">
                    <span> ID LINE : </span> <span class= "customerLine"> # </span>
                  </div>
                  <div class ="cus_msg">
                    <span> อื่น ๆ (ถ้ามี) : </span> <span class= "customerMsg"> # </span>
                  </div>
  
                </div>
              </div>
            <!-- /.row -->

            <!-- Table row -->
            <div class="row table-order-data">
              <div class="col-xs-12 table-responsive">
                <table class="table table-striped">
                  <thead>
                  <tr>
                    <th colspan="2">รายการสั่งซื้อ</th>
                    <th>โปรโมชั่น</th>
                    <th>ราคาสินค้า</th>
                  </tr>
                  </thead>
                  <tbody class="table-order-detail">
                    <tr>
                      <td> <img src = "<?php echo ROOT_URL;?>upload/2018/08/1535593529_20180830084529.jpg"> </td>
                      <td>.table-order-detail #ajax</td>
                      <td>'+price.toLocaleString()+' ฿</td>
                      <td>'+obj.order[i].Qty+'</td>
                    </tr>
                    <tr>
                      <td> <img src = "<?php echo ROOT_URL;?>upload/2018/08/1535593529_20180830084529.jpg"> </td>
                      <td>.table-order-detail #ajax</td>
                      <td>'+price.toLocaleString()+' ฿</td>
                      <td>'+obj.order[i].Qty+'</td>
                    </tr>
                    <tr class="total">
                      <td colspan="3"> ราคารวมสินค้า </td>
                      <td> # บาท </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class = "row customer_order_detail customerSlip">
              <div class = "header">
                รายละเอียดการโอนเงิน
              </div>
              <div class = "bodyDesc">
                  <div class = "slipImg">
                    <img src = "<?php echo ROOT_URL;?>upload/2018/08/1535593529_20180830084529.jpg">
                  </div>
                  <div class ="slipDesc">
                    <div class ="name">
                      <span> ชื่อ : </span> <span class= "customerName"> # </span>
                    </div>
                    <div class ="payedDate">
                       # 
                    </div>
                    <div class ="payedBy">
                      <span> ชำระผ่าน : </span> <span class= "payedByDesc"> # </span>
                    </div>
                  </div>
              </div>
            </div>

          </div>


        </div>
      </div>
      <div class="modal-footer " style="left: 0;border-top: 2px solid #99854a;"> 
        <!-- <button type="submit" class="btn btn-default pull-left" id="print-order">
          <i class="fa fa-print"></i> พิมพ์
        </button> -->

        <select id="order_status" name="orderStatus" style="height: 40px;min-width: 210px;font-size: 16px;padding: 0 10px;border: 1px solid #dddddd;border-radius: 3px;">
        <?php 
          foreach ($status as $key) {
            echo '<option value="'.$key['status_id'].'" >'.$key['orders_desc'].'</option>';
          }
        ?>
        </select>

        <button type="button" class="btn btn-success pull-right" id="save-order" style="margin-left: 25px;
    height: 40px;"><i class="fa fa-floppy-o"></i> บันทึกการเปลี่ยนแปลง
        </button>
        <!--button type="button" class="btn btn-primary pull-right" id="print-link-detail"><i class="fa fa-newspaper-o"></i> ปริ้นรายละเอียด
        </button>
        <button type="button" class="btn btn-primary pull-right" id="print-link-slip"><i class="fa fa-money-bill-alt"></i> ปริ้นสลิปเงิน
        </button-->
        <input type="hidden" id="order-id"></input>
        <!-- <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
          <i class="fa fa-download"></i> Generate PDF
        </button> -->
      </div>
    </div>
  </div>
</div>