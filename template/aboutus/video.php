<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-id-card-o" aria-hidden="true"></i> วีดีโอ
      <small>( <?php echo $language_fullname['display_name']; ?> ) </small>
    </h1>
    <ol class="breadcrumb">
      <li>
        <a href="<?php echo SITE_URL; ?>">
          <i class="fa fa-dashboard"></i> 
            <?php echo $LANG_LABEL['home'];?>
        </a>
      </li>
      <li class="active">วีดีโอ</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">

      <div class="col-md-7">
        <div class="box box-primary kt:box-shadow">
          <div style="display: block; width: 100%; text-align:right;">
            <!-- <a class="btn kt:btn-info" onclick="showFormAddProductCate()" style=" padding: 8px 40px; margin: 10px 10px 5px;color:white"><i class="fa fa-plus"></i> เพิ่มหมวดหมู่</a> -->
          </div>
          <div class="box-body">
            <div class="" style="margin-top: 20px;">
              <label for="">ลิ้ง วีดีโอ 1</label>
              <textarea name="" class="form-control" id="video-1" cols="10" rows="2" placeholder="ช่องใส่ link video youtube"></textarea>
              <a href="" class="btn btn-info" style="margin-top:10px;" onclick="previewVideo(event,1)"><i class="fa fa-play" aria-hidden="true"></i> preview</a>
              <a href="" class="btn btn-success" style="margin-top:10px;" onclick="saveVideo(event,1)"><i class="fa fa-save" aria-hidden="true"></i> บันทึก วีดีโอ</a>
              <a href="" class="btn btn-danger" style="margin-top:10px;" onclick="delVideo(event,1)"><i class="fa fa-trash" aria-hidden="true"></i> ลบ</a>
              <hr style="margin-top:10px;">
            </div>
            <div class="" style="margin-top: 20px;">
              <label for="">ลิ้ง วีดีโอ 2</label>
              <textarea name="" class="form-control" id="video-2" cols="10" rows="2" placeholder="ช่องใส่ link video youtube"></textarea>
              <a href="" class="btn btn-info" style="margin-top:10px;" onclick="previewVideo(event,2)"><i class="fa fa-play" aria-hidden="true"></i> preview</a>
              <a href="" class="btn btn-success" style="margin-top:10px;" onclick="saveVideo(event,2)"><i class="fa fa-save" aria-hidden="true"></i> บันทึก วีดีโอ</a>
              <a href="" class="btn btn-danger" style="margin-top:10px;" onclick="delVideo(event,2)"><i class="fa fa-trash" aria-hidden="true"></i> ลบ</a>
              <hr style="margin-top:10px;">
            </div>
            <div class="" style="margin-top: 20px;">
              <label for="">ลิ้ง วีดีโอ 3</label>
              <textarea name="" class="form-control" id="video-3" cols="10" rows="2" placeholder="ช่องใส่ link video youtube"></textarea>
              <a href="" class="btn btn-info" style="margin-top:10px;" onclick="previewVideo(event,3)"><i class="fa fa-play" aria-hidden="true"></i> preview</a>
              <a href="" class="btn btn-success" style="margin-top:10px;" onclick="saveVideo(event,3)"><i class="fa fa-save" aria-hidden="true"></i> บันทึก วีดีโอ</a>
              <a href="" class="btn btn-danger" style="margin-top:10px;" onclick="delVideo(event,3)"><i class="fa fa-trash" aria-hidden="true"></i> ลบ</a>
              <hr style="margin-top:10px;">
            </div>
            <div class="" style="margin-top: 20px;">
              <label for="">ลิ้ง วีดีโอ 4</label>
              <textarea name="" class="form-control" id="video-4" cols="10" rows="2" placeholder="ช่องใส่ link video youtube"></textarea>
              <a href="" class="btn btn-info" style="margin-top:10px;" onclick="previewVideo(event,4)"><i class="fa fa-play" aria-hidden="true"></i> preview</a>
              <a href="" class="btn btn-success" style="margin-top:10px;" onclick="saveVideo(event,4)"><i class="fa fa-save" aria-hidden="true"></i> บันทึก วีดีโอ</a>
              <a href="" class="btn btn-danger" style="margin-top:10px;" onclick="delVideo(event,4)"><i class="fa fa-trash" aria-hidden="true"></i> ลบ</a>
              <hr style="margin-top:10px;">
            </div>
            <div class="" style="margin-top: 20px;">
              <label for="">ลิ้ง วีดีโอ 5</label>
              <textarea name="" class="form-control" id="video-5" cols="10" rows="2" placeholder="ช่องใส่ link video youtube"></textarea>
              <a href="" class="btn btn-info" style="margin-top:10px;" onclick="previewVideo(event,5)"><i class="fa fa-play" aria-hidden="true"></i> preview</a>
              <a href="" class="btn btn-success" style="margin-top:10px;" onclick="saveVideo(event,5)"><i class="fa fa-save" aria-hidden="true"></i> บันทึก วีดีโอ</a>
              <a href="" class="btn btn-danger" style="margin-top:10px;" onclick="delVideo(event,5)"><i class="fa fa-trash" aria-hidden="true"></i> ลบ</a>
              <hr style="margin-top:10px;">
            </div>

          </div>
        </div>
      </div>


      <div class="col-md-5">
        <div class="box box-primary kt:box-shadow">
          <div style="display: block; width: 100%; text-align:right;">
            <!-- <a class="btn kt:btn-info" onclick="showFormAddProductCate()" style=" padding: 8px 40px; margin: 10px 10px 5px;color:white"><i class="fa fa-plus"></i> เพิ่มหมวดหมู่</a> -->
          </div>
          <div class="box-body">
            <label for="">Preview Video</label>
            <div class="" style="margin-top: 20px; " id="previewVideo">
              
            </div>

          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- popup status download  -->
  <div class="wrapper-pop">
    <div class="pop">
        <div class="loader10"></div>
        <h2 class="loadper" style="text-align:center;padding-top:50px;">0 %</h2>
        <h4 style="padding-top:30px">กำลังอัพโหลดรูปภาพ</h4>
    </div>
  </div>



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
<script src="<?php echo SITE_URL; ?>js/pages/aboutus/aboutus_video.js"></script>