<div class="col-md-12">
  <div class="tile bg-white">
    <h3 class="tile-title">Martabe Report</h3>
    
    
    <div class="tile-body">
    </div>
  </div>
</div><!-- <div id="loader"></div> -->

<!--START FORM DATA -->
<div class="col-md-12" id="is_transaction">
  <div class="tile bg-info">
    <!-- <h3 class="tile-title">Barang Masuk</h3> -->
    <div class="tile-body">
      <form class="row is_header">
      <!-- <form class="row is_header" action="<?php #echo base_url() ?>transactions/sumbawa/Timesheet/payrollProcess"> -->
     
     <div class="form-group col-sm-12 col-md-3">
          <label class="control-label" for="dataPrint">DATA PRINT</label> 
          <select class="form-control" id="dataPrint" name="dataPrint" required="">
            <option value="" selected="" disabled="">Pilih</option> 
            <option value="payment">PAYMENTLIST</option>
            <option value="summary">PAYMENT SUMMARY</option> 
            <!-- <option value="invoice">INVOICE</option>  -->
            <option value="form">PAYMENT FORM</option> 
            <!-- <option value="db">DB</option> -->
          </select>
        </div>  
        

        <div class="form-group col-sm-12 col-md-2">
          <label class="control-label">YEAR</label>
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
          <select class="form-control" id="monthPeriod" name="monthPeriod" required="">
    				<option value="" disabled="" selected="">Pilih</option>
    				<script type="text/javascript">
              var tDay = 1;
              for (var i = tDay; i <= 12; i++) 
              {
                if(i<10){
                  document.write("<option value='0"+i+"'>0"+i+"</option>");           

                }else{
                  document.write("<option value='"+i+"'>"+i+"</option>");           

                }            
              }
            </script>
    			</select>
        </div>

	<div class="form-group col-sm-12 col-md-2 multism">
          <label class="control-label">MULTI SM</label>
          <select class="form-control js-example-basic-multiple" id="multism" required="" name="states[]" multiple="multiple">
    				<option value="" disabled="" selected="">Pilih</option>
    				<script type="text/javascript">
              var tDay = 1;
              for (var i = tDay; i <= 200; i++) 
              {
                if(i<10){
                  document.write("<option value='SM0"+i+"'>SM 0"+i+"</option>");           

                }else{
                  document.write("<option value='SM"+i+"'>SM "+i+"</option>");           

                }            
              }
            </script>
    			</select>
        </div>

        <div class="form-group col-sm-12 col-md-2 sm">
          <label class="control-label">SM</label>
          <select class="form-control" id="sm" required="" name="sm">
            <option value="" disabled="" selected="">Pilih</option>
            <script type="text/javascript">
              var tDay = 1;
              for (var i = tDay; i <= 200; i++) 
              {
                if(i<10){
                  document.write("<option value='SM0"+i+"'>SM 0"+i+"</option>");           

                }else{
                  document.write("<option value='SM"+i+"'>SM "+i+"</option>");           

                }            
              }
            </script>
          </select>
        </div>

      




        


       

       
            
        <div class="form-group col-md-12 align-self-end">
			<button class="btn btn-warning btnProcessPanel" type="button" id="viewInvoiceList" name="viewInvoiceList">
	        		<span class="fa fa-list"></span> DISPLAY DATA
        	</button>
			<button class="btn btn-warning btnProcessPanel" type="button" id="printToFile" name="printToFile">
        		<span class="fa fa-print"></span> PRINT
        	</button>
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
                <!-- <th>Badge No</th> -->
                <th>Emp Name</th>
                <th>Dept</th>
                <th>Position</th>
                <!-- <th>Update</th> -->
                <!-- <th>Print</th> -->
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
          $('.js-example-basic-multiple').select2();
        var baseUrl = '<?php echo base_url()?>';
    /* START BIODATA TABLE */ 
    var slipTable = $('#invoiceList').DataTable({
              "paging":   true,
              "ordering": false,
              "info":     true,
              "filter":   true,        
          });
        $('.sm').hide();

                var name ='';
		            var name1 ='';
                $('#dataPrint').on('change', function(){
                  name = $('#dataPrint').val();
                  $('.sm').hide();
                  $('.multism').show();
              });


        $('#printToFile').on("click", function(){
                // debugger;
                var monthPeriod = $('#monthPeriod').val();
                var yearPeriod  = $('#yearPeriod').val();
		            var sm          = $('#sm').val();
                var multiSM   = $('#multism').val();

                var myUrl ='';
                dept = $('#dept').val();
               


                if (name == "payment") {
                  var myUrl ='<?php echo base_url() ?>/Report/Mtb_report/exportPaymentPayrollMartabeAgr/'+yearPeriod+'/'+monthPeriod+'/'+multiSM;
                } else if (name == "summary") {
                  var myUrl ='<?php echo base_url() ?>/Report/Mtb_report/exportSummaryPayrollMartabeAgr/'+yearPeriod+'/'+multiSM+'/'+monthPeriod;
                } else if (name == "form") {
                  var myUrl ='<?php echo base_url() ?>/Report/Mtb_report/exportPaymentForm/'+yearPeriod+'/'+monthPeriod+'/'+multiSM;
                }
                
                $.ajax({
                    url : myUrl,
                    method : "POST",
                    data   : {
                      monthPeriod  : monthPeriod,
                      yearPeriod   : yearPeriod
                    },
                   
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
        $('#viewInvoiceList').on("click", function(){
                // debugger;
                var monthPeriod = $('#monthPeriod').val();
                var yearPeriod  = $('#yearPeriod').val();
                var sm  = $('#sm').val();
                var multiSM   = $('#multism').val();

                var myUrl ='';
                if (name  == "payment") {
                  var myUrl ='<?php echo base_url() ?>/Report/Mtb_report/getPayrolldeptList/'+yearPeriod+'/'+monthPeriod+'/'+sm;
                } else if (name  == "invoice") {
                  var myUrl ='<?php echo base_url() ?>/Report/Mtb_report/getPayrollList/'+yearPeriod+'/'+monthPeriod+'/'+sm;
                } else if (name  == "summary") {
                  myUrl ='<?php echo base_url() ?>/Report/Mtb_report/getPayrollList/'+yearPeriod+'/'+monthPeriod+'/'+multiSM;
                } else if (name  == "form") {
                  myUrl ='<?php echo base_url() ?>/Report/Mtb_report/getPayrollList/'+yearPeriod+'/'+monthPeriod+'/'+multiSM;
                } else if (name == "db") {
                  var myUrl ='<?php echo base_url() ?>/Report/Mtb_report/getDbList/'+yearPeriod+'/'+monthPeriod;
                }


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
        
        $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
        })
        

    });
      </script>