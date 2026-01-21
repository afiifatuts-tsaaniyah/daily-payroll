  

      <div class="row">
        <div class="col-lg-12">
          <div class="tile">
                  <h3 class="tile-title">Welcome</h3>
                  <div class="tile-footer">          
                  <?php echo form_open_multipart('../Transaction/Upload/Upload') ?>
                  <?php
                    $session = \Config\Services::session();
                    if(!empty($session->getFlashdata('errors'))){
                      echo '<div class="alert alert-danger" role="alert">
                    '.$session->getFlashdata('errors').'
                  </div>';
                    } else if (!empty($session->getFlashdata('pesan'))) {
                      echo '<div class="alert alert-success" role="alert">
                    '.$session->getFlashdata('pesan').'</div>';
                    }            ?> 
                  
                  <label>Import Your Data Here</label>
                  <input name="fileimport" type="file" class="form-control"  accept=".xls, .xlsx">
                  <br>
                  <div class="form-group">
                    <button type="submit" class="btn btn-success">Proses Import</button>
                
                  <?php echo form_close(); ?>

                  </br>
                  </br>
                  
                  <!-- TABLE -->
                  <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="upload">
                      <thead style="background-color: rgb(13 81 198);color: white;">
                      <tr>
                          <th>Biodata Id</th>
                            <th>Full Name</th>
                            <th>Dept</th>
                          </tr>
                      </thead>
                          <?php 
                        foreach ($upload as $row)
                        { 
                        ?>
                        <tr>
                          <td><?php echo $row[0] ?></td>
                          <td><?php echo $row[2] ?></td>
                          <td><?php echo $row[3] ?></td>
                          <td>
                              </td>
                          </tr>
                        <?php
                          }
                        ?>
                  </div>
            
          
                      </table>
                          <tbody>
                          <!-- <tr> -->
                            <!-- <td>biodata_id</td> -->
                            <!-- <td>biodata_code</td> -->
                            <!-- <td>biodata_name</td> -->
                            <!-- <td>pic_input</td> -->
                            <!-- <td>input_time</td> -->
                            <!-- <td>pic_edit</td> -->
                            <!-- <td>edit_time</td> -->
                            <!-- <td>Link Edit</td> -->
                          <!-- </tr> -->
                          </tbody>
                        
            </div>
      </div>
   </div>



              <!-- <canvas class="embed-responsive-item" id="lineChartDemo"></canvas> -->
      <!-- This Line Must Have in Every Page Content -->
      <!-- <script src="<?php echo base_url(); ?>/assets/js/main.js"></script>  -->  
      <script src="<?php echo base_url()?>/assets/js/main.js">
        $('#select_all').on('click',function(){
  if(this.checked){
    $('.checkbox').each(function(){
      this.checked = true;
    });
  }else{
    $('.checkbox').each(function(){
      this.checked = false;
    });
  }
});

$('.checkbox').on('click',function(){
    if($('.checkbox:checked').length == $('.checkbox').length){
        $('#select_all').prop('checked',true);
    }else{
        $('#select_all').prop('checked',false);
    }
});

$('#select_all2').on('click',function(){
  if(this.checked){
    $('.checkbox2').each(function(){
      this.checked = true;
    });
  }else{
    $('.checkbox2').each(function(){
      this.checked = false;
    });
  }
});

$('.checkbox2').on('click',function(){
    if($('.checkbox2:checked').length == $('.checkbox2').length){
        $('#select_all2').prop('checked',true);
    }else{
        $('#select_all2').prop('checked',false);
    }
});
        var data = {
            labels: ["January", "February", "March", "April", "May"],
            datasets: [
                {
                    label: "My First dataset",
                    fillColor: "rgba(220,220,220,0.2)",
                    strokeColor: "rgba(220,220,220,1)",
                    pointColor: "rgba(220,220,220,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: [65, 59, 80, 81, 56]
                },
                {
                    label: "My Second dataset",
                    fillColor: "rgba(151,187,205,0.2)",
                    strokeColor: "rgba(151,187,205,1)",
                    pointColor: "rgba(151,187,205,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(151,187,205,1)",
                    data: [28, 48, 40, 19, 86]
                }
            ]
          };
        var pdata = [
            {
                value: 300,
                color: "#46BFBD",
                highlight: "#5AD3D1",
                label: "Complete"
            },
            {
                value: 50,
                color:"#F7464A",
                highlight: "#FF5A5E",
                label: "In-Progress"
            }
          ]
          
          var ctxl = $("#lineChartDemo").get(0).getContext("2d");
          var lineChart = new Chart(ctxl).Line(data);
          
          var ctxp = $("#pieChartDemo").get(0).getContext("2d");
          var pieChart = new Chart(ctxp).Pie(pdata);
      </script>
    
    
