      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <h3 class="tile-title">salary Update</h3>
            <div class="tile-body">
              <!-- Check Your Valid URL -->
              <form class="form-horizontal" method="POST" action="../insData">
                <input class="form-control" name="salaryId" id="salaryId" type="hidden" value="<?php echo $mtSalary['salary_id'] ?>">
                <div class="form-group col-md-3">
                  <label class="control-label">Salary Id</label>
                  <input class="form-control" name="salaryId" id="salaryId" type="text" value="<?php echo $mtSalary['salary_id'] ?>" disabled="">
                </div>
                <div class="form-group col-md-3">
                  <label class="control-label">Employee Name</label>
                  <select class="form-control" name="biodataId" id="biodataId">
                    <option value="" disabled="" selected="">Choose</option>
                    <?php
                    foreach ($data_biodata as $key => $value) {
                      echo '<option value="' . $value->biodata_id . '" ' . ($value->biodata_id == $mtSalary['biodata_id'] ? 'selected' : '') . '>' . $value->full_name . ' - ' . $value->biodata_id . ' </option>';
                    }
                    ?>
                  </select>
                </div>
                <div class="form-group col-md-3">
                  <label class="control-label">Client Name</label>
                  <select class="form-control" name="clientName" id="clientName">
                    <option value="" disabled="" selected="">Choose</option>
                    <?php
                    foreach ($clients as $value) {
                      echo '<option value="' . $value['client_value'] . '" ' . ($value['client_value'] == $mtSalary['company_name'] ? 'selected' : '') . ' >' . $value['client_name'] . ' - ' . $value['client_value'] . ' </option>';
                    }
                    ?>
                  </select>
                </div>
                <div class="form-group col-md-3">
                  <label class="control-label">Bank Id</label>
                  <select class="form-control" name="bankId" id="bankId">
                    <option value="" disabled="">Choose</option>
                    <?php
                    foreach ($data_bank as $key => $value) {
                      echo '<option value="' . $value->bank_id . '" ' . ($value->bank_id == $mtSalary['bank_id'] ? 'selected' : '') . '>' . $value->bank_name . ' - ' . $value->bank_id . ' </option>';
                    }
                    ?>
                  </select>
                </div>
                <div class="form-group col-md-3">
                  <label class="control-label">Account Number</label>
                  <input class="form-control" name="accNo" id="accNo" type="number" placeholder="Monthly" value="<?php echo $mtSalary['account_no'] ?>">
                </div>
                <div class="form-group col-md-3">
                  <label class="control-label">Account Name</label>
                  <input class="form-control" name="accName" id="accName" type="text" placeholder="Account Name" value="<?php echo $mtSalary['account_name'] ?>">
                </div>
                <div class="form-group col-md-3">
                  <label class="control-label">Monthly</label>
                  <input class="form-control" name="monthly" id="monthly" type="number" placeholder="Monthly" value="<?php echo $mtSalary['monthly'] ?>">
                </div>
                <div class="form-group col-md-3">
                  <label class="control-label">Daily</label>
                  <input class="form-control" name="daily" id="daily" type="number" placeholder="Daily" value="<?php echo $mtSalary['daily'] ?>" readonly>
                </div>


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
      <script src="<?php echo base_url(); ?>/assets/js/main.js"></script>
      <script>
        // $("#biodataId").select2({
        // placeholder: 'Select a Biodata Id',
        // allowClear: true});

        // $("#classId").select2().on('select2:select',function(e){//untuk change box
        // // debugger
        //   var classWage     = $('#classId option:selected').attr('data-class');
        //   var classCurrency = $('#classId option:selected').attr('kind-class'); 
        //   $('#classCurrency').val(classCurrency);//#ngambil dari IDnya////val kalau kosong mengambil, kalau diisi mengisi//
        //   $('#classWage').val(classWage);
        // });

        // var msSalaryBiodata = "<?php echo $mtSalary['biodata_id'] ?>";
        // // var msClassId       = "<?php //echo $mtSalary['class_id'] 
                                      ?>";
        // var msBankId   = "<?php echo $mtSalary['bank_id'] ?>";
        // // var msForeignBankId = "<?php //echo $mtSalary['foreign_bank_id'] 
                                      ?>";

        //   if( (msSalaryBiodata != "") && (msSalaryBiodata != undefined) )
        //   {
        //     $('#biodataId option[value="'+msSalaryBiodata+'"]').attr('selected','selected');
        //   }
        //   if( (msBankId != "") && (msBankId != undefined) )
        //   {
        //     $('#bankId option[value="'+msBankId+'"]').attr('selected','selected');
        //   }



        $(document).ready(function() {
          var baseUrl = '<?php echo base_url() ?>';
          $("#monthly").change(function() {
            let nomer = $("#monthly").val();
            hasil = nomer / 20.;
            $("#daily").val(hasil.toFixed(2));
          });

          var myUrl = '<?php echo base_url() ?>/Master/Mt_salary/upd';

          $("#dbSave").on("click", function() {
            /*alert(myUrl);*/
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
            // var myUrl ='<?php echo base_url() ?>/Master/Mt_salary/ins';


            // var isActive = "Y";
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
              url: myUrl,
              type: "POST",
              data: {
                salaryId: $('#salaryId').val(),
                biodataId: $('#biodataId').val(),
                accNo: accNo,
                accName: accName,
                // cbHealthBPJS,
                //    cbJHT,
                //    cbJP,
                //    cbJKKM,
                // biodataKind     : $('#biodataId option:selected').attr('data-biodata'),
                idNo: $('#idNo').val(),
                bankId: $('#bankId').val(),
                monthly: $('#monthly').val(),
                client: $('#clientName').val(),
                daily: $('#daily').val()
                // month : month
              },

              success: function(resp) {
                toastr.success("Data has been Save.", 'Alert', {
                  "positionClass": "toast-top-center"
                });
                /*Your redirect is here */
                setTimeout(function() {
                  window.location.href = baseUrl + '/Master/Mt_salary'; //will redirect to google.
                }, 2000);
              }
            })
          });
        });
      </script>