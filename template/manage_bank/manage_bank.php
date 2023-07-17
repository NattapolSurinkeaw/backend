<div class="content-wrapper">
   <section class="content-header">
      <h1>
         <i class="fa fa-cube"></i> บัญชีธนาคาร
         <small>( <?php echo $language_fullname['display_name']; ?> ) </small>
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo $LANG_LABEL['home'];  ?></a></li>   
         <li class="active">บัญชีธนาคาร</li>
      </ol>
   </section>
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-primary">
               <div style="display: block; width: 100%; text-align:right;">
                  <a class="btn kt:btn-info" style=" padding: 8px 40px; margin: 10px 10px 5px;color:white" data-toggle="modal" data-target="#CreateFacilities">
                     <i class="fa fa-plus"></i>
                     ธนาคาร
                  </a>
               </div>
               <div class="box-body">
                  <table id="bank-grid" class="table table-striped table-bordered table-hover no-footer" width="100%">
                     <thead>
                        <tr>
                           <th style="width:2%">No.</th>
                           <th style="width:5%">Name</th>
                           <th style="width:10%">Number</th>
                           <th style="width:10%">Image</th>
                           <th style="width:10%">Date Create</th>
                           <th style="width:10%">Date Update</th>
                           <th style="width:10%">Action</th>
                        </tr>
                     </thead>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>

<!-- CreatePromotion  -->
<div class="modal" tabindex="-1" role="dialog" id="CreateFacilities">
   <div class="modal-dialog" role="document">
      <div class="modal-content modal-sm" style="margin:auto;">
         <div class="modal-header">
            <span class="modal-title"><?= $LANG_LABEL['setting_bank'] ?></span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-md-12">
                  <form id="formUploadImg" class="w-full mt-8 text-center">
                     <h2 class="text-base text-center"><?=$LANG_LABEL['uploadimage']?></h2>
                     <input type="file" name="inputFile" id="inputFileImg" style="display:none;">
                     <input type="number" name="inputID" id="inputID" style="display:none;">
                     <!-- <label for="" style="display:block;text-align:center;font-size:1em;color:red">(ไฟล์ jpg , jpeg , png เท่านั้น)</label> -->
                     <img id="img-handle-upload-image" style="width: 100%;height: 200px;object-fit:contain;" class="" src="/backend/images/image134x134.jpg" alt="" data-action="no">
                     <!-- <br> -->
                     <!-- <label for="" style="display:block;text-align:center;">ไฟล์ที่เลือก: <span class="showFileNameImg">คุณยังไม่ได้เลือกไฟล์</span></label> -->
                     <br>
                  </form>
               </div>
               <div class="col-md-12 text-center" style="margin-top: 5px;"><button class="btn btn-primary btn-click-upload-add"><?=$LANG_LABEL['selectimage']?></button></div>
               <div class="col-md-12" style="margin-top: 5px;"><label for="">ชื่อบัญชี</label></div>
               <div class="col-md-12"><input type="text" class="form-control" id="add-name"></div>
               <div class="col-md-12" style="margin-top: 5px;"><label for="">ชื่อธนาคาร</label></div>
               <div class="col-md-12"><input type="text" class="form-control" id="add-bank"></div>
               <div class="col-md-12" style="margin-top: 5px;"><label for="">หมายเลขบัญชี</label></div>
               <div class="col-md-12"><input type="text" class="form-control" id="add-number"></div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i>
               Close
            </button>
            <button type="button" class="btn kt:btn-success" onclick="SaveBank(event)"><i class="fa fa-save"></i>
               Save changes
            </button>
         </div>
      </div>
   </div>
</div>
<!-- End CreatePromotion  -->
 
<!-- EditPromotion  -->
<div class="modal" tabindex="-1" role="dialog" id="EditFacilities">
   <div class="modal-dialog" role="document">
      <div class="modal-content modal-sm" style="margin:auto;">
         <div class="modal-header">
            <span class="modal-title"><?= $LANG_LABEL['addmember'] ?></span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-md-12">
                  <form id="edit-formUploadImg" class="w-full mt-8 text-center">
                     <h2 class="text-base text-center"><?=$LANG_LABEL['uploadimage']?></h2>
                     <input type="file" name="inputFile" id="edit-inputFileImg" style="display:none;">
                     <input type="number" name="inputID" id="edit-inputID" style="display:none;">
                     <!-- <label for="" style="display:block;text-align:center;font-size:1em;color:red">(ไฟล์ jpg , jpeg , png เท่านั้น)</label> -->
                     <img id="edit-img-handle-upload-image" style="width: 100%;height: 200px;object-fit:contain;" class="" src="/backend/images/image134x134.jpg" alt="" data-action="no">
                     <!-- <br> -->
                     <!-- <label for="" style="display:block;text-align:center;">ไฟล์ที่เลือก: <span class="showFileNameImg">คุณยังไม่ได้เลือกไฟล์</span></label> -->
                     <br>
                  </form>
               </div>
               <div class="col-md-12 text-center" style="margin-top: 5px;"><button class="btn btn-primary btn-click-upload-edit"><?=$LANG_LABEL['selectimage']?></button></div>
               <div class="col-md-12" style="margin-top: 5px;"><label for="">ชื่อบัญชี</label></div>
               <div class="col-md-12"><input type="text" class="form-control" id="edit-name"></div>
               <div class="col-md-12" style="margin-top: 5px;"><label for="">ชื่อธนาคาร</label></div>
               <div class="col-md-12"><input type="text" class="form-control" id="edit-bank"></div>
               <div class="col-md-12" style="margin-top: 5px;"><label for="">หมายเลขบัญชี</label></div>
               <div class="col-md-12"><input type="text" class="form-control" id="edit-number"></div>


            </div>
         </div>
         <div class="modal-footer">
            <input type="hidden" name="" id="edit-id">
            <button 
               type="button" 
               class="btn btn-secondary"
               data-dismiss="modal"><i class="fa fa-times"></i>
               Close
            </button>
            <button 
               type="button" 
               class="btn kt:btn-success" 
               onclick="EditSaveBank(event)"><i class="fa fa-save"></i>
               Save changes
            </button>
         </div>
      </div>
   </div>
</div>
<!-- End EditPromotion  -->







<!-- css -->

<link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/datatables/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">
<link rel="stylesheet" href="/css/uploadimg.css">



<script src="<?php echo SITE_URL; ?>plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/datatables/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/th.js"></script>
<script src="<?php echo SITE_URL; ?>js/pages/manage_bank/manage_bank.js?v=<?=time()?>"></script>