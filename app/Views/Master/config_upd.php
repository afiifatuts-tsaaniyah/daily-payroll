      <div class="row">
        <div class="col-md-12">
      	 	<div class="tile">
      	 	  <h3 class="tile-title">Config Update</h3>
      	 	  <div class="tile-body">
        		  <!-- Check Your Valid URL -->
              <form class="form-horizontal" method="POST" action="../upd">
                <div class="form-group col-md-3">
                <label class="control-label">Conf Id</label>
                <input class="form-control" name="confId" id="confId" type="text" value="<?php echo $mtConfig['conf_id'] ?>">
              </div>
              <div class="form-group col-md-3">
                <label class="control-label">Conf Code</label>
                <input class="form-control" name="confCode" id="confCode" type="text" value="<?php echo $mtConfig['conf_code'] ?>">
              </div>
              <div class="form-group col-md-3">
                <label class="control-label">Conf Name</label>
                <input class="form-control" name="confName" id="confName" type="text" value="<?php echo $mtConfig['conf_name'] ?>">
              </div>
              <div class="form-group col-md-3">
                <label class="control-label">Conf Value</label>
                <input class="form-control" name="confValue" id="confValue" type="text" value="<?php echo $mtConfig['conf_value'] ?>">
              </div>
              <div class="form-group col-md-3">
                <label class="control-label">Remarks</label>
                <input class="form-control" name="remarks" id="remarks" type="text" value="<?php echo $mtConfig['remarks'] ?>">
              </div>
                <!-- <div class="form-group row">
                  <label class="control-label col-md-2">Is Local</label>
                  <div class="col-md-1">
                    <input type="checkbox" id="isActive" name="isActive" value="1" <?= ($mtConfig['is_active']==1)? 'checked' : ''; ?> >
                  </div>
                </div> -->
      	 	  </div> <!-- class="tile-body" -->
              </form>
      	 	  <div class="tile-footer">
      	 	    <button class="btn btn-primary" type="button" id="dbSave"><i class="fa fa-fw fa-lg fa-check-circle"></i>Save</button>
              <a class="btn btn-secondary" href="<?php echo base_url(); ?>/master/mt_Config/reset"><i class="fa fa-fw fa-lg fa fa-times-circle"></i>Cancel</a>
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
          $("#ConfigId").focus();
          $("#dbSave").on("click", function(){
             let confId = $("#confId").val();
             let confCode = $("#confCode").val();
             let confName = $("#confName").val();
             let confValue = $("#confValue").val();
             let remarks = $("#remarks").val();
             let picEdit  = $("#picEdit").val();
             let editTime = $("#editTime").val();
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
             // else if(isActive.trim() == "")
             // {
             //   $("#isActive").focus();
             //   $(".errSaveMess").html("Is Local cannot be empty");
             // }
      	 	  /* ***Put URL your here */
             var myUrl ='<?php echo base_url() ?>/Master/Mt_Config/upd';
             var msSalaryBiodata = "<?php echo $mtSalary['biodata_id'] ?>";
            // var msClassId       = "<?php //echo $mtSalary['class_id'] ?>";
             var msBankId   = "<?php echo $mtSalary['bank_id'] ?>";

             // var isActive = "Y";
          

             $.ajax({
                url    : myUrl,
                method : "POST",
                data   : {
                   confId : $("#confId").val(),
                   confCode : $("#confCode").val(),
                   confName : $("#confName").val(),
                   confValue : $("#confValue").val(),
                   remarks : $("#remarks").val(),
                   picEdit   : $("#picEdit").val(),
                   editTime  : $("#editTime").val()
                },
                success : function(data)
                {
      	 	        toastr.success("Data has been Save.", 'Alert', {"positionClass": "toast-top-center"});
                   /* Your redirect is here */
                  setTimeout(function () {
                    window.location.href = baseUrl+'/Master/Mt_Config'; //will redirect to google.
                  }, 2000);
                }
             })
          });
        });
      </script>
