   <?php 
    //$mydata->readExcel();
   ?>
   <div class="content-wrapper dashboard-box">
    <section class="content-header">
      <h1>
        EXCEL
        <small>Control panel</small>
      </h1> 
    </section>

    <section class="content">
        <?php 
            echo $mydata->readExcel();
        ?>
    </section>
  </div>
