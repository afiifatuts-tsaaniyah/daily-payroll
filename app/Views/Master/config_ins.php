      <div class="app-title">
        <div>
          <h1>Input Master config</h1>
          <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('home') ?>"><i class="fa fa-home fa-lg"></i></a></li>
            <li class="breadcrumb-item">Master</li>
            <li class="breadcrumb-item">Master config</li>
          </ul>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item">
            <!-- <a href="<?= base_url('Master/Mt_config/ins_view') ?>" class="btn btn-primary"><i class="fa fa-fw fa-lg fas fa-plus-circle "></i> New </a> -->
          </li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body">
              <!-- Check Your Valid URL -->
              <form class="form-horizontal" method="POST" action="../insData">
                <div class="form-group col-md-3">
                                <label class="control-label">Conf Id</label>
                                <input class="form-control" name="confId" id="confId" type="text" placeholder="Config ID">
                              </div>
                              <div class="form-group col-md-3">
                                <label class="control-label">Conf Code</label>
                                <input class="form-control" name="confCode" id="confCode" type="text" placeholder="Config Code">
                              </div>
                              <div class="form-group col-md-3">
                                <label class="control-label">Conf Name</label>
                                <input class="form-control" name="confName" id="confName" type="text" placeholder="Config Name">
                              </div>
                              <div class="form-group col-md-3">
                                <label class="control-label">Conf Value</label>
                                <input class="form-control" name="confValue" id="confValue" type="text" placeholder="Config Value">
                              </div>
                              <div class="form-group col-md-3">
                                <label class="control-label">Remarks</label>
                                <input class="form-control" name="remarks" id="remarks" type="text" placeholder="Id Karyawan">
                              </div>
                              <div class="form-group row">
                  <label class="control-label col-md-2">Is Aktif</label>
                  <div class="col-md-1">
                    <input type="checkbox" id="isActive" name="isActive" value="1">
                  </div>
                </div>
              </div>
              </form>
            </div> <!-- class="tile-body" -->
            <div class="tile-footer">
              <button class="btn btn-primary" type="button" id="dbSave"><i class="fa fa-fw fa-lg fa-check-circle"></i>Save</button>
              <a class="btn btn-secondary" href="<?php echo base_url(); ?>/master/mt_config/reset"><i class="fa fa-fw fa-lg fa fa-times-circle"></i>Cancel</a>
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
          $("#configId").focus();
          $("#dbSave").on("click", function(){
             // let configId = $("#configId").val();
             let confId = $("#confId").val();
             let confCode = $("#confCode").val();
             let confName = $("#confName").val();
             let confValue = $("#confValue").val();
             let remarks = $("#remarks").val();
             let isActive = $("#isActive").val();
             let inputTime = $('#inputTime').val();
             let picInput = $('#picInput').val();
             $(".errSaveMess").html("");
             if(confId.trim() == "")
             {
               $("#confId").focus();
               $(".errSaveMess").html("Conf Id cannot be empty");
             }
             else if(confCode.trim() == "")
             {
               $("#confCode").focus();
               $(".errSaveMess").html("Conf Code cannot be empty");
             }
             else if(confName.trim() == "")
             {
               $("#confName").focus();
               $(".errSaveMess").html("Conf Name cannot be empty");
             }
             else if(confValue.trim() == "")
             {
               $("#confValue").focus();
               $(".errSaveMess").html("Conf Value cannot be empty");
             }
             else if(remarks.trim() == "")
             {
               $("#remarks").focus();
               $(".errSaveMess").html("Remarks cannot be empty");
             }
             else if(isActive.trim() == "")
             {
               $("#isActive").focus();
               $(".errSaveMess").html("Is Local cannot be empty");
             }
             /* ***Put URL your here */
             var myUrl ='<?php echo base_url() ?>/Master/Mt_config/ins';

             // var isActive = "Y";
              if ($('#isActive').is(":checked"))
              { 
                isActive = "Y";
              }              
              else
              {
                isActive = "T";
              }

             $.ajax({
                url    : myUrl,
                method : "POST",
                data   : {
                   // configId : $("#configId").val(),
                   confId : $("#confId").val(),
                   confCode : $("#confCode").val(),
                   confName : $("#confName").val(),
                   confValue : $("#confValue").val(),
                   remarks : $("#remarks").val(),
                   isActive,
                   picInput : $("#picInput").val(),
                   inputTime : $("#inputTime").val()
                },
                success : function(data)
                {
                  toastr.success("Data has been Save.", 'Alert', {"positionClass": "toast-top-center"});
                   /* Your redirect is here */
                  setTimeout(function () {
                    window.location.href = baseUrl+'/Master/Mt_config'; //will redirect to google.
                  }, 2000);
                }
             })
          });
        });
      </script>
