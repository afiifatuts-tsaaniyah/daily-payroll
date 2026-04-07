<div id="spinner" style="display:none;">
    <div class="loading"></div>
</div>

<div class="col-md-12">
    <div class="tile bg-white">
        <h3 class="tile-title">Report Salary</h3>
        <div class="tile-body">
        </div>
    </div>
</div>

<!--START FORM DATA -->
<div class="col-md-12" id="is_transaction">
    <div class="tile bg-info">
        <div class="tile-body">
            <form class="row is_header">
                <div class="form-group col-sm-12 col-md-2 clientName">
                    <label class="control-label">Clients </label>
                    <!-- <code id="docKindErr" class="errMsg"><span> : Required</span></code> -->
                    <select class="form-control" id="clientName" name="clientName" required="">
                        <option value="" disabled="" selected="">Pilih</option>
                        <option value="All">All</option>
                        <?php
                        foreach ($clients as $value) {
                            echo '<option data-code="' . $value['client_value'] . '" value="' . $value['client_value'] . '">' . $value['client_name'] . ' </option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group col-sm-12 col-md-2 dataPrint">
                    <label class="control-label">Status </label>
                    <!-- <code id="docKindErr" class="errMsg"><span> : Required</span></code> -->
                    <select class="form-control" id="dataPrint" name="dataPrint" required="">
                        <option value="" disabled="" selected="">Pilih</option>
                        <option value="All">All</option>
                        <option value="Daily">Daily</option>
                        <option value="Monthly">Monthly</option>
                    </select>
                </div>



                <div class="form-group col-md-12 align-self-end">
                    <button class="btn btn-warning btnProcessPanel" type="button" id="viewSalaryList" name="viewSalaryList">
                        <span class="fa fa-list"></span> DISPLAY DATA
                    </button>
                    <button class="btn btn-warning btnProcessPanel" type="button" id="printToFile" name="printToFile">
                        <span class="fa fa-print"></span> PRINT SALARY
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
                <table class="table table-hover table-bordered" id="salaryList">
                    <thead class="thead-dark">
                        <tr>
                            <th>Biodata Id</th>
                            <th>Emp Name</th>
                            <th>Company Name</th>
                            <th>Account No</th>
                            <th>Bank</th>
                            <th>Monthly</th>
                            <th>Daily</th>
                            <th>Status Payroll</th>
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



<script src="<?php echo base_url() ?>/assets/js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    $(document).ready(function() {
        $('#printToFile').hide();

        var base_url = "<?= base_url() ?>";
        /* START BIODATA TABLE */
        var salaryList = $('#salaryList').DataTable({
            "paging": true,
            "ordering": false,
            "info": true,
            "filter": true,
            "columns": [{
                    data: 'biodata_id',
                },
                {
                    data: 'account_name'
                },
                {
                    data: 'company_name'
                },
                {
                    data: 'account_no'
                },
                {
                    data: 'bank_id'
                },
                {
                    data: 'monthly'
                },
                {
                    data: 'daily'
                },
                {
                    data: 'status_payroll'
                }
            ]
        });
        /* END BIODATA TABLE */

        /* START SELECT BROWSE DATA */
        var internalId = "";
        var name = "";
        var rowData = null;

        $('#salaryList tbody').on('click', 'tr', function() {
            var rowData = salaryList.row(this).data();

            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            } else {
                salaryList.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }

            internalId = rowData[0];
            name = rowData[1];
        });
        /* END SELECT BROWSE DATA */

        /* START SELECT BROWSE DATA */
        var internalId = "";
        var name = "";
        var rowData = null;

        $('#salaryList tbody').on('click', 'tr', function() {
            var rowData = salaryList.row(this).data();

            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            } else {
                salaryList.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }

            internalId = rowData[0];
            name = rowData[1];
        });
        /* END SELECT BROWSE DATA */

        $('.errMsg').hide();
        $('#dataProses').html('');
        var isValid = true;

        $('#clientName, #dataPrint').on('input', function() {
            isValid = true;
        });

        $('#viewSalaryList').on('click', function() {

            $("#loader").show();
            $('.errMsg').hide();
            // $('#viewSalaryList').prop('disabled', true);

            // VALIDATION
            if ($('#clientName option:selected').text() == 'Pilih') {
                $('#clientName').focus();
                $('#clientNameErr').show();
                isValid = false;

            } else if ($('#dataPrint option:selected').text() == 'Pilih') {
                $('#dataPrint').focus();
                $('#dataPrintErr').show();
                isValid = false;
            }

            if (!isValid) {
                $("#loader").hide();
                $('#viewSalaryList').prop('disabled', false);

                Swal.fire({
                    title: "Information!",
                    text: "Check Data.",
                    icon: "warning"
                });

                return false;
            }

            // GET PARAMETER
            var clientName = $('#clientName').val();
            var dataPrint = $('#dataPrint').val();

            // 🔥 ROUTE CI4
            var myUrl =
                base_url + "/Report/Report_salary/getEmployeeListByClient/" +
                clientName + "/" + dataPrint;

            // REQUEST AJAX CI4
            $.ajax({
                method: "GET",
                url: myUrl,
                success: function(response) {
                    console.log(response);

                    var dataSrc = response.data;

                    if (dataSrc.length === 0) {
                        Swal.fire({
                            title: "Warning!",
                            text: "There are no data",
                            icon: "warning"
                        });
                        $('#printToFile').hide();
                    } else {
                        $('#printToFile').show();
                    }

                    $("#loader").hide();
                    $('#viewSalaryList').prop('disabled', false);

                    salaryList.clear().draw();
                    salaryList.rows.add(dataSrc).draw(false);
                },

                error: function(data) {

                    console.log(data);

                    Swal.fire({
                        title: "Warning!",
                        text: "There is no data",
                        icon: "error"
                    });

                    $('#printToFile').hide();
                    $("#loader").hide();
                    $('#viewSalaryList').prop('disabled', false);
                }
            });
        });


        const printAll = () => {
            $("#loader").show();
            $('#printToFile').prop('disabled', true);

            var clientName = $('#clientName').val();
            var dataPrint = $('#dataPrint').val();
            var isValid = true;

            if ($('#clientName option:selected').text() == 'Pilih') {
                $('#clientName').focus();
                $('#clientNameErr').show();
                isValid = false;
            } else if ($('#dataPrint option:selected').text() == 'Pilih') {
                $('#dataPrint').focus();
                $('#dataPrintErr').show();
                isValid = false;
            }

            let myUrl = base_url + "/Report/Report_salary/printSalary/" +
                clientName + "/" + dataPrint;

            if (!isValid) {
                $("#loader").hide();
                $('#printToFile').prop('disabled', false);

                Swal.fire({
                    text: "Check Data",
                    icon: "warning"
                });
                return false;
            }

            // Open link
            window.open(myUrl, '_blank');

            $("#loader").hide();
            $('#printToFile').prop('disabled', false);
        }

        /* START PRINT SLIP */
        $('#printToFile').on('click', function(e) {
            var selectedOptionText = $('#dataPrint option:selected').text();
            var dataPrint = $('#dataPrint').val();
            var clientName = $("#clientName").val();

            // Swal.fire({
            //     text: "Are you sure to print " + selectedOptionText + " " + clientName + " ?",
            //     icon: "warning",
            //     showCancelButton: true,
            //     confirmButtonColor: "#3085d6",
            //     cancelButtonColor: "#d33",
            //     confirmButtonText: "Yes!"
            // }).then((result) => {
            //     if (result.isConfirmed) {}
            // });
            printAll();

        });
        /* END PRINT SLIP */




    });
</script>