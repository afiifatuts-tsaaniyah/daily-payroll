<div id="spinner" style="display:none;">
    <div class="loading"></div>
</div>

<div class="col-md-12">
    <div class="tile bg-white">
        <h3 class="tile-title">Summary Payroll</h3>


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

                <div class="form-group col-sm-12 col-md-2 dataGroup">
                    <label class="control-label">Dept </label>
                    <select class="form-control" id="dataGroup" name="dataGroup" required="">
                        <option value="" disabled="" selected="">Pilih</option>
                        <option value="All">All</option>
                    </select>
                </div>


                <div class="form-group col-md-12 align-self-end">
                    <button class="btn btn-warning btnProcessPanel" type="button" id="btnDisplay" name="btnDisplay">
                        <span class="fa fa-list"></span> DISPLAY DATA
                    </button>
                    <button class="btn btn-warning btnProcessPanel" type="button" id="btnPrintForPayroll" name="btnPrintForPayroll" style="display: none;">
                        <span class="fa fa-print"></span> Print For Payroll
                    </button>
                    <button class="btn btn-warning btnProcessPanel" type="button" id="btnPrintForAccounting" name="btnPrintForAccounting" style="display: none;">
                        <span class="fa fa-print"></span> Print For Accounting
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
            <!-- START DATA TABLE -->
            <div class="tile-body">
                <table class="table table-hover table-bordered" id="invoiceList">
                    <thead class="thead-dark">
                        <tr>
                            <th>Slip Id</th>
                            <th>Emp Name</th>
                            <th>Client Name</th>
                            <th>Position</th>
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
        var base_url = "<?= base_url() ?>";
        /* START BIODATA TABLE */
        var invoiceList = $('#invoiceList').DataTable({
            "paging": true,
            "ordering": false,
            "info": true,
            "filter": true,
        });
        /* END BIODATA TABLE */

        // $('#clientName').on('change', function() {
        //     $('#btnPrint').hide();
        //     invoiceList.clear().draw();

        //     let cn = $('#clientName').val();
        //     let label = 'Departement';
        //     let routeUrl = base_url + "/api/departement/getDepartementsByClient/" + cn;

        //     $('.dataGroupLabel').text(label);
        //     $('.labelGroup').html('GROUP');
        //     $('#dataGroup').find('option').remove();
        //     $('#dataGroup').append(new Option("All", "All"));

        //     $.ajax({
        //         method: "GET",
        //         url: routeUrl,
        //         dataType: 'json',
        //         success: function(response) {
        //             var data = response.data;

        //             for (var i = 0; i < data.length; i++) {
        //                 let optionText = '';
        //                 let optionValue = '';

        //                 if (label === 'Payroll group') {
        //                     optionText = data[i].group_name;
        //                     optionValue = data[i].group_name;
        //                 } else if (label === 'Placements') {
        //                     optionText = data[i].placement_name;
        //                     optionValue = data[i].placement_name;
        //                 } else if (label === 'Departement') {
        //                     optionText = data[i].dept;
        //                     optionValue = data[i].dept;
        //                 }

        //                 $('#dataGroup').append(new Option(optionText, optionValue));
        //             }
        //         }
        //     });
        // });



        /* START SELECT BROWSE DATA */
        var internalId = "";
        var name = "";
        var rowData = null;

        $('#invoiceList tbody').on('click', 'tr', function() {
            var rowData = invoiceList.row(this).data();

            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            } else {
                invoiceList.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }

            internalId = rowData[0];
            name = rowData[1];
        });
        /* END SELECT BROWSE DATA */

        $('.errMsg').hide();
        $('#dataProses').html('');
        var isValid = true;

        $('#clientName, #monthPeriod, #yearPeriod').on('input', function() {
            isValid = true;
        });

        $('#btnDisplay').on('click', function() {

            $("#loader").show();
            $('.errMsg').hide();
            $('#btnDisplay').prop('disabled', true);

            // VALIDATION
            if ($('#clientName option:selected').text() == 'Pilih') {
                $('#clientName').focus();
                $('#clientNameErr').show();
                isValid = false;

            } else if ($('#monthPeriod option:selected').text() == 'Pilih') {
                $('#monthPeriod').focus();
                $('#monthPeriodErr').show();
                isValid = false;

            } else if ($('#yearPeriod option:selected').text() == 'Pilih') {
                $('#yearPeriod').focus();
                $('#yearPeriodErr').show();
                isValid = false;

            } else if ($('#rosterMaster option:selected').text() == 'Pilih') {
                $('#rosterMaster').focus();
                $('#rosterMasterErr').show();
                isValid = false;
            }

            if (!isValid) {
                $("#loader").hide();
                $('#btnDisplay').prop('disabled', false);

                Swal.fire({
                    title: "Information!",
                    text: "Check Data.",
                    icon: "warning"
                });

                return false;
            }

            // GET PARAMETER
            var clientName = $('#clientName').val();
            var monthPeriod = $('#monthPeriod').val();
            var yearPeriod = $('#yearPeriod').val();
            var dataGroup = $('#dataGroup').val();

            // 🔥 ROUTE CI4
            var myUrl =
                base_url + "/Report/Summary_payroll/getDataList/" +
                clientName + "/" + yearPeriod + "/" + monthPeriod + "/" + dataGroup;

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
                        $('#btnPrintForPayroll').hide();
                        $('#btnPrintForAccounting').hide();
                    } else {
                        $('#btnPrintForPayroll').show();
                        $('#btnPrintForAccounting').show();
                    }

                    $("#loader").hide();
                    $('#btnDisplay').prop('disabled', false);

                    invoiceList.clear().draw();
                    invoiceList.rows.add(dataSrc).draw(false);
                },

                error: function(data) {

                    console.log(data);

                    Swal.fire({
                        title: "Warning!",
                        text: "There is no data",
                        icon: "error"
                    });

                    $('#btnPrintSlip').hide();
                    $("#loader").hide();
                    $('#btnDisplay').prop('disabled', false);
                }
            });
        });

        /* START PRINT SUMMARY PAYROLL FOR PAYROLL */
        $('#btnPrintForPayroll').on('click', function(e) {
            var dataPrint = $('#dataPrint').val();
            var clientName = $("#clientName").val();

            Swal.fire({
                title: "Are you sure to print summary payroll for Payroll " + clientName + " ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes!"
            }).then((result) => {
                if (result.isConfirmed) {
                    printAll();
                }
            });

            const printAll = () => {
                $("#loader").show();
                $('#btnPrintSlip').prop('disabled', true);

                var clientName = $('#clientName').val();
                var monthPeriod = $('#monthPeriod').val();
                var dataPrint = $('#dataPrint').val();
                var yearPeriod = $('#yearPeriod').val();
                var dataGroup = $('#dataGroup').val();

                let isValid = true;

                if ($('#clientName option:selected').text() == 'Pilih') {
                    $('#clientName').focus();
                    $('#clientNameErr').show();
                    isValid = false;

                } else if ($('#yearPeriod option:selected').text() == 'Pilih') {
                    $('#yearPeriod').focus();
                    $('#yearPeriodErr').show();
                    isValid = false;

                } else if ($('#monthPeriod option:selected').text() == 'Pilih') {
                    $('#monthPeriod').focus();
                    $('#monthPeriodErr').show();
                    isValid = false;
                }

                // 🔄 URL CI4
                let myUrl =
                    "<?= base_url('/Report/Summary_payroll/exportSumarryPayrollForPayroll') ?>" +
                    "/" + clientName +
                    "/" + yearPeriod +
                    "/" + monthPeriod +
                    "/" + dataGroup;

                if (!isValid) {
                    $("#loader").hide();
                    $('#btnPrintSlip').prop('disabled', false);
                    Swal.fire({
                        text: "Check Data",
                        icon: "warning"
                    });
                    return false;
                }

                // 🔄 OPEN URL (download excel)
                window.open(myUrl, '_blank');

                $("#loader").hide();
                $('#btnPrintSlip').prop('disabled', false);
            }
        });
        /* END PRINT SLIP */

        /* START PRINT SUMMARY PAYROLL FOR PAYROLL */
        $('#btnPrintForAccounting').on('click', function(e) {
            var dataPrint = $('#dataPrint').val();
            var clientName = $("#clientName").val();

            Swal.fire({
                title: "Are you sure to print summary payroll for Accounting " + clientName + " ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes!"
            }).then((result) => {
                if (result.isConfirmed) {
                    printAll();
                }
            });

            const printAll = () => {
                $("#loader").show();
                $('#btnPrintSlip').prop('disabled', true);

                var clientName = $('#clientName').val();
                var monthPeriod = $('#monthPeriod').val();
                var dataPrint = $('#dataPrint').val();
                var yearPeriod = $('#yearPeriod').val();
                var dataGroup = $('#dataGroup').val();

                let isValid = true;

                if ($('#clientName option:selected').text() == 'Pilih') {
                    $('#clientName').focus();
                    $('#clientNameErr').show();
                    isValid = false;

                } else if ($('#yearPeriod option:selected').text() == 'Pilih') {
                    $('#yearPeriod').focus();
                    $('#yearPeriodErr').show();
                    isValid = false;

                } else if ($('#monthPeriod option:selected').text() == 'Pilih') {
                    $('#monthPeriod').focus();
                    $('#monthPeriodErr').show();
                    isValid = false;
                }

                // 🔄 URL CI4
                let myUrl =
                    "<?= base_url('/Report/Summary_payroll/exportSumarryPayrollForAccounting') ?>" +
                    "/" + clientName +
                    "/" + yearPeriod +
                    "/" + monthPeriod +
                    "/" + dataGroup;

                if (!isValid) {
                    $("#loader").hide();
                    $('#btnPrintSlip').prop('disabled', false);
                    Swal.fire({
                        text: "Check Data",
                        icon: "warning"
                    });
                    return false;
                }

                // 🔄 OPEN URL (download excel)
                window.open(myUrl, '_blank');

                $("#loader").hide();
                $('#btnPrintSlip').prop('disabled', false);
            }
        });
        /* END PRINT SLIP */



    });
</script>