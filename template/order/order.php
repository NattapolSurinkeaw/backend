  <style>
    .form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
      background-color: #fff;
      opacity: 1;
    }
  </style>
  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-list"></i> รายการจอง 
        <small>( <?php echo $language_name['display_name']; ?> )</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?=$site_url?>"><i class="fa fa-dashboard"></i> หน้าหลัก</a></li>
        <li class="active">รายการจอง</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box box-primary">
            <div class="box-body">
              <table id="admin-grid" class="table table-striped table-bordered table-hover no-footer" width="100%">
                  <thead>
                    <tr>
                      <th>รหัสการจอง</th>
                      <th>ชื่อผู้จอง</th>
                      <th>เบอร์ติดต่อ</th>
                      <th>อีเมล</th>
                      <th>รุ่นรถ</th>
                      <th>เส้นทาง</th>
                      <th>วันที่</th>
                      <th style="max-width: 130px;">Action</th>
                    </tr>
                  </thead>
              </table>
            </div>
          </div>

        </div>
      </div>
    </section>
  </div>

  <div class="modal fade" id="modal-order">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> ข้อมูลการจอง</h4>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" id="form-edit-user">
            <div class="box-body">

              <div class="form-group" id="edit-display-group">
                <label class="col-sm-2 control-label">รหัสจอง</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="order_id" readonly>
                </div>
              </div>

              <div class="form-group" id="edit-email-group">
                <label class="col-sm-2 control-label">ชื่อผู้จอง</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="name" readonly>
                </div>
              </div>

              <div class="form-group" id="edit-email-group">
                <label class="col-sm-2 control-label">เบอร์โทร</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="phone" readonly>
                </div>
              </div>

              <div class="form-group" id="edit-email-group">
                <label class="col-sm-2 control-label">อีเมล</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="email" readonly>
                </div>
              </div>

              <div class="form-group" id="edit-email-group">
                <label class="col-sm-2 control-label">Fight Number</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="fight_number" readonly>
                </div>
              </div>

              <div class="form-group" id="edit-email-group">
                <label class="col-sm-2 control-label">รุ่นรถ</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="vehicle_type" readonly>
                </div>
              </div>

              <div class="form-group" id="edit-email-group">
                <label class="col-sm-2 control-label">เส้นทาง</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="location_route" readonly>
                </div>
              </div>

              <div class="form-group" id="">
                <label class="col-sm-2 control-label">อื่นๆ</label>
                <div class="col-sm-10">
                  <textarea class="form-control" rows="3" id="message" readonly></textarea>
                </div>
              </div>

              <div class="form-group" id="edit-email-group">
                <label class="col-sm-2 control-label">วันที่</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="order_date" readonly>
                </div>
              </div>

            </div>
          </form>
        </div>
        <div class="modal-footer">
          <!-- <button type="button" class="btn btn-default pull-left" id="reset-password"><i class="fa fa-refresh"></i> Reset Password</button>
          <button type="button" class="btn btn-primary" id="save-edit-user"><i class="fa fa-floppy-o"></i> Save Changes</button> -->
        </div>
      </div>
    </div>
  </div>
  <!-- css -->
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>plugins/datatables/css/dataTables.bootstrap.min.css">
  <!-- script -->
  <script src="<?php echo SITE_URL; ?>plugins/datatables/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo SITE_URL; ?>plugins/datatables/js/dataTables.bootstrap.min.js"></script>
  <script src="<?php echo SITE_URL; ?>js/pages/js-order/order.js"></script>