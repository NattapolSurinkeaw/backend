<section class="content newForm category"> 
      <div class="row"> 
        <div class="col-xs-12 col-md-8 formDetailsData">			
         <div class="box box-primary">

           <div class="box-body"> 
			  <div class="box-head-action">							 
			  <span class="headTitle-prednumb">หมวดหมู่สินค้า</span>
			  <button  type="button" class="addagentForm addCateProduct btn btn-primary"><i class="fa fa-fw fa-plus"></i> เพิ่มหมวดหมู่</button>
			  </div>
			  <hr>
		      <table id="admin-grid" class="table table-striped table-bordered table-hover no-footer" width="100%"> 
                <thead> 
                 <tr>
                  <th class="">ลำดับ</th> 
                  <th class="">ชื่อหมวดหมู่<span style="color:red;"> [จำนวนสินค้า]</span> </th>      
                  <th class="">รหัสหมวด</th>   
                  <th class="">Display</th> 
                  <th class="" style="text-align:center;">ดูสินค้าหมวดนี้</th>
                  <th class="">จัดการ</th>
                 </tr>
                </thead>
              </table>
             </div>
           </div>
      </div>			
      
		  <div class="col-xs-3 formHidding newFormAction" style="width:340px;">
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
             <?php /* 

             <div class="col-md-12">             
                <div class="form-group form-add-images">
                <label>อัพโหลดรูปภาพ</label>
                  <div id="image-preview">
                    <label for="image-upload" class="image-label">
                      <i class="fa fa-camera"></i>
                    </label>
                    <div class="blog-preview-add"></div>
                    <input type="file" name="imagesadd[]" class="exampleInputFile" id="add-images-content" data-preview="blog-preview-add" data-type="add" />
                  </div>
                   
                  <input type="hidden" id="add-images-content-hidden">
                  <div class="b-row space-15"></div>                                            
                </div>
              </div>  
              */ ?>

             <div class="fix-type unselect-txt">
                <!-- <span class="add-more-group" data-toggle="modal" data-target="#loverformnumber">เพิ่มเบอร์คู่รัก</span> -->
             </div>
             
             <div>
                 <label class="labagent">ชื่อหมวดหมู่ : <a class="labagentShow"></a></label>
             </div>
             <div> <input type="text" class="form-control txt_catename" value="" placeholder="ชื่อหมวดหมู่"> </div>
             
             <div class="hide-love-mode">
                 <label class="labagent ">เลขที่ต้องการ :<a class="labagentShow"></a> <span style="color:red;"> เช่น 1,2,3,456 </span></label>
             </div>
             <div class="hide-love-mode"><textarea type="text" class="form-control txt_needful" value="" placeholder="เลขที่ต้องการ"> </textarea></div>
             
             <div class="hide-love-mode">
                 <label class="labagent">เลขที่ไม่ต้องการ :<a class="labagentShow"></a> <span style="color:red;"> เช่น 1,2,3,456 </span></label>
             </div>
             <div class="hide-love-mode"><textarea type="text" class="form-control txt_needless" value="" placeholder="เลขที่ไม่ต้องการ"> </textarea></div>
               
             <div class="category-fix">
                 <label class="labagent">หมวดหมู่ที่ : <a class="labagentShow"></a></label>
                 <span>
                   <input style="width:25%; padding: 0px 5px; text-align:center;" type="text" class="form-control txt_category" value="" placeholder="หมวดหมู่">
                 </span>
                 <span style="color:red;" class="oldData" > 0 </span>
             </div>

            <div class="slcStatus type-btn">
                <label class="labagent">ระบบออโต้ : <a class="labagentShow"></a></label>  
                <label class="switch systemAuto">
                  <input type="checkbox" id="systemAuto" value="yes" checked>
                  <span class="slider round"></span>
                </label>
                <!-- <label class="txtSysAuto" style="color:rgb(34, 189, 83);">ON</label>  -->
             </div>
             
             <div class="slcStatus">
                <label class="labagent">การแสดง : <a class="labagentShow"></a></label>  
                <label class="switch displayStatus">
                  <input type="checkbox" id="displayStatus" value="yes" checked>
                  <span class="slider round"></span>
                </label>
                <!-- <label class="txtDisplayStatus" style="color:rgb(34, 189, 83);">ON</label>  -->
             </div>

             <div>
                 <label class="labagent">ลำดับที่ : <a class="labagentShow"></a></label>
                 <span>
                   <input style="width:25%; padding: 0px 5px;" type="text" class="form-control txt_catepriority" value="" placeholder="ลำดับที่">
                 </span>
                 <span style="color:red;" class="oldData" > 0 </span>
             </div>
 
             <hr>
      
             <div>
		        	  <button class="btnFixForm btn btn-primary fixColor btnSaveCategory" data-status="" data-id="" data-old="" style="margin-top:10px;"> <i class="fa fa-save"></i> บันทึก </button>
		        	  <button type="reset" class="btnFixForm btn btn-danger btnFormatnewForm" style="margin-top:10px;"> ล้างค่า <span class="agent"></span></button>
		        	</div>
           </div>              
		    </div>
		  </div>
       </div>
     </section>