<script>
    $(document).ready(function() {
        // hideProgressModal()

        var defaultClient = $('#clientName').val();
        var defaultYear = $('#yearPeriod').val();
        var defaultMonth = $('#monthPeriod').val();
        var defaultOT = $('#otInOffCount').val();
        var defaultDataGroup = $('#dataGroup').val();
        var defaultHealthBPJS = $('#cbHealthBPJS').prop('checked');
        var defaultJHT = $('#cbJHT').prop('checked');
        var defaultJP = $('#cbJP').prop('checked');
        var defaultJKKM = $('#cbJKKM').prop('checked');
        var defaultEnd = $('#cbEnd').prop('checked');
        $('#closePayroll').hide();
        $('#btnPrintSlip').hide();
        $('#btnSelectPrint').hide();


        const $nieContainer = $('#nieContainer');
        const $biodataInput = $('#biodataId');

        refresh();

        function refresh() {
            $('#clientName').val(defaultClient);
            $('#yearPeriod').val(defaultYear);
            $('#monthPeriod').val(defaultMonth);
            $('#otInOffCount').val(defaultOT);
            $('#dataGroup').val(defaultDataGroup);

            $('#cbHealthBPJS').prop('checked', defaultHealthBPJS);
            $('#cbJHT').prop('checked', defaultJHT);
            $('#cbJP').prop('checked', defaultJP);
            $('#cbJKKM').prop('checked', defaultJKKM);
            $('#cbEnd').prop('checked', defaultEnd);
        }

        // tombol Process by ID → tampilkan input
        $('#btnGetNie').on('click', function() {
            $('#biodataId').val(null);
            const $icon = $(this).find('i');
            $icon.removeClass('d-none');
            $(this).prop('disabled', true);

            // simulasi proses loading
            setTimeout(() => {
                $icon.addClass('d-none');
                $(this).prop('disabled', false);
                $nieContainer.removeClass('d-none'); // tampilkan input
                $biodataInput.focus();
            }, 500); // setengah detik aja biar cepat respons
        });

        // tombol Cancel Process → sembunyikan input
        $('#btnCancelNie').on('click', function() {
            $('#biodataId').val(null);
            const $icon = $(this).find('i');
            $icon.removeClass('d-none');
            $(this).prop('disabled', true);

            setTimeout(() => {
                $icon.addClass('d-none');
                $(this).prop('disabled', false);
                $nieContainer.addClass('d-none'); // sembunyikan input
            }, 500);
        });

        // tombol Reset → sembunyikan input dan kosongkan value
        $('#btnReset').on('click', function() {
            $('#biodataId').val(null);
            const $icon = $(this).find('i');
            $icon.removeClass('d-none');
            $(this).prop('disabled', true);

            setTimeout(() => {
                $icon.addClass('d-none');
                $(this).prop('disabled', false);
                $biodataInput.val(''); // hapus isi input
                $nieContainer.addClass('d-none'); // sembunyikan input
            }, 500);
        });

        var baseUrl = '<?php echo base_url() ?>';
        /* START BIODATA TABLE */
        var slipTable = $('#slipTable').DataTable({
            "paging": true,
            "ordering": false,
            "info": true,
            "filter": true,
            "columnDefs": [{
                    "targets": -2,
                    "data": null,
                    "defaultContent": "<button class='btn btn-warning btn-xs btn_update' data-toggle='modal' data-target='#editPayrollModal'><i class='fa fa-edit'></i></button>"
                },
                {
                    "targets": -3,
                    "data": null,
                    "defaultContent": "<button class='btn btn-warning btn-xs btn_print'><i class='fa fa-print'></i></button>"
                },
                {
                    "targets": -1,
                    "data": null,
                    "defaultContent": "<button class='btn btn-warning btn-xs btn_process'><i class='fa fa-spinner'></i></button>"
                },
            ]
        });

        $('#btnReset').click(function() {
            refresh();
            $('#clossingAlert').addClass('d-none').hide();
            $('#closePayroll').addClass('d-none').hide();
            slipTable.clear().draw();
            // slipTableSelect.clear().draw();
        });


        /* START SELECT BROWSE DATA */
        var internalId = "";
        var name = "";
        var rowData = null;
        $('#slipTable tbody').on('click', 'tr', function() {
            var rowData = slipTable.row(this).data();
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            } else {
                slipTable.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
            internalId = rowData[0];
            name = rowData[1];
        });
        /* END SELECT BROWSE DATA */



        $('.errMsg').hide();
        $('#dataProses').html('');
        $('#btnProsesRoster').on('click', function() {
            let client = $('#clientName').val();
            let year = $('#yearPeriod').val();
            let month = $('#monthPeriod').val();
            let group = $('#dataGroup').val();

            // validasi
            if (!client || !year || !month || !group) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Form belum lengkap',
                    text: 'Silakan pilih Client, Year, Month, dan Group terlebih dahulu!',
                    confirmButtonText: 'OK'
                });
                return false; // hentikan proses
            }


            let clientName = $('#clientName').val();
            Swal.fire({
                title: "Are you sure you want to process?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, process!"
            }).then((result) => {
                if (result.isConfirmed) {
                    processRoster(0);
                }
            });
        });

        const processRoster = (currentBatch) => {
            showProgressModal()
            $('.errMsg').hide();
            $('#btnProsesRoster').prop('disabled', true);
            var client = $('#clientName').val();
            var month = $('#monthPeriod').val();
            var year = $('#yearPeriod').val();
            var roster = $('#rosterMaster').val();
            var otOffCount = $('#otInOffCount').val();
            var bioId = $('#biodataId').val();

            var dataGroup = $('#dataGroup').val();
            var myUrl = "<?php echo base_url('Transaction/Timesheet_process/process') ?>";
            var isHealthBPJS = '0';
            var isJHT = '0';
            var isJP = '0';
            var isJKKM = '0';
            var isEnd = '0';
            // debugger;
            if ($("#cbHealthBPJS").is(':checked')) {
                isHealthBPJS = '1';
            }
            if ($("#cbJHT").is(':checked')) {
                isJHT = '1';
            }
            if ($("#cbJP").is(':checked')) {
                isJP = '1';
            }
            if ($("#cbJKKM").is(':checked')) {
                isJKKM = '1';
            }
            if ($("#cbEnd").is(':checked')) {
                isEnd = '1';
            }


            $.ajax({
                method: "POST",
                url: myUrl,
                data: {
                    slipId: 0,
                    biodataId: bioId,
                    dataGroup: dataGroup,
                    monthPeriod: month,
                    clientName: client,
                    yearPeriod: year,
                    isHealthBPJS: isHealthBPJS,
                    isJHT: isJHT,
                    isJP: isJP,
                    isJKKM: isJKKM,
                    isEnd: isEnd,
                    batch: currentBatch
                },
                success: function(response) {
                    console.log(response)
                    let dataSrc = response.data;
                    let data = dataSrc.data;
                    let nextBatch = dataSrc.nextBatch;
                    let currentBatch = dataSrc.currentBatch;
                    let totalBatch = dataSrc.totalBatch;


                    let progress = Math.round((currentBatch / totalBatch) * 100);
                    updateProgress(progress)
                    $("#loader").hide();
                    if (nextBatch == 0) {
                        let textSuccess = "All data has been processed.";
                        // if (bioId != '') {
                        //     textSuccess = "Biodata " + bioId + " successfully reprocess";
                        // }
                        Swal.fire({
                            title: "Finished!",
                            text: textSuccess,
                            icon: "success"
                        });
                        $("#loader").hide();
                        updateProgress(0)
                        hideProgressModal()

                        // getConfetti()
                        slipTable.clear().draw();
                        slipTable.rows.add(data).draw(false);

                        slipTableSelect.clear().draw();
                        slipTableSelect.rows.add(data).draw(false);

                        $('#btnPrintSlip').removeClass('d-none');
                        $('#btnSelectPrint').removeClass('d-none');
                        $('#btnProsesRoster').prop('disabled', false);
                        $('#btnPrintSlip').show();
                        $('#btnSelectPrint').show();
                        return;
                    } else {
                        $("#loader").hide();
                        processRoster(nextBatch);
                    }
                    $("#loader").hide();

                },
                error: function(xhr) {
                    console.log(xhr);

                    $('#btnProsesRoster').prop('disabled', false);

                    hideProgressModal()
                    var messageError = xhr.responseJSON.message;
                    var responseError = xhr.responseJSON.errors;
                    var responseStatus = xhr.status;


                    $('#btnProsesRoster').prop('disabled', false);
                    $('#btnPrintSlip').hide();
                    $('#btnSelectPrint').hide();
                    $("#loader").hide();
                    if (xhr.status === 422) {
                        var response = xhr.responseJSON;
                        $('.invalid-feedback').remove();
                        $('.form-control').removeClass('is-invalid');

                        if (response.errors) {
                            $.each(response.errors, function(key, value) {
                                var inputName = key.split('.')
                                    .pop(); // Extract field name from key
                                $('#' + inputName).after(
                                    '<div class="invalid-feedback">' +
                                    value[0] + '</div>');
                                $('#' + inputName).addClass('is-invalid');
                            });
                        }
                    } else if (responseStatus == 419 || responseStatus == 401) {
                        handleCsrfError();
                    } else {
                        Swal.fire({
                            title: messageError,
                            text: responseError,
                            icon: "error"
                        });
                    }
                }
            });
        }



        $('#btnDisplay').on('click', function() {
            $("#loader").show();
            $('.errMsg').hide();
            $('#btnDisplay').prop('disabled', true);

            var client = $('#clientName').val();
            var dataGroup = $('#dataGroup').val();
            var month = $('#monthPeriod').val();
            var year = $('#yearPeriod').val();
            var bioId = $('#idFilter').val();

            // validasi
            if (!client || !year || !month || !dataGroup) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Form belum lengkap',
                    text: 'Silakan pilih Client, Year, Month, dan Group terlebih dahulu!',
                    confirmButtonText: 'OK'
                });
                return false; // hentikan proses
            }

            var myUrlCheck = "<?php echo base_url('Transaction/Timesheet_process/checkClosingPayrollByPeriod') ?>/" +
                client + "/" + year + "/" + month + "/" + dataGroup;

            $.ajax({
                method: "POST",
                url: myUrlCheck,
                data: {
                    _token: "{{ csrf_token() }}",
                    biodataId: bioId,
                    clientName: client,
                    dataGroup: dataGroup,
                    monthPeriod: month,
                    yearPeriod: year,
                },
                success: function(response) {
                    var payrollList = response.data.payrollList;
                    var clossingStatus = response.data.clossingStatus;

                    $('#clossingAlert').addClass('d-none').hide();

                    if (payrollList.length === 0) {
                        Swal.fire({
                            title: "Warning!",
                            text: "There are no data",
                            icon: "warning"
                        });
                    } else if (payrollList.length > 0 && clossingStatus == 1) {
                        $('#btnProsesRoster').prop('disabled', true);
                        $('#clossingAlert').removeClass('d-none').show();
                        Swal.fire({
                            title: "Warning!",
                            text: "Payroll period has been closed",
                            icon: "warning"
                        });
                        $('#btnPrintSlip').show();
                        $('#btnSelectPrint').show();
                    } else {
                        $('#btnProsesRoster').prop('disabled', false);
                        $('.btn_update').prop('disabled', false);
                        $('.btn_process').prop('disabled', false);
                        $('#btnPrintSlip').show();
                        $('#btnSelectPrint').show();
                    }

                    $("#loader").hide();
                    $('#btnDisplay').prop('disabled', false);
                    slipTable.clear().draw();
                    slipTable.rows.add(payrollList).draw(false);

                    slipTableSelect.clear().draw();
                    slipTableSelect.rows.add(payrollList).draw(false);

                    if (payrollList.length > 0 && clossingStatus == 1) {
                        $('.btn_update').prop('disabled', true);
                        $('.btn_process').prop('disabled', true);
                    }
                },
                error: function() {
                    $('#btnPrintSlip').hide();
                    $('#btnSelectPrint').hide();
                    $("#loader").hide();
                    $('#btnDisplay').prop('disabled', false);
                    Swal.fire({
                        title: "Error!",
                        text: "There is no data",
                        icon: "error"
                    });
                }
            });
        });




        /* START PRINT SLIP */
        $('#btnPrintSlip').on('click', function(e) {
            Swal.fire({
                title: "Are you sure you want to print the slip?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, print!"
            }).then((result) => {
                if (result.isConfirmed) {
                    printAll();
                }
            });

            function printAll() {
                let isValid = true; // ✅ harus deklarasi, jangan "== false"
                $("#loader").show();
                $('#btnPrintSlip').prop('disabled', true);

                // Ambil nilai input
                const dept = $('#dept').val();
                const month = $('#monthPeriod').val();
                const year = $('#yearPeriod').val();
                const ptName = $("#clientName").val();
                const dataGroup = $('#dataGroup').val();

                // Default flags
                let isEnd = $('#cbEnd').is(':checked') ? '1' : '0';
                let isHealthBPJS = $('#cbHealthBPJS').is(':checked') ? '1' : '0';
                let isJHT = $('#cbJHT').is(':checked') ? '1' : '0';
                let isJP = $('#cbJP').is(':checked') ? '1' : '0';
                let isJKKM = $('#cbJKKM').is(':checked') ? '1' : '0';

                // Validasi
                if ($('#clientName option:selected').text() === 'Pilih') {
                    $('#clientName').focus();
                    $('#clientNameErr').show();
                    isValid = false;
                } else if ($('#yearPeriod option:selected').text() === 'Pilih') {
                    $('#yearPeriod').focus();
                    $('#yearPeriodErr').show();
                    isValid = false;
                } else if ($('#monthPeriod option:selected').text() === 'Pilih') {
                    $('#monthPeriod').focus();
                    $('#monthPeriodErr').show();
                    isValid = false;
                }

                if (!isValid) {
                    $("#loader").hide();
                    $('#btnPrintSlip').prop('disabled', false);
                    Swal.fire({
                        text: "Check Data",
                        icon: "warning"
                    });
                    return false;
                }

                // ✅ base_url CI4 jangan double slash (hapus "/"+"/")
                const myUrl =
                    "<?= base_url('Transaction/Payroll_controller/excelDept') ?>/" +
                    ptName + "/" + dataGroup + "/" + month + "/" + year + "/" + isEnd + "/" +
                    isHealthBPJS + "/" + isJHT + "/" + isJP + "/" + isJKKM;

                // Buka file Excel di tab baru
                window.open(myUrl, '_blank');

                // Reset loader dan tombol
                $("#loader").hide();
                $('#btnPrintSlip').prop('disabled', false);
            }
        });

        /* END PRINT SLIP */


        /* START PRINT PAYSLIP */
        $('#slipTable tbody').on('click', '.btn_print', function() {
            var data = slipTable.row($(this).parents('tr')).data();
            var dataId = data[0];
            var ptName = data[1];
            var calDate = $("#startDate").val();
            var payrollGroup = $("#payrollGroup").val();
            var isHealthBPJS = '0';
            var isJHT = '0';
            var isJP = '0';
            var isJKKM = '0';
            var isEnd = '0';
            // debugger;
            if ($("#cbHealthBPJS").is(':checked')) {
                isHealthBPJS = '1';
            }
            if ($("#cbJHT").is(':checked')) {
                isJHT = '1';
            }
            if ($("#cbJP").is(':checked')) {
                isJP = '1';
            }
            if ($("#cbJKKM").is(':checked')) {
                isJKKM = '1';
            }
            if ($("#cbEnd").is(':checked')) {
                isEnd = '1';
            }
            var myUrl = ''


            myUrl =
                "<?= base_url('Transaction/Payroll_controller/toexcel') ?>/" +
                dataId +
                "/" + ptName + "/" + calDate + "/" + isEnd + "/" + isHealthBPJS +
                "/" + isJHT + "/" + isJP + "/" + isJKKM;

            window.open(myUrl, '_blank');

        });
        /* END PRINT PAYSLIP */


        /* START Re-Process PAYSLIP */
        $('#slipTable tbody').on('click', '.btn_process', function() {
            let data = slipTable.row($(this).parents('tr')).data();
            let slipId = data[0];
            let ptName = data[1];
            let fullName = data[3];
            Swal.fire({
                title: "Are you sure to re-process " + fullName + "?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, process!"
            }).then((result) => {
                if (result.isConfirmed) {
                    reProcessRoster();
                }
            });

            const reProcessRoster = () => {

                $("#loader").show();
                $('.errMsg').hide();
                $('#btnProsesRoster').prop('disabled', true);
                var client = $('#clientName').val();
                var month = $('#monthPeriod').val();
                var year = $('#yearPeriod').val();
                var roster = $('#rosterMaster').val();
                var otOffCount = $('#otInOffCount').val();
                var bioId = $('#idFilter').val();
                var dataGroup = $('#dataGroup').val();
                var myUrl = "<?php echo base_url('Transaction/Timesheet_process/process') ?>";


                var isEnd = '0';
                var isHealthBPJS = '0';
                var isJHT = '0';
                var isJP = '0';
                var isJKKM = '0';
                // debugger;
                if ($("#cbHealthBPJS").is(':checked')) {
                    isHealthBPJS = '1';
                }
                if ($("#cbJHT").is(':checked')) {
                    isJHT = '1';
                }
                if ($("#cbJP").is(':checked')) {
                    isJP = '1';
                }
                if ($("#cbJKKM").is(':checked')) {
                    isJKKM = '1';
                }
                if ($("#cbEnd").is(':checked')) {
                    isEnd = '1';
                }


                $.ajax({
                    method: "POST",
                    url: myUrl,
                    data: {
                        slipId: slipId,
                        biodataId: 'BySlipId',
                        clientName: client,
                        dataGroup: dataGroup,
                        monthPeriod: month,
                        yearPeriod: year,
                        otInOffCount: otOffCount,
                        //custom process
                        isHealthBPJS: isHealthBPJS,
                        isJHT: isJHT,
                        isJP: isJP,
                        isJKKM: isJKKM,
                        isEnd: isEnd,
                    },
                    success: function(response) {
                        $('#btnProsesRoster').prop('disabled', false);
                        let dataSrc = response.data.data
                        if (dataSrc.length === 0) {
                            Swal.fire({
                                title: "Warning!",
                                text: "There's no data processed",
                                icon: "warning"
                            });
                            $('#btnPrintSlip').hide();
                        } else {
                            Swal.fire({
                                title: "Success!",
                                text: "Success re process data " +
                                    fullName,
                                icon: "success"
                            });
                            slipTable.clear().draw();
                            slipTable.rows.add(dataSrc).draw(false);
                            $('#btnPrintSlip').show();
                        }
                        $("#loader").hide();
                        $('#biodataId').val("");
                        $('#biodataId').hide();


                    },
                    error: function(xhr) {
                        console.log(xhr);
                        var messageError = xhr.responseJSON.message;
                        var responseError = xhr.responseJSON.errors;

                        $('#btnProsesRoster').prop('disabled', false);
                        $('#btnPrintSlip').hide();
                        $("#loader").hide();
                        if (xhr.status === 422) {
                            var response = xhr.responseJSON;
                            $('.invalid-feedback').remove();
                            $('.form-control').removeClass('is-invalid');

                            if (response.errors) {
                                $.each(response.errors, function(key,
                                    value) {
                                    var inputName = key.split('.')
                                        .pop(); // Extract field name from key
                                    $('#' + inputName).after(
                                        '<div class="invalid-feedback">' +
                                        value[0] + '</div>');
                                    $('#' + inputName).addClass(
                                        'is-invalid');
                                });
                            }
                        } else {

                            Swal.fire({
                                title: messageError,
                                text: responseError,
                                icon: "error"
                            });
                        }
                    }
                });
            }
        });
        /* END Re-Process PAYSLIP */

        /* START UPDATE BUTTON CLICK */
        $('#slipTable tbody').on('click', '.btn_update', function() {
            var data = slipTable.row($(this).parents('tr')).data();
            var slipId = data[0];
            var clientName = data[1];
            var month = $('#monthPeriod').val();
            var year = $('#yearPeriod').val();



            myUrl =
                "<?= base_url('Transaction/Payroll_controller/getAllowanceBySlipId') ?>/" + slipId + "/" +
                clientName +
                "/" + year + "/" + month;



            $("#payrollId").val(slipId);
            $.ajax({
                url: myUrl,
                method: "GET",
                data: {
                    slipId: slipId,
                    clientName: clientName
                },
                success: function(response) {
                    var dataSrc = response.data || {};

                    let fullName = dataSrc.fullName || "";
                    let payrollId = dataSrc.payrollId || "";
                    let clientNameContract = dataSrc.clientName || "";
                    let biodataId = dataSrc.biodataId || "";
                    let monthPeriod = dataSrc.monthPeriod || "";
                    let yearPeriod = dataSrc.yearPeriod || "";

                    let attendance_bonus = Number(dataSrc.attendance_bonus ?? 0);
                    let transport_bonus = Number(dataSrc.transport_bonus ?? 0);
                    let night_shift_bonus = Number(dataSrc.night_shift_bonus ?? 0);
                    let tunjangan = Number(dataSrc.tunjangan ?? 0);
                    let thr = Number(dataSrc.thr ?? 0);
                    let adjustment_in = Number(dataSrc.adjustment_in ?? 0);
                    let adjustment_out = Number(dataSrc.adjustment_out ?? 0);
                    let workday_adjustment = Number(dataSrc.workday_adjustment ?? 0);
                    let thr_by_user = Number(dataSrc.thr_by_user ?? 0);
                    let debt_burden = Number(dataSrc.debt_burden ?? 0);

                    // Isi ke form
                    $("#biodataIdUpdate").val(biodataId);
                    $("#payrollId").val(payrollId);
                    $("#payrollName").val(fullName);
                    $("#clientNameContract").val(clientName);
                    $("#attendanceBonus").val(Math.abs(attendance_bonus));
                    $("#transportBonus").val(Math.abs(transport_bonus));
                    $("#tunjangan").val(Math.abs(tunjangan));
                    $("#thr").val(Math.abs(thr));
                    $("#adjustmentIn").val(Math.abs(adjustment_in));
                    $("#adjustmentOut").val(Math.abs(adjustment_out));
                    $("#thrByUser").val(Math.abs(thr_by_user));
                    $("#workDayAdjustment").val(Math.abs(workday_adjustment));
                    $("#debtBurden").val(Math.abs(debt_burden));

                },
                error: function(data) {
                    Swal.fire({
                        title: data.errors,
                        icon: "error"
                    });
                }
            });




        });
        /* END UPDATE BUTTON CLICK */




        /* START UPDATE BUTTON CLICK */
        payrollNameContract = '';
        $('#slipTable tbody').on('click', '.btn_update', function() {
            var data = slipTable.row($(this).parents('tr')).data();
            var slipId = data[0];
            var clientName = data[1];
            payrollNameContract = data[2];
            var month = $('#monthPeriod').val();
            var year = $('#yearPeriod').val();
            $("#payrollIdContract").val(slipId);
            $("#payrollNameContract").val(payrollNameContract);
            $("#clientNameContract").val(clientName);
        });
        /* END UPDATE BUTTON CLICK */


        /* START SAVE UPDATE */
        $("#savePayroll").on("click", function() {
            $("#loader").show();
            var clientName = $("#clientNameContract").val();
            var biodataId = $("#biodataIdUpdate").val();
            var year = $("#yearPeriod").val();
            var month = $("#monthPeriod").val();
            var payrollId = $("#payrollId").val();
            var thr = $("#thr").val();
            var tunjangan = $("#tunjangan").val();
            var adjustmentIn = $("#adjustmentIn").val();
            var adjustmentOut = $("#adjustmentOut").val();
            var workdayAdj = $("#workDayAdjustment").val();
            var debtBurden = $("#debtBurden").val();
            var thrByUser = $("#thrByUser").val();

            var isHealthBPJS = '0';
            var isJHT = '0';
            var isJP = '0';
            var isJKKM = '0';
            var isEnd = '0';
            // debugger;
            if ($("#cbHealthBPJS").is(':checked')) {
                isHealthBPJS = '1';
            }
            if ($("#cbJHT").is(':checked')) {
                isJHT = '1';
            }
            if ($("#cbJP").is(':checked')) {
                isJP = '1';
            }
            if ($("#cbJKKM").is(':checked')) {
                isJKKM = '1';
            }
            if ($("#cbEnd").is(':checked')) {
                isEnd = '1';
            }

            myUrl =
                "<?= base_url('Transaction/Payroll_controller/updatePayroll') ?>";



            /* Start Ajax Insert Is Here */
            $.ajax({
                method: "POST",
                url: myUrl,
                data: {
                    _token: "{{ csrf_token() }}",
                    payrollId: payrollId,
                    biodataId: biodataId,
                    payrollNameContract: payrollNameContract,
                    year: year,
                    month: month,
                    clientName: clientName,
                    thr: thr,
                    tunjangan: tunjangan,
                    adjustmentIn: adjustmentIn,
                    adjustmentOut: adjustmentOut,
                    workdayAdj: workdayAdj,
                    debtBurden: debtBurden,
                    thrByUser: thrByUser,
                    isHealthBPJS: isHealthBPJS,
                    isJHT: isJHT,
                    isJP: isJP,
                    isJKKM: isJKKM,
                    isEnd: isEnd

                },
                success: function(response) {
                    Swal.fire({
                        title: response.message,
                        icon: "success"
                    });
                },
                error: function(data) {
                    let responseJSON = data
                        .responseJSON; // Ambil semua properti dari responseJSON

                    if (responseJSON && responseJSON
                        .errors) { // Periksa apakah responseJSON dan properti `errors` ada
                        Swal.fire({
                            title: responseJSON.errors,
                            icon: "error"
                        });
                    } else if (data.errors) { // Periksa jika data.errors tersedia
                        Swal.fire({
                            title: data.errors,
                            icon: "error"
                        });
                    } else {
                        Swal.fire({
                            title: "An unexpected error occurred. Please try again.",
                            icon: "error"
                        });
                    }
                }
            });
            /* End Ajax Insert Is Here */
            $("#loader").hide();
        });
        /* END SAVE UPDATE */


        $("body").append(`
    <div id="progressModal" style="
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    ">
        <div style="
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
            width: 300px;
            text-align: center;
        ">
            <h3 style="margin-bottom: 10px; color: #333;">Processing...</h3>
            <div id="progressBarContainer" style="
                width: 100%;
                background-color: #ddd;
                border-radius: 5px;
                overflow: hidden;
            ">
                <div id="progressBar" style="
                    width: 0%;
                    height: 30px;
                    background-color: #4caf50;
                    line-height: 30px;
                    color: white;
                    text-align: center;
                    transition: width 0.3s ease;
                ">0%</div>
            </div>
        </div>
    </div>
`);


        // Fungsi untuk menampilkan progress modal
        function showProgressModal() {
            $("#progressModal").css({
                display: "flex", // Pakai CSS flex baru muncul
            }).hide().fadeIn(300);
        }


        // Fungsi untuk update progress
        function updateProgress(percentage) {
            $("#progressBar").css("width", percentage + "%").text(percentage + "%");
        }


        // Fungsi untuk menyembunyikan modal
        function hideProgressModal() {
            $("#progressModal").fadeOut(300);
        }



        var slipTableSelect = $('#slipTableSelect').DataTable({
            "paging": true,
            "ordering": false,
            "lengthMenu": [20, 50, 100, 150, 200],
            "info": true,
            "filter": true,
            "columnDefs": [{
                    "targets": 0, // Target column index 0 (first column for checkbox)
                    "orderable": false, // Disable ordering for the checkbox column
                    "searchable": false, // Disable search for the checkbox column
                    "className": 'dt-body-center', // Center align the checkbox
                    "render": function(data, type, full, meta) {
                        return '<input type="checkbox" name="selectId" class="selectedId" value="' +
                            full[0] +
                            '">';
                    }
                },
                {
                    "targets": 1,
                    // "orderable": false,
                    // "searchable": false, // Disable search for the checkbox column
                    "className": 'dt-body-center', // Center align the checkbox
                    "render": function(data, type, full, meta) {
                        return full[0];
                    }
                },
                {
                    "targets": 2,
                    // "orderable": false,
                    // "searchable": false, // Disable search for the checkbox column
                    "className": 'dt-body-center', // Center align the checkbox
                    "render": function(data, type, full, meta) {
                        return full[1];
                    }
                },
                {
                    "targets": 3,
                    // "orderable": false,
                    // "searchable": false, // Disable search for the checkbox column
                    "className": 'dt-body-center', // Center align the checkbox
                    "render": function(data, type, full, meta) {
                        return full[2];
                    }
                },
                {
                    "targets": 4,
                    // "orderable": false,
                    // "searchable": false, // Disable search for the checkbox column
                    "className": 'dt-body-center', // Center align the checkbox
                    "render": function(data, type, full, meta) {
                        return full[3];
                    }
                },
                {
                    "targets": 5,
                    // "orderable": false,
                    // "searchable": false, // Disable search for the checkbox column
                    "className": 'dt-body-center', // Center align the checkbox
                    "render": function(data, type, full, meta) {
                        return full[4];
                    }
                }

            ],
        });

        $('#clientName').on('change', function() {
            slipTable.clear().draw();
            slipTableSelect.clear().draw();
        })

        var selectedValues = [];
        var allCheckboxValues = [];

        // Function to handle checkbox change
        $(document).on('change', 'input[name="selectId"]', function() {
            var value = $(this).val();

            if ($(this).is(':checked')) {
                if (!selectedValues.includes(value)) {
                    selectedValues.push(value);
                }
            } else {
                selectedValues = selectedValues.filter(function(item) {
                    return item !== value;
                });
            }
            console.log(selectedValues); // Tampilkan nilai checkbox yang dipilih di console
            updateSelectedCount();
        });


        function updateSelectedCount() {
            var count = selectedValues.length; // Hitung jumlah yang terpilih
            $('#selectedCount').text(count); // Tampilkan jumlah terpilih di elemen dengan ID 'selectedCount'
        }

        $('#selectAllCheckbox').on('click', function() {
            var isChecked = $(this).prop('checked'); // Cek apakah "Select All" dicentang

            // Update semua checkbox dengan status "Select All"
            $('input[name="selectId"]').prop('checked', isChecked);

            // Update array selectedValues sesuai dengan status "Select All"
            if (isChecked) {
                // Jika "Select All" dicentang, tambahkan semua nilai ke selectedValues
                $('input[name="selectId"]').each(function() {
                    var value = $(this).val();
                    if (!selectedValues.includes(value)) {
                        selectedValues.push(value);
                    }
                });
            } else {
                // Jika "Select All" dibatalkan, kosongkan selectedValues
                selectedValues = [];
            }

            console.log(selectedValues); // Tampilkan nilai yang dipilih di console
            updateSelectedCount(); // Update jumlah yang dipilih
        });
        $('#btnPrintSelectedIds').click(function(e) {
            e.preventDefault();

            var isHealthBPJS = '0';
            var isJHT = '0';
            var isJP = '0';
            var isJKKM = '0';
            var isEnd = '0';
            // debugger;
            if ($("#cbHealthBPJS").is(':checked')) {
                isHealthBPJS = '1';
            }
            if ($("#cbJHT").is(':checked')) {
                isJHT = '1';
            }
            if ($("#cbJP").is(':checked')) {
                isJP = '1';
            }
            if ($("#cbJKKM").is(':checked')) {
                isJKKM = '1';
            }
            if ($("#cbEnd").is(':checked')) {
                isEnd = '1';
            }


            let myUrl = "<?= base_url('Transaction/Payroll_controller/printPayslipBySelectedId') ?>/" +
                isHealthBPJS + "/" +
                isJHT + "/" +
                isJP + "/" +
                isJKKM + "/" +
                isEnd + "/" +
                selectedValues;


            window.open(myUrl, '_blank');
        });

    });
</script>