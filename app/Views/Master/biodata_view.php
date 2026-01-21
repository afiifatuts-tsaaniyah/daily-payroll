      <div class="app-title">
        <div>
          <h1>View Master Biodata</h1>
          <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('home') ?>"><i class="fa fa-home fa-lg"></i></a></li>
            <li class="breadcrumb-item">Master</li>
            <li class="breadcrumb-item">Master Bank</li>
          </ul>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item">
            <a href="<?= base_url('Master/Biodata/ins_view') ?>" class="btn btn-primary"><i class="fa fa-fw fa-lg fas fa-plus-circle "></i> New </a>
	    <a href="<?= base_url('Master/Biodata/printBiodata') ?>" class="btn btn-warning"><i class="fa fa-fw fa-lg fas fa-print "></i> Print </a>
          </li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
           <div class="tile-body">
           	<!-- TABLE -->
           	<div class="table-responsive">
           	  <table class="table table-hover table-bordered" id="mtBiodata">
           	    <thead style="background-color: rgb(13 81 198);color: white;">
           	     <tr>
           	       <!-- <th>Bank Id</th> -->
           	       <th>Biodata ID</th>
           	       <th>Full Name</th>
           	       <th>Action</th>
           	     </tr>
           	    </thead>
           	    <tbody>
           	     <!-- <tr> -->
           	      <!-- <td>bank_id</td> -->
           	      <!-- <td>biodata_id</td> -->
           	      <!-- <td>full_name</td> -->
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
        		url : baseUrl+'/master/Biodata/getAll1',
        		method : "POST",
        		success : function(data)
        		{
        			let srcData = JSON.parse(data);
        			/* Edit Url Controller is here */
          			/* ***Using Valid Path */
        			let updUrl = '<?php echo base_url(); ?>/Master/Biodata/upd_view/';
        			/* START TABLE */
        			let mtBiodata = $("#mtBiodata").DataTable({
        				"paging":   true,
        				"ordering": true,
        				"info":     true,
        				"filter":   true,
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
        									"targets": 2,
        									"data": "download_link",
        									"render": function ( data, type, row, meta ) {
        									  /* Change table_id with primary key of your table  */
        									  return '<a href="'+updUrl+row['biodata_id']+'" class="btn btn-sm btn-warning">Edit</a> <button class="btn btn-sm btn-danger" type="button" id="delete" data-id="'+row['biodata_id']+'">Delete</button>';
        									}
        								}
        				],
        				data : srcData,
        				columns: [
        					// { data: "bank_id" },
        					{ data: "biodata_id" },
        					{ data: "full_name" }
                  // { data: "is_local" },
        				]
        			})
        			/* END TABLE */
        		}
        	});

          $('#mtBiodata').on('click','#delete', function (e) {
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
            url: baseUrl+'/Master/Biodata/del',
            success : function(resp){

              if(resp.status == 'ERROR INSERT' || resp.status == false) {
                toastr.success('Data not saved successfully', 'Alert', {"positionClass": "toast-top-center"});
                return false;

              } else {
                toastr.success("Data has been Delete.", 'Alert', {"positionClass": "toast-top-center"});

                setTimeout(function () {
                  window.location.href = baseUrl+'/master/Biodata'; //will redirect to google.
                }, 2000);
              }
            }
          });
        }
      </script>
