
      <!-- <div class="app-title">
        <div>
          <h1><i class="fa fa-th-list"></i> Data Table</h1>
          <p>Table to display analytical data effectively</p>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item">Tables</li>
          <li class="breadcrumb-item active"><a href="#">Data Table</a></li>
        </ul>
      </div> -->
      <!-- FORM -->
      <div class="row">    	
		    <div class="col-md-12">
          <div class="tile">
            <h3 class="tile-title">Users Form</h3>
            <div class="tile-body">
              <form class="row">
                <div class="form-group col-md-6">
                  <label class="control-label">User Id</label>
                  <input class="form-control" id="userId" type="text" placeholder="Enter id">
                </div>
                <div class="form-group col-md-6">
                  <label class="control-label">Password</label>
                  <input class="form-control" type="password" placeholder="Enter password">
                </div>
                <!-- <div class="form-group col-md-6">
                  <label class="control-label">NIP</label>
                  <input class="form-control" id="nip" type="text" placeholder="Enter NIP">
                </div> -->
                <div class="form-group col-md-6">
                  <label class="control-label">Full Name</label>
                  <input class="form-control" id="fullName" type="text" placeholder="Enter full name">
                </div>                
                <div class="form-group col-md-6">
                  <label for="exampleSelect1">Level</label>
                  <select class="form-control" id="exampleSelect1">
                    <option value="" disabled="" selected="">Choose</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                  </select>                  
                </div>
                <!-- <div class="form-group">
                  <label class="control-label">Email</label>
                  <input class="form-control" type="email" placeholder="Enter email address">
                </div> -->
                <!-- <div class="form-group">
                  <label class="control-label">Address</label>
                  <textarea class="form-control" rows="4" placeholder="Enter your address"></textarea>
                </div> -->
                <!-- <div class="form-group">
                  <label class="control-label">Gender</label>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="form-check-input" type="radio" name="gender">Male
                    </label>
                  </div>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="form-check-input" type="radio" name="gender">Female
                    </label>
                  </div>
                </div> -->
                <!-- <div class="form-group">
                  <label class="control-label">Identity Proof</label>
                  <input class="form-control" type="file">
                </div> -->
                <!-- <div class="form-group">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="form-check-input" type="checkbox">I accept the terms and conditions
                    </label>
                  </div>
                </div> -->
              </form>
            </div>
            <div class="tile-footer">
              <button class="btn btn-primary" type="button"><i class="fa fa-fw fa-lg fa-check-circle"></i>Register</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="#"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancel</a>
            </div>
          </div>
        </div>
      </div>
      <!-- TABLE -->
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body">
              <div class="table-responsive">
                <table class="table table-hover table-bordered" id="userTable">
                  <thead style="background-color: rgb(13 81 198);color: white;">
                    <tr>
                      <th>User Id</th>
                      <th>Password</th>
                      <th>Full Name</th>
                      <th>Level</th>
                      <!-- <th>Edit</th> -->
                      <!-- <th>Delete</th> -->
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Tiger Nixon</td>
                      <td>System Architect</td>
                      <td>Edinburgh</td>
                      <td>61</td>
                      <!-- <td>
                        <div class="toggle-flip">
                          <label>
                            <input type="checkbox">
                            <span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span>
                          </label>
                        </div>
                      </td> -->
                      <!-- <td>
                      	<div class="toggle-flip">
  			                  <label>
  			                    <input type="checkbox">
                            <span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span>
  			                  </label>
  			                </div>
                      </td> -->
                    </tr>                    
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>    
	
	  <!-- This Line Must Have in Every Page Content -->
	  <script src="<?php echo base_url(); ?>/assets/js/main.js"></script>  
	  <script>
      $('#demoSelect').select2();
      var baseUrl = '<?php echo base_url()?>';
      var dataList = []; 
      // var userTable = $('#userTable').DataTable();
      var userTable = null;
	  	$(document).ready(function() {
          // debugger;
          $('#userId').focus();          
	        
          users = {
            // dataList : [],
            init : function()
            {
              $.ajax({
                url    : baseUrl+'/Admin/Users/getAll',
                // url    : baseUrl+'/Admin/Users/getAllDataTable',
                method : "POST",
                success : function(data)
                {
                  debugger;
                  var srcData = JSON.parse(data);
                  // dataList = sanitizeData(srcData);

                  // userTable.clear().draw();

                  // userTable.rows.add(srcData).draw(false);
                  // {
                  //   data : srcData,
                  //   columns: [
                  //           { data: 'user_id' },
                  //           { data: 'user_password' },
                  //           { data: 'full_name' },
                  //           { data: 'user_level' }
                  // }


                  userTable = $('#userTable').DataTable({
                       data : srcData,
                       columns: [
                            { data: 'user_id' },
                            { data: 'user_password' },
                            { data: 'full_name' },
                            { data: 'user_level' }
                       ]  
                   })



                  // $('#userTable').DataTable({
                  //   data:  [
                  //     this.dataList.user_id, 
                  //     this.dataList.user_id,
                  //     this.dataList.user_id,
                  //     this.dataList.user_id
                  //   ],
                  //   columns: [
                  //     { data: 'UserId' },
                  //     { data: 'Password' },
                  //     { data: 'FullName' },
                  //     { data: 'Level' }
                  //   ]
                  // })  
                        

                  // alert(JSON.stringify(this.dataList));
                  // return false;
                  // userTable.rows.add(this.dataList).draw(false);
                  // userTable.rows.add(this.dataList).draw(false);
                }
              });
            },


            // userTable({
            //      data:  [
            //           this.dataList.user_id, 
            //           this.dataList.user_id,
            //           this.dataList.user_id,
            //           this.dataList.user_id
            //         ],
            //         columns: [
            //           { data: 'UserId' },
            //           { data: 'Password' },
            //           { data: 'FullName' },
            //           { data: 'Level' }
            //         ]
            // })

            

            

          }
          users.init();
	     } );

       function sanitizeData(data) {
          // debugger;
          var d = [];
          Object.keys(data).forEach(function(key) {
            d.push(data[key]);
          });
          return d;
       } 


	  </script>
