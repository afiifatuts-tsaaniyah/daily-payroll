      <div class="row">
        <div class="col-md-12">
          <div class="tile">
          <h3 class="tile-title">BIODATA ACTIVATION</h3>
           <div class="tile-footer">
            <form class = "row is_header">
                <div class="form-group col-sm-12 col-md-2">
                  <label class="control-label">SEARCH</label>
                  <input class="form-control" name="search" id="search" type="text" placeholder="Serach">
                </div>
              </form>
           </div>
           <div class="tile-body">
            <!-- TABLE -->
            <div class="table-responsive">
              <table class="table table-hover table-bordered" id="slipTable">
                <thead>
                 <tr>
                   <th>Biodata Id</th>
                   <th>Salary Id</th>
                   <th>Full Name</th>
                   <th>Dept</th>
                   <th>Basic Salary</th>
                   <th>Status</th>
                   <th>Edit</th>
                 </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            
      <!-- ***Using Valid js Path -->
      <script src="<?php echo base_url()?>/assets/js/main.js"></script>
      <script>
         $(document).ready(function(){
          $('.SM').hide();
        var baseUrl = '<?php echo base_url()?>';
    /* START BIODATA TABLE */ 
        var slipTable = $('#slipTable').DataTable({
              "paging":   true,
              "ordering": false,
              "info":     true,
              "filter":   true,
              "columnDefs": [
                {
                    // "targets": -1,
                    // "data": null,
                    // "defaultContent": "<button class='btn btn-warning btn-xs btn_process'><i class='fa fa-check'></i> Active</button>"
                },
                ]
          });

      $('#search').on('keydown', function(event) {
      if (event.keyCode == 13) { // 13 adalah kode tombol "Enter"
        event.preventDefault(); // Mencegah form untuk memuat ulang halaman

        // Mendapatkan nilai keyword pencarian
        var keyword = $('#search').val();

        // Melakukan request AJAX ke controller untuk mencari data
        $.ajax({
          url: '<?php echo base_url(); ?>/Master/biodata_act/search/'+keyword,
          method: 'POST',
          data: {keyword: keyword},
          success: function(data) {
            // Menampilkan hasil pencarian di dalam div dengan id 'search-result'
          $.notify({
              title: "<h5>Informasi : </h5>",
              message: "<strong>Pencarian Data Success</strong> </br></br> ",
              icon: '' 
          },
          {
              type: "success",
              delay: 2000
          }); 
          slipTable.clear().draw();
            var dataSrc = JSON.parse(data);                 
            slipTable.rows.add(dataSrc).draw(false);
          }
        });
      }
    });

      $('#slipTable').on('click','#nonActive', function (e) {
            var v_id      = $(this).data('id');

            toastr.warning(
                'Do you want to Non Active this Employee ?<br /><br />'+
                '<button type="button" id="okBtnNoAct" class="btn btn-danger" onclick="return nonActive(this)" data-id="'+v_id+'">Yes</button> '+
                '<button type="button" id="noBtnNoAct" class="btn btn-info">No</button>', 
                '<u>ALERT</u>', 
                {
                    "positionClass": "toast-top-center",
                    "onclick": null,
                    "closeButton": false,
                }
            );
          });

      $('#slipTable').on('click','#active', function (e) {
            var v_id      = $(this).data('id');

            toastr.warning(
                'Do you want to Active this Employee ?<br /><br />'+
                '<button type="button" id="okBtnAct" class="btn btn-danger" onclick="return active(this)" data-id="'+v_id+'">Yes</button> '+
                '<button type="button" id="noBtnAct" class="btn btn-info">No</button>', 
                '<u>ALERT</u>', 
                {
                    "positionClass": "toast-top-center",
                    "onclick": null,
                    "closeButton": false,
                }
            );
          });


    });

        function nonActive(e){
          var id  = $(e).data('id');

          $.ajax({
            data: {
              id  : id
            },
            type : "POST",
            url: '<?php echo base_url(); ?>/Master/biodata_act/nonActive',
            success : function(resp){

              if(resp.status == 'ERROR INSERT' || resp.status == false) {
                toastr.success('Data not saved successfully', 'Alert', {"positionClass": "toast-top-center"});
                return false;

              } else {
                toastr.success("This Employee Has Been Successfully Inactive.", 'Alert', {"positionClass": "toast-top-center"});

                setTimeout(function () {
                  location.reload(); //will redirect to google.
                }, 2000);
              }
            }
          });
        }

        function active(e){
          var id  = $(e).data('id');

          $.ajax({
            data: {
              id  : id
            },
            type : "POST",
            url: '<?php echo base_url(); ?>/Master/biodata_act/active',
            success : function(resp){

              if(resp.status == 'ERROR INSERT' || resp.status == false) {
                toastr.success('Data not saved successfully', 'Alert', {"positionClass": "toast-top-center"});
                return false;

              } else {
                toastr.success("This Employee Has Been Successfully Inactive.", 'Alert', {"positionClass": "toast-top-center"});

                setTimeout(function () {
                  location.reload(); //will redirect to google.
                }, 2000);
              }
            }
          });
        }
      </script>
