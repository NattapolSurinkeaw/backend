  $(function () {
    $('.mailbox-messages input[type="checkbox"]').iCheck({
      // checkboxClass: 'icheckbox_flat',
      radioClass: 'iradio_flat'
    });

    $(".checkbox-toggle").click(function () {
      var clicks = $(this).data('clicks');
      if (clicks) {
        $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
        $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
      } else {
        $(".mailbox-messages input[type='checkbox']").iCheck("check");
        $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
      }
      $(this).data("clicks", !clicks);
    });

    $(document).on('click', '.mailbox-star', function(e){
      e.preventDefault();
      //detect type
      var $this = $(this).find("a > i");
      var glyph = $this.hasClass("glyphicon");
      var fa = $this.hasClass("fa");
      var id = $(this).data("id");
      var fa_star_o = $this.hasClass("fas");
      var fa_star = $this.hasClass("far");

      if (fa_star_o) {
        var data = {
          action:"removestar",
          id: id
        };

        mailboxstar(data);
        $this.removeClass("fas");
        $this.addClass("far");
      }

      if (fa_star) {
        var data = {
          action:"addstar",
          id: id
        };

        mailboxstar(data);
        $this.removeClass("far");
        $this.addClass("fas");
      }
    });

    $.ajax({
      type: "POST",
      url: url_ajax_request + "ajax/ajax.contact.php",
      data: {
        action: "getpaginationcontact"
      },

      success: function(msg){
        // console.log(msg);
        currentPage = $("#inbox").data("pagi");
        amount = $("#inbox").data("amount");
        if (currentPage == '') {
          currentPage = 1;
        }

        sumCateOnPage = currentPage*amount;
        numCate = ((currentPage-1)*amount)+1;
        if (msg < sumCateOnPage) {
          sumCate = msg;
        }else {
          sumCate = sumCateOnPage;
        }

        $("#page-number").html(numCate+"-"+sumCate+" จาก "+msg+" ");
        $("#msg-count").val(msg);
      }
    });
  });

/////////////////////////////////////////////////////////////////////

  $("#inbox").on("click", function(){

    $(".mail-box-menu").removeClass("active");
    $("#inbox").addClass("active");
    $("#mail-box").show();
    $("#read-mail").hide();

    var data = {
        action:"getmessage",
        pagi: $(this).data("pagi"),
        amount: $(this).data("amount"),
        sortby: $(this).data("sortby"),
        search: $(this).data("search"),
        where: " "
    };

    getmessage(data);

    $.ajax({
      type: "POST",
      url: url_ajax_request + "ajax/ajax.contact.php",
      data: {
        action: "getpaginationcontact",
        where: "status NOT IN ('delete')"
      },

      success: function(msg){
   
        // console.log(msg);
        currentPage = $("#inbox").data("pagi");
        amount = $("#inbox").data("amount");
        if (currentPage == '') {
          currentPage = 1;
        }

        sumCateOnPage = currentPage*amount;
        numCate = ((currentPage-1)*amount)+1;
        if (msg < sumCateOnPage) {
          sumCate = msg;
        }else {
          sumCate = sumCateOnPage;
        }
        $("#page-number").html(numCate+"-"+sumCate+" จาก "+msg+" ");
        $("#msg-count").val(msg);
      }

    });

    $("#page").val(1);

  });

  $("#mail-box-star").on("click", function(){
    $(".mail-box-menu").removeClass("active");
    $(this).addClass("active");
    $("#mail-box").show();
    $("#read-mail").hide();

    var data = {
        action:"getmessage",
        pagi: $("#inbox").data("pagi"),
        amount: $("#inbox").data("amount"),
        sortby: $("#inbox").data("sortby"),
        search: $("#inbox").data("search"),
        where: "AND favorite='yes'"
    };

    getmessage(data);

    $.ajax({
      type: "POST",
      url: url_ajax_request + "ajax/ajax.contact.php",
      data: {
        action: "getpaginationcontact",
        where: "status NOT IN ('delete') AND favorite='yes'"
      },

      success: function(msg){
        // console.log(msg);
        currentPage = $("#inbox").data("pagi");
        amount = $("#inbox").data("amount");
        if (currentPage == '') {
          currentPage = 1;
        }

        sumCateOnPage = currentPage*amount;
        numCate = ((currentPage-1)*amount)+1;
        if (msg < sumCateOnPage) {
          sumCate = msg;
        }else {
          sumCate = sumCateOnPage;
        }
        $("#page-number").html(numCate+"-"+sumCate+" จาก "+msg+" ");
        $("#msg-count").val(msg);
      }

    });

    $("#page").val(1);

  });

