<div id="spinner" style="display:none;">
  <div class="loading"></div>
</div>
<!-- <div id="loader"></div> -->
<div class="col-md-12">
  <div class="tile bg-white">
    <h3 class="tile-title">Tax Calculation Report</h3>
    <div class="tile-body">
    </div>
  </div>
</div>
<!--START FORM DATA -->
<div class="col-md-12" id="is_transaction">
  <div class="tile bg-info">
    <div class="tile-body">
      <form class="is_header">
        <div class="form-group row">

          <div class="form-group col-sm-12 col-md-2 clientName">
            <label class="control-label">Clients </label>
            <!-- <code id="docKindErr" class="errMsg"><span> : Required</span></code> -->
            <select class="form-control" id="clientName" name="clientName" required="">
              <option value="" disabled="" selected="">Pilih</option>
              <?php
              foreach ($clients as $value) {
                echo '<option data-code="' . $value['client_value'] . '" value="' . $value['client_value'] . '">' . $value['client_name'] . ' </option>';
              }
              ?>
            </select>
          </div>

          <div class="form-group col-sm-12 col-md-2 clientName">
            <label class="control-label">Year </label>
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
        </div>

        <div class="form-group col-md-12 align-self-end">
          <button class="btn btn-warning btnProcessPanel" type="button" id="viewTaxList" name="viewInvoiceList"><span class="fa fa-list"></span> DISPLAY DATA</button>
          <button class="btn btn-warning btnProcessPanel" type="button" id="printToFile" name="printToFile"><span class="fa fa-print"></span> PRINT</button>
          <br>
          <!-- <br> -->
          <h3><code id="dataProses" class="backTransparent"><span></span></code></h3>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- END FORM DATA -->

<div class="col-md-12">
  <div class="tile bg-info">
    <!-- <h3 class="tile-title">Barang Masuk</h3> -->
    <div class="tile-body">
      <!-- START DATA TABLE -->
      <div class="tile-body">
        <table class="table table-hover table-bordered" id="invoiceList">
          <thead class="thead-dark">
            <tr>
              <th>Slip Id</th>
              <th>Emp Name</th>
              <th>Dept</th>
              <th>Position</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
      <!-- END DATA TABLE -->
    </div>
  </div>
</div>


<script src="<?php echo base_url() ?>/assets/js/main.js"></script>
<script>
  $(document).ready(function() {
    var baseUrl = '<?php echo base_url() ?>';
    /* START BIODATA TABLE */
    var slipTable = $('#invoiceList').DataTable({
      "paging": true,
      "ordering": false,
      "info": true,
      "filter": true,
    });
    $('.summary').hide();
    $('.invoice').hide();

    $('#dataPrint').on('change', function() {
      name = $('#dataPrint').val();
      if (name == 'AR') {
        $('.month').hide();
      } else {
        $('.month').show();
      }

      if (name == 'Summary') {
        $('.invoice').hide();
        $('.summary').show();
      } else {
        $('.summary').hide();
        $('.invoice').show();
      }
    });



    $('#printToFile').on("click", function() {

      var yearPeriod = $('#yearPeriod').val();
      var clientName = $('#clientName').val();
      var myUrl = '<?php echo base_url() ?>/Report/Tax_calculation/exportAllTaxCalculation/' + yearPeriod + '/' + clientName;

      spinner.style.display = 'flex';
      $.ajax({
        url: myUrl,
        method: "POST",
        data: {
          yearPeriod: yearPeriod,
          clientName: clientName
        },
        success: function(response) {
          // console.log(response);
          spinner.style.display = 'none';
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

    $('#viewTaxList').on("click", function() {
      var yearPeriod = $('#yearPeriod').val();
      var clientName = $('#clientName').val();
      var myUrl = '<?php echo base_url() ?>/Report/Tax_calculation/getMtbArList/' + yearPeriod + '/' + clientName;
      spinner.style.display = 'flex';
      $.ajax({
        url: myUrl,
        method: "POST",
        data: {
          // monthPeriod  : monthPeriod,
          yearPeriod: yearPeriod,
          clientName: clientName
        },
        success: function(data) {
          // debugger
          spinner.style.display = 'none';
          slipTable.clear().draw();
          var dataSrc = JSON.parse(data);
          slipTable.rows.add(dataSrc).draw(false);
        },
      });

    });

    $('#myModal').on('shown.bs.modal', function() {
      $('#myInput').trigger('focus')
    });
  });
</script>