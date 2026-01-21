      <div class="app-title">
        <div>
          <h1>View Master config</h1>
          <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('home') ?>"><i class="fa fa-home fa-lg"></i></a></li>
            <li class="breadcrumb-item">Master</li>
            <li class="breadcrumb-item">Master config</li>
          </ul>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item">
            <a href="<?= base_url('Master/Mt_config/ins_view') ?>" class="btn btn-primary"><i class="fa fa-fw fa-lg fas fa-plus-circle "></i> New </a>
          </li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
           <div class="tile-body">
           	<!-- TABLE -->
           	<div class="table-responsive">
           	  <table class="table table-hover table-bordered" id="mtConfig">
           	    <thead style="background-color: rgb(13 81 198);color: white;">
           	     <tr>
                   <th>Conf Id</th>
                   <th>Conf Code</th>
                   <th>Conf Name</th>
                   <th>Conf Value</th>
                   <th>Remarks</th>
                   <th>Edit</th>
                 </tr>
           	    </thead>
           	    <tbody>
           	     <!-- <tr> -->
           	      <!-- <td>conf_id</td> -->
           	      <!-- <td>config_code</td> -->
           	      <!-- <td>config_name</td> -->
           	      <!-- <td>pic_input</td> -->
           	      <!-- <td>input_time</td> -->
           	      <!-- <td>pic_edit</td> -->
           	      <!-- <td>edit_time</td> -->
           	      <!-- <td>Link Edit</td> -->
           	     <!-- </tr> -->
           	    </tbody>
           	  </table>
           	</div>
           </div>
          </div> <!-- class="tile" -->
        </div> <!-- class="col-md-12" -->
      </div> <!-- class="row" -->
      <!-- ***Using Valid js Path -->
      <script src="<?php echo base_url()?>/assets/js/main.js"></script>
      <script>
      	var baseUrl = '<?php echo base_url()?>';
        $(document).ready(function() {
        	/* START AJAX FOR LOAD DATA */
        	$.ajax({
        		/* ***Url is here */
        		url : baseUrl+'/master/Mt_config/getAll',
        		method : "POST",
        		success : function(data)
        		{
        			let srcData = JSON.parse(data);
        			/* Edit Url Controller is here */
          			/* ***Using Valid Path */
        			let updUrl = '<?php echo base_url(); ?>/Master/Mt_config/upd_view/';
        			/* START TABLE */
        			let mtconfig = $("#mtConfig").DataTable({
        				"paging":   true,
        				"ordering": true,
        				"info":     true,
        				"filter":   false,
        				"autoWidth": false,
        				"columnDefs": [
        								{
        									/* Hide Table Id */
        									// "targets": [0],
        									// "visible": false,
        									// "searchable": false
        								},
        								{
        									/* Column For Edit Link, (ex : 5) depend on last column no */
        									"targets": 5,
        									"data": "download_link",
        									"render": function ( data, type, row, meta ) {
        									  /* Change table_id with primary key of your table  */
        									  return '<a href="'+updUrl+row['conf_id']+'" class="btn btn-sm btn-warning">Edit</a> <button class="btn btn-sm btn-danger" type="button" id="delete" data-id="'+row['conf_id']+'">Delete</button>';
        									}
        								}
        				],
        				data : srcData,
        				columns: [
        					{ data: "conf_id" },
                  { data: "conf_code" },
                  { data: "conf_name" },
                  { data: "conf_value" },
                  { data: "remarks" }
        				]
        			})
        			/* END TABLE */
        		}
        	});

          $('#mtConfig').on('click','#delete', function (e) {
            var v_id      = $(this).data('id');

            toastr.warning(
                'Do you want to delete this row ?<br /><br />'+
                '<button type="button" id="okBtn" class="btn btn-danger" onclick="return deleteRow(this)" data-id="'+v_id+'">Yes</button> '+
                '<button type="button" id="noBtn" class="btn btn-info">No</button>', 
                '<u>ALERT</u>', 
                {
                    "positionClass": "toast-top-center",
                    "onclick": null,
                    "closeButton": false,
                }
            );
          });

        	/* END AJAX FOR LOAD DATA */          

        });
        function deleteRow(e){
          var id  = $(e).data('id');

          $.ajax({
            data: {
              id  : id
            },
            type : "POST",
            url: baseUrl+'/Master/Mt_config/del',
            success : function(resp){

              if(resp.status == 'ERROR INSERT' || resp.status == false) {
                toastr.success('Data not saved successfully', 'Alert', {"positionClass": "toast-top-center"});
                return false;

              } else {
                toastr.success("Data has been Delete.", 'Alert', {"positionClass": "toast-top-center"});

                setTimeout(function () {
                  window.location.href = baseUrl+'/master/Mt_config'; //will redirect to google.
                }, 2000);
              }
            }
          });
        }
      </script>
