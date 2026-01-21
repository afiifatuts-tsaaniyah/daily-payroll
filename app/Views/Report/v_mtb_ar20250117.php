<!-- <div id="loader"></div> -->

<!--START FORM DATA -->
<div class="col-md-12" id="is_transaction">
  <div class="tile bg-info">
    <!-- <h3 class="tile-title">Barang Masuk</h3> -->
    <div class="tile-body">
      <!-- <h3 class="tile-title">Martabe AR</h3> -->
      <form class="row is_header">

      <!-- <form class="row is_header" action="<?php #echo base_url() ?>transactions/sumbawa/Timesheet/payrollProcess"> -->
     
     <div class="form-group col-sm-12 col-md-3">
          <label class="control-label" for="dataPrint">DATA PRINT</label> 
          <select class="form-control" id="dataPrint" name="dataPrint" required="">
            <option value="" selected="" disabled="">Pilih</option> 
            <option value="SMmonthly">SM Monthly</option>
            <option value="AR">AR</option> 
            <option value="Summary">Summary Payroll</option>
            <!-- <option value="form">PAYMENT FORM</option>  -->
            <!-- <option value="db">DB</option> -->
          </select>
        </div>  
      <!-- <div class="form-group col-sm-12 col-md-2 dept">
          <label class="control-label">DEPT </label>
          <code id="docKindErr" class="errMsg"><span> : Required</span></code>
          <select class="form-control" id="dept" name="dept" required="">
            <option value="" disabled="" selected="">Pilih</option>
           
          </select>
        </div>  -->
        

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

        <div class="form-group col-sm-12 col-md-2 month">
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

  <!-- div class="form-group col-sm-12 col-md-1 sm">
          <label class="control-label">SM</label>
          <select class="form-control" id="sm" name="sm" required="">
            <option value="" disabled="" selected="">Pilih</option>
            <script type="text/javascript">
              var tDay = 1;
              for (var i = tDay; i <= 100; i++) 
              {
                if(i<10){
                  document.write("<option value='SM0"+i+"'>SM 0"+i+"</option>");           

                }else{
                  document.write("<option value='SM"+i+"'>SM "+i+"</option>");           

                }            
              }
            </script>
          </select>
        </div> -->

      




        


       

       
            
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

<div class="col-md-12 invoice">
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

<div class="col-md-12 summary">
  <div class="tile bg-info">
    <!-- <h3 class="tile-title">Barang Masuk</h3> -->
    <div class="tile-body">
       <!-- START DATA TABLE -->
        <div class="tile-body">
          <table class="table table-hover table-bordered" id="summary">
            <thead class="thead-dark">
              <tr>
                <th>Biodata Id</th>
                <th>Name</th>
                <th>NPWP</th>
                <th>External ID</th>
                <th>Basic Salary</th>
                <!-- <th>OT Total</th> -->
                <!-- <th>THR</th> -->
                <!-- <th>PVB</th> -->
                <!-- <th>Gross</th> -->
                <!-- <th>Adjustment</th> -->
                <!-- <th>Net Payment</th> -->
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
        var baseUrl = '<?php echo base_url()?>';
        /* START BIODATA TABLE */ 
        var slipTable = $('#invoiceList').DataTable({
                  "paging":   true,
                  "ordering": false,
                  "info":     true,
                  "filter":   true,        
              });
        var summaryTable = $('#summary').DataTable({
                  "paging":   true,
                  "ordering": false,
                  "info":     true,
                  "filter":   true,        
              });
        $('.summary').hide();
        $('.invoice').hide();

        $('#dataPrint').on('change', function(){
          name = $('#dataPrint').val();
          if(name == 'AR'){
            $('.month').hide();
          } else {
            $('.month').show();
          }
          if(name == 'Summary'){
            $('.invoice').hide();
            $('.summary').show();
          } else {
            $('.summary').hide();
            $('.invoice').show();
          }
        });
                


        $('#printToFile').on("click", function(){
          // alert('test')
                // debugger;
                var monthPeriod = $('#monthPeriod').val();
                var yearPeriod  = $('#yearPeriod').val();
                

                 
                var myUrl ='';

                if (name == "AR") {
                  var myUrl ='<?php echo base_url() ?>/Report/Mtb_ar/mtbAr/'+yearPeriod;
                } else if (name == "SMmonthly") {
                  var myUrl ='<?php echo base_url() ?>/Report/Mtb_arsm/exportReportMtbArsm/'+yearPeriod+'/'+monthPeriod;
                } else if (name == "Summary") {
                  var myUrl ="<?php echo base_url() ?>"+"/Report/Mtb_summary/exportSummaryPayrollMtb/"+yearPeriod+"/"+monthPeriod;
                }
                

                // alert(myUrl);
                
                $.ajax({
                    url : myUrl,
                    method : "POST",
                    data   : {
                      // monthPeriod  : monthPeriod,
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

            var myUrl ='<?php echo base_url() ?>/Report/Mtb_ar/getMtbArList/'+yearPeriod;
            
             if (name == "AR") {
              var myUrl ='<?php echo base_url() ?>/Report/Mtb_ar/getMtbArList/'+yearPeriod;
            } else if (name == "SMmonthly") {
              var myUrl ='<?php echo base_url() ?>/Report/Mtb_arsm/getMtbArSMList/'+yearPeriod+'/'+monthPeriod;
            } else if (name == "Summary") {
              var myUrl ="<?php echo base_url() ?>"+"/Report/Mtb_summary/getPayrollList/"+yearPeriod+"/"+monthPeriod;
            }


            // alert(myUrl);
            
            $.ajax({
                url : myUrl,
                method : "POST",
                data   : {
                  // monthPeriod  : monthPeriod,
                  yearPeriod   : yearPeriod
                },
                success : function(data){
                  if(name == 'Summary'){
                    // alert('test')
                    summaryTable.clear().draw();
                    var dataSrc = JSON.parse(data);                 
                    summaryTable.rows.add(dataSrc).draw(false);
                  } else {
                    slipTable.clear().draw();
                    var dataSrc = JSON.parse(data);                 
                    slipTable.rows.add(dataSrc).draw(false);
                  }
                },  
            });

          });
        
        $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
        })
        

    });
      </script>   