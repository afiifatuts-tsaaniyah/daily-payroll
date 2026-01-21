      <Style>
        #spinner {
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background-color: rgba(0, 0, 0, 0.5);
          display: flex;
          justify-content: center;
          align-items: center;
          z-index: 9999;
        }

        .loading {
          border: 6px solid #f3f3f3;
          border-radius: 50%;
          border-top: 6px solid #3498db;
          width: 40px;
          height: 40px;
          animation: spin 1s linear infinite;
        }

        @keyframes spin {
          0% {
            transform: rotate(0deg);
          }

          100% {
            transform: rotate(360deg);
          }
        }
      </Style>

      <div id="spinner" style="display:none;">
        <div class="loading"></div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <h3 class="tile-title">Download Template</h3>
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
                  <label class="control-label">Start Date</label>
                  <!-- <code id="docKindErr" class="errMsg"><span> : Required</span></code> -->
                  <select class="form-control" id="startDate" name="startDate" required="">
                    <option value="" disabled="" selected="">Pilih</option>
                    <script type="text/javascript">
                      var tDate = 1;
                      for (var i = tDate; i <= 31; i++) {
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
                  <select class="form-control" id="sm" name="sm" required="">
                    <option value="" disabled="" selected="">Pilih</option>
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
                  <a class="btn btn-primary" type="button" id="btnProcess"><i class="fa fa-fw fa-lg fas fa-plus-circle "></i>Download Template Timesheet</a>

                  <a class="btn btn-primary text-white" type="button" id="btnDownloadTemplate"><i class="fa fa-fw fa-lg fas fa-plus-circle "></i>Download Template Allowance</a>
                  <!-- <button class="btn btn-primary" type="submit" id="btnProcess">
                    <i class="fa fa-fw fa-lg fas fa-plus-circle "></i>Process
                  </button> -->
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

                      <th>Biodata Id</th>
                      <th>Full Name</th>
                      <th>Dept</th>
                      <th>Position</th>
                      <th>Status</th>
                      <th>Pilih</th>

                    </tr>
                  </thead>
                  <tbody>

                  </tbody>
                </table>
              </div>



              <script>
                const BASE_URL = "<?= base_url() ?>";
              </script>


              <script src="<?php echo base_url() ?>/assets/js/main.js"> </script>
              <script>
                function showSpinner() {
                  document.getElementById('spinner').style.display = 'flex';
                }

                function hideSpinner() {
                  document.getElementById('spinner').style.display = 'none';
                }
                var array_Bio = []

                function add_kode(e) {
                  // debugger
                  let kode = $(e).val();
                  let cek = array_Bio.lastIndexOf(kode);
                  if (cek < 0) {
                    array_Bio.push(kode);
                  } else {
                    array_Bio.splice(cek, 1)
                  }
                  // alert(array_Bio);
                }
                $(document).ready(function() {
                  var contractId = "";
                  /* START BIODATA TABLE */
                  var demobTable = $('#slipTable').DataTable({
                    "paging": true,
                    "ordering": false,
                    "info": false,
                    "filter": true,
                    //  "columnDefs": [
                    // {
                    //   "targets": -1,
                    //     "data": null,
                    //     "defaultContent": "<input type='checkbox' id='vehicle1' name='type' value='1'>"
                    // },
                    // ]

                  });
                  // var data = slipTable.row( $(this).parents('tr') ).data();
                  // var dataId = data[1]; 
                  // alert(dataId);
                  // var check = $('input[name="check"]:checked').map( function () { 
                  // return $(this).val(); 
                  // }).get().join('; ');


                  $.ajax({
                    method: "POST",
                    url: "<?php echo base_url() ?>" + "/Transaction/Download/loadData",
                    data: {
                      biodataId: ""
                    },
                    success: function(data) {
                      demobTable.clear().draw();
                      var dataSrc = JSON.parse(data);
                      demobTable.rows.add(dataSrc).draw(false);
                      // alert("Succeed");    
                    },
                    error: function() {
                      alert("Failed Load Data");
                    }
                  });

                  $('#btnProcess').on('click', function() {
                    // var array = [];
                    showSpinner();
                    var monthPeriod = $('#monthPeriod').val();
                    var yearPeriod = $('#yearPeriod').val();
                    var startDate = $('#startDate').val();
                    var sm = $('#sm').val();
                    // var biodataId   = $("input:checkbox[name=type]:checked").each(function() {
                    // array.push($(this).val()); });
                    var myUrl = '<?php echo base_url() ?>/Transaction/Download/Download/' + yearPeriod + '/' + monthPeriod + '/' + startDate + '/' + sm + '/' + array_Bio;

                    window.open(myUrl, '_blank');
                    hideSpinner();
                  });


                  $('#btnDownloadTemplate').on('click', function() {
                    // var array = [];
                    showSpinner();
                    var monthPeriod = $('#monthPeriod').val();
                    var yearPeriod = $('#yearPeriod').val();
                    var startDate = $('#startDate').val();
                    var sm = $('#sm').val();
                    var myUrl = '<?php echo base_url() ?>/Transaction/Allowance/downloadAllowance/' + yearPeriod + '/' + monthPeriod + '/' + startDate + '/' + sm + '/' + array_Bio;

                    window.open(myUrl, '_blank');
                    hideSpinner();
                  });
                });
              </script>