<div id="spinner" style="display:none;">
  <div class="loading"></div>
</div>


<style type="text/css">
  tr.selected {
    background-color: #B0BED9 !important;
  }
</style>

<div id="loader"></div>

<div class="row">
  <div class="col-md-12">
    <div class="tile">
      <h3 class="tile-title">Slip Process</h3>
      <div class="tile-footer">
        <form class="row is_header">
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
                for (var i = startYear; i >= endYear; i--) {
                  document.write("<option value='" + i + "'>" + i + "</option>");
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
                for (var i = tMonth; i <= 12; i++) {
                  if (i < 10) {
                    document.write("<option value='0" + i + "'>0" + i + "</option>");
                  } else {
                    document.write("<option value='" + i + "'>" + i + "</option>");
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
                for (var i = tMonth; i <= 31; i++) {
                  if (i < 10) {
                    document.write("<option value='0" + i + "'>0" + i + "</option>");
                  } else {
                    document.write("<option value='" + i + "'>" + i + "</option>");
                  }

                }
              </script>
            </select>
          </div>

          <div class="form-group col-sm-12 col-md-2">
            <label class="control-label">SM</label>
            <!-- <code id="docKindErr" class="errMsg"><span> : Required</span></code> -->
            <select class="form-control" id="smprocess" name="smprocess" required="">
              <option value="all" selected>All</option>
              <script type="text/javascript">
                var tDate = 1;
                for (var i = tDate; i <= 200; i++) {
                  if (i < 10) {
                    document.write("<option value='SM0" + i + "'>SM 0" + i + "</option>");
                  } else {
                    document.write("<option value='SM" + i + "'>SM " + i + "</option>");
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
              <label class="control-label" for="JKK-JKM">JKK-JKM</label>&nbsp;&nbsp;&nbsp;&nbsp;

              <input type="checkbox" id="cbEnd" name="cbEnd" value="1">
              <label class="control-label" for="cbEnd">END CONTRACT</label>

            </div>
            <a class="btn btn-primary" type="button" id="btnProcess"><i class="fa fa-fw fa-lg fas fa-plus-circle "></i>Process</a>
            <a class="btn btn-primary" type="button" id="btnProcessDB"><i class="fa fa-fw fa-lg fas fa-plus-circle "></i>Process DB</a>
            <a class="btn btn-primary" type="button" id="btnView"><i class="fa fa-fw fa-lg fas fa-plus-circle "></i>View</a>
            <button id="insert_due" class='btn btn-warning btn-xs' type="button" data-toggle='modal' data-target='#editDueDateModal'><i class='fa fa-clock'></i>Insert Due Date</button>
            <a class="btn btn-warning" type="button" id="btnProcessSM"><i class='fa fa-edit'></i>DOWNLOAD SM</a>
            <!-- <button class="btn btn-primary" type="submit" id="btnProcess">
                    <i class="fa fa-fw fa-lg fas fa-plus-circle "></i>Process
                  </button> -->
          </div>
          <!-- <div class="form-group col-sm-12 SM">
                  <div class="form-group col-sm-12 col-md-2">
                    <label class="control-label">SM</label>
                    <select class="form-control" id="sm" name="sm" required="">
                      <option value="all" selected="">All</option>
                      <script type="text/javascript">
                        var tDate = 1;
                        for (var i = tDate; i <= 200; i++){
                          if(i < 10){
                            document.write("<option value='SM0"+i+"'>SM 0"+i+"</option>");             
                          }else{
                            document.write("<option value='SM"+i+"'>SM "+i+"</option>");               
                          }                          
                        }

                      </script>
                    </select>
                  </div>
                  <button class='btn btn-warning btn-xs' type="button" data-toggle='modal' data-target='#editDueDateModal'><i class='fa fa-clock'></i>Insert Due Date</button>
                  <a class="btn btn-warning" type="button" id="btnProcessSM"><i class='fa fa-edit'></i>DOWNLOAD SM</a>
                </div> -->
        </form>
      </div>
      <br>
      <br>
      <div class="tile-body">
        <!-- TABLE -->
        <div class="table-responsive">
          <div id="closePayroll">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <!--<span aria-hidden="true">&times;</span>-->
              </button>
              <strong>Data Payroll Sudah Di Closing.</strong>
            </div>
          </div>
          <table class="table table-hover table-bordered" id="slipTable">
            <thead>
              <tr>
                <th>Slip Id</th>
                <th>Biodata Id</th>
                <th>Full Name</th>
                <th>Dept</th>
                <th>Print</th>
                <th>Edit</th>
                <th>Reprocess</th>
                <!-- <th>Due Date</th> -->
              </tr>
            </thead>
            <tbody>

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
                      <label class="control-label" for="keterangan">Keterangan</label>
                      <input class="form-control" type="text" id="keterangan" name="keterangan" placeholder="Keterangan">
                    </div>

                    <div class="form-group thr">
                      <label class="control-label" for="thr">THR</label>
                      <input class="form-control" type="text" id="thr" name="thr" placeholder="THR">
                    </div>

                    <div class="form-group thr">
                      <label class="control-label" for="thr">Lain - Lain</label>
                      <input class="form-control" type="text" id="lainlain" name="lainlain" placeholder="THR">
                    </div>

                    <div class="form-group ">
                      <label class="control-label" for="adjustmentIn">Adjustment In</label>
                      <input class="form-control" type="text" id="adjustmentIn" name="adjustmentIn" placeholder="Adjustment In">
                    </div>

                    <div class="form-group ">
                      <label class="control-label" for="adjustmentOut">Adjustment Out</label>
                      <input class="form-control" type="text" id="adjustmentOut" name="adjustmentOut" placeholder="Adjustment Out">
                    </div>

                    <div class="form-group ">
                      <label class="control-label" for="thrByUser">Thr By User</label>
                      <input class="form-control" type="text" id="thrByUser" name="thrByUser" placeholder="THR By User">
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

        <div class="modal fade" id="editDueDateModal" tabindex="-1" role="dialog" aria-labelledby="isModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-notify modal-warning" role="document">
            <div class="modal-content">
              <div class="modal-header bg-primary">
                <h5 class="modal-title text-center" id="isModalLabel">Due Date</h5>
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
                      <label class="control-label" for="payrollId">SM</label>
                      <input class="form-control" type="text" name="sm_duedate" id="sm_duedate" disabled>
                      <!-- <div id="sm_text"></div> -->
                    </div>
                    <div class="form-group adjustmentIn">
                      <label class="control-label" for="adjustmentIn">Due Date</label>
                      <input class="form-control" type="date" id="dueDate" name="dueDate" placeholder="Due Date">
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
                <button type="button" id="saveDueDate" data-dismiss="modal" class="btn btn-primary">Save</button>
              </div>
            </div> <!-- class="tile" -->
          </div> <!-- class="col-md-12" -->
        </div> <!-- class="row" -->
        <!-- ***Using Valid js Path -->
        <script src="<?php echo base_url() ?>/assets/js/main.js"></script>
        <script>
          $(document).ready(function() {
            $('#closePayroll').hide();
            $('#insert_due').hide();
            $("#loader").hide();
            $('.SM').hide();
            $('#btnProcessSM').hide();
            var baseUrl = '<?php echo base_url() ?>';
            /* START BIODATA TABLE */
            var slipTable = $('#slipTable').DataTable({
              "paging": true,
              "ordering": false,
              "info": true,
              "filter": true,
              "columnDefs": [{
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

            $('#insert_due').on("click", function() {
              var smprocess = $('#smprocess').val();
              $('#sm_duedate').val(smprocess);
              $('#sm_tax').text(smprocess);
            });

            $('#btnProcess').on("click", function() {
              // debugger;
              // $('.SM').show();
              var isEnd = "0";
              if ($("#cbEnd").is(':checked')) {
                isEnd = '1';
              }

              var monthPeriod = $('#monthPeriod').val();
              var yearPeriod = $('#yearPeriod').val();
              var startDate = $('#startDate').val();
              var smprocess = $('#smprocess').val();

              $('#closePayroll').hide();
              $.ajax({
                url: "<?php echo base_url('Transaction/Tr_timesheet/checkClosingPayrollByPeriod') ?>",
                method: "POST",
                data: {
                  monthPeriod: monthPeriod,
                  yearPeriod: yearPeriod,
                  client: "agincourt"
                },
                success: function(resp) {
                  var dataClosing = JSON.parse(resp);

                  if (dataClosing.data_count >= 1) {
                    $.notify({
                      title: "<h5>Danger : </h5>",
                      message: "<strong>Data Payroll Sudah Di Closing</strong>",
                      icon: ''
                    }, {
                      type: "Danger",
                      delay: 2000
                    });
                    $('#closePayroll').show();
                  } else {
                    var myUrl = '<?php echo base_url() ?>/Transaction/Tr_timesheet/proses/' + yearPeriod + '/' + monthPeriod + '/' + startDate + '/' + smprocess + '/' + isEnd;
                    $.post("<?php echo base_url() ?>/Transaction/Tr_timesheet/validasiCheck/" + yearPeriod + "/" + monthPeriod + "/" + startDate + '/' + smprocess, {
                      data: yearPeriod
                    }, function(response) {
                      if (response === "true") {
                        // Data valid
                        if (window.confirm("Data Sudah Di Proses !! Apakah Anda Ingin Memproses Ulang?")) {
                          spinner.style.display = 'flex';

                          $.ajax({
                            url: myUrl,
                            method: "POST",
                            data: {
                              monthPeriod: monthPeriod,
                              yearPeriod: yearPeriod,
                              smprocess: smprocess,
                              startDate: startDate
                            },
                            success: function(data) {
                              console.log(data);
                              $("#loader").hide();
                              $('#btnProsesRoster').prop('disabled', false);
                              spinner.style.display = 'none';
                              $.notify({
                                title: "<h5>Informasi : </h5>",
                                message: "<strong>Data Processed</strong> </br></br> ",
                                icon: ''
                              }, {
                                type: "success",
                                delay: 2000
                              });

                              slipTable.clear().draw();
                              var dataSrc = JSON.parse(data);
                              $('#insert_due').hide();
                              if (dataSrc.myData.length) {
                                $('#insert_due').show();
                              }
                              slipTable.rows.add(dataSrc.myData).draw(false);
                            },
                          });
                        } else {
                          return false;
                        }
                      } else if (response === "false") {
                        // Data tidak valid
                        spinner.style.display = 'flex';
                        $.ajax({
                          url: myUrl,
                          method: "POST",
                          data: {
                            monthPeriod: monthPeriod,
                            yearPeriod: yearPeriod,
                            smprocess: smprocess,
                            startDate: startDate
                          },
                          success: function(data) {
                            spinner.style.display = 'none';
                            $("#loader").hide();
                            $('#btnProsesRoster').prop('disabled', false);
                            $.notify({
                              title: "<h5>Informasi : </h5>",
                              message: "<strong>Data Processed</strong> </br></br> ",
                              icon: ''
                            }, {
                              type: "success",
                              delay: 2000
                            });

                            slipTable.clear().draw();
                            var dataSrc = JSON.parse(data);
                            $('#insert_due').hide();
                            if (dataSrc.length.myData) {
                              $('#insert_due').show();
                              $('#btnProcessSM').hide();
                            }
                            slipTable.rows.add(dataSrc.myData).draw(false);
                          },
                        });
                      }
                    });
                  }
                }
              });
            });

            $('#btnProcessDB').on("click", function() {

              $("#loader").show();
              var monthPeriod = $('#monthPeriod').val();
              var yearPeriod = $('#yearPeriod').val();
              var startDate = $('#startDate').val();
              var smprocess = $('#smprocess').val();

              $.ajax({
                url: "<?php echo base_url('Transaction/Tr_timesheet/checkClosingPayrollByPeriod') ?>",
                method: "POST",
                data: {
                  monthPeriod: monthPeriod,
                  yearPeriod: yearPeriod,
                  client: "agincourt"
                },
                success: function(resp) {
                  var dataClosing = JSON.parse(resp);

                  if (dataClosing.data_count >= 1) {
                    $.notify({
                      title: "<h5>Danger : </h5>",
                      message: "<strong>Data Payroll Sudah Di Closing</strong>",
                      icon: ''
                    }, {
                      type: "Danger",
                      delay: 2000
                    });
                  } else {

                    var myUrl = '<?php echo base_url() ?>/Transaction/Tr_timesheet/processDB/' + yearPeriod + '/' + monthPeriod + '/' + startDate + '/' + smprocess;
                    if (window.confirm("Apakah Anda Ingin Meporoses DB ? Proses Ini Mungkin Membutuhkan Waktu !!")) {
                      spinner.style.display = 'flex';
                      $.ajax({
                        url: myUrl,
                        method: "POST",
                        data: {
                          monthPeriod: monthPeriod,
                          yearPeriod: yearPeriod,
                          smprocess: smprocess,
                          startDate: startDate
                        },
                        success: function(data) {
                          spinner.style.display = 'none';
                          $("#loader").hide();
                          $('#btnProsesRoster').prop('disabled', false);
                          $.notify({
                            title: "<h5>Informasi : </h5>",
                            message: "<strong>Data Processed</strong> </br></br> ",
                            icon: ''
                          }, {
                            type: "success",
                            delay: 2000
                          });
                        },
                      });
                    } else {
                      return false;
                    }
                  }
                }
              });
            });

            $('#btnView').on("click", function() {
              // debugger;
              var monthPeriod = $('#monthPeriod').val();
              var yearPeriod = $('#yearPeriod').val();
              var startDate = $('#startDate').val();
              var smprocess = $('#smprocess').val();

              var myUrl = '<?php echo base_url() ?>/Transaction/Tr_timesheet/getPayrollList/' + yearPeriod + '/' + monthPeriod + '/' + startDate + '/' + smprocess;
              $('.SM').show();
              // alert(myUrl);  

              $.ajax({
                url: myUrl,
                method: "POST",
                data: {
                  monthPeriod: monthPeriod,
                  yearPeriod: yearPeriod,
                  startDate: startDate,
                  smprocess: smprocess
                },
                success: function(data) {
                  slipTable.clear().draw();
                  var dataSrc = JSON.parse(data);

                  if (dataSrc.myData.length) {
                    if (dataSrc.due_date >= 1) {
                      $('#btnProcessSM').show();
                      $('#insert_due').hide();
                    } else {
                      $('#btnProcessSM').hide();
                      $('#insert_due').show();
                    }
                  }


                  slipTable.rows.add(dataSrc.myData).draw(false);
                },
              });
            });

            $('#btnProcessSM').on("click", function() {
              // debugger;
              var monthPeriod = $('#monthPeriod').val();
              var yearPeriod = $('#yearPeriod').val();
              var startDate = $('#startDate').val();
              var sm = $('#smprocess').val();

              if ($('#cbHealthBPJS').is(":checked")) {
                var cbHealthBPJS = "1";
              } else {
                var cbHealthBPJS = "0";
              }
              if ($('#cbJHT').is(":checked")) {
                cbJHT = "1";
              } else {
                var cbJHT = "0";
              }
              if ($('#cbJP').is(":checked")) {
                var cbJP = "1";
              } else {
                var cbJP = "0";
              }
              if ($('#cbJKKM').is(":checked")) {
                var cbJKKM = "1";
              } else {
                var cbJKKM = "0";
              }

              var isEnd = "0";
              if ($("#cbEnd").is(':checked')) {
                isEnd = '1';
              }

              $('.SM').show();
              // let cbHealthBPJS = $("#cbHealthBPJS").val();
              // let cbJHT = $("#cbJHT").val();
              // let cbJP = $("#cbJP").val();
              // let cbJKKM = $("#cbJKKM").val();

              var myUrl = "<?php echo base_url() ?>" + "/Transaction/Tr_timesheet/printAllSM/" + yearPeriod + "/" + monthPeriod + "/" + startDate + "/" + sm + "/" + cbHealthBPJS + "/" + cbJHT + "/" + cbJP + "/" + cbJKKM + "/" + isEnd;


              // alert(myUrl);

              $.ajax({
                url: myUrl,
                method: "POST",
                data: {
                  monthPeriod: monthPeriod,
                  yearPeriod: yearPeriod,

                  // month,
                  startDate: startDate
                },
                success: function(response) {
                  console.log(response);
                  window.open(myUrl, '_blank');
                  // location.reload();
                },
                error: function(data) {
                  $.notify({
                    title: "<h5>Informasi : </h5>",
                    message: "<strong>" + myUrl + "</strong> </br></br> ",
                    icon: ''
                  }, {
                    type: "warning",
                    delay: 3000
                  });
                }
              });

            });

            $('#slipTable tbody').on('click', '.btn_print', function() {
              var data = slipTable.row($(this).parents('tr')).data();
              var dataId = data[4];

              if ($('#cbHealthBPJS').is(":checked")) {
                var cbHealthBPJS = "1";
              } else {
                var cbHealthBPJS = "0";
              }
              if ($('#cbJHT').is(":checked")) {
                cbJHT = "1";
              } else {
                var cbJHT = "0";
              }
              if ($('#cbJP').is(":checked")) {
                var cbJP = "1";
              } else {
                var cbJP = "0";
              }
              if ($('#cbJKKM').is(":checked")) {
                var cbJKKM = "1";
              } else {
                var cbJKKM = "0";
              }
              if ($('#cbEnd').is(":checked")) {
                var cbEnd = "1";
              } else {
                var cbEnd = "0";
              }

              // var myUrl = "<?php #echo base_url() 
                              ?>"+"transactions/timika/Payroll/my_report/";
              var myUrl = "<?php echo base_url() ?>" + "/Transaction/Tr_timesheet/toExcel/" + dataId + "/" + cbHealthBPJS + "/" + cbJHT + "/" + cbJP + "/" + cbJKKM + "/" + cbEnd;
              // alert(myUrl);

              $.ajax({
                method: "POST",
                url: myUrl,
                data: {
                  slipId: dataId,
                  cbHealthBPJS: cbHealthBPJS,
                  cbJHT: cbJHT,
                  cbJP: cbJP,
                  cbJKKM: cbJKKM,
                  cbEnd: cbEnd
                },
                // success : function(data){
                //  alert(data);
                // },
                success: function(response) {
                  console.log(response);
                  window.open(myUrl, '_blank');
                },
                error: function(data) {
                  $.notify({
                    title: "<h5>Informasi : </h5>",
                    message: "<strong>" + myUrl + "</strong> </br></br> ",
                    icon: ''
                  }, {
                    type: "warning",
                    delay: 3000
                  });
                }
              });
            });
            $('#myModal').on('shown.bs.modal', function() {
              $('#myInput').trigger('focus')
            })
            $('#slipTable tbody').on('click', '.btn_update', function() {
              var data = slipTable.row($(this).parents('tr')).data();
              var slipId = data[0];

              $("#payrollId").val(slipId);
              var myUrl = "<?php echo base_url() ?>" + "/Transaction/Tr_timesheet/getSlipByIid/" + slipId;

              $.ajax({
                url: myUrl,
                method: "POST",
                data: {
                  slipId: slipId
                },
                success: function(data) {
                  var dataSrc = JSON.parse(data);
                  // $("#payrollName").val(Number(dataSrc[1] ?? 0));
                  // $("#adjustment").val(dataSrc[4]);
                  // $("#thr").val(dataSrc[2]);
                  // $("#lainlain").val(dataSrc[3]);
                  // $("#keterangan").val(dataSrc[5]);
                  // $("#adjustmentIn").val(dataSrc[6]);
                  // $("#adjustmentOut").val(dataSrc[7]);
                  // $("#thrByUser").val(dataSrc[8]);

                  $("#payrollName").val(dataSrc[1]);
                  $("#adjustment").val(Number(dataSrc[4] ?? 0));
                  $("#thr").val(Number(dataSrc[2] ?? 0));
                  $("#lainlain").val(Number(dataSrc[3] ?? 0));
                  $("#adjustmentIn").val(Number(dataSrc[6] ?? 0));
                  $("#adjustmentOut").val(Number(dataSrc[7] ?? 0));
                  $("#thrByUser").val(Number(dataSrc[8] ?? 0));

                  // text / remarks
                  $("#keterangan").val(dataSrc[5] ?? '');


                },
                error: function(data) {
                  // alert('Failed');
                  $.notify({
                    title: "<h5>Informasi : </h5>",
                    message: "<strong>Failed</strong> </br></br> ",
                    icon: ''
                  }, {
                    type: "warning",
                    delay: 3000
                  });
                }

              });
            });

            $('#slipTable tbody').on('click', '.btn_duedate', function() {
              var data = slipTable.row($(this).parents('tr')).data();
              var slipId = data[0];

              $("#idKaryawan").val(slipId);
              var myUrl = "<?php echo base_url() ?>" + "/Transaction/Tr_timesheet/getDueDate/" + slipId;

              $.ajax({
                url: myUrl,
                method: "POST",
                data: {
                  slipId: slipId
                },
                success: function(data) {
                  var dataSrc = JSON.parse(data);
                  $("#nameKaryawan").val(dataSrc[1]);
                  $("#dueDate").val(dataSrc[2]);

                },
                error: function(data) {
                  // alert('Failed');
                  $.notify({
                    title: "<h5>Informasi : </h5>",
                    message: "<strong>Failed</strong> </br></br> ",
                    icon: ''
                  }, {
                    type: "warning",
                    delay: 3000
                  });
                }

              });

            });

            $('#slipTable tbody').on('click', '.btn_process', function() {
              var data = slipTable.row($(this).parents('tr')).data();
              var tsId = data[4];
              var name = data[2];

              // $("#payrollId").val(slipId);
              var myUrl = "<?php echo base_url() ?>" + "/Transaction/Tr_timesheet/ProsesSolo/" + tsId;
              if (window.confirm("Apakah Anda Ingin Memproses Ulang Data Dengan Name : " + name + " ?")) {

                // alert(tsId)

                $.ajax({
                  url: myUrl,
                  method: "POST",
                  data: {
                    tsId: tsId
                  },
                  success: function(data) {
                    // alert('Failed');
                    $.notify({
                      title: "<h5>Informasi : </h5>",
                      message: "<strong>Data Processed</strong> </br></br> ",
                      icon: ''
                    }, {
                      type: "success",
                      delay: 2000
                    });
                  },
                  error: function(data) {
                    // alert('Failed');
                    $.notify({
                      title: "<h5>Informasi : </h5>",
                      message: "<strong>Failed</strong> </br></br> ",
                      icon: ''
                    }, {
                      type: "warning",
                      delay: 3000
                    });
                  }

                });
              } else {
                return false;
              }
            });

            $("#savePayroll").on("click", function() {
              /*alert(myUrl);*/
              var myUrl = "<?php echo base_url() ?>/Transaction/Tr_slip/upd";
              $.post({
                url: myUrl,
                type: "POST",
                data: {
                  slipId: $('#payrollId').val(),
                  fullName: $('#payrollName').val(),
                  adjustment: $('#adjustment').val(),
                  thr: $('#thr').val(),
                  statusRemarks: $('#keterangan').val(),
                  lainlain: $('#lainlain').val(),
                  adjustmentIn: $('#adjustmentIn').val(),
                  adjustmentOut: $('#adjustmentOut').val(),
                  thrByUser: $('#thrByUser').val(),
                },

                success: function(resp) {
                  toastr.success("Data has been Save.", 'Alert', {
                    "positionClass": "toast-top-center"
                  });
                  /*Your redirect is here */
                }
              })
            });

            $("#saveDueDate").on("click", function() {
              var myUrl = "<?php echo base_url() ?>/Transaction/Tr_slip/updDueDate";
              $.post({
                url: myUrl,
                type: "POST",
                data: {
                  monthPeriod: $('#monthPeriod').val(),
                  yearPeriod: $('#yearPeriod').val(),
                  startDate: $('#startDate').val(),
                  // SM            : $('#smDueDate').val(),
                  SM: $('#sm_duedate').val(),
                  dueDate: $('#dueDate').val()
                },

                success: function(resp) {
                  $('#insert_due').hide();
                  $('#btnProcessSM').show();
                  toastr.success("Data has been Save.", 'Alert', {
                    "positionClass": "toast-top-center"
                  });
                  /*Your redirect is here */
                }
              })

            });
          });
        </script>