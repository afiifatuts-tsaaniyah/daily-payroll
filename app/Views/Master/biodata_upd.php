      <div class="row">
        <div class="col-md-12">
      	 	<div class="tile">
      	 	  <h3 class="tile-title">Biodata Update</h3>
      	 	  <div class="tile-body">
        		  <!-- Check Your Valid URL -->
              <form class="form-horizontal" method="POST" action="/hris_new/Master/Biodata/upd">
              <!-- Hidden Id -->
              <input class="form-control" name="BiodataId" id="BiodataId" type="hidden" value="<?php echo $mtBiodata['biodata_id'] ?>">
            <?php 
            // echo $mtBiodata['dept'];
            // dd($data_dept);
            ?>   
             <div class="form-group row">
                <label class="control-label col-md-2">Dept</label>
                <div class="col-md-3">
                <select  name="dept" id="dept">
                  <option value="" disabled="" selected="">Choose</option>
                  <?php 
                  foreach ($data_dept as $key => $value) {
                    if($value->dept_name==$mtBiodata['dept']){
                      echo '<option data-code="'.$value->dept_code.'" value="'.$value->dept_name.'" selected>'.$value->dept_name.' </option>';
                    }else{
                      echo '<option data-code="'.$value->dept_code.'" value="'.$value->dept_name.'">'.$value->dept_name.' </option>';
                    }
                  
                  }
                  ?>
                </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="control-label col-md-2">Position</label>
                <div class="col-md-3">
                <input class="form-control" id="empPosition" name="empPosition" type="text" value="<?php echo $mtBiodata['emp_position'] ?>">
              </div>
            </div>
              <div class="form-group row">
                <label class="control-label col-md-2">Place Of Birth</label>
                <div class="col-md-3">
                <input class="form-control" id="placeOfBirth" name="placeOfBirth" type="text" value="<?php echo $mtBiodata['place_of_birth'] ?>">
                
              </div>
            </div>
              <div class="form-group row">
                <label class="control-label col-md-2">Date Of Birth</label>
                <div class="col-md-3">
                <input class="form-control" id="dateOfBirth" name="dateOfBirth" type="date" value="<?php echo $mtBiodata['date_of_birth'] ?>">
              </div>
            </div>
              <div class="form-group row">
                <label class="control-label col-md-2" for="gender">Gender</label>
                  <div class="col-md-3">
                <select name="gender" id="gender">
                  <option value="" disabled="" selected="">Choose</option>
                  <option value="Male" <?= ($mtBiodata['gender']=='Male')? 'selected' : ''; ?>>Male</option>
                  <option value="Female" <?= ($mtBiodata['gender']=='Female')? 'selected' : ''; ?>>Female</option>
                </select>                  
              </div>
            </div>
               <div class="form-group row">
                <label class="control-label col-md-2">Ethnic</label>
                <div class="col-md-3">
                <input class="form-control" id="ethnic" name="ethnic" type="text" value="<?php echo $mtBiodata['ethnic'] ?>">
              </div>
            </div>
              <div class="form-group row">
                <label class="control-label col-md-2">Nationality</label>
                <div class="col-md-3">
                <input class="form-control" id="nationality" name="nationality" type="text" value="<?php echo $mtBiodata['nationality'] ?>">
              </div>
            </div>
              <div class="form-group row">
                <label class="control-label col-md-2">Placement</label>
                <div class="col-md-3">
                <input class="form-control" id="placement" name="placement" type="text" value="<?php echo $mtBiodata['placement'] ?>">
              </div> 
              </div>
              <div class="form-group row">
                <label class="control-label col-md-2">Join Date</label>
                <div class="col-md-3">
                <input class="form-control" id="joinDate" name="joinDate" type="date" value="<?php echo $mtBiodata['join_date'] ?>">
              </div>
            </div>
              <div class="form-group row">
                <label class="control-label col-md-2">ID Card Number</label>
                <div class="col-md-3">
                <input class="form-control" id="idCardNo" name="idCardNo" type="number" value="<?php echo $mtBiodata['id_card_no'] ?>">
              </div> 
            </div>
              <div class="form-group row">
                <label class="control-label col-md-2">ID Card Address</label>
                <div class="col-md-3">
                <input class="form-control" id="idCardAddress" name="idCardAddress" type="text" value="<?php echo $mtBiodata['id_card_address'] ?>">
              </div>
              </div> 
              <div class="form-group row">
                <label class="control-label col-md-2">Current Address</label>
                <div class="col-md-3">
                <input class="form-control" id="currentAddress" name="currentAddress" type="text" value="<?php echo $mtBiodata['current_address'] ?>">
              </div>
              </div>
              <div class="form-group row">
                <label class="control-label col-md-2">Religion</label>
                <div class="col-md-3">
                <input class="form-control" id="religion" name="religion" type="text" value="<?php echo $mtBiodata['religion'] ?>">
              </div>
              </div> 
              <div class="form-group row">
                <label class="control-label col-md-2">Driving License</label>
                <div class="col-md-3">
                <input class="form-control" id="drivingLicense" name="drivingLicense" type="text" value="<?php echo $mtBiodata['driving_license'] ?>">
              </div>
              </div> 
              <div class="form-group row">
                <label class="control-label col-md-2" for="maritalStatus">Marital Status</label>
                <div class="col-md-3">
                <select id="maritalStatus" name="maritalStatus">
                  <option value="" disabled="" <?= ($mtBiodata['marital_status']=='')? 'selected' : ''; ?>>Choose</option>
                  <option value="TK0" <?= ($mtBiodata['marital_status']=='TK0')? 'selected' : ''; ?>>TK0</option>
                  <option value="K0"  <?= ($mtBiodata['marital_status']=='K0')? 'selected' : ''; ?>>K0</option>
                  <option value="K1"  <?= ($mtBiodata['marital_status']=='K1')? 'selected' : ''; ?>>K1</option>
                  <option value="K2"  <?= ($mtBiodata['marital_status']=='K2')? 'selected' : ''; ?>>K2</option>
                  <option value="K3"  <?= ($mtBiodata['marital_status']=='K3')? 'selected' : ''; ?>>K3</option>
                </select>                  
              </div>
              </div>
            </div>
              <div class="form-group row">
                <label class="control-label col-md-2">Height</label>
                <div class="col-md-3">
                <input class="form-control" id="empHeight" name="empHeight" type="number" value="<?php echo $mtBiodata['emp_height'] ?>">
              </div>
              </div> 
              <div class="form-group row">
                <label class="control-label col-md-2">Weight</label>
                <div class="col-md-3">
                <input class="form-control" id="empWeight" name="empWeight" type="number" value="<?php echo $mtBiodata['emp_weight'] ?>">
              </div>
              </div> 
              <div class="form-group row">
                <label class="control-label col-md-2">Blood Type</label>
                <div class="col-md-3">
                <input class="form-control" id="bloodType" name="bloodType" type="text" value="<?php echo $mtBiodata['blood_type'] ?>">
              </div>
              </div> 
              <div class="form-group row">
                <label class="control-label col-md-2">Email</label>
                <div class="col-md-3">
                <input class="form-control" id="emailAddress" name="emailAddress" type="text" value="<?php echo $mtBiodata['email_address'] ?>">
              </div>
              </div> 
              <div class="form-group row">
                <label class="control-label col-md-2">Telp No</label>
                <div class="col-md-3">
                <input class="form-control" id="telpNo" name="telpNo" type="number" value="<?php echo $mtBiodata['telp_no'] ?>">
              </div>
              </div> 
              <div class="form-group row">
                <label class="control-label col-md-2">Cell No</label>
                <div class="col-md-3">
                <input class="form-control" id="cellNo" name="cellNo" type="number" value="<?php echo $mtBiodata['cell_no'] ?>">
              </div>
              </div> 
              <div class="form-group row">
                <label class="control-label col-md-2" for="isGlasses">Glasses</label>
                 <div class="col-md-3">
                <select id="isGlasses" name="isGlasses">
                  <option value="" disabled="" selected="">Choose</option>
                  <option value="0" <?= ($mtBiodata['is_glasses']=='0')? 'selected' : ''; ?>>No</option>
                  <option value="1" <?= ($mtBiodata['is_glasses']=='1')? 'selected' : ''; ?>>Yes</option>
                </select>                  
              </div>
            </div>
              <div class="form-group row">
                <label class="control-label col-md-2">Emp Status</label>
                <div class="col-md-3">
                <!-- <input class="form-control" id="empStatus" name="empStatus" type="text" value="<?php echo $mtBiodata['emp_status'] ?>">  -->
                <select id="empStatus" name="empStatus">
                  <option value="" disabled="" selected="">Choose</option>
                  <option value="Daily" <?= ($mtBiodata['emp_status']=='Daily')? 'selected' : ''; ?>>Daily</option>
                  <option value="Daily Pkwt" <?= ($mtBiodata['emp_status']=='Daily Pkwt')? 'selected' : ''; ?>>Daily PKWT</option>
                  <option value="Monthly" <?= ($mtBiodata['emp_status']=='Monthly')? 'selected' : ''; ?>>Monthly</option>
                </select>               
              </div>
            </div>
              <div class="form-group row">
                <label class="control-label col-md-2">Tax No</label>
                <div class="col-md-3">
                <input class="form-control" id="taxNo" name="taxNo" type="number" value="<?php echo $mtBiodata['tax_no'] ?>">
              </div>
              </div> 
              <div class="form-group row">
                <label class="control-label col-md-2">BPJS No</label>
                <div class="col-md-3">
                <input class="form-control" id="bpjsNo" name="bpjsNo" type="number" value="<?php echo $mtBiodata['bpjs_no'] ?>">
              </div>
              </div> 
              <div class="form-group row">
                <label class="control-label col-md-2">BPJS TK No</label>
                <div class="col-md-3">
                <input class="form-control" id="bpjsTkNo" name="bpjsTkNo" type="number" value="<?php echo $mtBiodata['bpjs_tk_no'] ?>">
              </div>
              </div> 
              <div class="form-group row">
                  <label class="control-label col-md-2">BPJS Status</label>
                  <div class="col-md-1">
                    <input type="checkbox" id="bpjsStatus" name="bpjsStatus" value="1" <?php if ($mtBiodata['bpjs_status'] == 1){
                      echo 'checked'; } ?> >
                  </div>
                </div>
              
      	 	   <!-- class="tile-body" -->
              </form>
      	 	  <div class="tile-footer">
      	 	    <button class="btn btn-primary" type="button" id="dbSave"><i class="fa fa-fw fa-lg fa-check-circle"></i>Save</button>
              <a class="btn btn-secondary" href="<?php echo base_url(); ?>/Master/Biodata/reset"><i class="fa fa-fw fa-lg fa fa-times-circle"></i>Cancel</a>
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
        var baseUrl = '<?php echo base_url() ?>';
        var msBiodataDept = "<?php echo $mtBiodata['dept_id'] ?>";
        if( (msBiodataDept != "") && (msBiodataDept != undefined) )
        {
          $('#deptId option[value="'+msBiodataDept+'"]').attr('selected','selected');
        }
        
        // alert(BiodataId);
        $('#dbSave').on('click', function(){
          
          let BiodataId   = $("#BiodataId").val();
          // alert(BiodataId)
          // if(!$('#firstName').val()){
          //       alert("First Name Can't Be Empty")
          //       $('#firstName').focus();
          //       return false;
          //       }
                if ($('#bpjsStatus').is(":checked"))
                { 
                  bpjsStatus = "1";
                }              
                else
                {
                  bpjsStatus = "0";
                }
                $.ajax({
                  url    : '<?php echo base_url() ?>/Master/Biodata/upd',
                  method : "POST",
                  data   : {
                    /* Header */
                    BiodataId       : BiodataId,
                    deptId          : $('#deptId option:selected').attr('data-code'),
                    dept            : $('#dept').val(),
                    placeOfBirth    : $('#placeOfBirth').val(),
                    dateOfBirth     : $('#dateOfBirth').val(),
                    gender          : $('#gender').val(),
                    ethnic          : $('#ethnic').val(),
                    nationality     : $('#nationality').val(),
                    empPosition     : $('#empPosition').val(),
                    placement       : $('#placement').val(),
                    joinDate        : $('#joinDate').val(),
                    idCardNo        : $('#idCardNo').val(),
                    idCardAddress   : $('#idCardAddress').val(),
                    currentAddress  : $('#currentAddress').val(),
                    religion        : $('#religion').val(),
                    drivingLicense  : $('#drivingLicense').val(),
                    maritalStatus   : $('#maritalStatus').val(),
                    empHeight       : $('#empHeight').val(),
                    empWeight       : $('#empWeight').val(),
                    bloodType       : $('#bloodType').val(),
                    emailAddress    : $('#emailAddress').val(),
                    telpNo          : $('#telpNo').val(),
                    cellNo          : $('#cellNo').val(),
                    isGlasses       : $('#isGlasses').val(),
                    empStatus       : $('#empStatus').val(),
                    taxNo           : $('#taxNo').val(),
                    bpjsNo          : $('#bpjsNo').val(),
                    bpjsTkNo        : $('#bpjsTkNo').val(),
                    bpjsStatus      : bpjsStatus
                    },
                  success : function(data)
                {
                  toastr.success("Data has been Save.", 'Alert', {"positionClass": "toast-top-center"});
                   /* Your redirect is here */
                  setTimeout(function () {
                    window.location.href = baseUrl+'/Master/Biodata'; //will redirect to google.
                  }, 2000);
                }
             })
    });
      });
      </script>
