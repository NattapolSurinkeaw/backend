<section class="content  product"  >
    <div class="row"> 
      <div class="col-xs-12 col-md-9 formDetailsData">			
        <div class="box box-primary">
          <div class="box-body"> 
		        <div class="box-head-action">						
              <button  type="button" class="backtocate btn btn-primary" data-id=""><i class="fas fa-chevron-left"></i> กลับสู่หมวดหมู่ </button>	 
      			  <span class="headTitle-product">  <span class="cateSlcName"></span></span>
			        <button  type="button" class="  addProducts btn btn-primary"><i class="fa fa-fw fa-plus"></i> เพิ่มหมายเลข </button>
			       </div>
			       <hr>
		          <table id="product-table" class="table table-striped table-bordered table-hover no-footer" width="100%"> 
                <thead> 
                  <tr>
                    <th class="">ลำดับ</th> 
                    <th class="">หมายเลข</th>      
                    <th class="">ผลรวม</th>
                    <th class="">เครือข่าย</th> 
                    <th class="">ราคา</th> 
                    <th class="">ads</th> 
                    <th class="">sold</th>                   
                    <th class="">pin</th> 
                    <th class="">hot</th>  
                    <th class="">จัดการ</th>
                  </tr>
                 </thead>
               </table>
             </div>
           </div>
      </div>			
      
		  <div class="col-xs-3 formHidding newproductBoxAction unselect-txt" style="width:230px;">
		    <div class="box box-primary ">
		       <div class="box-header with-border">
		         <h3 class="box-title productBoxTitle"> </h3>
		         <div class="box-tools  pull-right">
		       		<i class="fa fa-times  productFormClose" aria-hidden="true"></i>
		       	 </div> 
		     </div>
 
		    <div class="box-body">     
		      <div class="form-inline  productFormAction">
 
              <div class="secSlcNetwork">
                <label>เครือข่าย :</label>                 
                <select class="form-control slcNetwork" id="slcNetwork"> 
                   <?php echo $dataNetwork = $mydata->getSlcNetwork();   ?>   
                </select> 
              </div>
              <div class="numberSum">
                    <label>ผลรวม :  <span id="numsum">0</span></label>   
              </div>

              <div class="phonenum">
                 <label class="labagent">หมายเลข : <a class="labagentShow"></a></label>
                 <input type="text" class="form-control txt_phoneNum" maxlength="10" value="" placeholder="หมายเลข">
              </div>
              <div class="phoneprice">
                 <label class="labagent">ราคา : <a class="labagentShow"></a></label>
                 <input type="text" class="form-control txt_phoneprice" maxlength="10" value="" placeholder="ราคา">
              </div>
              <div class="switchForm">                  
                 <div class="adsStatus secMargi ">
                   <label class="labProduct vas">Ads : <a class="labagentShow"></a></label> 
                   <label class="switch">
                      <input type="checkbox" id="adsSwitch" value="no" >
                      <span class="slider round"></span>
                    </label>
                   <label class="txt adsSwitch vas">OFF</label>
                 </div>



                 <div class="soldStatus secMargi ">
                   <label class="labProduct vas">Sold : <a class="labagentShow"></a></label> 
                   <label class="switch">
                     <input type="checkbox" id="soldSwitch" value="no" >
                     <span class="slider round"></span>
                    </label>
                   <label class="txt soldSwitch vas">OFF</label>
                 </div>

                 <div class="pinStatus secMargi ">
                   <label class="labProduct vas">Pin : <a class="labagentShow"></a></label> 
                   <label class="switch">
                      <input type="checkbox" id="pinSwitch" value="no" >
                      <span class="slider round"></span>
                    </label>
                   <label class="txt pinSwitch vas">OFF</label>
                  </div> 
                 <div class="hotStatus secMargi ">
                   <label class="labProduct vas">Hot : <a class="labagentShow"></a></label> 
                   <label class="switch">
                       <input type="checkbox" id="hotSwitch" value="no" >
                       <span class="slider round"></span>
                    </label>
                   <label class="txt hotSwitch vas">OFF</label>
                 </div>

               </div>              
              <div class="product_formActMng">
		            <button class="btnFixForm btn btn-primary fixColor btnSaveproductBer" data-id="" data-old="" style="margin-top:10px;"> <i class="fa fa-save"></i> บันทึก </button>
		            <button type="reset" class="btnFixForm btn btn-danger btnFormatproductForm" style="margin-top:3px;"><i class="fa fa-refresh"></i> ล้างค่า <span class="agent"></span></button>
		          </div>
           </div>              
		    </div>
		  </div>
    </div>
   </section>
 