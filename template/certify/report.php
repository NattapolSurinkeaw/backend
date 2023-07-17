<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-graduation-cap" aria-hidden="true"></i> รายงานผลข้อสอบ
            <small>( <?php echo $language_fullname['display_name']; ?> ) </small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo $LANG_LABEL['home']; //หน้าหลัก     
                                                                                    ?></a></li>
            <li class="active">รายงานผลข้อสอบ</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">

            <div class="col-md-12">
                <div class="box box-primary">
                    <div style="display: block; width: 100%; text-align:center;">
                        <label for="" style="margin-top: 10px;">เลือกหมวดหมู่</label>
                        <select name="" class="form-control selectCertifyTitle" id="selectCertifyTitle" style="width: 300px;margin:5px auto;background:#00d1b2;color:white;">
                            <option value="0">เลือกหมวดหมู่</option>
                            <?php 
                                foreach($mydata->getCertifyTitleAll() as $certifyTitle){
                                    echo "<option value=\"".$certifyTitle['id']."\">".$certifyTitle['title']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="box-body">
                        <table id="certify-report-grid" class="table table-striped table-bordered table-hover no-footer" width="100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>ลำดับ</th>
                                    <th>ID</th>
                                    <th>หมวดหมู่</th>
                                    <th>ชื่อ</th>
                                    <th>จำนวนข้อสอบ</th>
                                    <th>คะแนนที่ทำได้</th>
                                    <th>วันที่ทำข้อสอบ</th>
                                    <th>จัดการ</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

<div class="modal" id="model-show-certify-by-logid" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="width:100vh;">
        <div class="modal-content" style="overflow-y: scroll;height:90vh;">
            <div class="modal-header">
                <h3 class="modal-title" style="text-align:center;margin-top:0;"></h3>
                <h3 class="modal-member-name" style="text-align:center;margin-top:0;"></h3>
                <h3 class="modal-date" style="text-align:center;margin-top:0;"></h3>
                <h3 class="modal-point" style="text-align:center;margin-top:0;"></h3>

            </div>
            <div class="modal-body" id="model-show-certify-by-logid-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" 
                onclick="clearModalCertify(event)"
                >Close
                </button>
                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>

<!-- popup status download  -->
<div class="wrapper-pop">
    <div class="pop">
        <div class="loader10"></div>
        <h2 class="loadper" style="text-align:center;padding-top:50px;">0 %</h2>
        <h4 style="padding-top:30px">กำลังอัพโหลดรูปภาพ</h4>
    </div>
</div>

<!-- css -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/datatables/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="<?php echo SITE_URL; ?>css/animate.css">


<script src="<?php echo SITE_URL; ?>plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datatables/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
<script src="<?php echo SITE_URL; ?>js/pages/certify/certify.js"></script>