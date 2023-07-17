<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"><i class="fa fa-file-text"></i> <?php echo $LANG_LABEL['datatype'];//ประเภทข้อมูล?></h3>

    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
      </button>
    </div>
  </div>
  
  <div class="box-body no-padding">
    <div class="categorybox-controls category-box">

      <div class="pull-right">
        <button type="button" class="btn btn btn-primary kt:btn-info" style="padding: 8px 40px" data-toggle="modal" data-target="#modal-web-info-type-add"><i class="fa fa-plus"></i>  <?php echo $LANG_LABEL['add'];//เพิ่ม?></button>
      </div>

    </div>
  </div>

  <div class="box-body no-padding">
    <div class="box-body box-category table-responsive">

      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th width="10">#</th>
            <th>info_type</th>
            <th>info_title</th>
            <th>language</th>
            <th width="160">Action</th>
          </tr>
        </thead>
        <tbody>
        <?php
          $content = $mydata->get_web_info_type();
          $i=0;
          foreach($content as $a){
            $i++;
        ?>
            <tr>
              <td><?php echo $i; ?></td>
              <td><?php echo $a['info_type']; ?></td>
              <td><?php echo $a['info_title']; ?></td>
              <td><?php echo $a['lang_info']; ?></td>
              <td>
              <?php
                if(strpos($a['lang_info'],$_SESSION['backend_language']) > -1){
              ?>
                <a data-id="<?php echo $a['id']; ?>" data-type="edit" data-toggle="modal" data-target="#modal-web-info-type-edit" class="btn btn-success kt:btn-warning btn-xs edit" id="web-info-type-edit" style="margin-right: 7px;">
                  <i class="fa fa-pencil-square-o"></i> <?php echo $LANG_LABEL['edit'];//แก้ไข?>
                </a> |
                <a data-id="<?php echo $a['id']; ?>" data-type="<?php echo $a['info_type']; ?>" class="btn btn-danger kt:btn-danger btn-xs delete" id="web-info-type-delete" style="margin-left: 7px;">
                  <i class="fa fa-trash-o"></i>  <?php echo $LANG_LABEL['delete'];//ลบ?>
                </a>
              <?php
                }else {
              ?>
                <a data-id="<?php echo $a['id']; ?>" data-type="add" data-toggle="modal" data-target="#modal-web-info-type-edit" class="btn btn-attree btn-xs edit" id="web-info-type-edit" style="margin-right: 7px;">
                  <i class="fa fa-plus"></i>   <?php echo $LANG_LABEL['add'];//เพิ่ม?>
                </a> 
              <?php
                }
              ?>
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


<!-- Add Web Info Type -->
<div class="modal fade" id="modal-web-info-type-add">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> <?php echo $LANG_LABEL['add_data_type'];//เพิ่มประเภทข้อมูล?></h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" id="form-add-info-type">
          <div class="box-body">

            <div class="form-group" id="add-type-group">
              <label class="col-sm-2 control-label">info_type</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="add-info-type">
              </div>
            </div>

            <div class="form-group" id="add-title-group">
              <label class="col-sm-2 control-label">info_title</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="add-info-title">
                <span class="help-block edit-email-error"></span>
              </div>
            </div>

          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success kt:btn-primary" id="save-add-web-info-type"><i class="fa fa-floppy-o"></i> <?php echo $LANG_LABEL['save'];//บันทึก?></button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Web Info Type -->
<div class="modal fade" id="modal-web-info-type-edit">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i>  <?php echo $LANG_LABEL['edit'];//แก้ไขข้อมูล?></h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" id="form-edit-info-type">
          <div class="box-body">

            <div class="form-group" id="edit-display-group">
              <label class="col-sm-2 control-label">info_type</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="edit-info-type">
              </div>
            </div>

            <div class="form-group" id="edit-email-group">
              <label class="col-sm-2 control-label">info_title</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="edit-info-title">
                <span class="help-block edit-email-error"></span>
              </div>
            </div>

            <input type="hidden" class="form-control" id="action-type">
            <input type="hidden" class="form-control" id="current-info-type">
            <input type="hidden" class="form-control" id="edit-web-info-type-id">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success kt:btn-info" id="save-edit-web-info-type"><i class="fa fa-floppy-o"></i> <?php echo $LANG_LABEL['save'];//บันทึก?></button>
      </div>
    </div>
  </div>
</div>