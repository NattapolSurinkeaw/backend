<div class="content-wrapper">
  <section class="content-header">
    <h1>
        <i class="fa fa-info" aria-hidden="true"></i> แก้ไขข้อความ
      <small>( <?php echo $language_fullname['display_name']; ?> ) </small>
    </h1>
    <ol class="breadcrumb">
      <li>
        <a href="<?php echo SITE_URL; ?>">
          <i class="fa fa-dashboard"></i> 
            <?php echo $LANG_LABEL['home'];?>
        </a>
      </li>
      <li class="active">ติดต่อเรา</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">

      <div class="col-md-12">
        <div class="box box-primary kt:box-shadow">
          <!-- <div style="display: block; width: 100%; text-align:right;"> -->
            <!-- <a class="btn kt:btn-info" onclick="showFormAddProductCate()" style=" padding: 8px 40px; margin: 10px 10px 5px;color:white"><i class="fa fa-plus"></i> เพิ่มหมวดหมู่</a> -->
          <!-- </div> -->
          <div class="box-body">
            <div class="" style="">
              <label for="">ข้อความเลื่อน <i class="fa fa-hospital-o" aria-hidden="true"></i> </label>
              <textarea name="" class="form-control" id="message-marquee" data-id="3" cols="10" rows="3" placeholder="ข้อความเลื่อน"></textarea>
            </div>
            <div class="" style="margin-top: 10px;">
              <label for="">รายละเอียดการเติมเงิน <i class="fa fa-location-arrow" aria-hidden="true"></i></label>
              <textarea name="" class="form-control" id="message-deposit" data-id="1" cols="10" rows="3" placeholder="รายละเอียดการเติมเงิน"></textarea>
            </div>
            <div class="" style="margin-top: 10px;">
              <label for="">รายละเอียดการถอนเงิน <i class="fa fa-location-arrow" aria-hidden="true"></i></label>
              <textarea name="" class="form-control" id="message-withdraw" data-id="2" cols="10" rows="3" placeholder="รายละเอียดการถอนเงิน"></textarea>
            </div>
      
            <div style="margin-top: 20px;">
              <a href="" class="btn btn-success pull-right kt:btn-success" style="padding:10px 40px;" onclick="addDataContact(event)"><i class="fa fa-save"></i> บันทึกข้อมูล</a>
            </div> 

          </div>
        </div>
      </div>

    </div>
  </section>  

</div>

<!-- css --> 
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/animate.css"> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script> 
<script src="<?php echo SITE_URL; ?>js/pages/message/message.js?v=<?=date('YmdHis')?>"></script>