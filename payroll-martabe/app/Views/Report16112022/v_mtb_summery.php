<div id="loader"></div>

<!--START FORM DATA -->
<div class="col-md-12" id="is_transaction">
  <div class="tile bg-info">
    <!-- <h3 class="tile-title">Barang Masuk</h3> -->
    <div class="tile-body">
      <form class="row is_header">
      

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
        
        <!-- START CUSTOMIZE GOVERNMENT REGULATION/   -->
                       
        <div class="form-group col-md-12 align-self-end">
          <button class="btn btn-warning" id="viewSummaryPayroll" type="button"><i class="fa fa-refresh"></i>VIEW</button>
          <button class="btn btn-warning" id="printSummaryPayroll" type="button"><i class="fa fa-refresh"></i>PRINT</button>
		      <br>
		      <!-- <br> -->
    	  <!-- <h3><code id="dataProses" class="backTransparent"><span></span></code></h3>	 -->
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
                <th>Biodata Id</th>
                <th>Name</th>
                <th>NPWP</th>
                <th>External ID</th>
                <th>Basic Salary</th>
                <th>OT Total</th>
                <th>THR</th>
                <th>PVB</th>
                <th>Gross</th>
    <th>Adjustment</th>
                <th>Net Payment</th>
              </tr>
            </thead>
            <tbody>
              <!-- <tr>
                <td>Tiger Nixon</td>
                <td>System Architect</td>
                <td>Edinburgh</td>
                <td>61</td>
                <td>2011/04/25</td>
                <td>$320,800</td>
              </tr>  -->       
            </tbody>
          </table>
        </div>
        <!-- END DATA TABLE -->
  </div>
  </div>
</div>

    <script src="<?php echo base_url()?>/assets/js/main.js"></script>
      <script>
    $(document).ready(function(){
      var slipTable = $('#invoiceList').DataTable({
              "paging":   true,
              "ordering": false,
              "info":     true,
              "filter":   true,        
          });

      $('#printSummaryPayroll').on( 'click', function () {
            var yearPeriod  = $("#yearPeriod").val();
            var monthPeriod = $("#monthPeriod").val();
            // var payrollGroup = $("#payrollGroup").val();
            var myUrl = "<?php echo base_url() ?>"+"/Report/Mtb_summary/exportSummaryPayrollTrk/"+yearPeriod+"/"+monthPeriod;
            var isValid = true;
            if($('#monthPeriod option:selected').text() == "Pilih")
            {
                $("#monthPeriod").focus();
                alert('Bulan Harus Dipilih ');
                isValid = false;
            } 
            else if($('#yearPeriod option:selected').text() == "Pilih")
            {
                $("#yearPeriod").focus();
                alert('Tahun Harus Dipilih ');
                isValid = false;
            }
            $.ajax({
                method : "POST",
                url : myUrl,
                data : {
                    yearPeriod : yearPeriod,
                    monthPeriod : monthPeriod 
                },
                // success : function(data){
                    // alert(data);
                success : function(response){
                    console.log(response);
                    window.open(myUrl,'_blank');
                },
                error : function(data){
                    $.notify({
                        title: "<h5>Informasi : </h5>",
                        message: "<strong>"+data+"</strong> </br></br> ",
                        icon: '' 
                    },
                    {
                        type: "warning",
                        delay: 3000
                    }); 
                }   
            });
        });
      $('#viewSummaryPayroll').on("click", function(){
                // debugger;
                var monthPeriod = $('#monthPeriod').val();
                var yearPeriod  = $('#yearPeriod').val();

                var myUrl = "<?php echo base_url() ?>"+"/Report/Mtb_summary/getPayrollList/"+yearPeriod+"/"+monthPeriod;


                // alert(myUrl);
                
                $.ajax({
                    url : myUrl,
                    method : "POST",
                    data   : {
                      monthPeriod  : monthPeriod,
                      yearPeriod   : yearPeriod
                    },
                    success : function(data){
                    
                   
                  slipTable.clear().draw();
                    var dataSrc = JSON.parse(data);                 
                    slipTable.rows.add(dataSrc).draw(false);
          },
                });

          });
      });
</script>