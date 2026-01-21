<style type="text/css">
tr.selected {
  background-color: #B0BED9!important;
}
</style>

<div id="spinner" style="display:none;">
  <div class="loading"></div>
</div>

<div class="col-md-12">
  <div class="tile bg-white">
    <h3 class="tile-title">Martabe Invoice</h3>
    <div class="tile-body"></div>
  </div>
</div><!-- <div id="loader"></div> -->

<!--START FORM DATA -->
<div class="col-md-12" id="is_transaction">
  <div class="tile bg-info">
    <!-- <h3 class="tile-title">Barang Masuk</h3> -->
    <div class="tile-body">
      <form class="row is_header">
        <div class="form-group col-sm-12 col-md-3">
          <label class="control-label" for="dataPrint">DATA PRINT</label> 
          <code id="dataPrintErr" class="errMsg"><span> : Required</span></code>
          <select class="form-control" id="dataPrint" name="dataPrint" required="">
            <option value="" selected="" disabled="">Pilih</option> 
            <!-- <option value="payment">PAYMENTLIST</option> -->
            <option value="invoice">Summary Invoice</option> 
            <option value="allDept">Dept Summary</option> 
            <option value="timesheet">Summary Timesheet</option>
            <option value="nap">NAP</option> 
            <option value="db">DB</option> 
          </select>
        </div>  
        <div class="form-group col-sm-12 col-md-2 dept">
          <label class="control-label">DEPT </label>
          <code id="deptErr" class="errMsg"><span> : Required</span></code>
            <select class="form-control" id="dept" name="dept" required="">
              <option value="" disabled="" selected="">Pilih</option>
              <option value="all" >ALL Dept</option>
              <?php 
              foreach ($data_dept as $key => $value) {
                echo '<option data-code="'.$value->dept_name.'" value="'.$value->dept_name.'">'.$value->dept_name.' </option>';
              }
              ?>
            </select>
        </div>
        <div class="form-group col-sm-12 col-md-2">
          <label class="control-label">YEAR</label>
          <code id="yearErr" class="errMsg"><span> : Required</span></code>
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
          <code id="monthErr" class="errMsg"><span> : Required</span></code>
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
        <div class="form-group col-md-12 align-self-end">
          <button class="btn btn-warning btnProcessPanel" type="button" id="viewInvoiceList" name="viewInvoiceList">
  	      	<span class="fa fa-list"></span> DISPLAY DATA
          </button>
  		    <button class="btn btn-warning btnProcessPanel" type="button" id="printToFile" name="printToFile">
          	<span class="fa fa-print"></span> PRINT
          </button>
  		    <br>
  		    <h3><code id="dataProses" class="backTransparent"><span></span></code></h3>	
        </div>
      </form>
    </div>
  </div>
</div>
<!-- END FORM DATA -->

<div class="col-md-12 invoiceList">
  <div class="tile bg-info">
    <!-- <h3 class="tile-title">Barang Masuk</h3> -->
    <div class="tile-body">
       <!-- START DATA TABLE -->
        <div class="tile-body">
          <table class="table table-hover table-bordered" id="invoiceList">
            <thead class="thead-dark">
              <tr>
                <th>Emp Name</th>
                <th>Dept</th>
                <th>Position</th>
                <th>Status</th>
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
<div class="col-md-12 summaryInvoice">
  <div class="tile bg-info">
    <!-- <h3 class="tile-title">Barang Masuk</h3> -->
    <div class="tile-body">
       <!-- START DATA TABLE -->
        <div class="tile-body">
          <table class="table table-hover table-bordered" id="summaryInvoice">
            <thead class="thead-dark">
              <tr>
                <th>Dept</th>
                <th>Total Employees</th>
                <th>Total Work Day</th>
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



