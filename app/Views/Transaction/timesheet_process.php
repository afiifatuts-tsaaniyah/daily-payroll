<div id="spinner" style="display:none;">
    <div class="loading"></div>
</div>


<style type="text/css">
    tr.selected {
        background-color: #B0BED9 !important;
    }
</style>

<div id="loader"></div>

<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <h3 class="tile-title">Timesheet Process PMC</h3>
            <div class="tile-footer">
                <form class="row is_header">

                    <div class="form-group col-sm-12 col-md-2 d-none">
                        <label class="control-label">CLIENT NAME</label>
                        <!-- <code id="docKindErr" class="errMsg"><span> : Required</span></code> -->
                        <select class="form-control" name="clientName" id="clientName" required>
                            <option value="Promincon_Indonesia" selected>Promincon Indonesia</option>
                            <!-- <option value="" disabled selected>Pilih</option> -->

                            <!-- <?php if (!empty($clients)): ?>
                                <?php foreach ($clients as $client): ?>
                                    <option value="<?= esc($client['client_value']) ?>">
                                        <?= esc($client['client_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>Tidak ada client tersedia</option>
                            <?php endif; ?> -->
                        </select>
                    </div>

                    <div class="form-group col-sm-12 col-md-2">
                        <label class="control-label">YEAR</label>
                        <!-- <code id="docKindErr" class="errMsg"><span> : Required</span></code> -->
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

                    <div class="form-group col-sm-12 col-md-2">
                        <label class="control-label">MONTH</label>
                        <!-- <code id="docKindErr" class="errMsg"><span> : Required</span></code> -->
                        <select class="form-control" id="monthPeriod" name="monthPeriod" required="">
                            <option value="" disabled="" selected="">Pilih</option>
                            <script type="text/javascript">
                                var tMonth = 1;
                                for (var i = tMonth; i <= 12; i++) {
                                    if (i < 10) {
                                        document.write("<option value='0" + i + "'>0" + i + "</option>");
                                    } else {
                                        document.write("<option value='" + i + "'>" + i + "</option>");
                                    }

                                }
                            </script>
                        </select>
                    </div>

                    <div class="form-group col-sm-12 col-md-2">
                        <label class="control-label">GROUP</label>
                        <!-- <code id="docKindErr" class="errMsg"><span> : Required</span></code> -->
                        <select class="form-control" id="dataGroup" name="dataGroup" required="">
                            <option value="" disabled="" selected="">Pilih</option>
                            <option value="All">All</option>
                        </select>
                    </div>

                    <!-- <input type="file" name="tsFile" id="tsFile"> -->
                    <!-- <a href="#"> -->
                    <div class="form-group col-sm-12">
                        <div class="form-group col-sm-12 col-md-6 govReg">
                            <input type="checkbox" id="cbHealthBPJS" name="cbHealthBPJS" value="1" checked>
                            <label class="control-label" for="healthBPJS">Health BPJS</label>&nbsp;&nbsp;&nbsp;&nbsp;

                            <input type="checkbox" id="cbJHT" name="cbJHT" value="1" checked>
                            <label class="control-label" for="JHT">JHT</label>&nbsp;&nbsp;&nbsp;&nbsp;

                            <input type="checkbox" id="cbJP" name="cbJP" value="1" checked>
                            <label class="control-label" for="JP">JP</label>&nbsp;&nbsp;&nbsp;&nbsp;

                            <input type="checkbox" id="cbJKKM" name="cbJKKM" value="1" checked>
                            <label class="control-label" for="JKK-JKM">JKK-JKM</label>&nbsp;&nbsp;&nbsp;&nbsp;

                            <div class="d-none">
                                <input type="checkbox" id="cbEnd" name="cbEnd" value="1">
                                <label class="control-label" for="cbEnd">END CONTRACT</label>
                            </div>

                        </div>
                        <button class="btn btn-primary" type="button" id="btnProsesRoster"><i class="fa fa-fw fa-lg fas fa-spinner "></i>Roster Process</button>
                        <a class="btn btn-primary" type="button" id="btnDisplay"><i class="fa fa-fw fa-lg fas fa-plus-circle "></i>View</a>
                        <a class="btn btn-warning" type="button" id="btnPrintSlip"><i class='fa fa-print'></i>Print Payslip</a>
                        <button id="btnSelectPrint" class='btn btn-warning btn-xs' type="button"
                            data-toggle='modal' data-target='#selectPrint'>
                            <i class='fa fa-print'></i>Select Print
                        </button>

                        <br />
                        <div class="mt-2">
                            <button class="btn btn-warning" id="btnReset" type="button">
                                <i class="fas fa-spinner fa-spin d-none me-2"></i>
                                Reset
                            </button>

                            <button class="btn btn-warning" id="btnGetNie" type="button">
                                <i class="fas fa-spinner fa-spin d-none me-2"></i>
                                Process by ID
                            </button>

                            <button class="btn btn-warning" id="btnCancelNie" type="button">
                                <i class="fas fa-spinner fa-spin d-none me-2"></i>
                                Cancel Process by ID
                            </button>
                        </div>

                        <br>

                        <div class="form-group col-md-2 align-self-end d-none" id="nieContainer">
                            <input class="form-control" type="text" id="biodataId" name="biodataId" placeholder="Badge No">
                        </div>
                    </div>
                </form>
            </div>
            <br>
            <br>
            <div class="tile-body">
                <!-- TABLE -->
                <div class="table-responsive">
                    <div id="closePayroll">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <!--<span aria-hidden="true">&times;</span>-->
                            </button>
                            <strong>Data Payroll Sudah Di Closing.</strong>
                        </div>
                    </div>
                    <table class="table table-hover table-bordered" id="slipTable">
                        <thead>
                            <tr>
                                <th>Slip Id</th>
                                <th>Client Name</th>
                                <th>Biodata Id</th>
                                <th>Full Name</th>
                                <th>Dept</th>
                                <th>Print</th>
                                <th>Edit</th>
                                <th>Reprocess</th>
                                <!-- <th>Due Date</th> -->
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>

                <div class="modal fade" id="editPayrollModal" tabindex="-1" role="dialog" aria-labelledby="isModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-notify modal-warning" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-primary">
                                <h5 class="modal-title text-center" id="isModalLabel">Data Payroll</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- START DATA TABLE -->
                                <div class="tile-body">
                                    <!-- Start Form EDIT PAYROLL -->
                                    <form method="post" class="my_detail">
                                        <div class="form-group">
                                            <label class="control-label" for="biodataIdUpdate">Biodata ID</label>
                                            <input class="form-control" type="text" id="biodataIdUpdate" name="biodataIdUpdate" placeholder="Id" readonly="">
                                            <input class="form-control" type="hidden" id="clientNameContract" name="clientNameContract" placeholder="Id" readonly="">
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label" for="payrollName">Name</label>
                                            <input class="form-control" type="text" id="payrollName" name="payrollName" placeholder="Nama Karyawan" readonly="">
                                        </div>

                                        <div class="form-group attendanceBonus">
                                            <label class="control-label" for="attendanceBonus">Tunjangan Kehadiran</label>
                                            <input class="form-control" type="text" id="attendanceBonus" name="attendanceBonus" placeholder="Tunjangan Kehadiran" readonly>
                                        </div>

                                        <div class="form-group transportBonus">
                                            <label class="control-label" for="transportBonus">Tunjangan Transportasi</label>
                                            <input class="form-control" type="text" id="transportBonus" name="transportBonus" placeholder="Tunjangan Transportasi" readonly>
                                        </div>

                                        <div class="form-group nightShiftBonus">
                                            <label class="control-label" for="nightShiftBonus">Tunjangan Shift Malam</label>
                                            <input class="form-control" type="text" id="nightShiftBonus" name="nightShiftBonus" placeholder="Tunjangan Shift Malam" readonly>
                                        </div>

                                        <div class="form-group tunjangan">
                                            <label class="control-label" for="tunjangan">Tunjangan </label>
                                            <input class="form-control" type="text" id="tunjangan" name="tunjangan" placeholder="Tunjangan">
                                        </div>
                                        <div class="form-group thr">
                                            <label class="control-label" for="thr">THR</label>
                                            <input class="form-control" type="text" id="thr" name="thr" placeholder="THR">
                                        </div>

                                        <div class="form-group adjustmentIn">
                                            <label class="control-label" for="adjustmentIn">Adjustmen In</label>
                                            <input class="form-control" type="text" id="adjustmentIn" name="adjustmentIn" placeholder="Adjustment In">
                                        </div>

                                        <div class="form-group adjustmentOut">
                                            <label class="control-label" for="adjustmentOut">Adjustment Out</label>
                                            <input class="form-control" type="text" id="adjustmentOut" name="adjustmentOut" placeholder="Adustment Out">
                                        </div>

                                        <div class="form-group thrByUser">
                                            <label class="control-label" for="thrByUser">THR By User</label>
                                            <input class="form-control" type="text" id="thrByUser" name="thrByUser" placeholder="THR By User">
                                        </div>
                                        <div class="form-group workDayAdjustment">
                                            <label class="control-label" for="workDayAdjustment">Workday Adjustment</label>
                                            <input class="form-control" type="text" id="workDayAdjustment" name="workDayAdjustment" placeholder="Workday Adjustment">
                                        </div>
                                        <div class="form-group debtBurden">
                                            <label class="control-label" for="debtBurden">Debt Burden</label>
                                            <input class="form-control" type="text" id="debtBurden" name="debtBurden" placeholder="Debt Burden">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" type="hidden" id="payrollId" name="payrollId" value="" readonly="">
                                        </div>
                                    </form>
                                    <!-- End Form EDIT PAYROLL -->
                                </div>
                                <!-- END DATA TABLE -->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" id="savePayroll" data-dismiss="modal" class="btn btn-primary">Save</button>
                            </div>
                        </div> <!-- class="tile" -->
                    </div> <!-- class="col-md-12" -->
                </div> <!-- class="row" -->



                <div class="modal fade" id="selectPrint" tabindex="-1" role="dialog" aria-labelledby="isModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-notify modal-warning" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-primary">
                                <h5 class="modal-title text-center" id="isModalLabel">Select Print</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="tile-body">
                                    <table class="table table-hover table-bordered" id="slipTableSelect">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="selectAllCheckbox"></th>
                                                <th>Slip Id</th>
                                                <th>Client Name</th>
                                                <th>Emp Name</th>
                                                <th>Dept</th>
                                                <th>Position</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>

                                    <div>
                                        <p>Jumlah yang dipilih: <span id="selectedCount">0</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" id="btnPrintSelectedIds" class="btn btn-primary">Print</button>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- ***Using Valid js Path -->
                <script src="<?php echo base_url() ?>/assets/js/main.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <?= $this->include('Transaction/js/js_timesheet_process'); ?>