/////////////////////////////////////////////////////////////////////

  $("#refresh-data").on("click", function(){
    $("#mail-box").show();
    $("#read-mail").hide();

    var inbox = $("#inbox").hasClass("active");
    var mail_box_star = $("#mail-box-star").hasClass("active");

    if (mail_box_star) {
      var data = {
        action:"getmessage",
        pagi: $("#inbox").data("pagi"),
        amount: $("#inbox").data("amount"),
        sortby: $("#inbox").data("sortby"),
        search: $("#inbox").data("search"),
        where: "AND favorite='yes'"
      };

    }else {
      var data = {
        action:"getmessage",
        pagi: $("#inbox").data("pagi"),
        amount: $("#inbox").data("amount"),
        sortby: $("#inbox").data("sortby"),
        search: $("#inbox").data("search")
      };

    }

    getmessage(data);

    $.ajax({
      type: "POST",
      url: url_ajax_request + "ajax/ajax.contact.php",
      data: {
        action: "getpaginationcontact"
      },

      success: function(msg){
        // console.log(msg);
        currentPage = $("#inbox").data("pagi");
        amount = $("#inbox").data("amount");
        if (currentPage == '') {
          currentPage = 1;
        }
        sumCateOnPage = currentPage*amount;
        numCate = ((currentPage-1)*amount)+1;
        if (msg < sumCateOnPage) {
          sumCate = msg;
        }else {
          sumCate = sumCateOnPage;
        }

        $("#page-number").html(numCate+"-"+sumCate+" จาก "+msg+" ");
        $("#msg-count").val(msg);
      }

    });

    $("#page").val(1);

  });

/////////////////////////////////////////////////////////////////////

  $(document).on('click', '.check-box, .mailbox-star', function(){
    return false;
  });

  $(document).on('click', '.new, .read', function(){
    var id = $(this).data("id"),
        topic = $(this).data("topic"),
        message = $(this).data("message"),
        name = $(this).data("name"),
        email = $(this).data("email"),
        phone = $(this).data("phone"),
        time = $(this).data("time"),
        status = $(this).data("status");

    if (status == "new") {
      var data = {
          action: "updatestatus",
          id: id
      };
      updatestatus(data);
    }

    if (phone != '') {
      var phoneNumber = '<i class="fa fa-phone" style="margin-left: 10px;"></i> '+phone+' ';
    }else {
      var phoneNumber = '';
    }

    $(".mail-box-menu").removeClass("active");
    $("#mail-box").hide();
    $("#read-mail").show();

    $(".mailbox-read-message p").html(message);
    $(".mailbox-read-title").html(topic);
    $(".mailbox-read-info h5").html('<i class="fa fa-user"></i> '+name+' \
      <i class="fa fa-envelope-o" style="margin-left: 10px;"></i> '+email+' \
      '+phoneNumber+' \
      <span class="mailbox-read-time pull-right">'+time+'</span>');
    $("#delete-contact").attr("data-id",id);
  });

  $(document).on('click', '#delete-contact', function(){
    var id = $("#delete-contact").attr("data-id");
    var data = {
      action: "deletecontact",
      id: id
    };

    $.confirm({
      title: 'Are you sure?',
      content: 'you want to delete this message.',
      theme: 'material',
      icon: 'fa fa-warning',
      type: 'red',
      draggable: false,
      buttons: {
        confirm:  {
          text: 'Yes, delete it!',
          btnClass: 'btn-red',
          action: function(){
            delete_contact(data);
          }
        },

        formCancel: {
          text: 'Cancel',
          cancel: function () {}  
        }
      }
    }); 
  });

  function delete_contact(data) {
    var url = "ajax/ajax.contact.php",
        dataSet = data;
    $.ajax({
      type: "POST",
      url: url,
      data: dataSet,
      success: function(data){
        var obj = jQuery.parseJSON(data);
        console.log(obj);
        if (obj['message'] === "OK") {
          $.confirm({
            title: 'Deleted!',
            content: 'Successfully Deleted!',
            theme: 'modern',
            icon: 'fa fa-check',
            type: 'darkgreen',
            draggable: false,
            backgroundDismiss: true,
            buttons: {
              confirm:  {
                text: 'OK',
                btnClass: 'btn-darkgreen',
                action: function(){
                  location.reload();
                }
              }
            },

            backgroundDismiss: function(){
              location.reload();
            }
          });
        }
      }
    });
  }

