<div class="content-wrapper">
    <section class="content-header">
      <h1>
      <i class="fa fa-shopping-cart" aria-hidden="true"></i> ตะกร้าสินค้า (สมาชิกทั่วไป)
        <small>( <?php echo $language_fullname['display_name']; ?> ) </small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo SITE_URL; ?>"><i class="fa fa-dashboard"></i> <?php echo $LANG_LABEL['home']; //หน้าหลัก     ?></a></li>
        <li class="active">สมาชิก (Member)</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">

        <div class="col-md-12">
            <div class="box box-primary">
              <div style="display: block; width: 100%; text-align:center;">
                <label for="">เลือก..</label>
                <select name="" id="selectOrderType" class="form-control" style="width: 300px;margin:auto;">
                  <option value="">เลือก..</option>
                  <option value="orderNew">Order New</option>
                  <option value="productCrash">Product Crash</option>
                </select>
              </div>
              <div class="box-body">
                <table id="cart-general-grid" class="table table-striped table-bordered table-hover no-footer" width="100%">
                    <thead>
                      <tr>
                        <th></th>
                        <th>ลำดับ</th>
                        <th>Order ID</th>
                        <th>Member ID</th>
                        <th>ชื่อ</th>
                        <th>จำนวน</th>
                        <th>ราคา</th>
                        <th>date regis</th>
                        <th>Order Status</th>
                        <!-- <th>status</th> -->
                        <th style="width:200px;">จัดการ</th>
                      </tr>
                    </thead>
                </table>
              </div>
            </div>
        </div>

      </div>
    </section>

    <div class="modal" id="model-orderGeneral-show" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document" style="width:1200px;">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title" style="text-align:center" id="modal-general-date">Modal title</h3>
            <h3 class="modal-title" style="text-align:center" id="modal-general-order_id">Modal title</h3>
            <h3 class="modal-title" style="text-align:center" id="modal-general-member_name"></h3>
            <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal(event)">
              <span aria-hidden="true">&times;</span>
            </button> -->
          </div>
          <div class="modal-body" id="model-orderGeneral-show-body">
            
          </div>
          <div class="modal-footer">
            <a href="" target="_blank" id="generalPrint" class="btn btn-secondary" ><i class="fa fa-print" aria-hidden="true"></i> พิมพ์</a>
            <button type="button" class="btn kt:btn-success btn-editDataOrderGeneral" data-orderid="0" style="display:none" data-dismiss="modal" onclick="editDataOrderGeneral(event)">บันทึกข้อมูล</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeModal(event)">Close</button>
            <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
          </div>
        </div>
      </div>
    </div>
  
  </div>

  <!-- css -->
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/datatables/css/dataTables.bootstrap.min.css">

  <script src="<?php echo SITE_URL; ?>plugins/datatables/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo SITE_URL; ?>plugins/datatables/js/dataTables.bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
  <script src="<?php echo SITE_URL; ?>plugins/uploadImage/js/uploadimg.js"></script>
  <script src="<?php echo SITE_URL; ?>js/pages/cart/cart.js"></script>