<script src="<?php echo base_url()?>/assets/js/main.js"></script>
<script>
$(document).ready(function(){
  $('#dataPrintErr').hide();
  $('#deptErr').hide();
  $('#yearErr').hide();
  $('#monthErr').hide();

  var baseUrl = '<?php echo base_url()?>';
  var slipTable = $('#invoiceList').DataTable({
    "paging":   true,
    "ordering": false,
    "info":     true,
    "filter":   true,        
  });
  var summaryInvoice = $('#summaryInvoice').DataTable({
    "paging":   true,
    "ordering": false,
    "info":     true,
    "filter":   true,        
  });
  
  var name ='';
  var name1 ='';
  $("#loader").hide();
  $('.dept').hide();
  $('.startdate').hide();
  $('.sm').hide();
  $('.summaryInvoice').hide();
  $('.invoiceList').hide();
  $('#dataPrint').on('change', function(){
    name = $('#dataPrint').val();
              
    if(name == 'allDept' || name == 'timesheet'){
      $('.dept').show();
      $('.invoiceList').show();
      $('.summaryInvoice').hide();
      // invoiceListTable.clear().draw();
      summaryInvoice.clear().draw();
    } else if (name == 'invoice' || name == 'nap' || name == 'db') {
      $('.dept').hide();
      $('.invoiceList').hide();
      $('.summaryInvoice').show();
      summaryInvoice.clear().draw();
      // invoiceListTable.clear().draw();
    }
  });

  var dept = '';
  $('#dept').on('change', function(){
    dept = $('#dept').val();
  });


  $('#printToFile').on("click", function(){
    $("#loader").show();
    // debugger;
    var monthPeriod = $('#monthPeriod').val();
    var yearPeriod  = $('#yearPeriod').val();

    var myUrl ='';
    dept = $('#dept').val();
   
    if (name == "allDept") {
      var myUrl ='<?php echo base_url() ?>/Report/Mtb_invoice/exportinvoiceAllDept/'+yearPeriod+'/'+monthPeriod+'/'+dept;
    } else if (name == "invoice") {
      var myUrl ='<?php echo base_url() ?>/Report/Mtb_invoice/exportinvoicePayrollMartabeAgr/'+yearPeriod+'/'+monthPeriod;
    } else if (name == "timesheet") {
      var myUrl ='<?php echo base_url() ?>/Report/Mtb_invoice/summaryTimesheet/'+yearPeriod+'/'+monthPeriod+'/'+dept;
    } else if (name == "nap") {
      var myUrl ='<?php echo base_url() ?>/Report/Mtb_invoice/exportNap/'+yearPeriod+'/'+monthPeriod;
    } else if (name == "db") {
      var myUrl ='<?php echo base_url() ?>/Report/Mtb_invoice/dbInvoice/'+yearPeriod+'/'+monthPeriod;
    }

    var encodedUrl = encodeURI(myUrl);
    spinner.style.display = 'flex';
    $.ajax({
      url : encodedUrl,
      method : "POST",
      data   : {
        monthPeriod  : monthPeriod,
        yearPeriod   : yearPeriod,
        depart : dept
      },
      success : function(response){
        // $("#loader").hide();
        // console.log(response);
        spinner.style.display = 'none';
        window.open(encodedUrl,'_blank');
      },
      error : function(data){
        $("#loader").hide();
        $.notify({
          title: "<h5>Informasi : </h5>",
          message: "<strong>"+encodedUrl+"</strong> </br></br> ",
          icon: '' 
        },{
          type: "warning",
          delay: 3000
        }); 
      }  
    });

  });

  $('#viewInvoiceList').on("click", function(){
    // debugger
    $("#loader").show();
    $('#dataPrintErr').hide();
    $('#deptErr').hide();
    $('#yearErr').hide();
    $('#monthErr').hide();

    var monthPeriod = $('#monthPeriod').val();
    var yearPeriod  = $('#yearPeriod').val();
    // var sm  = $('#sm').val();
    var isDataValid = true;

    if(name=='allDept' || name=='timesheet'){
      if(dept==''){
        $('#deptErr').show();
        isDataValid = false;        
      }
    } 

    if(name==''){
      $('#dataPrintErr').show();
      isDataValid = false;        
    }  
    if (yearPeriod === null || yearPeriod === '') {
      $('#yearErr').show(); 
      isDataValid = false;   
    } 
    if(monthPeriod === null || yearPeriod === '') {
      $('#monthErr').show();
      isDataValid = false;        
    }

    if(isDataValid == false){
      return false;
    }

    var myUrl ='';
    if (name  == "nap") {
      var myUrl ='<?php echo base_url() ?>/Report/Mtb_invoice/getPayrollListInvoice/'+yearPeriod+'/'+monthPeriod;
    } else if (name  == "invoice") {
      var myUrl ='<?php echo base_url() ?>/Report/Mtb_invoice/getPayrollListInvoice/'+yearPeriod+'/'+monthPeriod;
    } else if (name == "timesheet") {
      var myUrl ='<?php echo base_url() ?>/Report/Mtb_invoice/getPayrollListInvoiceDept/'+yearPeriod+'/'+monthPeriod+'/'+dept;
    } else if (name == "allDept") {
      var myUrl ='<?php echo base_url() ?>/Report/Mtb_invoice/getPayrollListInvoiceDept/'+yearPeriod+'/'+monthPeriod+'/'+dept;
    } else if (name == "db") {
      var myUrl ='<?php echo base_url() ?>/Report/Mtb_invoice/getPayrollListInvoice/'+yearPeriod+'/'+monthPeriod;
    }
    
    spinner.style.display = 'flex';
    $.ajax({
      url : myUrl,
      method : "POST",
      data   : {
        monthPeriod  : monthPeriod,
        yearPeriod   : yearPeriod
      },
      success : function(data){
        $("#loader").hide();
      
        spinner.style.display = 'none';
        if (name == 'invoice' || name == 'nap' || name == 'db') {
          summaryInvoice.clear().draw();
          var dataSrc = JSON.parse(data);                 
          summaryInvoice.rows.add(dataSrc).draw(false);  
        } else if (name == 'allDept' || name == 'timesheet'){
          slipTable.clear().draw();
          var dataSrc = JSON.parse(data);                 
          slipTable.rows.add(dataSrc).draw(false);  
        } 
      },
    });
  });
  
  $('#myModal').on('shown.bs.modal', function () {
    $('#myInput').trigger('focus')
  });
});
</script>