<div class="content-wrapper berddpage agentpage">
    <section class="content-header">
      <h1>
        <i class="fa fa-id-card"></i> ตัวแทนจำหน่าย 
        <small>( <?php echo $language_name['display_name']; ?> )</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> หน้าหลัก</a></li>
        <li class="active">ตัวแทนจำหน่าย</li>
      </ol>
    </section>  
    <section class="content newForm ">
      <div class="row"> 
        <div class="col-xs-8">			
          <div class="box box-primary">
            <div class="box-body"> 
							<div class="box-head-action">							 
				 				<span>ข้อมูลตัวแทนจำหน่าย</span>
								<button  type="button" class="addagentForm btn btn-primary"><i class="fa fa-fw fa-plus"></i> เพิ่มข้อมูล</button>
							</div>
							<hr>
						  <table id="admin-grid" class="table table-striped table-bordered table-hover no-footer" width="100%"> 
                 <thead> 
                    <tr>
                      <th>User</th>
                      <th>ชื่อ-นามสกุล</th>
                      <th>Link สำหรับใช้งาน</th>
                      <th>บัตรประชาชน</th>
                      <th>จัดการ</th>
                    </tr>
                  </thead>
              </table>
            </div>
          </div>
				</div>
			
				<div class="col-xs-3 formHidding newFormAction" style="width:315px;">
				  <div class="box box-primary ">
					  <div class="box-header with-border">
					    <h3 class="box-title agentBoxTitle"> </h3>
					   	<div class="box-tools  pull-right">
								<i class="fa fa-times agentFormClose" aria-hidden="true"></i>
							</div> 
						</div>
						<hr>
					 	<div class="box-body"> 
						 <div class="form-inline  fixformCenter">
               <div class="form-group form-add-images " style="width: 100%;">
									<label style="color: red;">อัพโหลดรูปภาพ</label> 
                  <div id="image-preview">
                    <label for="image-upload" class="image-label">
                      <i class="fa fa-camera"></i>
                    </label>
                    <div class="blog-preview-add"></div>
                    <input type="file" name="imagesadd[]" class="exampleInputFile" id="add-images-Agent" data-preview="blog-preview-add" data-type="add" />
                  </div>
                  <span class="help-block add-images-error">Please select images file!</span>
                  <input type="hidden" id="add-images-Agent-hidden">
								</div>

							 <div>
								 <label class="labagent">Username : <a class="labagentShow"></a></label></div>
							 <div>
								 <input type="text" class="form-control txt_username" value="" placeholder="สำหรับเข้าเว็บส่วนของเซล"></div>
							 
							 <div>
								 <label class="labagent">ชื่อ-นามสกุล : <a class="labagentShow"></a></label></div>
							 <div> 
								 <input type="text" class="form-control txt_name" value="" placeholder="ชื่อ-นามสกุล"></div>
							 
							 <div>
								 <label class="labagent">E-mail : <a class="labagentShow"></a></label></div>
							 <div>
								 <input type="text" class="form-control txt_email" value="" placeholder="google@gmail.com"></div>

							 <div>
								 <label  class="labagent">เบอร์ติดต่อ : <a class="labagentShow"></a></label></div>
							 <div>
								 <input type="number" class="form-control txt_phone" value="" placeholder="หมายเลขโทรศัพท์"></div>

							<div>
								 <label class="labagent">Line ID : <a class="labagentShow"></a></label></div>
						    <div>
                 <input type="text" class="form-control txt_line" value="" placeholder="ไลน์ไอดี">
               </div>
 
              <div>
								 <label  class="labagent">Facebook : <a class="labagentShow"></a></label></div>
                <div>
                  <div> 
                    <input type="text" class="form-control txt_facebook" value="" placeholder="facebook">
                  </div>
                  <div>
                     <input type="text" class="form-control fbid" value="" placeholder="FACEBOOK ID">
                  </div>
                </div> 
              <div>
								 <label  class="labagent">Instagram : <a class="labagentShow"></a></label></div>
               <div>
                 <input type="text" class="form-control txt_instagram" value="" placeholder="instagram">
               </div>


						 
									<div>
										<button class=" btn btn-primary addMoreBank"  > เพิ่มบัญชีธนาคาร </button>
									</div>
								  <div id="bankSec">
							    	<div class="bankSlc">
							    	  <label class="labagent">ธนาคาร : <a class="labagentShow"></a></label>
							    		<select id="bankSaleSlc">
									   
                        <option disabled selected value="0">เลือกธนาคาร</option>
                        <option data-name="SCB" value="SCB">[SCB] ไทยพาณิชย์</option>
                        <option data-name="KTB" value="KTB">[KTB] กรุงไทย</option>
                        <option data-name="KBANK" value="KBANK">[KBANK] กสิกรไทย</option>
                        <option data-name="BBL" value="BBL">[BBL] กรุงเทพ</option>
                        <option data-name="BAY" value="BAY">[BAY] กรุงศรีอยุธยา</option>
                        <option data-name="GSB" value="GSB">[GSB] ออมสิน</option>
                        <option data-name="TMB" value="TMB">[TMB] ทหารไทย</option>
							    		</select> 
							    	</div>
							    	<div class="bankName">
							    	 <label class="labagent">ชื่อบัญชี : <a class="labagentShow" ></a></label> 				
							    	 <span> <input type="text" class="form-control txt_bankName" style="width: 175px;"value="" placeholder="ชื่อเจ้าของบัญชี" ></span>
							    	</div>
							     
							    	<div class="bankId">
                        <label class="labagent">เลขบัญชี : <a class="labagentShow" ></a></label> 				
                        <input type="number" class="form-control txt_bankId" value="" placeholder="เลขบัญชีธนาคาร" >
							    	
                    </div>
                    <div style="margin-top:5px;">
                        <span type="" class="btnFixForm btn  fixColor btnAddMoreBank" data-id=""><i class="fa fa-plus"> เพิ่มบัญชี</i></span>    
                        <span type="" class="btnFixForm btn  fixColor btnCancelMoreBank" data-id="">ยกเลิก</span>
                    </div>
									 </div>
                   <div class="titleSort">
                          <span>ลากชื่อเพื่อสลับตำแหน่ง <i class="fas fa-sort"></i></span>
                          <span class="btnAddBank">
                            <span type="" class="btnFixForm btn  fixColor ">เพิ่มบัญชี</span>
                          </span>
                   </div>
									 <ul id="sortable" class="listBankSale">
											<?php  //ส่วนของข้อมูลธนาคารของเซล  	?>				
									 </ul>
							  <hr class="endTab">
							 	<div>
									<button class="btnFixForm btn btn-primary fixColor btnSaveAgent"  style="margin-top:10px;"> <i class="fa fa-save"></i> บันทึก </button>
									<button type="reset" class="btnFixForm btn btn-danger btnResetAgent" style="margin-top:10px;"> ล้างค่า </button>  
									<span class="agent"></span></button>
								</div>
							</div>
						</div>
					</div>
      </div>
		</section>
  </div>

  <div class="modal fade" id="modal-admin">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> แก้ไขข้อมูลของผู้ดูแลระบบ</h4>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" id="form-edit-user">
            <div class="box-body">

              <div class="form-group" id="edit-display-group">
                <label class="col-sm-2 control-label">ชื่อ</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="edit-display-name">
                  <span class="help-block edit-display-error"></span>
                </div>
              </div>

              <div class="form-group" id="edit-email-group">
                <label class="col-sm-2 control-label">อีเมล</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="edit-email">
                  <span class="help-block edit-email-error"></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">หน้าที่</label>
                <div class="col-sm-10">
								<?php 
		 
                if ($_SESSION['role'] == 'admin') {
                ?>
                  <style>#type-id-3{display: none;}</style>
                <?php
                }
                ?>

								<?php 
								 
                if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'editor') {
                ?>
                  <style>#type-id-1{display: none;}</style>
                <?php
                }
                ?>
                  <select class="form-control" id="edit-user-type">                        
                    <?php
                      echo $data->option_multilingual('user_type','user_type','member_type','type-id-','id');
                    ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">สถานะ</label>
                <div class="col-sm-10">
                  <select class="form-control" id="edit-user-status">
                   <?php
                   echo $data->option_multilingual('user_status','status_user','status_user','status-id-','id');
                   ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">ภาษา</label>
                <div class="col-sm-6">
                  <table class="table table-bordered table-striped table-lang">
                  <?php
                    $lan_arr = $data->get_language();
                    foreach ($lan_arr as $a) {
                  		?>
                      <tr>
                        <td style="width: 10px">
                          <input type="checkbox" name="language" id="lang-<?php echo $a['language']; ?>" value="<?php echo $a['language']; ?>">
                        </td>
                        <td>
                          <?php echo $a['display_name']; ?>
                        </td>
                        <td style="width: 40px">
                          <span class="badge bg-green">
                            <?php echo $a['language']; ?>
                          </span>
                        </td>
                      </tr>
                    <?php
                      }
                    ?>
                  </table>
                </div>
              </div>
              <input type="hidden" class="form-control" id="edit-member-id">
              <input type="hidden" class="form-control" id="current-email">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" id="reset-password"><i class="fa fa-refresh"></i> Reset Password</button>
          <button type="button" class="btn btn-primary" id="save-edit-user"><i class="fa fa-floppy-o"></i> Save Changes</button>
        </div>
      </div>
    </div>
  </div>
  <!-- css -->
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/datatables/css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/meStyle.css">
 
	<!-- script -->
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script type="text/javascript" src="<?php echo SITE_URL; ?>plugins/fancybox-2.1.7/jquery.fancybox.pack.js?v=2.1.7"></script>
  <script src="<?php echo SITE_URL; ?>plugins/datatables/js/jquery.dataTables.min.js"></script>
	<script src="<?php echo SITE_URL; ?>plugins/datatables/js/dataTables.bootstrap.min.js"></script>
	<script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
	<!-- <script src="<?php echo SITE_URL; ?>js/pages/admin.js"></script> -->
	<script src="<?php echo SITE_URL; ?>js/pages/agentpage/agent.js?v=<?=date('his')?>"></script>

 
	<script src="<?php echo SITE_URL; ?>plugins/jquery-confirm/js/jquery-confirm.min.js"></script>
 