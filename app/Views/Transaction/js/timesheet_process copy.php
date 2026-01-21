 <script>
     $(document).ready(function() {
         $('#closePayroll').hide();
         $('#insert_due').hide();
         $("#loader").hide();
         $('.SM').hide();
         $('#btnProcessSM').hide();
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

         $('#insert_due').on("click", function() {
             var smprocess = $('#smprocess').val();
             $('#sm_duedate').val(smprocess);
             $('#sm_tax').text(smprocess);
         });

         $('#btnProcess').on("click", function() {
             // debugger;
             // $('.SM').show();
             var isEnd = "0";
             if ($("#cbEnd").is(':checked')) {
                 isEnd = '1';
             }

             var monthPeriod = $('#monthPeriod').val();
             var yearPeriod = $('#yearPeriod').val();
             var startDate = $('#startDate').val();
             var smprocess = $('#smprocess').val();

             $('#closePayroll').hide();
             $.ajax({
                 url: "<?php echo base_url('Transaction/Tr_timesheet/checkClosingPayrollByPeriod') ?>",
                 method: "POST",
                 data: {
                     monthPeriod: monthPeriod,
                     yearPeriod: yearPeriod,
                     client: "agincourt"
                 },
                 success: function(resp) {
                     var dataClosing = JSON.parse(resp);

                     if (dataClosing.data_count >= 1) {
                         $.notify({
                             title: "<h5>Danger : </h5>",
                             message: "<strong>Data Payroll Sudah Di Closing</strong>",
                             icon: ''
                         }, {
                             type: "Danger",
                             delay: 2000
                         });
                         $('#closePayroll').show();
                     } else {
                         var myUrl = '<?php echo base_url() ?>/Transaction/Tr_timesheet/proses/' + yearPeriod + '/' + monthPeriod + '/' + startDate + '/' + smprocess + '/' + isEnd;
                         $.post("<?php echo base_url() ?>/Transaction/Tr_timesheet/validasiCheck/" + yearPeriod + "/" + monthPeriod + "/" + startDate + '/' + smprocess, {
                             data: yearPeriod
                         }, function(response) {
                             if (response === "true") {
                                 // Data valid
                                 if (window.confirm("Data Sudah Di Proses !! Apakah Anda Ingin Memproses Ulang?")) {
                                     spinner.style.display = 'flex';

                                     $.ajax({
                                         url: myUrl,
                                         method: "POST",
                                         data: {
                                             monthPeriod: monthPeriod,
                                             yearPeriod: yearPeriod,
                                             smprocess: smprocess,
                                             startDate: startDate
                                         },
                                         success: function(data) {
                                             $("#loader").hide();
                                             $('#btnProsesRoster').prop('disabled', false);
                                             spinner.style.display = 'none';
                                             $.notify({
                                                 title: "<h5>Informasi : </h5>",
                                                 message: "<strong>Data Processed</strong> </br></br> ",
                                                 icon: ''
                                             }, {
                                                 type: "success",
                                                 delay: 2000
                                             });

                                             slipTable.clear().draw();
                                             var dataSrc = JSON.parse(data);
                                             $('#insert_due').hide();
                                             if (dataSrc.myData.length) {
                                                 $('#insert_due').show();
                                             }
                                             slipTable.rows.add(dataSrc.myData).draw(false);
                                         },
                                     });
                                 } else {
                                     return false;
                                 }
                             } else if (response === "false") {
                                 // Data tidak valid
                                 spinner.style.display = 'flex';
                                 $.ajax({
                                     url: myUrl,
                                     method: "POST",
                                     data: {
                                         monthPeriod: monthPeriod,
                                         yearPeriod: yearPeriod,
                                         smprocess: smprocess,
                                         startDate: startDate
                                     },
                                     success: function(data) {
                                         spinner.style.display = 'none';
                                         $("#loader").hide();
                                         $('#btnProsesRoster').prop('disabled', false);
                                         $.notify({
                                             title: "<h5>Informasi : </h5>",
                                             message: "<strong>Data Processed</strong> </br></br> ",
                                             icon: ''
                                         }, {
                                             type: "success",
                                             delay: 2000
                                         });

                                         slipTable.clear().draw();
                                         var dataSrc = JSON.parse(data);
                                         $('#insert_due').hide();
                                         if (dataSrc.length.myData) {
                                             $('#insert_due').show();
                                             $('#btnProcessSM').hide();
                                         }
                                         slipTable.rows.add(dataSrc.myData).draw(false);
                                     },
                                 });
                             }
                         });
                     }
                 }
             });
         });

         $('#btnProcessDB').on("click", function() {

             $("#loader").show();
             var monthPeriod = $('#monthPeriod').val();
             var yearPeriod = $('#yearPeriod').val();
             var startDate = $('#startDate').val();
             var smprocess = $('#smprocess').val();

             $.ajax({
                 url: "<?php echo base_url('Transaction/Tr_timesheet/checkClosingPayrollByPeriod') ?>",
                 method: "POST",
                 data: {
                     monthPeriod: monthPeriod,
                     yearPeriod: yearPeriod,
                     client: "agincourt"
                 },
                 success: function(resp) {
                     var dataClosing = JSON.parse(resp);

                     if (dataClosing.data_count >= 1) {
                         $.notify({
                             title: "<h5>Danger : </h5>",
                             message: "<strong>Data Payroll Sudah Di Closing</strong>",
                             icon: ''
                         }, {
                             type: "Danger",
                             delay: 2000
                         });
                     } else {

                         var myUrl = '<?php echo base_url() ?>/Transaction/Tr_timesheet/processDB/' + yearPeriod + '/' + monthPeriod + '/' + startDate + '/' + smprocess;
                         if (window.confirm("Apakah Anda Ingin Meporoses DB ? Proses Ini Mungkin Membutuhkan Waktu !!")) {
                             spinner.style.display = 'flex';
                             $.ajax({
                                 url: myUrl,
                                 method: "POST",
                                 data: {
                                     monthPeriod: monthPeriod,
                                     yearPeriod: yearPeriod,
                                     smprocess: smprocess,
                                     startDate: startDate
                                 },
                                 success: function(data) {
                                     spinner.style.display = 'none';
                                     $("#loader").hide();
                                     $('#btnProsesRoster').prop('disabled', false);
                                     $.notify({
                                         title: "<h5>Informasi : </h5>",
                                         message: "<strong>Data Processed</strong> </br></br> ",
                                         icon: ''
                                     }, {
                                         type: "success",
                                         delay: 2000
                                     });
                                 },
                             });
                         } else {
                             return false;
                         }
                     }
                 }
             });
         });

         $('#btnView').on("click", function() {
             // debugger;
             var monthPeriod = $('#monthPeriod').val();
             var yearPeriod = $('#yearPeriod').val();
             var startDate = $('#startDate').val();
             var smprocess = $('#smprocess').val();

             var myUrl = '<?php echo base_url() ?>/Transaction/Tr_timesheet/getPayrollList/' + yearPeriod + '/' + monthPeriod + '/' + startDate + '/' + smprocess;
             $('.SM').show();
             // alert(myUrl);  

             $.ajax({
                 url: myUrl,
                 method: "POST",
                 data: {
                     monthPeriod: monthPeriod,
                     yearPeriod: yearPeriod,
                     startDate: startDate,
                     smprocess: smprocess
                 },
                 success: function(data) {
                     slipTable.clear().draw();
                     var dataSrc = JSON.parse(data);

                     if (dataSrc.myData.length) {
                         if (dataSrc.due_date >= 1) {
                             $('#btnProcessSM').show();
                             $('#insert_due').hide();
                         } else {
                             $('#btnProcessSM').hide();
                             $('#insert_due').show();
                         }
                     }


                     slipTable.rows.add(dataSrc.myData).draw(false);
                 },
             });
         });

         $('#btnProcessSM').on("click", function() {
             // debugger;
             var monthPeriod = $('#monthPeriod').val();
             var yearPeriod = $('#yearPeriod').val();
             var startDate = $('#startDate').val();
             var sm = $('#smprocess').val();

             if ($('#cbHealthBPJS').is(":checked")) {
                 var cbHealthBPJS = "1";
             } else {
                 var cbHealthBPJS = "0";
             }
             if ($('#cbJHT').is(":checked")) {
                 cbJHT = "1";
             } else {
                 var cbJHT = "0";
             }
             if ($('#cbJP').is(":checked")) {
                 var cbJP = "1";
             } else {
                 var cbJP = "0";
             }
             if ($('#cbJKKM').is(":checked")) {
                 var cbJKKM = "1";
             } else {
                 var cbJKKM = "0";
             }

             var isEnd = "0";
             if ($("#cbEnd").is(':checked')) {
                 isEnd = '1';
             }

             $('.SM').show();
             // let cbHealthBPJS = $("#cbHealthBPJS").val();
             // let cbJHT = $("#cbJHT").val();
             // let cbJP = $("#cbJP").val();
             // let cbJKKM = $("#cbJKKM").val();

             var myUrl = "<?php echo base_url() ?>" + "/Transaction/Tr_timesheet/printAllSM/" + yearPeriod + "/" + monthPeriod + "/" + startDate + "/" + sm + "/" + cbHealthBPJS + "/" + cbJHT + "/" + cbJP + "/" + cbJKKM + "/" + isEnd;


             // alert(myUrl);

             $.ajax({
                 url: myUrl,
                 method: "POST",
                 data: {
                     monthPeriod: monthPeriod,
                     yearPeriod: yearPeriod,

                     // month,
                     startDate: startDate
                 },
                 success: function(response) {
                     console.log(response);
                     window.open(myUrl, '_blank');
                     // location.reload();
                 },
                 error: function(data) {
                     $.notify({
                         title: "<h5>Informasi : </h5>",
                         message: "<strong>" + myUrl + "</strong> </br></br> ",
                         icon: ''
                     }, {
                         type: "warning",
                         delay: 3000
                     });
                 }
             });

         });

         $('#slipTable tbody').on('click', '.btn_print', function() {
             var data = slipTable.row($(this).parents('tr')).data();
             var dataId = data[4];

             if ($('#cbHealthBPJS').is(":checked")) {
                 var cbHealthBPJS = "1";
             } else {
                 var cbHealthBPJS = "0";
             }
             if ($('#cbJHT').is(":checked")) {
                 cbJHT = "1";
             } else {
                 var cbJHT = "0";
             }
             if ($('#cbJP').is(":checked")) {
                 var cbJP = "1";
             } else {
                 var cbJP = "0";
             }
             if ($('#cbJKKM').is(":checked")) {
                 var cbJKKM = "1";
             } else {
                 var cbJKKM = "0";
             }
             if ($('#cbEnd').is(":checked")) {
                 var cbEnd = "1";
             } else {
                 var cbEnd = "0";
             }

             // var myUrl = "<?php #echo base_url() 
                                ?>"+"transactions/timika/Payroll/my_report/";
             var myUrl = "<?php echo base_url() ?>" + "/Transaction/Tr_timesheet/toExcel/" + dataId + "/" + cbHealthBPJS + "/" + cbJHT + "/" + cbJP + "/" + cbJKKM + "/" + cbEnd;
             // alert(myUrl);

             $.ajax({
                 method: "POST",
                 url: myUrl,
                 data: {
                     slipId: dataId,
                     cbHealthBPJS: cbHealthBPJS,
                     cbJHT: cbJHT,
                     cbJP: cbJP,
                     cbJKKM: cbJKKM,
                     cbEnd: cbEnd
                 },
                 // success : function(data){
                 //  alert(data);
                 // },
                 success: function(response) {
                     console.log(response);
                     window.open(myUrl, '_blank');
                 },
                 error: function(data) {
                     $.notify({
                         title: "<h5>Informasi : </h5>",
                         message: "<strong>" + myUrl + "</strong> </br></br> ",
                         icon: ''
                     }, {
                         type: "warning",
                         delay: 3000
                     });
                 }
             });
         });
         $('#myModal').on('shown.bs.modal', function() {
             $('#myInput').trigger('focus')
         })
         $('#slipTable tbody').on('click', '.btn_update', function() {
             var data = slipTable.row($(this).parents('tr')).data();
             var slipId = data[0];

             $("#payrollId").val(slipId);
             var myUrl = "<?php echo base_url() ?>" + "/Transaction/Tr_timesheet/getSlipByIid/" + slipId;

             $.ajax({
                 url: myUrl,
                 method: "POST",
                 data: {
                     slipId: slipId
                 },
                 success: function(data) {
                     var dataSrc = JSON.parse(data);
                     $("#payrollName").val(dataSrc[1]);
                     $("#adjustment").val(dataSrc[4]);
                     $("#thr").val(dataSrc[2]);
                     $("#lainlain").val(dataSrc[3]);
                     $("#keterangan").val(dataSrc[5]);

                 },
                 error: function(data) {
                     // alert('Failed');
                     $.notify({
                         title: "<h5>Informasi : </h5>",
                         message: "<strong>Failed</strong> </br></br> ",
                         icon: ''
                     }, {
                         type: "warning",
                         delay: 3000
                     });
                 }

             });
         });

         $('#slipTable tbody').on('click', '.btn_duedate', function() {
             var data = slipTable.row($(this).parents('tr')).data();
             var slipId = data[0];

             $("#idKaryawan").val(slipId);
             var myUrl = "<?php echo base_url() ?>" + "/Transaction/Tr_timesheet/getDueDate/" + slipId;

             $.ajax({
                 url: myUrl,
                 method: "POST",
                 data: {
                     slipId: slipId
                 },
                 success: function(data) {
                     var dataSrc = JSON.parse(data);
                     $("#nameKaryawan").val(dataSrc[1]);
                     $("#dueDate").val(dataSrc[2]);

                 },
                 error: function(data) {
                     // alert('Failed');
                     $.notify({
                         title: "<h5>Informasi : </h5>",
                         message: "<strong>Failed</strong> </br></br> ",
                         icon: ''
                     }, {
                         type: "warning",
                         delay: 3000
                     });
                 }

             });

         });

         $('#slipTable tbody').on('click', '.btn_process', function() {
             var data = slipTable.row($(this).parents('tr')).data();
             var tsId = data[4];
             var name = data[2];

             // $("#payrollId").val(slipId);
             var myUrl = "<?php echo base_url() ?>" + "/Transaction/Tr_timesheet/ProsesSolo/" + tsId;
             if (window.confirm("Apakah Anda Ingin Memproses Ulang Data Dengan Name : " + name + " ?")) {

                 // alert(tsId)

                 $.ajax({
                     url: myUrl,
                     method: "POST",
                     data: {
                         tsId: tsId
                     },
                     success: function(data) {
                         // alert('Failed');
                         $.notify({
                             title: "<h5>Informasi : </h5>",
                             message: "<strong>Data Processed</strong> </br></br> ",
                             icon: ''
                         }, {
                             type: "success",
                             delay: 2000
                         });
                     },
                     error: function(data) {
                         // alert('Failed');
                         $.notify({
                             title: "<h5>Informasi : </h5>",
                             message: "<strong>Failed</strong> </br></br> ",
                             icon: ''
                         }, {
                             type: "warning",
                             delay: 3000
                         });
                     }

                 });
             } else {
                 return false;
             }
         });

         $("#savePayroll").on("click", function() {
             /*alert(myUrl);*/
             var myUrl = "<?php echo base_url() ?>/Transaction/Tr_slip/upd";
             $.post({
                 url: myUrl,
                 type: "POST",
                 data: {
                     slipId: $('#payrollId').val(),
                     fullName: $('#payrollName').val(),
                     adjustment: $('#adjustment').val(),
                     thr: $('#thr').val(),
                     statusRemarks: $('#keterangan').val(),
                     lainlain: $('#lainlain').val()
                 },

                 success: function(resp) {
                     toastr.success("Data has been Save.", 'Alert', {
                         "positionClass": "toast-top-center"
                     });
                     /*Your redirect is here */
                 }
             })
         });

         $("#saveDueDate").on("click", function() {
             var myUrl = "<?php echo base_url() ?>/Transaction/Tr_slip/updDueDate";
             $.post({
                 url: myUrl,
                 type: "POST",
                 data: {
                     monthPeriod: $('#monthPeriod').val(),
                     yearPeriod: $('#yearPeriod').val(),
                     startDate: $('#startDate').val(),
                     // SM            : $('#smDueDate').val(),
                     SM: $('#sm_duedate').val(),
                     dueDate: $('#dueDate').val()
                 },

                 success: function(resp) {
                     $('#insert_due').hide();
                     $('#btnProcessSM').show();
                     toastr.success("Data has been Save.", 'Alert', {
                         "positionClass": "toast-top-center"
                     });
                     /*Your redirect is here */
                 }
             })

         });
     });
 </script>