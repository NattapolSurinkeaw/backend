<?php
/*$order_status = $mydata -> get_order_status();
$payment_status = $mydata -> get_payment_status();
*/
$status = $mydata -> get_status();
?>
<style>
  .blog-content-lg .modal-lg {
    width: 1200px;
    height: 100%;
    margin: 0 auto;
  }
  @media (max-width: 1240px) {
    .blog-content-lg .modal-lg {
      width: 1024px;
    }
  }
  @media (max-width: 1040px) {
    .blog-content-lg .modal-lg {
      width: 900px;
    }
  }
  @media (max-width: 940px) {
    .blog-content-lg .modal-lg {
      width: 800px;
    }
  }
  @media (max-width: 840px) {
    .blog-content-lg .modal-lg {
      width: 768px;
    }
  }
  @media (max-width: 768px) {
    .blog-content-lg .modal-lg {
      width: auto;
    }
  }

  .body-row-content {
    height: 100%;
    overflow-x: hidden;
  }

  #order-grid-table td{
    position: relative;
  }

  #order-grid-table td .tdChild{
    position: absolute;
    top: 50%;
    left: 50%;
    display: inline-block;
    transform: translate(-50%,-50%);
    width: fit-content;
  }

.statuses {
    background-color: #f5f5f5;
    padding: 5px 9px 9px 16px;
    border: 1px solid #e3e3e3;
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    border-radius: 3px;
}
#order-grid-table {
    border-top: none;
    margin-top: 0 !important;
    margin-bottom: 70px !important;
}
@media (max-width: 992px) {
  .statuses .form-horizontal .control-label {
      padding-top: 0;
      margin-bottom: 5px;
      text-align: left;
  }
}
@media (max-width: 768px) {
  .statuses {
      margin: 25px 0;
  }
  .table-order-data {
    padding-right: 15px;
    padding-left: 15px;
  }
}
</style>

<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-shopping-cart"></i> รายการสั่งซื้อสินค้า
      <small>( <?php echo $language_name['display_name']; ?> )</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> หน้าหลัก</a></li>
      <li class="active">รายการสั่งซื้อสินค้า</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-xs-12">

        <div class="box box-primary">
          <div class="box-body">
            <table id="order-grid-table" class="table table-striped table-bordered table-hover no-footer" width="100%">
                <thead>
                  <tr>
                    <th style="text-align: center;">รหัสการสั่งซื้อ</th>
                    <th style="text-align: center;">ชื่อลูกค้า</th>
                    <th style="text-align: center;">วันที่สั่งซื้อ (ว/ด/ป)</th>
                    <th style="text-align: center;">เบอร์โทร</th>
                    <th style="text-align: center;">อีเมล์</th>
                    <th style="text-align: center;">สถานะ</th>
                    <th style="text-align: center;">จัดการ</th>
                  </tr>
                </thead>
            </table>
          </div>
        </div>

      </div>
    </div>
    <div class = "row" style="padding:15px;font-weight: 500;">
      
    </div>
  </section>
</div>
<?php
  include 'template/orders/vieworder.php';
?>
<!-- css -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/datatables/css/dataTables.bootstrap.min.css">
<!-- script -->
<script src="<?php echo SITE_URL; ?>plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datatables/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo SITE_URL; ?>js/pages/orders/orders.js"></script>