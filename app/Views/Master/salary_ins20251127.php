      <div class="app-title">
        <div>
          <h1>Input Master salary</h1>
          <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('home') ?>"><i class="fa fa-home fa-lg"></i></a></li>
            <li class="breadcrumb-item">Master</li>
            <li class="breadcrumb-item">Master salary</li>
          </ul>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item">
            <!-- <a href="<?= base_url('Master/Mt_salary/ins_view') ?>" class="btn btn-primary"><i class="fa fa-fw fa-lg fas fa-plus-circle "></i> New </a> -->
          </li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body">
              <!-- Check Your Valid URL -->
              <form class="form-horizontal" method="POST" action="/hris_new/Master/Mt_salary/ins">
                
              <div class="form-group col-md-3">
                <label class="control-label">Biodata ID</label>
                <select class="form-control" name="biodataId" id="biodataId">
                  <option value="" disabled="" selected="">Choose</option>
                  <?php 
                  foreach ($data_biodata as $key => $value) {
                  echo '<option value="'.$value->biodata_id.'">'.$value->full_name.' - '.$value->biodata_id.' </option>';
                  }
                  ?>
                </select>
              </div>
              <div class="form-group col-md-3">
                <label class="control-label">Bank</label>
                <select class="form-control" name="bankId" id="bankId">
                  <option value="" disabled="" selected="">Choose</option>
                  <?php 
                  foreach ($data_bank as $key => $value) {
                  echo '<option value="'.$value->bank_id.'">'.$value->bank_id.' - '.$value->bank_name.' </option>';
                  }
                  ?>
                </select>
              </div>
              <div class="form-group col-md-3">
                <label class="control-label">Account Number</label>
                <input class="form-control" name="accNo" id="accNo" type="text" placeholder="Account Number">
              </div>
              <div class="form-group col-md-3">
                <label class="control-label">Account Name</label>
                <input class="form-control" name="accName" id="accName" type="text" placeholder="Account Name">
              </div>
              <div class="form-group col-md-3">
                <label class="control-label">Monthly</label>
                <input class="form-control" name="monthly" id="monthly" type="text" placeholder="">
              </div>
              <div class="form-group col-md-3">
                <label class="control-label">Daily</label>
                <input class="form-control" name="daily" id="daily" type="text" disabled="">
              </div>
              <!-- <div class="form-group col-md-3"> Payroll Status </br>
              <input class="form-check-input" type="radio" name="payrollstatus" id="payrollstatus" value="month">
              <label class="form-check-label" for="flexRadioDefault1">
                Monthly&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              </label>            
              <input class="form-check-input" type="radio" name="payrollstatus" id="payrollstatus" value="daily">
              <label class="form-check-label" for="flexRadioDefault2">
                Daily&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp
              </label> 
              <input class="form-check-input" type="radio" name="payrollstatus" id="payrollstatus" value="daily pkwt" checked>
              <label class="form-check-label" for="flexRadioDefault3">
                Daily PKWT
              </label> 
            </div>  -->
                <!-- <div class="form-group row">
                  <label class="control-label col-md-2">Is Active</label>
                    <input type="checkbox" id="isActive" name="isActive" value="1">
                </div>
                  <div class="form-group col-sm-12 col-md-6 govReg">     
                <input type="checkbox" id="cbHealthBPJS" name="cbHealthBPJS" value="1">
                <label class="control-label" for="healthBPJS">Health BPJS</label>&nbsp;&nbsp;&nbsp;&nbsp; -->

                <!-- <input type="checkbox" id="cbEmpBPJS" name="cbEmpBPJS" checked="">
                <label class="control-label" for="empBPJS">Employment BPJS</label> -->

                <!-- <input type="checkbox" id="cbJHT" name="cbJHT" value="1">
                <label class="control-label" for="JHT">JHT</label>&nbsp;&nbsp;&nbsp;&nbsp;

                <input type="checkbox" id="cbJP" name="cbJP" value="1">
                <label class="control-label" for="JP">JP</label>&nbsp;&nbsp;&nbsp;&nbsp;

                <input type="checkbox" id="cbJKKM" name="cbJKKM" value="1">
                <label class="control-label" for="JKK-JKM">JKK-JKM</label>
                </div> -->
              </form>
            </div> <!-- class="tile-body" -->
            <div class="tile-footer">
              <button class="btn btn-primary" type="button" id="dbSave"><i class="fa fa-fw fa-lg fa-check-circle"></i>Save</button>
              <a class="btn btn-secondary" href="<?php echo base_url(); ?>/Master/Mt_salary/reset"><i class="fa fa-fw fa-lg fa fa-times-circle"></i>Cancel</a>
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
          $("#monthly").change(function(){
            let nomer = $("#monthly").val();
            hasil = nomer/20;
              $("#daily").val(hasil.toFixed(2));
          });

          $("#biodataId ").focus();
          $("#dbSave").on("click", function(){
             // let configId = $("#configId").val();
             let biodataId = $("#biodataId").val();
             let bankId = $("#bankId").val();
             let accNo = $("#accNo").val();
             let accName = $("#accName").val();
             // let isActive = $("#isActive").val();
             // let cbHealthBPJS = $("#cbHealthBPJS").val();
             // let cbJHT = $("#cbJHT").val();
             // let cbJP = $("#cbJP").val();
             // let cbJKKM = $("#cbJKKM").val();
             // let month = $("input[name='payrollstatus']:checked").val();

            /*alert(isActive)*/
             /*$(".errSaveMess").html("");
             if(biodataId.trim() == "")
             {
               $("#biodataId").focus();
               $(".errSaveMess").html("Conf Id cannot be empty");
             }
             else if(bankId.trim() == "")
             {
               $("#bankId").focus();
               $(".errSaveMess").html("Conf Code cannot be empty");
             }
             else if(accName.trim() == "")
             {
               $("#accName").focus();
               $(".errSaveMess").html("Conf Name cannot be empty");
             }
             else if(accNo.trim() == "")
             {
               $("#accNo").focus();
               $(".errSaveMess").html("Conf Value cannot be empty");
             }
             else if(monthly.trim() == "")
             {
               $("#monthly").focus();
               $(".errSaveMess").html("monthly cannot be empty");
             }
             else if(isActive.trim() == "")
             {
               $("#isActive").focus();
               $(".errSaveMess").html("Is Local cannot be empty");
             }*/
             /* ***Put URL your here */
             var myUrl ='<?php echo base_url() ?>/Master/Mt_salary/ins';
              

             // var isActive = "Y";
              // if ($('#isActive').is(":checked"))
              // { 
              //   isActive = "Y";
              // }              
              // else
              // {
              //   isActive = "T";
              // }
              // if ($('#cbHealthBPJS').is(":checked"))
              // { 
              //   cbHealthBPJS = "Y";
              // }               
              // else
              // {
              //   cbHealthBPJS = "T";
              // }
              // if ($('#cbJHT').is(":checked"))
              // { 
              //   cbJHT = "Y";
              // }              
              // else
              // {
              //   cbJHT = "T";
              // }
              // if ($('#cbJP').is(":checked"))
              // { 
              //   cbJP = "Y";
              // }              
              // else
              // {
              //   cbJP = "T";
              // }
              // if ($('#cbJKKM').is(":checked"))
              // { 
              //   cbJKKM = "Y";
              // }              
              // else
              // {
              //   cbJKKM = "T";
              // }
            
              

             $.ajax({
                url    : myUrl,
                method : "POST",
                data   : {
                   // configId : $("#configId").val(),
                   biodataId : $("#biodataId").val(),
                   bankId : $("#bankId").val(),
                   accNo : $("#accNo").val(),
                   accName : $("#accName").val(),
                   monthly : $("#monthly").val(),
                   // isActive,
                   // cbHealthBPJS,
                   // cbJHT,
                   // cbJP,
                   // cbJKKM,
                   // month,
                   daily : $("#daily").val()
                },
                success : function(data)
                {
                  toastr.success("Data has been Save.", 'Alert', {"positionClass": "toast-top-center"});
                   /* Your redirect is here */
                  setTimeout(function () {
                    window.location.href = baseUrl+'/Master/Mt_salary'; //will redirect to google.
                  }, 2000);
                }
             })
          });
        });
      </script>
