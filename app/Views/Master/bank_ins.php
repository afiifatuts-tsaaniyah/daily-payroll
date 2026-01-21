      <div class="app-title">
        <div>
          <h1>Input Master Bank</h1>
          <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('home') ?>"><i class="fa fa-home fa-lg"></i></a></li>
            <li class="breadcrumb-item">Master</li>
            <li class="breadcrumb-item">Master Bank</li>
          </ul>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item">
            <!-- <a href="<?= base_url('Master/Mt_bank/ins_view') ?>" class="btn btn-primary"><i class="fa fa-fw fa-lg fas fa-plus-circle "></i> New </a> -->
          </li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body">
              <!-- Check Your Valid URL -->
              <form class="form-horizontal" method="POST" action="../insData">
                <div class="form-group row">
                  <label class="control-label col-md-2">Bank Code</label>
                  <div class="col-md-1">
                    <input class="form-control" name="bankCode" id="bankCode" type="text" placeholder="Code">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-2">Bank Name</label>
                  <div class="col-md-3">
                    <input class="form-control" name="bankName" id="bankName" type="text" placeholder="Bank Name">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-2">Is Local</label>
                  <div class="col-md-1">
                    <input type="checkbox" id="isLocal" name="isLocal" value="1">
                  </div>
                </div>
              </form>
            </div> <!-- class="tile-body" -->
            <div class="tile-footer">
              <button class="btn btn-primary" type="button" id="dbSave"><i class="fa fa-fw fa-lg fa-check-circle"></i>Save</button>
              <a class="btn btn-secondary" href="<?php echo base_url(); ?>/master/mt_bank/reset"><i class="fa fa-fw fa-lg fa fa-times-circle"></i>Cancel</a>
              <!-- &nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="#"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancel</a> -->
              <strong>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <span style="color: red" class="errSaveMess"></span>
              </strong>
            </div>
          </div> <!-- class="tile" -->
         </div> <!-- class="col-md-12 -->
      </div> <!-- class="row" -->
      <!-- ***Using Valid js Path -->
      <script src="<?php echo base_url()?>/assets/js/main.js"></script>
      <script>
        $(document).ready(function() {
          var baseUrl = '<?php echo base_url()?>';
          $("#bankId").focus();
          $("#dbSave").on("click", function(){
             // let bankId = $("#bankId").val();
             let bankCode = $("#bankCode").val();
             let bankName = $("#bankName").val();
             let isLocal = $("#isLocal").val();
             let inputTime = $('#inputTime').val();
             let picInput = $('#picInput').val();
             $(".errSaveMess").html("");
             if(bankCode.trim() == "")
             {
               $("#bankCode").focus();
               $(".errSaveMess").html("Bank Code cannot be empty");
             }
             else if(bankName.trim() == "")
             {
               $("#bankName").focus();
               $(".errSaveMess").html("Bank Name cannot be empty");
             }
             else if(isLocal.trim() == "")
             {
               $("#isLocal").focus();
               $(".errSaveMess").html("Is Local cannot be empty");
             }
             /* ***Put URL your here */
             var myUrl ='<?php echo base_url() ?>/Master/Mt_bank/insData';

             // var isLocal = "Y";
              if ($('#isLocal').is(":checked"))
              { 
                isLocal = "Y";
              }              
              else
              {
                isLocal = "T";
              }

             $.ajax({
                url    : myUrl,
                method : "POST",
                data   : {
                   // bankId : $("#bankId").val(),
                   bankCode : $("#bankCode").val(),
                   bankName : $("#bankName").val(),
                   isLocal,
                   picInput : $("#picInput").val(),
                   inputTime : $("#inputTime").val()
                },
                success : function(data)
                {
                  toastr.success("Data has been Save.", 'Alert', {"positionClass": "toast-top-center"});
                   /* Your redirect is here */
                  setTimeout(function () {
                    window.location.href = baseUrl+'/Master/Mt_bank'; //will redirect to google.
                  }, 2000);
                }
             })
          });
        });
      </script>
