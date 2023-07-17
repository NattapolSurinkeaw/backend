<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"><i class="fa fa-language"></i> <?php echo $LANG_LABEL['managelang'];//จัดการภาษา?></h3>

    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
      </button>
    </div>
  </div>

  <div class="box-body no-padding">
    <div class="categorybox-controls category-box">

      <div class="pull-right">
        <button type="button" class="btn btn btn-primary kt:btn-info" style="padding: 8px 40px;" data-toggle="modal" data-target="#modal-add-language"><i class="fa fa-plus"></i> <?php echo $LANG_LABEL['add']; //เพิ่ม?></button>
      </div>

    </div>
  </div>

  <div class="box-body no-padding">
    <div class="box-body box-category table-responsive">

      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th width="10">#</th>
            <th><?php echo $LANG_LABEL['displayname']; //ชื่อที่แสดง?></th>
            <th width="180"><?php echo $LANG_LABEL['shortname']; //ชื่อย่อ?></th>
            <th width="160">Action</th>
          </tr>
        </thead>
        <tbody>
        <?php
          $language = $mydata->get_all_language();
          $i = 0;
          foreach ($language as $key => $value) {
            $i++;
        ?>
            <tr>
              <td><?php echo $i; ?></td>
              <td><?php echo $value['display_name']; ?></td>
              <td><?php echo $value['language']; ?></td>
              <td>
                <a data-id="<?php echo $value['id']; ?>" data-toggle="modal" data-target="#modal-edit-language" class="btn btn-success kt:btn-warning btn-xs edit-language" style="margin-right: 7px;">
                  <i class="fa fa-pencil-square-o"></i> <?php echo $LANG_LABEL['edit'];//แก้ไข?>
                </a> |
                <a data-id="<?php echo $value['id']; ?>" class="btn btn-danger kt:btn-danger btn-xs delete-language" style="margin-left: 7px;">
                  <i class="fa fa-trash-o"></i> ลบ<?php echo $LANG_LABEL['ลบ'];//ลบ?>
                </a> |
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

<!-- Add Language -->
<div class="modal fade" id="modal-add-language">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> <?php echo $LANG_LABEL['addnewlang'];//เพิ่มภาษา?></h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" id="form-add-language">
          <div class="box-body">

            <div class="form-group" id="edit-display-group">
              <label class="col-sm-2 control-label"><?php echo $LANG_LABEL['displayname'];//ชื่อที่แสดง?></label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="add-language-display">
              </div>
            </div>

            <div class="form-group" id="edit-display-group">
              <label class="col-sm-2 control-label"><?php echo $LANG_LABEL['shortname'];//ชื่อย่อ?></label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="add-language-name">
              </div>
            </div>

          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success kt:btn-primary" id="save-add-language"><i class="fa fa-floppy-o"></i> <?php echo $LANG_LABEL['save'];//บันทึก?></button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Language -->
<div class="modal fade" id="modal-edit-language">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> <?php echo $LANG_LABEL['editlang'];//แก้ไขชื่อภาษา?></h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" id="form-edit-language">
          <div class="box-body">

            <div class="form-group" id="edit-display-group">
              <label class="col-sm-2 control-label"><?php echo $LANG_LABEL['displayname'];//ชื่อที่แสดง?></label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="edit-language-display">
              </div>
            </div>

            <div class="form-group" id="edit-display-group">
              <label class="col-sm-2 control-label"><?php echo $LANG_LABEL['shortname'];//ชื่อย่อ?></label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="edit-language-name" readonly>
              </div>
            </div>

            <input type="hidden" class="form-control" id="edit-language-id">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success kt:btn-danger" id="save-edit-language"><i class="fa fa-floppy-o"></i> <?php echo $LANG_LABEL['save'];//บันทึก?></button>
      </div>
    </div>
  </div>
</div>