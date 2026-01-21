<?php
//header("Content-type: application/vnd-ms-excel");
//header("Content-Disposition: attachment; filename=AR.xls");
use App\Models\Transaction\M_tr_timesheet;
?>
 
<!DOCTYPE html>
<html>
  <head>
    <title>Export Data Ke Excel </title>
  </head>
  <body>
    <h3>REKAP PAYROLL MARTABE<br>SUMMARY PEMBAYARAN GAJI 2022</h3>
    <!-- <h3>SUMMARY PEMBAYARAN GAJI 2022</h3> -->
      <table border='1'>


          <tr>
            <th colspan="20" style="background-color:rgb(17, 255, 236)"><center></center></th>
            <?php
            $mtbAr = new M_tr_timesheet;
            $mtbAr->setYearProcess($year);
            $dataSM = $mtbAr->getSM();

             //var_dump($dataSM);
            // exit();
            $rowNo = 0;
            
              
                // var_dump($data);

              

              for ($x = '1'; $x <= '13'; $x++) {
                if ($x == 13) {
                  $x = 'Total';
                }
                echo '<th colspan="6" style="background-color:rgb(17, 255, 236)"><center>'.$x.'</center></th>';
              }
              ?>
          </tr>
          <tr>
              <th style="background-color:rgb(255, 143, 0)"><center>NO</center></th>
              <th style="background-color:rgb(255, 143, 0)"><center>Nama</center></th>
              <th style="background-color:rgb(255, 143, 0)"><center>No. NPWP</center></th>
              <th style="background-color:rgb(255, 143, 0)"><center>NO. KTP</center></th>
              <th style="background-color:rgb(255, 143, 0)"><center>Alamat</center></th>
              <th style="background-color:rgb(255, 143, 0)"><center>Jabatan</center></th>
              <th style="background-color:rgb(255, 143, 0)"><center>P/L</center></th>
              <th style="background-color:rgb(255, 143, 0)"><center>Status</center></th>
              <th style="background-color:rgb(255, 143, 0)"><center>Nama <br> Penerima</center></th>
              <th style="background-color:rgb(255, 143, 0)"><center>Bank</center></th>
              <th style="background-color:rgb(255, 143, 0)"><center>No. Rek</center></th>
              <th style="background-color:rgb(255, 143, 0)"><center>ID</center></th>
              <th style="background-color:rgb(255, 143, 0)"><center>Dept.</center></th>
              <th style="background-color:rgb(255, 143, 0)"><center>No. SM</center></th>
              <th style="background-color:rgb(255, 143, 0)"><center>Periode</center></th>
              <th style="background-color:rgb(255, 143, 0)"><center>Mulai <br> Kerja</center></th>
              <th style="background-color:rgb(255, 143, 0)"><center>Akhir <br> Kerja</center></th>
              <th style="background-color:rgb(255, 143, 0)"><center>Status</center></th>
              <th style="background-color:rgb(255, 143, 0)"><center>Client</center></th>
              <th style="background-color:rgb(255, 143, 0)"><center>Basic <br> Salary</center></th>
              <?php
              for ($x = 1; $x <= 13; $x++) {
                echo '<th style="background-color:rgb(255, 143, 0)"><center>Salary This Month</center></th>';
                echo '<th style="background-color:rgb(255, 143, 0)"><center>Rapel Basic</center></th>';
                echo '<th style="background-color:rgb(255, 143, 0)"><center>Tax Allowance</center></th>';
                echo '<th style="background-color:rgb(255, 143, 0)"><center>OT/Allowance</center></th>';
                echo '<th style="background-color:rgb(255, 143, 0)"><center>Bonus/THR</center></th>';
                echo '<th style="background-color:rgb(255, 143, 0)"><center>PKWT Komp.</center></th>';
              }

              // foreach ($data as $row) {
                // $rowNo++;

              ?>
          </tr>
            <?php
              foreach ($dataSM as $rowSM) {
              $SM = $rowSM['payroll_group'];
		//echo $SM;
              $dataId = $mtbAr->getBiodataIdBySM($SM);
		//var_dump($dataId);
                foreach ($dataId as $rowId) {
                $biodataId = $rowId['biodata_id'];
                $status = $rowId['status_payroll'];
                $rowNo++;
                
		//var_dump($dataId);
                // ($dataId);
                // $month = $rowId['month_period'];
                // echo $month++;
              // }
            
             ?>
          <tr>
              <td ><center><?php echo $rowNo ?></center></td>
              <td ><center><?php echo $rowId['full_name'] ?></center></td>
              <td ><center><?php echo $rowId['tax_no'] ?></center></td>
              <td ><center><?php echo $rowId['id_card_no'] ?></center></td>
              <td ><center><?php echo $rowId['id_card_address'] ?></center></td>
              <td ><center><?php echo $rowId['emp_position'] ?></center></td>
              <td ><center><?php echo $rowId['gender'] ?></center></td>
              <td ><center><?php echo $rowId['marital_status'] ?></center></td>
              <td ><center><?php echo $rowId['account_name'] ?></center></td>
              <td ><center><?php echo $rowId['bank_name'] ?></center></td>
              <td ><center><?php echo $rowId['account_no'] ?></center></td>
              <td ><center><?php echo $rowId['biodata_id'] ?></center></td>
              <td ><center><?php echo $rowId['dept'] ?></center></td>
              <td ><center><?php echo $rowId['payroll_group'] ?></center></td>
              <td ><center><?php echo  "JAN $year - DES $year"?></center></td>
              <td ><center>Jan <?php echo $year ?></center></td>
              <td ><center>Des <?php echo $year ?></center></td>
              <td ><center></center></th>
              <td ><center>AGR</center></th>
              <td ><center><?php echo $rowId['monthly'] ?></center></td>
              <?php
              
              $data = $mtbAr->getDataByBiodataId($biodataId,$SM);

              $data[0]['gaji'] = isset($data[0]['gaji']) ? $data[0]['gaji'] : '';
              $data[1]['gaji'] = isset($data[1]['gaji']) ? $data[1]['gaji'] : '';
              $data[2]['gaji'] = isset($data[2]['gaji']) ? $data[2]['gaji'] : '';
              $data[3]['gaji'] = isset($data[3]['gaji']) ? $data[3]['gaji'] : '';
              $data[4]['gaji'] = isset($data[4]['gaji']) ? $data[4]['gaji'] : '';
              $data[5]['gaji'] = isset($data[5]['gaji']) ? $data[5]['gaji'] : '';
              $data[6]['gaji'] = isset($data[6]['gaji']) ? $data[6]['gaji'] : '';
              $data[7]['gaji'] = isset($data[7]['gaji']) ? $data[7]['gaji'] : '';
              $data[8]['gaji'] = isset($data[8]['gaji']) ? $data[8]['gaji'] : '';
              $data[9]['gaji'] = isset($data[9]['gaji']) ? $data[9]['gaji'] : ''; 
              $data[10]['gaji'] = isset($data[10]['gaji']) ? $data[10]['gaji'] : '';
              $data[11]['gaji'] = isset($data[11]['gaji']) ? $data[11]['gaji'] : '';

              $data[0]['month_period'] = isset($data[0]['month_period']) ? $data[0]['month_period'] : '';
              $data[1]['month_period'] = isset($data[1]['month_period']) ? $data[1]['month_period'] : '';
              $data[2]['month_period'] = isset($data[2]['month_period']) ? $data[2]['month_period'] : '';
              $data[3]['month_period'] = isset($data[3]['month_period']) ? $data[3]['month_period'] : '';
              $data[4]['month_period'] = isset($data[4]['month_period']) ? $data[4]['month_period'] : '';
              $data[5]['month_period'] = isset($data[5]['month_period']) ? $data[5]['month_period'] : '';
              $data[6]['month_period'] = isset($data[6]['month_period']) ? $data[6]['month_period'] : '';
              $data[7]['month_period'] = isset($data[7]['month_period']) ? $data[7]['month_period'] : '';
              $data[8]['month_period'] = isset($data[8]['month_period']) ? $data[8]['month_period'] : '';
              $data[9]['month_period'] = isset($data[9]['month_period']) ? $data[9]['month_period'] : ''; 
              $data[10]['month_period'] = isset($data[10]['month_period']) ? $data[10]['month_period'] : '';
              $data[11]['month_period'] = isset($data[11]['month_period']) ? $data[11]['month_period'] : '';

              $data[0]['totalot'] = isset($data[0]['totalot']) ? $data[0]['totalot'] : '';
              $data[1]['totalot'] = isset($data[1]['totalot']) ? $data[1]['totalot'] : '';
              $data[2]['totalot'] = isset($data[2]['totalot']) ? $data[2]['totalot'] : '';
              $data[3]['totalot'] = isset($data[3]['totalot']) ? $data[3]['totalot'] : '';
              $data[4]['totalot'] = isset($data[4]['totalot']) ? $data[4]['totalot'] : '';
              $data[5]['totalot'] = isset($data[5]['totalot']) ? $data[5]['totalot'] : '';
              $data[6]['totalot'] = isset($data[6]['totalot']) ? $data[6]['totalot'] : '';
              $data[7]['totalot'] = isset($data[7]['totalot']) ? $data[7]['totalot'] : '';
              $data[8]['totalot'] = isset($data[8]['totalot']) ? $data[8]['totalot'] : '';
              $data[9]['totalot'] = isset($data[9]['totalot']) ? $data[9]['totalot'] : ''; 
              $data[10]['totalot'] = isset($data[10]['totalot']) ? $data[10]['totalot'] : '';
              $data[11]['totalot'] = isset($data[11]['totalot']) ? $data[11]['totalot'] : '';

              $data[0]['thr'] = isset($data[0]['thr']) ? $data[0]['thr'] : '';
              $data[1]['thr'] = isset($data[1]['thr']) ? $data[1]['thr'] : '';
              $data[2]['thr'] = isset($data[2]['thr']) ? $data[2]['thr'] : '';
              $data[3]['thr'] = isset($data[3]['thr']) ? $data[3]['thr'] : '';
              $data[4]['thr'] = isset($data[4]['thr']) ? $data[4]['thr'] : '';
              $data[5]['thr'] = isset($data[5]['thr']) ? $data[5]['thr'] : '';
              $data[6]['thr'] = isset($data[6]['thr']) ? $data[6]['thr'] : '';
              $data[7]['thr'] = isset($data[7]['thr']) ? $data[7]['thr'] : '';
              $data[8]['thr'] = isset($data[8]['thr']) ? $data[8]['thr'] : '';
              $data[9]['thr'] = isset($data[9]['thr']) ? $data[9]['thr'] : ''; 
              $data[10]['thr'] = isset($data[10]['thr']) ? $data[10]['thr'] : '';
              $data[11]['thr'] = isset($data[11]['thr']) ? $data[11]['thr'] : '';

              $data[0]['allowance_03'] = isset($data[0]['allowance_03']) ? $data[0]['allowance_03'] : '';
              $data[1]['allowance_03'] = isset($data[1]['allowance_03']) ? $data[1]['allowance_03'] : '';
              $data[2]['allowance_03'] = isset($data[2]['allowance_03']) ? $data[2]['allowance_03'] : '';
              $data[3]['allowance_03'] = isset($data[3]['allowance_03']) ? $data[3]['allowance_03'] : '';
              $data[4]['allowance_03'] = isset($data[4]['allowance_03']) ? $data[4]['allowance_03'] : '';
              $data[5]['allowance_03'] = isset($data[5]['allowance_03']) ? $data[5]['allowance_03'] : '';
              $data[6]['allowance_03'] = isset($data[6]['allowance_03']) ? $data[6]['allowance_03'] : '';
              $data[7]['allowance_03'] = isset($data[7]['allowance_03']) ? $data[7]['allowance_03'] : '';
              $data[8]['allowance_03'] = isset($data[8]['allowance_03']) ? $data[8]['allowance_03'] : '';
              $data[9]['allowance_03'] = isset($data[9]['allowance_03']) ? $data[9]['allowance_03'] : ''; 
              $data[10]['allowance_03'] = isset($data[10]['allowance_03']) ? $data[10]['allowance_03'] : '';
              $data[11]['allowance_03'] = isset($data[11]['allowance_03']) ? $data[11]['allowance_03'] : '';

              $data[0]['tunjangan_jabatan'] = isset($data[0]['tunjangan_jabatan']) ? $data[0]['tunjangan_jabatan'] : '';
              $data[1]['tunjangan_jabatan'] = isset($data[1]['tunjangan_jabatan']) ? $data[1]['tunjangan_jabatan'] : '';
              $data[2]['tunjangan_jabatan'] = isset($data[2]['tunjangan_jabatan']) ? $data[2]['tunjangan_jabatan'] : '';
              $data[3]['tunjangan_jabatan'] = isset($data[3]['tunjangan_jabatan']) ? $data[3]['tunjangan_jabatan'] : '';
              $data[4]['tunjangan_jabatan'] = isset($data[4]['tunjangan_jabatan']) ? $data[4]['tunjangan_jabatan'] : '';
              $data[5]['tunjangan_jabatan'] = isset($data[5]['tunjangan_jabatan']) ? $data[5]['tunjangan_jabatan'] : '';
              $data[6]['tunjangan_jabatan'] = isset($data[6]['tunjangan_jabatan']) ? $data[6]['tunjangan_jabatan'] : '';
              $data[7]['tunjangan_jabatan'] = isset($data[7]['tunjangan_jabatan']) ? $data[7]['tunjangan_jabatan'] : '';
              $data[8]['tunjangan_jabatan'] = isset($data[8]['tunjangan_jabatan']) ? $data[8]['tunjangan_jabatan'] : '';
              $data[9]['tunjangan_jabatan'] = isset($data[9]['tunjangan_jabatan']) ? $data[9]['tunjangan_jabatan'] : ''; 
              $data[10]['tunjangan_jabatan'] = isset($data[10]['tunjangan_jabatan']) ? $data[10]['tunjangan_jabatan'] : '';
              $data[11]['tunjangan_jabatan'] = isset($data[11]['tunjangan_jabatan']) ? $data[11]['tunjangan_jabatan'] : '';
              // echo $data[1]['totalot'];
              $gaji =0;
              $gaji1 =0;
              $gaji2 =0;
              $gaji3 =0;
              $gaji4 =0;
              $gaji5 =0;
              $gaji6 =0;
              $gaji7 =0;
              $gaji8 =0;
              $gaji9 =0;
              $gaji10 = 0;
              $gaji11 = 0;

              $totalOt = 0;
              $totalOt1 = 0;
              $totalOt2 = 0;
              $totalOt3 = 0;
              $totalOt4 = 0;
              $totalOt5 = 0;
              $totalOt6 = 0;
              $totalOt7 = 0;
              $totalOt8 = 0;
              $totalOt9 = 0;
              $totalOt10 = 0;
              $totalOt11 = 0;

              $thr = 0;
              $thr1 = 0;
              $thr2 = 0;
              $thr3 = 0;
              $thr4 = 0;
              $thr5 = 0;
              $thr6 = 0;
              $thr7 = 0;
              $thr8 = 0;
              $thr9 = 0;
              $thr10 = 0;
              $thr11 = 0;

              $lainLain = 0;
              $lainLain1 = 0;
              $lainLain2 = 0;
              $lainLain3 = 0;
              $lainLain4 = 0;
              $lainLain5 = 0;
              $lainLain6 = 0;
              $lainLain7 = 0;
              $lainLain8 = 0;
              $lainLain9 = 0;
              $lainLain10 = 0;
              $lainLain11 = 0;

              $tunjanganJabatan = 0;
              $tunjanganJabatan1 = 0;
              $tunjanganJabatan2 = 0;
              $tunjanganJabatan3 = 0;
              $tunjanganJabatan4 = 0;
              $tunjanganJabatan5 = 0;
              $tunjanganJabatan6 = 0;
              $tunjanganJabatan7 = 0;
              $tunjanganJabatan8 = 0;
              $tunjanganJabatan9 = 0;
              $tunjanganJabatan10 = 0;
              $tunjanganJabatan11 = 0;
              

              
              



              if ($data[0]['gaji'] != '' && $data[0]['month_period'] == 1 && $data[0]['month_period'] != ''){
                $gaji = $data[0]['gaji'];
                $totalOt = $data[0]['totalot'];
                $thr = $data[0]['thr'];
                $tunjanganJabatan = $data[0]['tunjangan_jabatan'];
                $lainLain = $data[0]['allowance_03'];
              }
              if ($data[0]['gaji'] != '' && $data[0]['month_period'] == 2 && $data[0]['month_period'] != ''){
                $gaji1 = $data[0]['gaji'];
                $totalOt1 = $data[0]['totalot'];
                $thr1 = $data[0]['thr'];
                $tunjanganJabatan1 = $data[0]['tunjangan_jabatan'];
                $lainLain1 = $data[0]['allowance_03'];
              }
              if ($data[1]['gaji'] != '' && $data[1]['month_period'] == 2 && $data[1]['month_period'] != '') {
                $gaji1 = $data[1]['gaji'];
                $totalOt1 = $data[1]['totalot'];
                $thr1 = $data[1]['thr'];
                $tunjanganJabatan1 = $data[1]['tunjangan_jabatan'];
                $lainLain1 = $data[1]['allowance_03'];
              }
              if ($data[0]['gaji'] != '' && $data[0]['month_period'] == 3 && $data[0]['month_period'] != ''){
                $gaji2 = $data[0]['gaji'];
                $totalOt2 = $data[0]['totalot'];
                $thr2 = $data[0]['thr'];
                $tunjanganJabatan2 = $data[0]['tunjangan_jabatan'];
                $lainLain2 = $data[0]['allowance_03'];
              }
              if ($data[1]['gaji'] != '' && $data[1]['month_period'] == 3 && $data[1]['month_period'] != ''){
                $gaji2 = $data[1]['gaji'];
                $totalOt2 = $data[1]['totalot'];
                $thr2 = $data[1]['thr'];
                $tunjanganJabatan2 = $data[1]['tunjangan_jabatan'];
                $lainLain2 = $data[1]['allowance_03'];
              }
              if ($data[2]['gaji'] != '' && $data[2]['month_period'] == 3 && $data[2]['month_period'] != ''){
                $gaji2 = $data[2]['gaji'];
                $totalOt2 = $data[2]['totalot'];
                $thr2 = $data[2]['thr'];
                $tunjanganJabatan2 = $data[2]['tunjangan_jabatan'];
                $lainLain2 = $data[2]['allowance_03'];
              }
              if ($data[0]['gaji'] != '' && $data[0]['month_period'] == 4 && $data[0]['month_period'] != ''){
                $gaji3 = $data[0]['gaji'];
                $totalOt3 = $data[0]['totalot'];
                $thr3 = $data[0]['thr'];
                $tunjanganJabatan3 = $data[0]['tunjangan_jabatan'];
                $lainLain3 = $data[0]['allowance_03'];
              }
              if ($data[1]['gaji'] != '' && $data[1]['month_period'] == 4 && $data[1]['month_period'] != ''){
                $gaji3 = $data[1]['gaji'];
                $totalOt3 = $data[1]['totalot'];
                $thr3 = $data[1]['thr'];
                $tunjanganJabatan3 = $data[1]['tunjangan_jabatan'];
                $lainLain3 = $data[1]['allowance_03'];
              }
              if ($data[2]['gaji'] != '' && $data[2]['month_period'] == 4 && $data[2]['month_period'] != ''){
                $gaji3 = $data[2]['gaji'];
                $totalOt3 = $data[2]['totalot'];
                $thr3 = $data[2]['thr'];
                $tunjanganJabatan3 = $data[2]['tunjangan_jabatan'];
                $lainLain3 = $data[2]['allowance_03'];
              }
              if ($data[3]['gaji'] != '' && $data[3]['month_period'] == 4 && $data[3]['month_period'] != ''){
                $gaji3 = $data[3]['gaji'];
                $totalOt3 = $data[3]['totalot'];
                $thr3 = $data[3]['thr'];
                $tunjanganJabatan3 = $data[3]['tunjangan_jabatan'];
                $lainLain3 = $data[3]['allowance_03'];
              }
              if ($data[0]['gaji'] != '' && $data[0]['month_period'] == 5 && $data[0]['month_period'] != ''){
                $gaji4 = $data[0]['gaji'];
                $totalOt4 = $data[0]['totalot'];
                $thr4 = $data[0]['thr'];
                $tunjanganJabatan4 = $data[0]['tunjangan_jabatan'];
                $lainLain4 = $data[0]['allowance_03'];
              }
              if ($data[1]['gaji'] != '' && $data[1]['month_period'] == 5 && $data[1]['month_period'] != ''){
                $gaji4 = $data[1]['gaji'];
                $totalOt4 = $data[1]['totalot'];
                $thr4 = $data[1]['thr'];
                $tunjanganJabatan4 = $data[1]['tunjangan_jabatan'];
                $lainLain11 = $data[1]['allowance_03'];
              }
              if ($data[2]['gaji'] != '' && $data[2]['month_period'] == 5 && $data[2]['month_period'] != ''){
                $gaji4 = $data[2]['gaji'];
                $totalOt4 = $data[2]['totalot'];
                $thr4 = $data[2]['thr'];
                $tunjanganJabatan4 = $data[2]['tunjangan_jabatan'];
                $lainLain4 = $data[2]['allowance_03'];
              }
              if ($data[3]['gaji'] != '' && $data[3]['month_period'] == 5 && $data[3]['month_period'] != ''){
                $gaji4 = $data[3]['gaji'];
                $totalOt4 = $data[3]['totalot'];
                $thr4 = $data[3]['thr'];
                $tunjanganJabatan4 = $data[3]['tunjangan_jabatan'];
                $lainLain4 = $data[3]['allowance_03'];
              }
              if ($data[4]['gaji'] != '' && $data[4]['month_period'] == 5 && $data[4]['month_period'] != ''){
                $gaji4 = $data[4]['gaji'];
                $totalOt4 = $data[4]['totalot'];
                $thr4 = $data[4]['thr'];
                $tunjanganJabatan4 = $data[4]['tunjangan_jabatan'];
                $lainLain14 = $data[4]['allowance_03'];
              }
              if ($data[0]['gaji'] != '' && $data[0]['month_period'] == 6 && $data[0]['month_period'] != ''){
                $gaji5 = $data[0]['gaji'];
                $totalOt5 = $data[0]['totalot'];
                $thr5 = $data[0]['thr'];
                $tunjanganJabatan5 = $data[0]['tunjangan_jabatan'];
                $lainLain5 = $data[0]['allowance_03'];
              }
              if ($data[1]['gaji'] != '' && $data[1]['month_period'] == 6 && $data[1]['month_period'] != ''){
                $gaji5 = $data[1]['gaji'];
                $totalOt5 = $data[1]['totalot'];
                $thr5 = $data[1]['thr'];
                $tunjanganJabatan5 = $data[1]['tunjangan_jabatan'];
                $lainLain5 = $data[1]['allowance_03'];
              }
              if ($data[2]['gaji'] != '' && $data[2]['month_period'] == 6 && $data[2]['month_period'] != ''){
                $gaji5 = $data[2]['gaji'];
                $totalOt5 = $data[2]['totalot'];
                $thr5 = $data[2]['thr'];
                $tunjanganJabatan5 = $data[2]['tunjangan_jabatan'];
                $lainLain5 = $data[2]['allowance_03'];
              }
              if ($data[3]['gaji'] != '' && $data[3]['month_period'] == 6 && $data[3]['month_period'] != ''){
                $gaji5 = $data[3]['gaji'];
                $totalOt5 = $data[3]['totalot'];
                $thr5 = $data[3]['thr'];
                $tunjanganJabatan5 = $data[3]['tunjangan_jabatan'];
                $lainLain5 = $data[3]['allowance_03'];
              }
              if ($data[4]['gaji'] != '' && $data[4]['month_period'] == 6 && $data[4]['month_period'] != ''){
                $gaji5 = $data[4]['gaji'];
                $totalOt5 = $data[4]['totalot'];
                $thr5 = $data[4]['thr'];
                $tunjanganJabatan5 = $data[4]['tunjangan_jabatan'];
                $lainLain5 = $data[4]['allowance_03'];
              }
              if ($data[5]['gaji'] != '' && $data[5]['month_period'] == 6 && $data[5]['month_period'] != ''){
                $gaji5 = $data[5]['gaji'];
                $totalOt5 = $data[5]['totalot'];
                $thr5 = $data[5]['thr'];
                $tunjanganJabatan5 = $data[5]['tunjangan_jabatan'];
                $lainLain5 = $data[5]['allowance_03'];
              }
              if ($data[0]['gaji'] != '' && $data[0]['month_period'] == 7 && $data[0]['month_period'] != ''){
                $gaji6 = $data[0]['gaji'];
                $totalOt6 = $data[0]['totalot'];
                $thr6 = $data[0]['thr'];
                $tunjanganJabatan6 = $data[0]['tunjangan_jabatan'];
                $lainLain6 = $data[0]['allowance_03'];
              }
              if ($data[1]['gaji'] != '' && $data[1]['month_period'] == 7 && $data[1]['month_period'] != ''){
                $gaji6 = $data[1]['gaji'];
                $totalOt6 = $data[1]['totalot'];
                $thr6 = $data[1]['thr'];
                $tunjanganJabatan6 = $data[1]['tunjangan_jabatan'];
                $lainLain11 = $data[1]['allowance_03'];
              }
              if ($data[2]['gaji'] != '' && $data[2]['month_period'] == 7 && $data[2]['month_period'] != ''){
                $gaji6 = $data[2]['gaji'];
                $totalOt6 = $data[2]['totalot'];
                $thr6 = $data[2]['thr'];
                $tunjanganJabatan6 = $data[2]['tunjangan_jabatan'];
                $lainLain6 = $data[2]['allowance_03'];
              }
              if ($data[3]['gaji'] != '' && $data[3]['month_period'] == 7 && $data[3]['month_period'] != ''){
                $gaji6 = $data[3]['gaji'];
                $totalOt6 = $data[3]['totalot'];
                $thr6 = $data[3]['thr'];
                $tunjanganJabatan6 = $data[3]['tunjangan_jabatan'];
                $lainLain6 = $data[3]['allowance_03'];
              }
              if ($data[4]['gaji'] != '' && $data[4]['month_period'] == 7 && $data[4]['month_period'] != ''){
                $gaji6 = $data[4]['gaji'];
                $totalOt6 = $data[4]['totalot'];
                $thr6 = $data[4]['thr'];
                $tunjanganJabatan6 = $data[4]['tunjangan_jabatan'];
                $lainLain6 = $data[4]['allowance_03'];
              }
              if ($data[5]['gaji'] != '' && $data[5]['month_period'] == 7 && $data[5]['month_period'] != ''){
                $gaji6 = $data[5]['gaji'];
                $totalOt6 = $data[5]['totalot'];
                $thr6 = $data[5]['thr'];
                $tunjanganJabatan6 = $data[5]['tunjangan_jabatan'];
                $lainLain6 = $data[5]['allowance_03'];
              }
              if ($data[6]['gaji'] != '' && $data[6]['month_period'] == 7 && $data[6]['month_period'] != ''){
                $gaji6 = $data[6]['gaji'];
                $totalOt6 = $data[6]['totalot'];
                $thr6 = $data[6]['thr'];
                $tunjanganJabatan6 = $data[6]['tunjangan_jabatan'];
                $lainLain6 = $data[6]['allowance_03'];
              }
              if ($data[0]['gaji'] != '' && $data[0]['month_period'] == 8 && $data[0]['month_period'] != ''){
                $gaji7 = $data[0]['gaji'];
                $totalOt7 = $data[0]['totalot'];
                $thr7 = $data[0]['thr'];
                $tunjanganJabatan7 = $data[0]['tunjangan_jabatan'];
                $lainLain7 = $data[0]['allowance_03'];
              }
              if ($data[1]['gaji'] != '' && $data[1]['month_period'] == 8 && $data[1]['month_period'] != ''){
                $gaji7 = $data[1]['gaji'];
                $totalOt7 = $data[1]['totalot'];
                $thr7 = $data[1]['thr'];
                $tunjanganJabatan7 = $data[1]['tunjangan_jabatan'];
                $lainLain7 = $data[1]['allowance_03'];
              }
              if ($data[2]['gaji'] != '' && $data[2]['month_period'] == 8 && $data[2]['month_period'] != ''){
                $gaji7 = $data[2]['gaji'];
                $totalOt7 = $data[2]['totalot'];
                $thr7 = $data[2]['thr'];
                $tunjanganJabatan7 = $data[2]['tunjangan_jabatan'];
                $lainLain7 = $data[2]['allowance_03'];
              }
              if ($data[3]['gaji'] != '' && $data[3]['month_period'] == 8 && $data[3]['month_period'] != ''){
                $gaji7 = $data[3]['gaji'];
                $totalOt7 = $data[3]['totalot'];
                $thr7 = $data[3]['thr'];
                $tunjanganJabatan7 = $data[3]['tunjangan_jabatan'];
                $lainLain7 = $data[3]['allowance_03'];
              }
              if ($data[4]['gaji'] != '' && $data[4]['month_period'] == 8 && $data[4]['month_period'] != ''){
                $gaji7 = $data[4]['gaji'];
                $totalOt7 = $data[4]['totalot'];
                $thr7 = $data[4]['thr'];
                $tunjanganJabatan7 = $data[4]['tunjangan_jabatan'];
                $lainLain7 = $data[4]['allowance_03'];
              }
              if ($data[5]['gaji'] != '' && $data[5]['month_period'] == 8 && $data[5]['month_period'] != ''){
                $gaji7 = $data[5]['gaji'];
                $totalOt7 = $data[5]['totalot'];
                $thr7 = $data[5]['thr'];
                $tunjanganJabatan7 = $data[5]['tunjangan_jabatan'];
                $lainLain7 = $data[5]['allowance_03'];
              }
              if ($data[6]['gaji'] != '' && $data[6]['month_period'] == 8 && $data[6]['month_period'] != ''){
                $gaji7 = $data[6]['gaji'];
                $totalOt7 = $data[6]['totalot'];
                $thr7 = $data[6]['thr'];
                $tunjanganJabatan7 = $data[6]['tunjangan_jabatan'];
                $lainLain7 = $data[6]['allowance_03'];
              }
              if ($data[7]['gaji'] != '' && $data[7]['month_period'] == 8 && $data[7]['month_period'] != ''){
                $gaji7 = $data[7]['gaji'];
                $totalOt7 = $data[7]['totalot'];
                $thr7 = $data[7]['thr'];
                $tunjanganJabatan7 = $data[7]['tunjangan_jabatan'];
                $lainLain7 = $data[7]['allowance_03'];
              }
              if ($data[0]['gaji'] != '' && $data[0]['month_period'] == 9 && $data[0]['month_period'] != ''){
                $gaji8 = $data[0]['gaji'];
                $totalOt8 = $data[0]['totalot'];
                $thr8 = $data[0]['thr'];
                $tunjanganJabatan8 = $data[0]['tunjangan_jabatan'];
                $lainLain8 = $data[0]['allowance_03'];
              }
              if ($data[1]['gaji'] != '' && $data[1]['month_period'] == 9 && $data[1]['month_period'] != ''){
                $gaji8 = $data[1]['gaji'];
                $totalOt8 = $data[1]['totalot'];
                $thr8 = $data[1]['thr'];
                $tunjanganJabatan8 = $data[1]['tunjangan_jabatan'];
                $lainLain8 = $data[1]['allowance_03'];
              }
              if ($data[2]['gaji'] != '' && $data[2]['month_period'] == 9 && $data[2]['month_period'] != ''){
                $gaji8 = $data[2]['gaji'];
                $totalOt8 = $data[2]['totalot'];
                $thr8 = $data[2]['thr'];
                $tunjanganJabatan8 = $data[2]['tunjangan_jabatan'];
                $lainLain8 = $data[2]['allowance_03'];
              }
              if ($data[3]['gaji'] != '' && $data[3]['month_period'] == 9 && $data[3]['month_period'] != ''){
                $gaji8 = $data[3]['gaji'];
                $totalOt8 = $data[3]['totalot'];
                $thr8 = $data[3]['thr'];
                $tunjanganJabatan8 = $data[3]['tunjangan_jabatan'];
                $lainLain8 = $data[3]['allowance_03'];
              }
              if ($data[4]['gaji'] != '' && $data[4]['month_period'] == 9 && $data[4]['month_period'] != ''){
                $gaji8 = $data[4]['gaji'];
                $totalOt8 = $data[4]['totalot'];
                $thr8 = $data[4]['thr'];
                $tunjanganJabatan8 = $data[4]['tunjangan_jabatan'];
                $lainLain8 = $data[4]['allowance_03'];
              }
              if ($data[5]['gaji'] != '' && $data[5]['month_period'] == 9 && $data[5]['month_period'] != ''){
                $gaji8 = $data[5]['gaji'];
                $totalOt8 = $data[5]['totalot'];
                $thr8 = $data[5]['thr'];
                $tunjanganJabatan8 = $data[5]['tunjangan_jabatan'];
                $lainLain8 = $data[5]['allowance_03'];
              }
              if ($data[6]['gaji'] != '' && $data[6]['month_period'] == 9 && $data[6]['month_period'] != ''){
                $gaji8 = $data[6]['gaji'];
                $totalOt8 = $data[6]['totalot'];
                $thr8 = $data[6]['thr'];
                $tunjanganJabatan8 = $data[6]['tunjangan_jabatan'];
                $lainLain8 = $data[6]['allowance_03'];
              }
              if ($data[7]['gaji'] != '' && $data[7]['month_period'] == 9 && $data[7]['month_period'] != ''){
                $gaji8 = $data[7]['gaji'];
                $totalOt8 = $data[7]['totalot'];
                $thr8 = $data[7]['thr'];
                $tunjanganJabatan8 = $data[7]['tunjangan_jabatan'];
                $lainLain8 = $data[7]['allowance_03'];
              }
              if ($data[8]['gaji'] != '' && $data[8]['month_period'] == 9 && $data[8]['month_period'] != ''){
                $gaji8 = $data[8]['gaji'];
                $totalOt8 = $data[8]['totalot'];
                $thr8 = $data[8]['thr'];
                $tunjanganJabatan18 = $data[8]['tunjangan_jabatan'];
                $lainLain8 = $data[8]['allowance_03'];
              }
              if ($data[0]['gaji'] != '' && $data[0]['month_period'] == 10 && $data[0]['month_period'] != ''){
                $gaji9 = $data[0]['gaji'];
                $totalOt9 = $data[0]['totalot'];
                $thr9 = $data[0]['thr'];
                $tunjanganJabatan9 = $data[0]['tunjangan_jabatan'];
                $lainLain9 = $data[0]['allowance_03'];
              }
              if ($data[1]['gaji'] != '' && $data[1]['month_period'] == 10 && $data[1]['month_period'] != ''){
                $gaji9 = $data[1]['gaji'];
                $totalOt9 = $data[1]['totalot'];
                $thr9 = $data[1]['thr'];
                $tunjanganJabatan9 = $data[1]['tunjangan_jabatan'];
                $lainLain9 = $data[1]['allowance_03'];
              }
              if ($data[2]['gaji'] != '' && $data[2]['month_period'] == 10 && $data[2]['month_period'] != ''){
                $gaji9 = $data[2]['gaji'];
                $totalOt9 = $data[2]['totalot'];
                $thr9 = $data[2]['thr'];
                $tunjanganJabatan9 = $data[2]['tunjangan_jabatan'];
                $lainLain9 = $data[2]['allowance_03'];
              }
              if ($data[3]['gaji'] != '' && $data[3]['month_period'] == 10 && $data[3]['month_period'] != ''){
                $gaji9 = $data[3]['gaji'];
                $totalOt9 = $data[3]['totalot'];
                $thr9 = $data[3]['thr'];
                $tunjanganJabatan9 = $data[3]['tunjangan_jabatan'];
                $lainLain9 = $data[3]['allowance_03'];
              }
              if ($data[4]['gaji'] != '' && $data[4]['month_period'] == 10 && $data[4]['month_period'] != ''){
                $gaji9 = $data[4]['gaji'];
                $totalOt9 = $data[4]['totalot'];
                $thr9 = $data[4]['thr'];
                $tunjanganJabatan9 = $data[4]['tunjangan_jabatan'];
                $lainLain9 = $data[4]['allowance_03'];
              }
              if ($data[5]['gaji'] != '' && $data[5]['month_period'] == 10 && $data[5]['month_period'] != ''){
                $gaji9 = $data[5]['gaji'];
                $totalOt9 = $data[5]['totalot'];
                $thr9 = $data[5]['thr'];
                $tunjanganJabatan9 = $data[5]['tunjangan_jabatan'];
                $lainLain9 = $data[5]['allowance_03'];
              }
              if ($data[6]['gaji'] != '' && $data[6]['month_period'] == 10 && $data[6]['month_period'] != ''){
                $gaji9 = $data[6]['gaji'];
                $totalOt9 = $data[6]['totalot'];
                $thr9 = $data[6]['thr'];
                $tunjanganJabatan9 = $data[6]['tunjangan_jabatan'];
                $lainLain9 = $data[6]['allowance_03'];
              }
              if ($data[7]['gaji'] != '' && $data[7]['month_period'] == 10 && $data[7]['month_period'] != ''){
                $gaji9 = $data[7]['gaji'];
                $totalOt9 = $data[7]['totalot'];
                $thr9 = $data[7]['thr'];
                $tunjanganJabatan9 = $data[7]['tunjangan_jabatan'];
                $lainLain9 = $data[7]['allowance_03'];
              }
              if ($data[8]['gaji'] != '' && $data[8]['month_period'] == 10 && $data[8]['month_period'] != ''){
                $gaji9 = $data[8]['gaji'];
                $totalOt9 = $data[8]['totalot'];
                $thr9 = $data[8]['thr'];
                $tunjanganJabatan9 = $data[8]['tunjangan_jabatan'];
                $lainLain9 = $data[8]['allowance_03'];
              }
              if ($data[9]['gaji'] != '' && $data[9]['month_period'] == 10 && $data[9]['month_period'] != ''){
                $gaji9 = $data[9]['gaji'];
                $totalOt9 = $data[9]['totalot'];
                $thr9 = $data[9]['thr'];
                $tunjanganJabatan9 = $data[9]['tunjangan_jabatan'];
                $lainLain9 = $data[9]['allowance_03'];
              }
              if ($data[0]['gaji'] != '' && $data[0]['month_period'] == 11 && $data[0]['month_period'] != ''){
                $gaji10 = $data[0]['gaji'];
                $totalOt10 = $data[0]['totalot'];
                $thr10 = $data[0]['thr'];
                $tunjanganJabatan10 = $data[0]['tunjangan_jabatan'];
                $lainLain10 = $data[0]['allowance_03'];
              }
              if ($data[1]['gaji'] != '' && $data[1]['month_period'] == 11 && $data[1]['month_period'] != ''){
                $gaji10 = $data[1]['gaji'];
                $totalOt10 = $data[1]['totalot'];
                $thr10 = $data[1]['thr'];
                $tunjanganJabatan10 = $data[1]['tunjangan_jabatan'];
                $lainLain10 = $data[1]['allowance_03'];
              }
              if ($data[2]['gaji'] != '' && $data[2]['month_period'] == 11 && $data[2]['month_period'] != ''){
                $gaji10 = $data[2]['gaji'];
                $totalOt10 = $data[2]['totalot'];
                $thr10 = $data[2]['thr'];
                $tunjanganJabatan10 = $data[2]['tunjangan_jabatan'];
                $lainLain10 = $data[2]['allowance_03'];
              }
              if ($data[3]['gaji'] != '' && $data[3]['month_period'] == 11 && $data[3]['month_period'] != ''){
                $gaji10 = $data[3]['gaji'];
                $totalOt10 = $data[3]['totalot'];
                $thr10 = $data[3]['thr'];
                $tunjanganJabatan10 = $data[3]['tunjangan_jabatan'];
                $lainLain10 = $data[3]['allowance_03'];
              }
              if ($data[4]['gaji'] != '' && $data[4]['month_period'] == 11 && $data[4]['month_period'] != ''){
                $gaji10 = $data[4]['gaji'];
                $totalOt10 = $data[4]['totalot'];
                $thr10 = $data[4]['thr'];
                $tunjanganJabatan10 = $data[4]['tunjangan_jabatan'];
                $lainLain10 = $data[4]['allowance_03'];
              }
              if ($data[5]['gaji'] != '' && $data[5]['month_period'] == 11 && $data[5]['month_period'] != ''){
                $gaji10 = $data[5]['gaji'];
                $totalOt10 = $data[5]['totalot'];
                $thr10 = $data[5]['thr'];
                $tunjanganJabatan10 = $data[5]['tunjangan_jabatan'];
                $lainLain10 = $data[5]['allowance_03'];
              }
              if ($data[6]['gaji'] != '' && $data[6]['month_period'] == 11 && $data[6]['month_period'] != ''){
                $gaji10 = $data[6]['gaji'];
                $totalOt10 = $data[6]['totalot'];
                $thr10 = $data[6]['thr'];
                $tunjanganJabatan10 = $data[6]['tunjangan_jabatan'];
                $lainLain10 = $data[6]['allowance_03'];
              }
              if ($data[7]['gaji'] != '' && $data[7]['month_period'] == 11 && $data[7]['month_period'] != ''){
                $gaji10 = $data[7]['gaji'];
                $totalOt10 = $data[7]['totalot'];
                $thr10 = $data[7]['thr'];
                $tunjanganJabatan10 = $data[7]['tunjangan_jabatan'];
                $lainLain10 = $data[7]['allowance_03'];
              }
              if ($data[8]['gaji'] != '' && $data[8]['month_period'] == 11 && $data[8]['month_period'] != ''){
                $gaji10 = $data[8]['gaji'];
                $totalOt10 = $data[8]['totalot'];
                $thr10 = $data[8]['thr'];
                $tunjanganJabatan10 = $data[8]['tunjangan_jabatan'];
                $lainLain10 = $data[8]['allowance_03'];
              }
              if ($data[9]['gaji'] != '' && $data[9]['month_period'] == 11 && $data[9]['month_period'] != ''){
                $gaji10 = $data[9]['gaji'];
                $totalOt10 = $data[9]['totalot'];
                $thr10 = $data[9]['thr'];
                $tunjanganJabatan10 = $data[9]['tunjangan_jabatan'];
                $lainLain10 = $data[9]['allowance_03'];
              }
              if ($data[10]['gaji'] != '' && $data[10]['month_period'] == 11 && $data[10]['month_period'] != ''){
                $gaji10 = $data[10]['gaji'];
                $totalOt10 = $data[10]['totalot'];
                $thr10 = $data[10]['thr'];
                $tunjanganJabatan10 = $data[10]['tunjangan_jabatan'];
                $lainLain10 = $data[10]['allowance_03'];
              }
              if ($data[0]['gaji'] != '' && $data[0]['month_period'] == 12 && $data[0]['month_period'] != ''){
                $gaji11 = $data[0]['gaji'];
                $totalOt11 = $data[0]['totalot'];
                $thr11 = $data[0]['thr'];
                $tunjanganJabatan11 = $data[0]['tunjangan_jabatan'];
                $lainLain11 = $data[0]['allowance_03'];
              }
              if ($data[1]['gaji'] != '' && $data[1]['month_period'] == 12 && $data[1]['month_period'] != ''){
                $gaji11 = $data[1]['gaji'];
                $totalOt11 = $data[1]['totalot'];
                $thr11 = $data[1]['thr'];
                $tunjanganJabatan11 = $data[1]['tunjangan_jabatan'];
                $lainLain11 = $data[1]['allowance_03'];
              }
              if ($data[2]['gaji'] != '' && $data[2]['month_period'] == 12 && $data[2]['month_period'] != ''){
                $gaji11 = $data[2]['gaji'];
                $totalOt11 = $data[2]['totalot'];
                $thr11 = $data[2]['thr'];
                $tunjanganJabatan11 = $data[2]['tunjangan_jabatan'];
                $lainLain11 = $data[2]['allowance_03'];
              }
              if ($data[3]['gaji'] != '' && $data[3]['month_period'] == 12 && $data[3]['month_period'] != ''){
                $gaji11 = $data[3]['gaji'];
                $totalOt11 = $data[3]['totalot'];
                $thr11 = $data[3]['thr'];
                $tunjanganJabatan11 = $data[3]['tunjangan_jabatan'];
                $lainLain11 = $data[3]['allowance_03'];
              }
              if ($data[4]['gaji'] != '' && $data[4]['month_period'] == 12 && $data[4]['month_period'] != ''){
                $gaji11 = $data[4]['gaji'];
                $totalOt11 = $data[4]['totalot'];
                $thr11 = $data[4]['thr'];
                $tunjanganJabatan11 = $data[4]['tunjangan_jabatan'];
                $lainLain11 = $data[4]['allowance_03'];
              }
              if ($data[5]['gaji'] != '' && $data[5]['month_period'] == 12 && $data[5]['month_period'] != ''){
                $gaji11 = $data[5]['gaji'];
                $totalOt11 = $data[5]['totalot'];
                $thr11 = $data[5]['thr'];
                $tunjanganJabatan11 = $data[5]['tunjangan_jabatan'];
                $lainLain11 = $data[5]['allowance_03'];
              }
              if ($data[6]['gaji'] != '' && $data[6]['month_period'] == 12 && $data[6]['month_period'] != ''){
                $gaji11 = $data[6]['gaji'];
                $totalOt11 = $data[6]['totalot'];
                $thr11 = $data[6]['thr'];
                $tunjanganJabatan11 = $data[6]['tunjangan_jabatan'];
                $lainLain11 = $data[6]['allowance_03'];
              }
              if ($data[7]['gaji'] != '' && $data[7]['month_period'] == 12 && $data[7]['month_period'] != ''){
                $gaji11 = $data[7]['gaji'];
                $totalOt11 = $data[7]['totalot'];
                $thr11 = $data[7]['thr'];
                $tunjanganJabatan11 = $data[7]['tunjangan_jabatan'];
                $lainLain11 = $data[7]['allowance_03'];
              }
              if ($data[8]['gaji'] != '' && $data[8]['month_period'] == 12 && $data[8]['month_period'] != ''){
                $gaji11 = $data[8]['gaji'];
                $totalOt11 = $data[8]['totalot'];
                $thr11 = $data[8]['thr'];
                $tunjanganJabatan11 = $data[8]['tunjangan_jabatan'];
                $lainLain11 = $data[8]['allowance_03'];
              }
              if ($data[9]['gaji'] != '' && $data[9]['month_period'] == 12 && $data[9]['month_period'] != ''){
                $gaji11 = $data[9]['gaji'];
                $totalOt11 = $data[9]['totalot'];
                $thr11 = $data[9]['thr'];
                $tunjanganJabatan11 = $data[9]['tunjangan_jabatan'];
                $lainLain11 = $data[9]['allowance_03'];
              }
              if ($data[10]['gaji'] != '' && $data[10]['month_period'] == 12 && $data[10]['month_period'] != ''){
                $gaji11 = $data[10]['gaji'];
                $totalOt11 = $data[10]['totalot'];
                $thr11 = $data[10]['thr'];
                $tunjanganJabatan11 = $data[10]['tunjangan_jabatan'];
                $lainLain11 = $data[10]['allowance_03'];
              }
              if ($data[11]['gaji'] != '' && $data[11]['month_period'] == 12 && $data[11]['month_period'] != ''){
                $gaji11 = $data[11]['gaji'];
                $totalOt11 = $data[11]['totalot'];
                $thr11 = $data[11]['thr'];
                $tunjanganJabatan11 = $data[11]['tunjangan_jabatan'];
                $lainLain11 = $data[11]['allowance_03'];
              }
              $totalgaji = $gaji + $gaji1 + $gaji2 + $gaji3 + $gaji4 + $gaji5 + $gaji6 + $gaji7 + $gaji8 + $gaji9 + $gaji10 + $gaji11;
              $totalAllOt = $totalOt + $totalOt1 + $totalOt2 + $totalOt3 + $totalOt4 + $totalOt5 + $totalOt6 + $totalOt7 + $totalOt8 + $totalOt9 + $totalOt10 + $totalOt11;
              $totalthr = $thr + $thr1 + $thr2 + $thr3 + $thr4 + $thr5 + $thr6 + $thr7 + $thr8 + $thr9 + $thr10 + $thr11;
              $totallainLain = $lainLain + $lainLain1 + $lainLain2 + $lainLain3 + $lainLain4 + $lainLain5 + $lainLain6 + $lainLain7 + $lainLain8 + $lainLain9 + $lainLain10 + $lainLain11;
              $totaltunjanganJabatan = $tunjanganJabatan + $tunjanganJabatan1 + $tunjanganJabatan2 + $tunjanganJabatan3 + $tunjanganJabatan4 + $tunjanganJabatan5 + $tunjanganJabatan6 + $tunjanganJabatan7 + $tunjanganJabatan8 + $tunjanganJabatan9 + $tunjanganJabatan10 + $tunjanganJabatan11;

            // foreach ($data as $row) {
                  
            //       // dd($data);
            //     $month =  $row['month_period'];
                    //  BULAN 1
                  echo '<td><center>'.$gaji.'</center></td>';
                  echo '<td><center></center></td>';
                  echo "<td><center>$tunjanganJabatan</center></td>";
                  echo '<td><center>'.$totalOt.'</center></td>';
                  echo "<td><center>$thr</center></td>";
                  echo "<td><center>$lainLain</center></td>";
                  //  BULAN 2
                  echo '<td><center>'.$gaji1.'</center></td>';
                  echo '<td><center></center></td>';
                  echo "<td><center>$tunjanganJabatan1</center></td>";
                  echo '<td><center>'.$totalOt1.'</center></td>';
                  echo "<td><center>$thr1</center></td>";
                  echo "<td><center>$lainLain1</center></td>";
                  //  BULAN 3
                  echo '<td><center>'.$gaji2.'</center></td>';
                  echo '<td><center></center></td>';
                  echo "<td><center>$tunjanganJabatan2</center></td>";
                  echo '<td><center>'.$totalOt2.'</center></td>';
                  echo "<td><center>$thr2</center></td>";
                  echo "<td><center>$lainLain2</center></td>";
                  //  BULAN 4
                  echo '<td><center>'.$gaji3.'</center></td>';
                  echo '<td><center></center></td>';
                  echo "<td><center>$tunjanganJabatan3</center></td>";
                  echo '<td><center>'.$totalOt3.'</center></td>';
                  echo "<td><center>$thr3</center></td>";
                  echo "<td><center>$lainLain3</center></td>";
                  //  BULAN 5
                  echo '<td><center>'.$gaji4.'</center></td>';
                  echo '<td><center></center></td>';
                  echo "<td><center>$tunjanganJabatan4</center></td>";
                  echo '<td><center>'.$totalOt4.'</center></td>';
                  echo "<td><center>$thr4</center></td>";
                  echo "<td><center>$lainLain4</center></td>";
                  //  BULAN 6
                  echo '<td><center>'.$gaji5.'</center></td>';
                  echo '<td><center></center></td>';
                  echo "<td><center>$tunjanganJabatan5</center></td>";
                  echo '<td><center>'.$totalOt5.'</center></td>';
                  echo "<td><center>$thr5</center></td>";
                  echo "<td><center>$lainLain5</center></td>";
                  //  BULAN 7
                  echo '<td><center>'.$gaji6.'</center></td>';
                  echo '<td><center></center></td>';
                  echo "<td><center>$tunjanganJabatan6</center></td>";
                  echo '<td><center>'.$totalOt6.'</center></td>';
                  echo "<td><center>$thr6</center></td>";
                  echo "<td><center>$lainLain6</center></td>";
                  //  BULAN 8
                  echo '<td><center>'.$gaji7.'</center></td>';
                  echo '<td><center></center></td>';
                  echo "<td><center>$tunjanganJabatan7</center></td>";
                  echo '<td><center>'.$totalOt7.'</center></td>';
                  echo "<td><center>$thr7</center></td>";
                  echo "<td><center>$lainLain7</center></td>";
                  //  BULAN 9
                  echo '<td><center>'.$gaji8.'</center></td>';
                  echo '<td><center></center></td>';
                  echo "<td><center>$tunjanganJabatan8</center></td>";
                  echo '<td><center>'.$totalOt8.'</center></td>';
                  echo "<td><center>$thr8</center></td>";
                  echo "<td><center>$lainLain8</center></td>";
                  //  BULAN 10
                  echo '<td><center>'.$gaji9.'</center></td>';
                  echo '<td><center></center></td>';
                  echo "<td><center>$tunjanganJabatan9</center></td>";
                  echo '<td><center>'.$totalOt9.'</center></td>';
                  echo "<td><center>$thr9</center></td>";
                  echo "<td><center>$lainLain9</center></td>";
                  //  BULAN 11
                  echo '<td><center>'.$gaji10.'</center></td>';
                  echo '<td><center></center></td>';
                  echo "<td><center>$tunjanganJabatan10</center></td>";
                  echo '<td><center>'.$totalOt10.'</center></td>';
                  echo "<td><center>$thr10</center></td>";
                  echo "<td><center>$lainLain10</center></td>";
                  //  BULAN 12
                  echo '<td><center>'.$gaji11.'</center></td>';
                  echo '<td><center></center></td>';
                  echo "<td><center>$tunjanganJabatan11</center></td>";
                  echo '<td><center>'.$totalOt11.'</center></td>';
                  echo "<td><center>$thr11</center></td>";
                  echo "<td><center>$lainLain11</center></td>";
                  //  TOTAL
                  echo "<td><center>$totalgaji</center></td>";
                  echo "<td><center></center></td>";
                  echo "<td><center>$totaltunjanganJabatan</center></td>";
                  echo "<td><center>$totalAllOt</center></td>";
                  echo "<td><center>$totalthr</center></td>";
                  echo "<td><center>$totallainLain</center></td>";

                // }
            }
          }  
              ?>
          </tr>
          
         
           
           
          
          
      </table>
  </body>
</html>