<div id="spinner" style="display:none;">
    <div class="loading"></div>
</div>

<div class="col-md-12">
    <div class="tile bg-white">
        <h3 class="tile-title">Clossing Payroll</h3>
        <div class="tile-body">
        </div>
    </div>
</div>

<!--START FORM DATA -->
<div class="col-md-12" id="is_transaction">
    <div class="tile bg-info">
        <h3 class="tile-title">Input Clossing Payroll</h3>

        <div class="tile-body">
            <form class="row is_header">
                <div class="form-group col-sm-12 col-md-2 clientName">
                    <label class="control-label">Clients </label>
                    <!-- <code id="docKindErr" class="errMsg"><span> : Required</span></code> -->
                    <select class="form-control" id="clientName" name="clientName" required="">
                        <option value="" disabled="" selected="">Pilih</option>
                        <?php
                        foreach ($clients as $value) {
                            echo '<option data-code="' . $value['client_value'] . '" value="' . $value['client_value'] . '">' . $value['client_name'] . ' </option>';
                        }
                        ?>
                    </select>
                </div>


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
                            var endYear = startYear - 5;
                            for (var i = startYear; i >= endYear; i--) {
                                document.write("<option value='" + i + "'>" + i + "</option>");
                            }
                        </script>
                    </select>
                </div>

                <div class="form-group col-sm-12 col-md-1">
                    <label class="control-label">MONTH</label>
                    <select class="form-control" id="monthPeriod" name="monthPeriod" required="">
                        <option value="" disabled="" selected="">Pilih</option>
                        <script type="text/javascript">
                            var tDay = 1;
                            for (var i = tDay; i <= 12; i++) {
                                if (i < 10) {
                                    document.write("<option value='0" + i + "'>0" + i + "</option>");

                                } else {
                                    document.write("<option value='" + i + "'>" + i + "</option>");

                                }
                            }
                        </script>
                    </select>
                </div>

                <div class="form-group col-md-12 align-self-end">
                    <button class="btn btn-warning btnProcessPanel" type="button" id="btnClossing" name="btnClossing">
                        <span class="fa fa-list"></span> INPUT CLOSSING
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
        <div class="tile-body">
            <h3 class="tile-title">Display Closssing</h3>
            <form class="row is_header">
                <div class="form-group col-sm-12 col-md-2 clientNameDisplay">
                    <label class="control-label">Clients </label>
                    <!-- <code id="docKindErr" class="errMsg"><span> : Required</span></code> -->
                    <select class="form-control" id="clientNameDisplay" name="clientNameDisplay" required="">
                        <option value="" disabled="" selected="">Pilih</option>
                        <?php
                        foreach ($clients as $value) {
                            echo '<option data-code="' . $value['client_value'] . '" value="' . $value['client_value'] . '">' . $value['client_name'] . ' </option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group col-md-12 align-self-end">
                    <button class="btn btn-warning btnProcessPanel" type="button" id="btnDisplay" name="btnDisplay">
                        <span class="fa fa-list"></span> DISPLAY DATA
                    </button>
                    <br>
                    <!-- <br> -->
                    <h3><code id="dataProses" class="backTransparent"><span></span></code></h3>
                </div>
            </form>

            <!-- START DATA TABLE -->
            <div class="tile-body">
                <table class="table table-hover table-bordered" id="clossingPayrollTable">
                    <thead class="thead-dark">
                        <tr>
                            <th>Client Name</th>
                            <th>Year</th>
                            <th>Month</th>
                            <th>Status</th>
                            <th>Action</th>
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
        var clossingPayrollTable = $('#clossingPayrollTable').DataTable({
            "paging": true,
            "ordering": true,
            "info": false,
            "filter": true,
        });

        $('#btnDisplay').click(function(e) {
            e.preventDefault();
            console.log("AAA");


            let clientName = $('#clientNameDisplay').val();

            // CI4 URL
            let myUrl = "<?= site_url('Transaction/Clossing_payroll/getDataClossing') ?>";

            $.ajax({
                type: "GET",
                url: myUrl,
                data: {
                    clientName: clientName
                },
                dataType: "json",
                success: function(response) {
                    let dataSrc = response.data;
                    let dataLength = dataSrc.length;

                    clossingPayrollTable.clear().draw();

                    if (dataLength === 0) {
                        Swal.fire({
                            title: "Warning!",
                            text: "There's no data processed",
                            icon: "warning"
                        });
                    } else {
                        Swal.fire({
                            title: "Success!",
                            text: "There are " + dataLength + " datas",
                            icon: "success"
                        });

                        clossingPayrollTable.rows.add(dataSrc).draw(false);
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });


        $("#btnClossing").on("click", function() {
            var client_name = $('#clientName').val();
            var yearPeriod = $('#yearPeriod').val();
            var monthPeriod = $('#monthPeriod').val();
            var myUrl = "<?= base_url('Transaction/Clossing_payroll/insertClossing') ?>";


            $.ajax({
                method: "POST",
                url: myUrl,
                data: {
                    client_name: client_name,
                    yearPeriod: yearPeriod,
                    monthPeriod: monthPeriod,
                },
                success: function(data) {
                    Swal.fire({
                        title: "Success",
                        text: data.message,
                        icon: "success"
                    });
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        Swal.fire({
                            title: "Validation Error",
                            text: "Please check your input.",
                            icon: "error"
                        });
                    } else if (xhr.status === 409) {
                        Swal.fire({
                            title: "Error",
                            text: xhr.responseJSON.error,
                            icon: "error"
                        });
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: "Save failed.",
                            icon: "error"
                        });
                    }
                }
            });


        });


        $('#clossingPayrollTable tbody').on('click', '.btnClossingData', function() {
            var row = $(this).closest('tr');
            var data = clossingPayrollTable.row(row).data();
            var clientName = data[0];
            var yearPeriod = data[1];
            var monthPeriod = data[2];
            var currentStatus = data[3]; // 'Open' atau 'Close'

            Swal.fire({
                title: "Are you sure?",
                text: "You want to change the data?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, update it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "<?= site_url('Transaction/Clossing_payroll/updateClosingPayroll') ?>", // controller CI4

                        data: {
                            <?= csrf_token() ?>: "<?= csrf_hash() ?>",
                            clientName: clientName,
                            yearPeriod: yearPeriod,
                            monthPeriod: monthPeriod
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                // toggle status
                                var newStatus = currentStatus === 'Open' ? 'Close' : 'Open';
                                var newAction = newStatus === 'Open' ? 'Clossing' : 'Open';

                                // update data di DataTables
                                data[3] = newStatus; // status
                                data[4] = "<button class='btn btn-warning btn-sm btnClossingData' type='button' " +
                                    "data-client='" + clientName + "' " +
                                    "data-tahun='" + yearPeriod + "' " +
                                    "data-bulan='" + monthPeriod + "'>" + newAction + "</button>";

                                clossingPayrollTable.row(row).data(data).draw(false);

                                Swal.fire({
                                    title: "Success!",
                                    text: "Your data has been changed.",
                                    icon: "success"
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: "Error!",
                                text: "Something went wrong.",
                                icon: "error"
                            });
                        }
                    });
                }
            });
        });






    })
</script>