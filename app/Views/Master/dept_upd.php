      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <h3 class="tile-title">dept Update</h3>
            <div class="tile-body">
              <!-- Check Your Valid URL -->
              <form class="form-horizontal" method="POST" action="../insData">
                <div class="form-group row">
                  <label class="control-label col-md-2">dept Code</label>
                  <div class="col-md-1">
                    <input class="form-control" name="deptCode" id="deptCode" type="text" value="<?php echo $mtdept['dept_code'] ?>">
                    <input class="form-control" name="deptId" id="deptId" type="hidden" value="<?php echo $mtdept['dept_id'] ?>">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-2">dept Name</label>
                  <div class="col-md-3">
                    <input class="form-control" name="deptName" id="deptName" type="text" value="<?php echo $mtdept['dept_name'] ?>">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="control-label col-md-2">Is Active</label>
                  <div class="col-md-1">
                    <input type="checkbox" id="isActive" name="isActive" value="1" <?= ($mtdept['is_active']==1)? 'checked' : ''; ?> >
                  </div>
                </div>
              </form>
            </div> <!-- class="tile-body" -->
            <div class="tile-footer">
              <button class="btn btn-primary" type="button" id="dbSave"><i class="fa fa-fw fa-lg fa-check-circle"></i>Save</button>
              <a class="btn btn-secondary" href="<?php echo base_url(); ?>/master/mt_dept/reset"><i class="fa fa-fw fa-lg fa fa-times-circle"></i>Cancel</a>
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
          $("#deptId").focus();
          $("#dbSave").on("click", function(){
             let deptId   = $("#deptId").val();
             let deptCode = $("#deptCode").val();
             let deptName = $("#deptName").val();
             let isActive  = $("#isActive").val();
             let picEdit  = $("#picEdit").val();
             let editTime = $("#editTime").val();
             $(".errSaveMess").html("");
             if(deptId.trim() == "")
             {
               $("#deptId").focus();
               $(".errSaveMess").html("dept Id cannot be empty");
             }
             else if(deptCode.trim() == "")
             {
               $("#deptCode").focus();
               $(".errSaveMess").html("dept Code cannot be empty");
             }
             else if(deptName.trim() == "")
             {
               $("#deptName").focus();
               $(".errSaveMess").html("dept Name cannot be empty");
             }
             // else if(isActive.trim() == "")
             // {
             //   $("#isActive").focus();
             //   $(".errSaveMess").html("Is Active cannot be empty");
             // }
            /* ***Put URL your here */
             var myUrl ='<?php echo base_url() ?>/Master/Mt_dept/editData';

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
                   deptId    : $("#deptId").val(),
                   deptCode  : $("#deptCode").val(),
                   deptName  : $("#deptName").val(),
                   isActive,
                   picEdit   : $("#picEdit").val(),
                   editTime  : $("#editTime").val()
                },
                success : function(data)
                {
                  toastr.success("Data has been Save.", 'Alert', {"positionClass": "toast-top-center"});
                   /* Your redirect is here */
                  setTimeout(function () {
                    window.location.href = baseUrl+'/Master/Mt_dept'; //will redirect to google.
                  }, 2000);
                }
             })
          });
        });
      </script>
