      <?php $session = \Config\Services::session(); ?>
      <div class="app-title">
        <div>
          <h1>Input Master biodata</h1>
          <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('home') ?>"><i class="fa fa-home fa-lg"></i></a></li>
            <li class="breadcrumb-item">Master</li>
            <li class="breadcrumb-item">Master biodata</li>
          </ul>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item">
            <!-- <a href="<?= base_url('Master/Biodata/ins_view') ?>" class="btn btn-primary"><i class="fa fa-fw fa-lg fas fa-plus-circle "></i> New </a> -->
          </li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body">
              <!-- Check Your Valid URL -->
              <form class="form-horizontal" method="POST" action="/hris_new/Master/Biodata/ins">

                <div class="form-group row">
                  <label class="control-label col-md-2">Full Name</label>
                  <div class="col-md-3">
                <input class="form-control" id="fullName" name="fullName" type="text" placeholder="Full Name (Wajib Diisi)" required="">
                  </div>
                </div>
              <div class="form-group row">
                <label class="control-label col-md-2">Place Of Birth</label>
                <div class="col-md-3">
                <input class="form-control" id="placeOfBirth" name="placeOfBirth" type="text" placeholder="Tempat Lahir">
              </div>
              </div>
              <div class="form-group row">
                <label class="control-label col-md-2">Date Of Birth</label>
                <div class="col-md-3">
                <input class="form-control" id="dateOfBirth" name="dateOfBirth" type="date" value="<?php echo date('Y-m-d') ?>">
              </div>
              </div>
              <div class="form-group row">
                <label class="control-label col-md-2" for="gender">Gender</label>
                <div class="col-md-3">
                <select  name="gender" id="gender">
                  <option value="" disabled="" selected="">Choose</option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                </select>       
                </div>           
              </div>
               <div class="form-group row">
                <label class="control-label col-md-2">Ethnic</label>
                <div class="col-md-3">
                <input class="form-control" id="ethnic" name="ethnic" type="text" placeholder="Etnis">
              </div>
            </div>
              <div class="form-group row">
                <label class="control-label col-md-2">Nationality</label>
                <div class="col-md-3">
                <input class="form-control" id="nationality" name="nationality" type="text" placeholder="Kebangsaan">
              </div>
            </div>
              <div class="form-group row">
                <label class="control-label col-md-2">Dept</label>
                <div class="col-md-3">
                <select  name="dept" id="dept">
                  <option value="" disabled="" selected="">Choose</option>
                  <?php 
                  foreach ($data_dept as $key => $value) {
                  echo '<option data-code="'.$value->dept_name.'" value="'.$value->dept_name.'">'.$value->dept_name.' </option>';
                  }
                  ?>
                </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="control-label col-md-2">Position</label>
                <div class="col-md-3">
                <input class="form-control" id="empPosition" name="empPosition" type="text" placeholder="Position">
              </div>
            </div>
              <div class="form-group row">
                <label class="control-label col-md-2">Placement</label>
                <div class="col-md-3">
                <input class="form-control" id="placement" name="placement" type="text" placeholder="Placement">
              </div>
              </div> 
              <div class="form-group row">
                <label class="control-label col-md-2">Join Date</label>
                <div class="col-md-3">
                <input class="form-control" id="joinDate" name="joinDate" type="date" value="<?php echo date('Y-m-d') ?>">
              </div>
            </div>
              <div class="form-group row">
                <label class="control-label col-md-2" for="ktp">ID Card Number</label>
                <div class="col-md-3">
                <input class="form-control" id="idCardNo" name="idCardNo" type="text"  placeholder="Id Card Number">
              </div>
              </div> 
              <div class="form-group row">
                <label class="control-label col-md-2">ID Card Address</label>
                <div class="col-md-3">
                <input class="form-control" id="idCardAddress" name="idCardAddress" type="text" placeholder="Id Card Address">
              </div>
              </div> 
              <div class="form-group row">
                <label class="control-label col-md-2">Current Address</label>
                <div class="col-md-3">
                <input class="form-control" id="currentAddress" name="currentAddress" type="text" placeholder="Current Address">
              </div>
              </div> 
              <div class="form-group row">
                <label class="control-label col-md-2">Religion</label>
                <div class="col-md-3">
                <input class="form-control" id="religion" name="religion" type="text" placeholder="Religion">
              </div>
              </div> 
              <div class="form-group row">
                <label class="control-label col-md-2">Driving License</label>
                <div class="col-md-3">
                <input class="form-control" id="drivingLicense" name="drivingLicense" type="text" placeholder="Driving License">
              </div>
              </div> 
              <div class="form-group row">
                <label class="control-label col-md-2" for="maritalStatus">Marital Status</label>
                <div class="col-md-3">
                <select id="maritalStatus" name="maritalStatus">
                  <option value="" disabled="" selected="">Choose</option>
                  <option value="TK0">TK0</option>
                  <option value="K0">K0</option>
                  <option value="K1">K1</option>
                  <option value="K2">K2</option>
                  <option value="K3">K3</option>
                </select>                  
              </div>
            </div>
              <div class="form-group row">
                <label class="control-label col-md-2">Height</label>
                <div class="col-md-3">
                <input class="form-control" id="empHeight" name="empHeight" type="text" placeholder="Employee Height">
              </div>
              </div> 
              <div class="form-group row">
                <label class="control-label col-md-2">Weight</label>
                <div class="col-md-3">
                <input class="form-control" id="empWeight" name="empWeight" type="text" placeholder="Employee Weight">
              </div>
              </div> 
              <div class="form-group row">
                <label class="control-label col-md-2">Blood Type</label>
                <div class="col-md-3">
                <input class="form-control" id="bloodType" name="bloodType" type="text" placeholder="Blood Type">
              </div>
              </div> 
              <div class="form-group row">
                <label class="control-label col-md-2">Email</label>
                <div class="col-md-3">
                <input class="form-control" id="emailAddress" name="emailAddress" type="text" placeholder="Email">
              </div>
              </div> 
              <div class="form-group row">
                <label class="control-label col-md-2">Telp No</label>
                <div class="col-md-3">
                <input class="form-control" id="telpNo" name="telpNo" type="tel" pattern=”^\d{10}$” placeholder="Telp No" required>
              </div>
              </div> 
              <div class="form-group row">
                <label class="control-label col-md-2">Cell No</label>
                <div class="col-md-3">
                <input class="form-control" id="cellNo" name="cellNo" type="tel" placeholder="Cell No">
              </div>
              </div> 
              <div class="form-group row">
                <label class="control-label col-md-2" for="isGlasses">Glasses</label>
                <div class="col-md-3">
                <select id="isGlasses" name="isGlasses">
                  <option value="" disabled="" selected="">Choose</option>
                  <option value="0">No</option>
                  <option value="1">Yes</option>
                </select>                  
              </div>
            </div>
             <div class="form-group row">
                <label class="control-label col-md-2" for="isGlasses">Emp Status</label>
                <div class="col-md-3">
                <select id="empStatus" name="empStatus">
                  <option value="" disabled="" selected="">Choose</option>
                  <option value="Daily">Daily</option>
                  <option value="Daily Pkwt">Daily PKWT</option>
                  <option value="Monthly">Monthly</option>
                </select>                  
              </div>
            </div>
              <div class="form-group row">
                <label class="control-label col-md-2">Tax No</label>
                <div class="col-md-3">
                <input class="form-control" id="taxNo" name="taxNo" type="number" placeholder="Tax No">
              </div> 
            </div>
              <div class="form-group row">
                <label class="control-label col-md-2">BPJS No</label>
                <div class="col-md-3">
                <input class="form-control" id="bpjsNo" name="bpjsNo" type="number" placeholder="BPJS No">
              </div>
              </div> 
              <div class="form-group row">
                <label class="control-label col-md-2">BPJS TK No</label>
                <div class="col-md-3">
                <input class="form-control" id="bpjsTkNo" name="bpjsTkNo" type="number" placeholder="BPJS TK No">
              </div>
              </div> 
               <div class="form-group row">
                  <label class="control-label col-md-2">Is Active</label>
                  <div class="col-md-1">
                    <input type="checkbox" id="isActive" name="isActive" value="1">
                  </div>
                </div>
            </div> <!-- class="tile-body" -->
              </form>
            <div class="tile-footer">
              <button class="btn btn-primary" type="button" id="dbSave"><i class="fa fa-fw fa-lg fa-check-circle"></i>Save</button>
              <a class="btn btn-secondary" href="<?php echo base_url();?>/Master/Biodata/reset"><i class="fa fa-fw fa-lg fa fa-times-circle"></i>Cancel</a>
              <!-- &nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="#"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancel</a> -->
              <strong>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <span style="color: red" class="errSaveMess"><?php echo $session->getFlashdata('error'); ?></span>
              </strong>
            </div>
          </div> <!-- class="tile" -->
         </div> <!-- class="col-md-12 -->
      </div> <!-- class="row" -->
      <!-- ***Using Valid js Path -->
      <script src="<?php echo base_url()?>/assets/js/main.js"></script>
      <script>
       
        var baseUrl = '<?php echo base_url() ?>';
        $(document).ready(function() {
        $('#dbSave').on('click', function(){
          var idCardNo = $("#idCardNo").val();
          var idcard = idCardNo.length;

        
       
                   
        
        if(!$('#fullName').val()){
          $.notify({
          title: "Erorr : ",
          message: "Silahkan Isi Form Dengan Benar",
          icon: 'fa fa-times' 
        },{
          type: "danger",
          delay: 1000
        });
          $('#fullName').focus();
              return false;
        }
        if (idcard != 16) {          
          $.notify({
            title: "Erorr : ",
            message: "ID CARD ANDA SALAH!! SILAHKAN PERIKSA KEMBALI",
            icon: 'fa fa-times' 
          },{
            type: "danger",
            delay: 1000
          });
          $("#idCardNo").focus();
          return false;
        }
        /*
                alert(fullName)*/
                $.ajax({
                  url    : '<?php echo base_url() ?>/Master/Biodata/ins',
                  method : "POST",
                  data   : {
                    /* Header */
                    // empId           : $('#empId').val(),
                    /*biodataId,*/
                    dept            : $('#dept option:selected').attr('data-code'),
                    fullName        : $('#fullName').val(),
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
                    isActive        : $('#isActive').val()
                    },
                  success : function(res)
                { var datasrc = JSON.parse(res);
                
                  if (datasrc['status'] == false) {
                    toastr.error(datasrc['message'], 'Alert', {"positionClass": "toast-top-center"});
                  } 
                  else if (datasrc['status'] == true) { 
                  toastr.success(datasrc['message'], 'Alert', {"positionClass": "toast-top-center"});
                   /* Your redirect is here */
                  setTimeout(function () {
                    window.location.href = baseUrl+'/Master/Biodata'; //will redirect to google.
                  }, 2000);
                }
                }
             })
    });
      });
      </script>
