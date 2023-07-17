<?php
$memberGeneral = $mydata->getMemberIdNameGeneral();
$memberHospital = $mydata->getMemberIdNameHospital();

$yearG = $mydata->getYearFromOrderGeneral();
$yearH = $mydata->getYearFromOrderHospital();
$yeatMin = $mydata->getYearOrderMin();

$month = $mydata->getMonth();
$productAll = $mydata->getProductAll();
?>

<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-bar-chart"></i> สินค้าขายดี (ปี)
      <small>( <?php echo $language_fullname['display_name']; ?> ) </small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo $LANG_LABEL['home']; //หน้าหลัก     
                                                                            ?></a></li>
      <li class="active">สินค้าขายดี (ปี) <?php //echo $LANG_LABEL['sales']; //ผู้ดูแลระบบ    
                              ?></li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary kt:box-shadow">
          <div style="display: block; width: 100%; text-align:right;">
            <!-- <a class="btn kt:btn-info" onclick="showFormAddProductCate()" style=" padding: 8px 40px; margin: 10px 10px 5px;color:white"><i class="fa fa-plus"></i> เพิ่มหมวดหมู่</a> -->
          </div>
          <div class="box-body">


            <div class="row">
              <div class="col-md-3 text-right">สินค้าไหนขายดีที่สุดในรอบปี </div>
              <div class="col-md-2">
                <select class="form-control" id="SelectYearOrderProductBest">
                  <option value="">เลือกปี</option>
                  <?php for ($i=$yeatMin;$i<=date('Y');$i++) {  ?>
                    <option value="<?= $i ?>"><?= $i ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="col-md-7"></div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-6" id="OrderProductBest-price-wrapper">
                <canvas id="OrderProductBest-price"></canvas>
              </div>
              <div class="col-md-6" id="OrderProductBest-qty-wrapper">
                <canvas id="OrderProductBest-qty"></canvas>
              </div>
            </div>
            <br>
            <hr>





 



          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<!-- css -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/datatables/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/animate.css">

<script src="<?php echo SITE_URL; ?>plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datatables/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datepicker/js/bootstrap-datepicker.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datepicker/js/locales/bootstrap-datepicker.th.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.5.0"></script>
<script src="<?php echo SITE_URL; ?>js/pages/chart_order/chart_order.js"></script>
<script src="<?php echo SITE_URL; ?>js/pages/chart_order/bestsaleyear.js"></script>