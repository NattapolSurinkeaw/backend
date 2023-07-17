<section class="content network" >
    <div class="row">         
        <div class="col-xs-12 formHidding " style="width:270px;">
            <div class="box box-primary unselect-txt">

                <div class="box-header with-border">
                    <h3 class="box-title networkBoxTitle">จัดการเครือข่าย</h3>
                    <div class="box-tools  pull-right">
        		 
        		    </div> 
        	    </div>
                <div class="box-body"> 
                    <label class="addnetworkmore active">เพิ่มเครือข่าย</label> 
                    <div class="form-inline  networkFormAction networkAdding active">
                        <div class="flexControl">
                          <label>ชื่อเครือข่าย: </label>                         
                            <input type="text" placeholder="ชื่อ ..." class="txt_network">    
                        </div>       
                        <div class="network_addNetwork">
        		            <button class="btnFixForm btn btn-primary fixColor addNetwork" data-id=""> <i class="fa fa-save"></i> เพิ่มเครือข่าย </button>
                        </div>
                    </div> 
                    
                    <label class="editnetworkmore">แก้ไขเครือข่าย</label> 
        		    <div class="form-inline  networkFormAction networkEdit">
                   
                        <div class="secSlcNetwork">
                            <label>เครือข่าย:</label>                 
                            <select class="form-control mngSlcNetwork" id="mngSlcNetwork"> 
                               <?php echo $dataNetwork = $mydata->getSlcNetwork();   ?>   
                            </select> 
                        </div> 
                        <div style="margin-left: 14px; margin-right: 14px;">
                             <div class="networkDisplay" style="display: flex; justify-content: space-between;">
                                 <label class="labProduct vas">แสดงผล: <a class="labagentShow"></a></label> 
                                 <label class="switch">
                                     <input type="checkbox" id="networkDisplay" value="no" >
                                     <span class="slider  round"></span>
                                     </label>
                                 <label class="txt txt_networkDisplay">ON</label>
                             </div>  
                        </div> 
                        <div class="boxIconNetwork">
                            <div style="margin-left:14px;"><span style="text-align: left;">ไอคอนเครือข่าย</span></div>
                            <div class="form-group form-add-images imgNetwork" style="width: 100%;"> 
                                <div id="image-preview">
                                    <label for="image-upload" class="image-label">
                                        <i class="fa fa-camera"></i>
                                    </label> 
                                    <div class="blog-preview-add"></div>
                                    <input type="file" name="imagesadd[]" class="exampleInputFile" id="add-images-Agent" data-preview="blog-preview-add" data-type="add" />
                                </div> 
                                <input type="hidden" id="add-images-Agent-hidden">
                            </div>
                        </div>
                            <label class="txtUploadBanner">Table Banner <i class="fas fa-arrow-alt-circle-up"></i> </label>  
                        <hr>
                         <div class="network_formActMng">
        		               <button class="btnFixForm btn btn-primary fixColor btnSaveNetworkBer" data-id=""> <i class="fa fa-pencil"></i> อัพเดท </button>
        		               <button type="submit" class="btnFixForm btn btn-danger btndelNetwork">  <i class="fa fa-trash"></i> ลบ   </button>
                         </div>
                    </div> 
                 </div>
            </div>              
        </div>  
        <div class="col-xs-12 formHidding unselect-txt " style="width:600px;">
            <div class="box box-primary "> 
                <div class="box-header with-border">
                    <h3 class="box-title networkBoxTitle">จัดการช่วงราคา</h3>
                    <div class="box-tools  pull-right"><span data-toggle="modal" data-target="#priceRate" class="unselect-txt addPriceRate">เพิ่มช่วงราคา</span>
        		    </div>  
        	    </div>      
         
                <div class="box-body-in">  
                    <div class="in-list-head">
                        <span>ลำดับ</span>
                        <span>แสดงผล</span>
                        <span>ต่ำสุด</span>
                        <span>สูงสุด</span>
                        <span>จัดการ</span>
                    </div>     
                    <div class="in-list-body">
                     <!-- LOOP HERE  -->
                         <!-- <span>0</span>
                         <span>1000</span>
                         <span class="btnActionRate">
                             <span class="editRate" data-toggle="modal" data-target="#priceRate"  >แก้ไข</span>
                             <span class="delRate">ลบ</span>
                         </span> -->
                         
                    </div>            
                </div>
                <div class="box-body-footer">  
                </div>
             </div>
        </div>
 
        <!-- Modal -->
        <div class="modal fade" id="priceRate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">กำหนดช่วงราคา</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="box-body-top"> 
                     <div> 
                        <label>การแสดงผล</label>
                        <input  type="text" class="form-control txt_display" placeholder="0 - 1000">
                     </div>
                     <div> 
                        <label>ราคาต่ำสุด</label>
                        <input type="number" class="form-control txt_minprice" placeholder="ช่วงราคาต่ำสุด">
                     </div>
                     <div> 
                        <label>ราคาสูงสุด</label>
                        <input type="number" class="form-control txt_maxprice"  placeholder="ช่วงราคาสูงสุด">
                     </div>
                     
                     <div> 
                        <label>ลำดับ</label>
                        <input  type="number" class="form-control txt_prio" style="width:50%;" placeholder="ลำดับที่">
                     </div>
                     <div> 
                       
                     </div>
                </div> 
            </div>
            <div class="modal-footer"> 
                <button type="button" class="btn btn-primary btnPriceRateAction" style="width: 100%;" data-id="" data-old="0">บันทึก</button>
                <button type="button" class="btn btn-secondary btnCancelPriceRate" style="margin-left:0px;" data-dismiss="modal">ยกเลิก</button>
            </div>
            </div>
        </div>
        </div>

     
    </div>
</section>    