/////////////////////////////////////////////////////////////////////

  $("#prev-page").on('click', function () {
    var page = $("#page").val();
    var inbox = $("#inbox").hasClass("active");
    var mail_box_star = $("#mail-box-star").hasClass("active");
    if (mail_box_star) {
      var data = {
        action:"getmessage",
        pagi: parseInt(page)-1,
        amount: $("#inbox").data("amount"),
        sortby: $("#inbox").data("sortby"),
        search: $("#inbox").data("search"),
        where: "AND favorite='yes'"
      };
    }else {
      var data = {
        action:"getmessage",
        pagi: parseInt(page)-1,
        amount: $("#inbox").data("amount"),
        sortby: $("#inbox").data("sortby"),
        search: $("#inbox").data("search")
      };
    }

    msg = $("#msg-count").val();
    currentPage = data.pagi;
    amount = $("#inbox").data("amount");
    if (currentPage == '') {
      currentPage = 1;
    }

    sumCateOnPage = currentPage*amount;
    numCate = ((currentPage-1)*amount)+1;
    if (msg < sumCateOnPage) {
      sumCate = msg;
    }else {
      sumCate = sumCateOnPage;
    }

    if (data.pagi >= 1) {
      $("#page-number").html(numCate+"-"+sumCate+" จาก "+msg+" ");
      $("#page").val(data.pagi);
      getmessage(data);
    }
  });

  $("#next-page").on('click', function () {
    var page = $("#page").val();
    var inbox = $("#inbox").hasClass("active");
    var mail_box_star = $("#mail-box-star").hasClass("active");
    if (mail_box_star) {
      var data = {
        action:"getmessage",
        pagi: parseInt(page)+1,
        amount: $("#inbox").data("amount"),
        sortby: $("#inbox").data("sortby"),
        search: $("#inbox").data("search"),
        where: "AND favorite='yes'"
      };
    }else {
      var data = {
        action:"getmessage",
        pagi: parseInt(page)+1,
        amount: $("#inbox").data("amount"),
        sortby: $("#inbox").data("sortby"),
        search: $("#inbox").data("search")
      };
    }

    msg = $("#msg-count").val();
    currentPage = data.pagi;
    amount = $("#inbox").data("amount");
    if (currentPage == '') {
      currentPage = 1;
    }

    sumCateOnPage = currentPage*amount;
    numCate = ((currentPage-1)*amount)+1;
    if (msg < sumCateOnPage) {
      sumCate = msg;
    }else {
      sumCate = sumCateOnPage;
    }

    if (numCate <= sumCate) {
      $("#page-number").html(numCate+"-"+sumCate+" จาก "+msg+" ");
      $("#page").val(data.pagi);
      getmessage(data);
    }
  });

/////////////////////////////////////////////////////////////////////

  function updatestatus(data) {
    var dataSet = data
    $.ajax({
      type: "POST",
      url: url_ajax_request + "ajax/ajax.contact.php",
      data: dataSet,
      success:function(msg){
        var obj = jQuery.parseJSON(msg);
        // console.log(obj);
        if (obj == 'no_data') {
          $("#inbox span").hide();
          $("#contact .pull-right-container .bg-red").hide();
        }else {
          $("#inbox span").show();
          $("#inbox span").html(obj.length);
          $("#contact .pull-right-container .bg-red").html(obj.length);
        }
      }
    });
  }

  function getmessage(data) {
    
    var dataSet = data;
    $.ajax({
      type: "POST",
      url: url_ajax_request + "ajax/ajax.contact.php",
      data: dataSet,
      success:function(msg){
        var obj = jQuery.parseJSON(msg);

        // console.log(obj);
        if (obj.new == 0) {
          $("#inbox span").hide();
        }else {
          $("#inbox span").show();
          $("#inbox span").html(obj.new);
        }
        $("#messages-box").html(obj.data);
        $('.mailbox-messages input[type="checkbox"]').iCheck({
          // checkboxClass: 'icheckbox_flat',
          radioClass: 'iradio_flat'
        });

        var clicks = $(".checkbox-toggle").data('clicks');
        if (clicks) {
          $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
          $(".checkbox-toggle").data("clicks", !clicks);
        }
      }
    });
  }

  function mailboxstar(data) {
    var dataSet = data;
    $.ajax({
      type: "POST",
      url: url_ajax_request + "ajax/ajax.contact.php",
      data: dataSet,
      success:function(msg){
        var obj = jQuery.parseJSON(msg);
        console.log(obj);
      }
    });
  }