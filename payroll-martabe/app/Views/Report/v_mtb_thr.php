<div class="col-md-12">
  <div class="tile bg-white">
    <h3 class="tile-title">Martabe Report THR</h3>
    
    
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
     
     
      <div class="form-group col-sm-12 col-md-2 dept">
          <label class="control-label">DEPT </label>
          <code id="docKindErr" class="errMsg"><span> : Required</span></code>
          <select class="form-control" id="dept" name="dept" required="">
            <option value="" disabled="" selected="">Pilih</option>
            <option value="alldept" >All Dept</option>
            <?php 
                  foreach ($data_dept as $key => $value) {
                  echo '<option data-code="'.$value->dept_name.'" value="'.$value->dept_name.'">'.$value->dept_name.' </option>';
                  }
                  ?>
          </select>
        </div> 
        

        <div class="form-group col-sm-12 col-md-2">
          <label class="control-label">Start Date</label>
          <input class="form-control" type="date" id="startDate" name="startDate" required=""></input>
        </div>

        <div class="form-group col-sm-12 col-md-2">
          <label class="control-label">End Date</label>
          <input class="form-control" type="date" id="endDate" name="endDate" required=""></input>
        </div>

        <!-- <div class="form-group col-sm-12 col-md-1">
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
        </div> -->

	<!-- <div class="form-group col-sm-12 col-md-1 sm">
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
			<button class="btn btn-warning btnProcessPanel" type="button" id="viewThrList" name="viewThrList">
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
          <table class="table table-hover table-bordered" id="ThrList">
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
    var baseUrl = '<?php echo base_url()?>';
    /* START BIODATA TABLE */ 
    var slipTable = $('#ThrList').DataTable({
          "paging":   true,
          "ordering": false,
          "info":     true,
          "filter":   true,        
    });
    $('#printToFile').on("click", function(){
            var startDate  = $('#startDate').val();
            var endDate  = $('#endDate').val();
            var dept  = $('#dept').val();
            var myUrl ='<?php echo base_url() ?>/Report/Mtb_thr/Print/'+startDate+'/'+endDate+'/'+dept;
            if (dept == 'alldept') {
              var myUrl ='<?php echo base_url() ?>/Report/Mtb_thr/PrintAllDept/'+startDate+'/'+endDate;
            }
            $.ajax({
                url : myUrl,
                method : "POST",
                data   : {
                  startDate   : startDate,
                  endDate   : endDate,
                  depart : dept
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
      $('#viewThrList').on("click", function(){
              var startDate  = $('#startDate').val();
              var endDate  = $('#endDate').val();
              var dept  = $('#dept').val();

              var myUrl ='<?php echo base_url() ?>/Report/Mtb_thr/getThrListByDept/'+startDate+'/'+endDate+'/'+dept;
              if (dept == 'alldept') {
                var myUrl ='<?php echo base_url() ?>/Report/Mtb_thr/getThrListAllDept/'+startDate+'/'+endDate;
              }


              // alert(myUrl);
              
              $.ajax({
                  url : myUrl,
                  method : "POST",
                  data   : {
                    startDate   : startDate,
                  endDate   : endDate,
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