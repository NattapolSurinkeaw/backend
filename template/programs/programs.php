<?php
$getpost['cateid'] = 6;
$getpost['status'] = isset($_GET['status']) ? $_GET['status'] : "";
$search_text = isset($_GET['search']) ? $_GET['search'] : "";

$all_posts = $mydata->get_post($getpost);
$category = $mydata->get_category($getpost['cateid']);
?>
<style>
.form-group.has-error .cke_chrome {
    border-color: #dd4b39;
    box-shadow: none;
}

.box-content-cate.error,
.box-content-cate-edit.error {
    border: 1px solid #dd4b39;
}
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-newspaper-o"></i> ผลการตรวจวิเคราะห์<?php //echo $LANG_LABEL['content'];?>
            <small>( <?php echo $language_fullname['display_name']; ?> )</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i><?php echo $LANG_LABEL['home'] ?></a>
            </li>
            <li class="active"><?php echo $LANG_LABEL['content']; ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary kt:box-shadow">
                    <div style="display: block; width: 100%; text-align:right;">
                        <a class="btn kt:btn-info" onclick="OPenFormAdd(event)"
                            style=" padding: 8px 40px; margin: 10px 10px 5px;color:white"><i class="fa fa-plus"></i>
                            เพิ่มข้อมูล</a>
                    </div>
                    <div class="box-body">
                        <table id="programs-grid" class="table table-striped table-bordered table-hover no-footer"
                            width="100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>ลำดับ</th>
                                    <th>รูปภาพ</th>
                                    <th>ชื่อ</th>
                                    <th>Link</th>
                                    <th>Create</th>
                                    <th>Update</th>
                                    <th>Manage</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="ModalPrograms" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="Modaltitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 10px;">
                            <div class="form-group form-add-images">
                                <label style="text-align:center;display:block;">อัพโหลดรูปภาพหมวดหมู่สินค้า<?php //echo $LANG_LABEL['uploadimage'];
                                            ?> <span style="color:red">*</span> </label>
                                <div id="image-preview" style="margin:auto;">
                                    <label for="image-upload" class="image-label">
                                        <i class="fa fa-camera"></i>
                                    </label>
                                    <div class="blog-preview-add"></div>
                                    <input type="file" name="imagesadd[]" class="exampleInputFile"
                                        id="add-images-content" data-preview="blog-preview-add" data-type="add" />
                                </div>
                                <input type="hidden" id="add-images-content-hidden" name="add-images-content-hidden"
                                    required>
                                <div class="b-row space-15"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="">ชื่อ</label>
                            <input type="text" id="name" class="form-control" value="" placeholder="ช่องใส่ชื่อ">
                        </div>
                        <div class="col-md-12" style="margin-top:10px">
                            <label for="">Link URL</label>
                            <input type="url" id="url" class="form-control" value="" placeholder="ช่องใส่ Link URL">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="idEdit">
                    <button type="button" class="btn kt:btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    <button type="button" class="btn kt:btn-primary" id="addPrograms"><i class="fa fa-save"></i> Save changes</button>
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
<script src="<?php echo SITE_URL; ?>js/pages/programs/programs_new.js"></script>