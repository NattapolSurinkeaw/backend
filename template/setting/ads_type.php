<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"><i class="fa fa-photo"></i> <?php echo $LANG_LABEL['types']." ".$LANG_LABEL['banners'];//ประเภทโฆษณา?></h3>

    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
      </button>
    </div>
  </div>

  <div class="box-body no-padding">
    <div class="categorybox-controls category-box">

      <div class="pull-right">
        <button type="button" class="btn btn btn-primary kt:btn-info" style="padding: 8px 40px" data-toggle="modal" data-target="#modal-add-ads-type"><i class="fa fa-plus"></i> <?php echo $LANG_LABEL['add'];//เพิ่ม?></button>
      </div>

    </div>
  </div>

  <div class="box-body no-padding">
    <div class="box-body box-category table-responsive">

      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th width="10">#</th>
            <th>position</th>
            <th>type</th>
            <th>dimension</th>
            <th width="160">Action</th>
          </tr>
        </thead>
        <tbody>
        <?php
          $ads = $mydata->get_all_ads_type();
          $i = 0;
          foreach ($ads as $key => $value) {
            $i++;
        ?>
            <tr>
              <td><?php echo $i; ?></td>
              <td><?php echo $value['position']; ?></td>
              <td><?php echo $value['type']; ?></td>
              <td><?php echo $value['dimension']; ?></td>
              <td>
                <a data-id="<?php echo $value['id']; ?>" data-toggle="modal" data-target="#modal-edit-ads-type" class="btn btn-success kt:btn-warning btn-xs edit-ads-type" style="margin-right: 7px;">
                  <i class="fa fa-pencil-square-o"></i> <?php echo $LANG_LABEL['edit'];//แก้ไข?>
                </a> |
                <a data-id="<?php echo $value['id']; ?>" class="btn btn-danger kt:btn-danger btn-xs delete-ads-type" style="margin-left: 7px;">
                  <i class="fa fa-trash-o"></i> ลบ<?php echo $LANG_LABEL['delte'];//ลบ?>
                </a>
              </td>
            </tr>
        <?php
          }
        ?>
        </tbody>
      </table>
    </div> 
  </div>
</div>

<!-- Add Ads Type -->
<div class="modal fade" id="modal-add-ads-type">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> <?php echo $LANG_LABEL['add'];//เพิ่ม?></h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" id="form-add-ads-type">
          <div class="box-body">

            <div class="form-group" id="edit-display-group">
              <label class="col-sm-2 control-label">position</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="add-ads-position">
              </div>
            </div>

            <div class="form-group" id="edit-display-group">
              <label class="col-sm-2 control-label">type</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="add-ads-type">
              </div>
            </div>

            <div class="form-group" id="edit-display-group">
              <label class="col-sm-2 control-label">dimension</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="add-ads-dimension">
              </div>
            </div>

          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success kt:btn-primary " style="padding: 8px 40px" id="save-add-ads-type"><i class="fa fa-floppy-o"></i>  <?php echo $LANG_LABEL['add'];//เพิ่ม?></button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Language -->
<div class="modal fade" id="modal-edit-ads-type">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> <?php echo $LANG_LABEL['edit'];//แก้ไขประเภทโฆษณา?></h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" id="form-edit-ads-type">
          <div class="box-body">

            <div class="form-group" id="edit-display-group">
              <label class="col-sm-2 control-label">position</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="edit-ads-position">
              </div>
            </div>

            <div class="form-group" id="edit-display-group">
              <label class="col-sm-2 control-label">type</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="edit-type">
              </div>
            </div>

            <div class="form-group" id="edit-display-group">
              <label class="col-sm-2 control-label">dimension</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="edit-ads-dimension">
              </div>
            </div>

            <input type="hidden" class="form-control" id="edit-ads-type-id">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="save-edit-ads-type"><i class="fa fa-floppy-o"></i> <?php echo $LANG_LABEL['save'];//บันทึก?></button>
      </div>
    </div>
  </div>
</div>