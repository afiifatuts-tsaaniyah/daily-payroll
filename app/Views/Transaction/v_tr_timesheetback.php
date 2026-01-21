      <div class="row">
        <div class="col-md-12">
          <div class="tile">
          <h3 class="tile-title">Slip Process</h3>
           <div class="tile-footer">
            <form class = "row is_header">
               <!-- <div class="form-group col-sm-12 col-md-2"> -->
                  <!-- <label class="control-label">CLIENT</label> -->
                  <!-- <code id="docKindErr" class="errMsg"><span> : Required</span></code> -->
                  <!-- <select class="form-control" id="clientName" name="clientName" required=""> -->
                    <!-- <option value="" disabled="" selected="">Pilih</option> -->
                    <!-- <option value="Redpath_CAD">Redpath</option> -->
                  <!-- </select> -->
                <!-- </div> -->

                <div class="form-group col-sm-12 col-md-2">
                  <label class="control-label">YEAR</label>
                  <!-- <code id="docKindErr" class="errMsg"><span> : Required</span></code> -->
                  <select class="form-control" id="yearPeriod" name="yearPeriod" required="">
                    <option value="" disabled="" selected="">Pilih</option>
                    <script type="text/javascript">
                      var dt = new Date();
                      var currYear = dt.getFullYear();
                      var currMonth = dt.getMonth();
                              var currDay = dt.getDate();
                              var tmpDate = new Date(currYear + 1, currMonth, currDay);
                              var startYear = tmpDate.getFullYear();
                      var endYear = startYear - 80;             
                      for (var i = startYear; i >= endYear; i--) 
                      {
                        document.write("<option value='"+i+"'>"+i+"</option>");           
                      }
                    </script>
                  </select>
                </div>

                <div class="form-group col-sm-12 col-md-2">
                  <label class="control-label">MONTH</label>
                  <!-- <code id="docKindErr" class="errMsg"><span> : Required</span></code> -->
                  <select class="form-control" id="monthPeriod" name="monthPeriod" required="">
                    <option value="" disabled="" selected="">Pilih</option>
                    <script type="text/javascript">
                      var tMonth = 1;
                      for (var i = tMonth; i <= 12; i++) 
                      {
                        if(i < 10)
                        {
                          document.write("<option value='0"+i+"'>0"+i+"</option>");             
                        }
                        else
                        {
                          document.write("<option value='"+i+"'>"+i+"</option>");               
                        }
                        
                      }

                    </script>
                  </select>
                </div>

                <div class="form-group col-sm-12 col-md-2">
                  <label class="control-label">START DATE</label>
                  <!-- <code id="docKindErr" class="errMsg"><span> : Required</span></code> -->
                  <select class="form-control" id="startDate" name="startDate" required="">
                    <option value="" disabled="" selected="">Pilih</option>
                    <script type="text/javascript">
                      var tMonth = 1;
                      for (var i = tMonth; i <= 31; i++) 
                      {
                        if(i < 10)
                        {
                          document.write("<option value='0"+i+"'>0"+i+"</option>");             
                        }
                        else
                        {
                          document.write("<option value='"+i+"'>"+i+"</option>");               
                        }
                        
                      }

                    </script>                   
                  </select>
                </div>
                <!-- <input type="file" name="tsFile" id="tsFile"> -->
              <!-- <a href="#"> -->
                <div class="form-group col-sm-12">
                <div class="form-group col-sm-12 col-md-6 govReg">     
                <input type="checkbox" id="cbHealthBPJS" name="cbHealthBPJS" value="1" checked>
                <label class="control-label" for="healthBPJS">Health BPJS</label>&nbsp;&nbsp;&nbsp;&nbsp;

                <!-- <input type="checkbox" id="cbEmpBPJS" name="cbEmpBPJS" checked="">
                <label class="control-label" for="empBPJS">Employment BPJS</label> -->

                <input type="checkbox" id="cbJHT" name="cbJHT" value="1" checked>
                <label class="control-label" for="JHT">JHT</label>&nbsp;&nbsp;&nbsp;&nbsp;

                <input type="checkbox" id="cbJP" name="cbJP" value="1" checked>
                <label class="control-label" for="JP">JP</label>&nbsp;&nbsp;&nbsp;&nbsp;

                <input type="checkbox" id="cbJKKM" name="cbJKKM" value="1" checked>
                <label class="control-label" for="JKK-JKM">JKK-JKM</label>
                </div>
                  <a class="btn btn-primary" type="button" id="btnProcess"><i class="fa fa-fw fa-lg fas fa-plus-circle "></i>Process</a>
                  <a class="btn btn-primary" type="button" id="btnView"><i class="fa fa-fw fa-lg fas fa-plus-circle "></i>View</a>
                  <!-- <button class="btn btn-primary" type="submit" id="btnProcess">
                    <i class="fa fa-fw fa-lg fas fa-plus-circle "></i>Process
                  </button> -->
                </div>
                <div class="form-group col-sm-12 SM">
                  <div class="form-group col-sm-12 col-md-2">
                    <label class="control-label">SM</label>
                    <!-- <code id="docKindErr" class="errMsg"><span> : Required</span></code> -->
                    <select class="form-control" id="sm" name="sm" required="">
                      <option value="" disabled="" selected="">Pilih</option>
                      <script type="text/javascript">
                        var tDate = 1;
                        for (var i = tDate; i <= 200; i++) 
                        {
                          if(i < 10)
                          {
                            document.write("<option value='SM0"+i+"'>SM 0"+i+"</option>");             
                          }
                          else
                          {
                            document.write("<option value='SM"+i+"'>SM "+i+"</option>");               
                          }
                          
                        }

                      </script>
                    </select>
                  </div>
                <a class="btn btn-warning" type="button" id="btnProcessSM"><i class='fa fa-edit'></i>DOWNLOAD SM</a>
              </div>
                  <!-- <strong>
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <span style="color: red" class="errSaveMess"></span>
                  </strong> -->
              <!-- </a> -->
              </form>
           </div>
           <br>
           <br>
           <div class="tile-body">
            <!-- TABLE -->
            <div class="table-responsive">
              <table class="table table-hover table-bordered" id="slipTable">
                <thead>
                 <tr>
                   <!-- <th>Slip Id</th>
                   <th>Full Name</th>
                   <th>Dept</th>
                   <th>Position</th>
                   <th>Update</th> -->

                   <th>Slip Id</th>
                   <th>Biodata Id</th>
                   <th>Full Name</th>
                   <th>Dept</th>
                   <!-- <th>Class Base</th> -->
                   <!-- <th>Work Total</th> -->
                   <!-- <th>Base Wage</th> -->
                   <!-- <th>Wages In</th> -->
                   <!-- <th>Fixed Bonus</th> -->
                   <!-- <th>Variable Bonus</th> -->
                   <!-- <th>Jumbo Bonus</th> -->
                   <!-- <th>Salvac Bonus</th> -->
                   <!-- <th>Statutory</th> -->
                   <!-- <th>Travel</th> -->
                   <!-- <th>Pvb Val</th> -->
                   <!-- <th>Pvb Percent</th> -->
                   <!-- <th>Wl Qty</th> -->
                   <!-- <th>Wl Value</th> -->
                   <!-- <th>Allowance 01</th> -->
                   <!-- <th>Allowance 02</th> -->
                   <!-- <th>Allowance 03</th> -->
                   <!-- <th>Allowance 04</th> -->
                   <!-- <th>Allowance 05</th> -->
                   <!-- <th>Adjustment</th> -->
                   <!-- <th>Thr</th> -->
                   <!-- <th>Contract Bonus</th> -->
                   <!-- <th>Bpjs</th> -->
                   <!-- <th>Jkk</th> -->
                   <!-- <th>Jk</th> -->
                   <!-- <th>Jp</th> -->
                   <!-- <th>Jht</th> -->
                   <!-- <th>Emp Bpjs</th> -->
                   <!-- <th>Emp Jp</th> -->
                   <!-- <th>Emp Jht</th> -->
                   <!-- <th>Unpaid Count</th> -->
                   <!-- <th>Unpaid Total</th> -->
                   <!-- <th>Non Tax Allowance</th> -->
                   <!-- <th>Ptkp Total</th> -->
                   <!-- <th>Irregular Tax</th> -->
                   <!-- <th>Regular Tax</th> -->
                   <!-- <th>Salary Status</th> -->
                   <!-- <th>Status Remarks</th> -->
                   <th>Print</th>
                   <th>Edit</th>
                   <th>Reprocess</th>
                 </tr>
                </thead>
                <tbody>
                 <!-- <tr> -->
                  <!-- <td>slip_id</td> -->
                  <!-- <td>biodata_id</td> -->
                  <!-- <td>ts_id</td> -->
                  <!-- <td>year_period</td> -->
                  <!-- <td>month_period</td> -->
                  <!-- <td>full_name</td> -->
                  <!-- <td>dept</td> -->
                  <!-- <td>position</td> -->
                  <!-- <td>marital_status</td> -->
                  <!-- <td>class_id</td> -->
                  <!-- <td>class_base</td> -->
                  <!-- <td>work_total</td> -->
                  <!-- <td>base_wage</td> -->
                  <!-- <td>wages_in</td> -->
                  <!-- <td>fixed_bonus</td> -->
                  <!-- <td>variable_bonus</td> -->
                  <!-- <td>jumbo_bonus</td> -->
                  <!-- <td>salvac_bonus</td> -->
                  <!-- <td>statutory</td> -->
                  <!-- <td>travel</td> -->
                  <!-- <td>allowance_01</td> -->
                  <!-- <td>allowance_02</td> -->
                  <!-- <td>allowance_03</td> -->
                  <!-- <td>allowance_04</td> -->
                  <!-- <td>allowance_05</td> -->
                  <!-- <td>adjustment</td> -->
                  <!-- <td>thr</td> -->
                  <!-- <td>contract_bonus</td> -->
                  <!-- <td>bpjs</td> -->
                  <!-- <td>jkk_jkm</td> -->
                  <!-- <td>jp</td> -->
                  <!-- <td>jht</td> -->
                  <!-- <td>emp_bpjs</td> -->
                  <!-- <td>emp_jp</td> -->
                  <!-- <td>emp_jht</td> -->
                  <!-- <td>unpaid_count</td> -->
                  <!-- <td>unpaid_total</td> -->
                  <!-- <td>non_tax_allowance</td> -->
                  <!-- <td>ptkp_total</td> -->
                  <!-- <td>irregular_tax</td> -->
                  <!-- <td>regular_tax</td> -->
                  <!-- <td>salary_status</td> -->
                  <!-- <td>status_remarks</td> -->
                  <!-- <td>pic_edit</td> -->
                  <!-- <td>edit_time</td> -->
                  <!-- <td>pic_input</td> -->
                  <!-- <td>input_time</td> -->
                  <!-- <td>Link Edit</td> -->
                 <!-- </tr> -->
                </tbody>
              </table>
            </div>
            <div class="modal fade" id="editPayrollModal" tabindex="-1" role="dialog" aria-labelledby="isModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-notify modal-warning" role="document">
              <div class="modal-content">
                <div class="modal-header bg-primary">
                  <h5 class="modal-title text-center" id="isModalLabel">Data Payroll</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                     </div>
                     <div class="modal-body">     

                    <!-- START DATA TABLE -->
                    <div class="tile-body">

                  <!-- Start Form EDIT PAYROLL -->
                  <form method="post" class="my_detail">
                      <div class="form-group">
                          <label class="control-label" for="payrollId">Slip Id</label>
                          <input class="form-control" type="text" id="payrollId" name="payrollId" placeholder="Id" readonly="">
                      </div>                     

                      <div class="form-group">
                          <label class="control-label" for="payrollName">Name</label>
                          <input class="form-control" type="text" id="payrollName" name="payrollName" placeholder="Nama Karyawan" readonly="">
                      </div>    
          
                     
                        <div class="form-group adjustmentIn">
                            <label class="control-label" for="adjustmentIn">Adjustment</label>
                            <input class="form-control" type="text" id="adjustment" name="adjustment" placeholder="Penyesuaian Masuk">
                        </div>
                        
                        <div class="form-group thr">
                            <label class="control-label" for="thr">THR</label>
                            <input class="form-control" type="text" id="thr" name="thr" placeholder="THR" >
                        </div>

                        <div class="form-group thr">
                            <label class="control-label" for="thr">Lain - Lain</label>
                            <input class="form-control" type="text" id="lainlain" name="lainlain" placeholder="THR" >
                        </div>
                        <div class="form-group thr">
                            <label class="control-label" for="keterangan">Keterangan</label>
                            <input class="form-control" type="text" id="keterangan" name="keterangan" placeholder="Keterangan" >
                        </div>

                                            

                        <div class="form-group">
                            <!-- <label class="control-label" for="rowIdx">Index</label> -->
                            <input class="form-control" type="hidden" id="rowIdx" name="rowIdx" value="" readonly="">
                        </div>                                                                                                                                  
                  </form>
                  <!-- End Form EDIT PAYROLL -->

            
          </div>
          <!-- END DATA TABLE -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="savePayroll" data-dismiss="modal" class="btn btn-primary">Save</button>
      </div>
          </div> <!-- class="tile" -->
        </div> <!-- class="col-md-12" -->
      </div> <!-- class="row" -->
      <!-- ***Using Valid js Path -->
      <script src="<?php echo base_url()?>/assets/js/main.js"></script>
      <script>
         $(document).ready(function(){
          $('.SM').hide();
        var baseUrl = '<?php echo base_url()?>';
    /* START BIODATA TABLE */ 
        var slipTable = $('#slipTable').DataTable({
              "paging":   true,
              "ordering": false,
              "info":     true,
              "filter":   true,
              "columnDefs": [
                {
                  "targets": -2,
                    "data": null,
                    "defaultContent": "<button class='btn btn-warning btn-xs btn_update' data-toggle='modal' data-target='#editPayrollModal'><i class='fa fa-edit'></i></button>"
                },
                {
                    "targets": -3,
                    "data": null,
                    "defaultContent": "<button class='btn btn-warning btn-xs btn_print'><i class='fa fa-print'></i></button>"
                },
                {
                    "targets": -1,
                    "data": null,
                    "defaultContent": "<button class='btn btn-warning btn-xs btn_process'><i class='fa fa-spinner'></i></button>"
                },
                ]
          });
        $('#btnProcess').on("click", function(){
                // debugger;
                var monthPeriod = $('#monthPeriod').val();
                var yearPeriod  = $('#yearPeriod').val();
                var startDate  = $('#startDate').val();
                $('.SM').show();
                // let cbHealthBPJS = $("#cbHealthBPJS").val();
                // let cbJHT = $("#cbJHT").val();
                // let cbJP = $("#cbJP").val();
                // let cbJKKM = $("#cbJKKM").val();

                var myUrl ='<?php echo base_url() ?>/Transaction/Tr_timesheet/proses/'+yearPeriod+'/'+monthPeriod+'/'+startDate;

                
                // alert(myUrl);
                
                $.ajax({
                    url : myUrl,
                    method : "POST",
                    data   : {
                      monthPeriod  : monthPeriod,
                      yearPeriod   : yearPeriod,
                      
                      // month,
                      startDate     : startDate
                    },
                    success : function(data){
                    $("#loader").hide();
                    $('#btnProsesRoster').prop('disabled', false);
                              $.notify({
                      title: "<h5>Informasi : </h5>",
                      message: "<strong>Data Processed</strong> </br></br> ",
                      icon: '' 
                  },
                  {
                      type: "success",
                      delay: 2000
                  }); 
                  slipTable.clear().draw();
                    var dataSrc = JSON.parse(data);                 
                    slipTable.rows.add(dataSrc).draw(false);
          },
                });

          });
        $('#btnView').on("click", function(){
                // debugger;
                var monthPeriod = $('#monthPeriod').val();
                var yearPeriod  = $('#yearPeriod').val();
                var startDate  = $('#startDate').val();

                var myUrl ='<?php echo base_url() ?>/Transaction/Tr_timesheet/getPayrollList/'+yearPeriod+'/'+monthPeriod+'/'+startDate;
                $('.SM').show();
                // alert(myUrl);  
                
                $.ajax({
                    url : myUrl,
                    method : "POST",
                    data   : {
                      monthPeriod  : monthPeriod,
                      yearPeriod   : yearPeriod,
                      startDate     : startDate
                    },
                    success : function(data){
                    
                   
                  slipTable.clear().draw();
                    var dataSrc = JSON.parse(data);                 
                    slipTable.rows.add(dataSrc).draw(false);
          },
                });

          });

        $('#btnProcessSM').on("click", function(){
                // debugger;
                var monthPeriod = $('#monthPeriod').val();
                var yearPeriod  = $('#yearPeriod').val();
                var startDate  = $('#startDate').val();
                var sm  = $('#sm').val();

                if ($('#cbHealthBPJS').is(":checked"))
                    { 
                      var cbHealthBPJS = "1";
                    }               
                    else
                    {
                      var cbHealthBPJS = "0";
                    }
                    if ($('#cbJHT').is(":checked"))
                    { 
                      cbJHT = "1";
                    }              
                    else
                    {
                      var cbJHT = "0";
                    }
                    if ($('#cbJP').is(":checked"))
                    { 
                      var cbJP = "1";
                    }              
                    else
                    {
                      var cbJP = "0";
                    }
                    if ($('#cbJKKM').is(":checked"))
                    { 
                      var cbJKKM = "1";
                    }              
                    else
                    {
                      var cbJKKM = "0";
                    }
                $('.SM').show();
                // let cbHealthBPJS = $("#cbHealthBPJS").val();
                // let cbJHT = $("#cbJHT").val();
                // let cbJP = $("#cbJP").val();
                // let cbJKKM = $("#cbJKKM").val();

                var myUrl = "<?php echo base_url() ?>"+"/Transaction/Tr_timesheet/printAllSM/"+yearPeriod+"/"+monthPeriod+"/"+startDate+"/"+sm+"/"+cbHealthBPJS+"/"+cbJHT+"/"+cbJP+"/"+cbJKKM;

                
                // alert(myUrl);
                
                $.ajax({
                    url : myUrl,
                    method : "POST",
                    data   : {
                      monthPeriod  : monthPeriod,
                      yearPeriod   : yearPeriod,
                      
                      // month,
                      startDate     : startDate
                    },
                    success : function(response){
                    console.log(response);
                    window.open(myUrl,'_blank');
                    location.reload();
                },
                error : function(data){
                    $.notify({
                    title: "<h5>Informasi : </h5>",
                    message: "<strong>"+myUrl+"</strong> </br></br> ",
                    icon: '' 
                },
                {
                    type: "warning",
                    delay: 3000
                }); 
                }   
                });

          });

        $('#slipTable tbody').on( 'click', '.btn_print', function () {
            var data = slipTable.row( $(this).parents('tr') ).data();
            var dataId = data[4]; 

            if ($('#cbHealthBPJS').is(":checked"))
                { 
                  var cbHealthBPJS = "1";
                }               
                else
                {
                  var cbHealthBPJS = "0";
                }
                if ($('#cbJHT').is(":checked"))
                { 
                  cbJHT = "1";
                }              
                else
                {
                  var cbJHT = "0";
                }
                if ($('#cbJP').is(":checked"))
                { 
                  var cbJP = "1";
                }              
                else
                {
                  var cbJP = "0";
                }
                if ($('#cbJKKM').is(":checked"))
                { 
                  var cbJKKM = "1";
                }              
                else
                {
                  var cbJKKM = "0";
                }

            // var myUrl = "<?php #echo base_url() ?>"+"transactions/timika/Payroll/my_report/";
            var myUrl = "<?php echo base_url() ?>"+"/Transaction/Tr_timesheet/toExcel/"+dataId+"/"+cbHealthBPJS+"/"+cbJHT+"/"+cbJP+"/"+cbJKKM;
            // alert(myUrl);
            
            $.ajax({
                method : "POST",
                url : myUrl,
                data : {
                    slipId       : dataId,
                    cbHealthBPJS : cbHealthBPJS,
                    cbJHT        : cbJHT,
                    cbJP         : cbJP,
                    cbJKKM       : cbJKKM
                },                
                // success : function(data){
                //  alert(data);
                // },
                success : function(response){
                    console.log(response);
                    window.open(myUrl,'_blank');
                },
                error : function(data){
                    $.notify({
                    title: "<h5>Informasi : </h5>",
                    message: "<strong>"+myUrl+"</strong> </br></br> ",
                    icon: '' 
                },
                {
                    type: "warning",
                    delay: 3000
                }); 
                }   
            });
        });
        $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
        })
        $('#slipTable tbody').on( 'click', '.btn_update', function () {
            var data = slipTable.row( $(this).parents('tr') ).data();    
            var slipId = data[0];

            $("#payrollId").val(slipId);
            var myUrl = "<?php echo base_url() ?>"+"/Transaction/Tr_timesheet/getSlipByIid/"+slipId;

            $.ajax({
                url : myUrl,
                method : "POST",
                data : {
                    slipId : slipId
                },
                success : function(data) {
                    var dataSrc = JSON.parse(data);
                    $("#payrollName").val(dataSrc[1]);
                    $("#adjustment").val(dataSrc[4]); 
                    $("#thr").val(dataSrc[2]); 
                    $("#lainlain").val(dataSrc[3]);
                    $("#keterangan").val(dataSrc[5]);
                    
                },
                error : function(data) {
                    // alert('Failed');
                    $.notify({
                    title: "<h5>Informasi : </h5>",
                    message: "<strong>Failed</strong> </br></br> ",
                    icon: '' 
                },
                {
                    type: "warning",
                    delay: 3000
                });
                }

          });
        });

        $('#slipTable tbody').on( 'click', '.btn_process', function () {
            var data = slipTable.row( $(this).parents('tr') ).data();    
            var tsId = data[4];

            // $("#payrollId").val(slipId);
            var myUrl = "<?php echo base_url() ?>"+"/Transaction/Tr_timesheet/ProsesSolo/"+tsId;
          // alert(tsId)

            $.ajax({
                url : myUrl,
                method : "POST",
                data : {
                    tsId : tsId
                },
                success : function(data) {
                    // alert('Failed');
                    $.notify({
                      title: "<h5>Informasi : </h5>",
                      message: "<strong>Data Processed</strong> </br></br> ",
                      icon: '' 
                  },
                  {
                      type: "success",
                      delay: 2000
                  }); 
                },
                error : function(data) {
                    // alert('Failed');
                    $.notify({
                    title: "<h5>Informasi : </h5>",
                    message: "<strong>Failed</strong> </br></br> ",
                    icon: '' 
                },
                {
                    type: "warning",
                    delay: 3000
                });
                }

          });
        });

        $("#savePayroll").on("click", function(){
            /*alert(myUrl);*/
           var myUrl = "<?php echo base_url() ?>/Transaction/Tr_slip/upd";
            $.post({
              url: myUrl,
              type : "POST",  
              data: {
                slipId        : $('#payrollId').val(),
                fullName      : $('#payrollName').val(),
                adjustment    : $('#adjustment').val(),
                thr           : $('#thr').val(),
                statusRemarks : $('#keterangan').val(),
                lainlain      : $('#lainlain').val()
              },

                success : function(resp){
                  toastr.success("Data has been Save.", 'Alert', {"positionClass": "toast-top-center"});
                    /*Your redirect is here */ 
                }
             })
          });
    });
      </script>
