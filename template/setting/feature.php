<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"><i class="fa fa-wrench"></i> <?php echo $LANG_LABEL['siteconfig'];//จัดการเว็บไซต์ ?></h3>

    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
      </button>
    </div>
  </div>

  <div class="box-body no-padding">
    <div class="box-body box-category table-responsive">

      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th width="10">#</th>
            <th><?php echo $LANG_LABEL['name'];//ชื่อ?></th>
            <th width="180"><?php echo $LANG_LABEL['status'];//แสดงผลบนเว็บไซต์?></th>
            <th width="160">Action</th>
          </tr>
        </thead>
        <tbody>
        <?php
          $feature = $mydata->get_feature();
          $i = 0;
          foreach ($feature as $key => $value) {
            $i++;
        ?>
            <tr>
              <td><?php echo $i; ?></td>
              <td><?php echo $value['name']; ?></td>
              <td>
                <select class="form-control" id="<?= 'feature-status-'.$value['id']; ?>">
                  <?php
                  echo '<option value="yes" ';
                  if($value['status']=='yes'){
                    echo 'SELECTED';
                  }
                  echo '>YES</option><option value="no" ';
                  if($value['status']=='no'){
                    echo 'SELECTED';
                  }
                  echo '>NO</option>';
                  ?>
                </select>
              </td>
              <td>
                <button type="button" class="btn btn-block btn-success kt:btn-primary edit-feature" id="feature-<?php echo $value['id']; ?>" data-id="<?php echo $value['id']; ?>"><i class="fa fa-floppy-o"></i> Save</button>
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