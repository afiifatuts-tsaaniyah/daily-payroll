 <div class="row">
        <div class="col-md-12">
          <div class="tile">
          <h3 class="tile-title">Download Process</h3>
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
                  <label class="control-label">Start Date</label>
                  <!-- <code id="docKindErr" class="errMsg"><span> : Required</span></code> -->
                  <select class="form-control" id="startDate" name="startDate" required="">
                    <option value="" disabled="" selected="">Pilih</option>
                    <script type="text/javascript">
                      var tDate = 1;
                      for (var i = tDate; i <= 31; i++) 
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


                
                <!-- <input type="file" name="tsFile" id="tsFile"> -->
              <!-- <a href="#"> -->
                <div class="form-group col-sm-12">
                  <a class="btn btn-primary" type="button" id="btnProcess"><i class="fa fa-fw fa-lg fas fa-plus-circle "></i>Download</a>
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
                   <th>Pilih</th>
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
            
                
                     
      

    <script src="<?php echo base_url()?>/assets/js/main.js"> </script>
    <script>   
    var array_Bio = []
    function add_kode(e){
      // debugger
      let kode        = $(e).val();
      let cek         = array_Bio.lastIndexOf(kode);
      if(cek<0){
        array_Bio.push(kode);
      }else{
        array_Bio.splice(cek, 1)
      }
      // alert(array_Bio);
    }
    $(document).ready(function(){
    var contractId = "";
        /* START BIODATA TABLE */   
        var demobTable = $('#slipTable').DataTable({
                "paging":   true,
                "ordering": false,
                "info":     false,
                "filter":   true,
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
            method : "POST",
             url : "<?php echo base_url() ?>"+"/Transaction/Download/loadData", 
             // url : "<?php #echo base_url() ?>"+"masters/Mst_plh/loadPlhData", 
            data : {
                biodataId :""
            },
            success : function(data){
                demobTable.clear().draw();
                var dataSrc = JSON.parse(data); 
                demobTable.rows.add(dataSrc).draw(false);
                // alert("Succeed");    
            },
            error : function(){
                alert("Failed Load Data");
            }
        });

      $('#btnProcess').on('click', function() {
        // var array = [];
        var monthPeriod = $('#monthPeriod').val();
        var yearPeriod  = $('#yearPeriod').val();
        var startDate   = $('#startDate').val();
	      var sm          = $('#sm').val();
        // var biodataId   = $("input:checkbox[name=type]:checked").each(function() {
        // array.push($(this).val()); });
        var myUrl ='<?php echo base_url()?>/Transaction/Download/Download/'+yearPeriod+'/'+monthPeriod+'/'+startDate+'/'+sm+'/'+array_Bio;

         $.ajax({
                    url : myUrl,
                    method : "POST",
                    data   : {
                      monthPeriod  : monthPeriod,
                      yearPeriod   : yearPeriod,
                      startDate    : startDate,
                      sm           : sm,
                      array_Bio    : array_Bio
                    },
                    success : function(response){
                    console.log(response);
                    window.open(myUrl,'_blank');
                    location.reload();
                }
                    
      });
            

    });
      });
  </script>