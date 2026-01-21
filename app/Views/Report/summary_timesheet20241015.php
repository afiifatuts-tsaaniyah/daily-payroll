<?php
 // header("Content-type: application/vnd-ms-excel");
 // header("Content-Disposition: attachment; filename=SummaryTimesheet$monthPeriod-$yearPeriod.xls");
use App\Models\Transaction\M_tr_timesheet;
?>
 
<!DOCTYPE html>
<html>
  <head>
    <title>Export Data Ke Excel </title>
  </head>
  <body>
    <h2>SUMMARY TIMESHEET</h2>
    <h3>Site Project Martabe <?php echo $yearPeriod;?><br><?php echo $dateHeader;?></h3>
      <table border='1'>
        
          <tr>
              <th><center>NO</center></th>
              <th><center>NAMA</center></th>
              <th><center>ID<br>BADGE</center></th>
              <th><center>DEPARTMENT</center></th>
              <th><center>POSITION</center></th>
              <th><center>MARITAL STATUS</center></th>
              <th><center>ANAK</center></th>
              <th><center>LOCATION</center></th>
              <th><center>WORKING<br>STATUS</center></th>
              <th><center>BASIC SALARY (IDR)</center></th>
              <th><center>COST CODE</center></th>
              <th><center>AFTERNOON<br>SHIFT<br>ALLOWANCE</center></th>
              <th><center>NIGHT<br>SHIFT<br>ALLOWANCE</center></th>
              <?php
              $tanggal = 0; 
              $start = 16;
                 for ($x = 16; $x <= $daycount; $x++) {
                  $tgl = $start+$tanggal;
                  if ($tgl > cal_days_in_month(CAL_GREGORIAN, $monthPeriod, $yearPeriod)){
                    $tgl = $start+$tanggal - cal_days_in_month(CAL_GREGORIAN, $monthPeriod, $yearPeriod);
                    $monthNow = $monthPeriod +1;
                  }else{
                      $tgl = $start+$tanggal;
                      $monthNow = $monthPeriod;
                  }
                  if ($monthNow == 13) {
                      $yearsnow = $yearPeriod +1;
                      $monthNow = 1;
                  } else {
                      $yearsnow = $yearPeriod;
                      // $monthNow = 1;
                  }
                  $yearss = $tgl.'-'. $monthNow.'-'.$yearsnow;
                  $day = date('D', strtotime($yearss));
                      $dayList = array(
                          'Sun' => 'Minggu',
                          'Mon' => 'Senin',
                          'Tue' => 'Selasa',
                          'Wed' => 'Rabu',
                          'Thu' => 'Kamis',
                          'Fri' => 'Jumat',
                          'Sat' => 'Sabtu'
                      );
                  $rdData =  $dayList[$day];
                    echo '<th style="background-color:rgb(68, 255, 0)"><center>'.$tgl.'-'. $monthNow.'-'.$yearsnow.'<br>'.$rdData.'<br>SHIFT DAY</center></th>';
                    echo '<th style="background-color:rgb(254, 222, 156)"><center>NT</center></th>';
                    echo '<th style="background-color:rgb(254, 222, 156)"><center>TOTAL OT</center></th>';
                    echo '<th style="background-color:rgb(254, 222, 156)"><center>1 x1.5)</center></th>';
                    echo '<th style="background-color:rgb(254, 222, 156)"><center>2 x(2)</center></th>';
                    echo '<th style="background-color:rgb(254, 222, 156)"><center>3 x(3)</center></th>';
                    echo '<th style="background-color:rgb(254, 222, 156)"><center>4 x(4)</center></th>';
                  $tanggal++;
                  }
              ?>
              <th style="background-color:rgb(254, 246, 1)"><center>NT <br> Regular Time<br>(Hours)</center></th>
              <th style="background-color:rgb(254, 246, 1)"><center>Leave</center></th>
              <th style="background-color:rgb(254, 246, 1)"><center>PH / <br>RO</center></th>
              <th style="background-color:rgb(0, 205, 117)"><center>HA /<br>OA</center></th>
              <th style="background-color:rgb(254, 246, 1)"><center>HN / <br>ON</center></th>
              <th style="background-color:rgb(0, 205, 117)"><center>D/S</center></th>
              <th style="background-color:rgb(0, 205, 117)"><center>A/S</center></th>  
              <th style="background-color:rgb(0, 205, 117)"><center>N/S</center></th>
              <th style="background-color:rgb(254, 246, 1)"><center>DAY</center></th>
              <th style="background-color:rgb(0, 205, 117)"><center>1,5 <br>*</center></th>
              <th style="background-color:rgb(0, 205, 117)"><center>2,0<br>*</center></th>
              <th style="background-color:rgb(227, 1, 13)"><center>3,0 <br>*</center></th>
              <th style="background-color:rgb(227, 1, 13)"><center>4,0 <br>*</center></th>
              <th style="background-color:rgb(227, 1, 13)"><center>TOTAL <br>Over Time</center></th>

          </tr>
          
         
            <?php
            $rowNo = 0;
            $umk = 0;
              $model = new M_tr_timesheet();

              $dataId = $model->getDataInvoiceAllDept($yearPeriod, $monthPeriod, $dept); 
                // dd($dataId);
              foreach ($dataId as $row) {
                $slipId = $row['slip_id'];
                $bioId = $row['biodata_id'];
                $dataBiodata = $model->getBiodataNameInvoicePrint($bioId);
                $dataSalary  = $model->getSalaryNameInvoicePrint($bioId);
                // dd($row);
                $rowNo++;
                $data = $model->getDataDBInvoiceTimesheet($yearPeriod, $monthPeriod, $bioId);
                dd($data);
                $minDate = $row['min_date'];
                $maxDate = $row['max_date'];
                // echo $maxDate.' - '.$minDate;
                $tgl3 = strtotime($startDate); 
                $tgl4 = strtotime($minDate); 
                $jarak = $tgl3 - $tgl4;
                $hari = $jarak / 60 / 60 / 24;
                $minday = str_replace("-","",$hari);
                $tgl5 = strtotime($endDate); 
                $tgl6 = strtotime($maxDate); 
                $jarak = $tgl5 - $tgl6;
                $hari1 = $jarak / 60 / 60 / 24;
                $maxday = str_replace("-","",$hari1);


                $status = $dataSalary['status_payroll'];
                echo $status;
                $statuss = $dataBiodata['marital_status'];
                if ($status == 'daily' || $status == 'Daily') {
                    $umk = $dataSalary['daily'];
                }
                else if ($status == 'daily p') {
                    $umk = $dataSalary['daily'];
                }
                else if ($status == 'month' || $status == 'Monthly'){
                    $umk = $dataSalary['monthly'] ;
                }
                if ($statuss == 'TK0') {
                    $Mstatus = 'TK';
                    $anak = 0;
                } elseif ($statuss == 'K0') {
                    $Mstatus = 'K';
                    $anak = 0;
                } elseif ($statuss == 'K1') {
                    $Mstatus = 'K';
                    $anak = 1;
                } elseif ($statuss == 'K2') {
                    $Mstatus = 'K';
                    $anak = 2;
                } elseif ($statuss == 'K3') {
                    $Mstatus = 'K';
                    $anak = 3;
                } 
              //   $bpjsCheck = $row['bpjs_check'];
              //   if ($bpjsCheck == 1) {
              //       $statusBpjs = 'YA+';
              //   } else {
              //       $statusBpjs = 'TIDAK';
              //   }
              //     // echo $endDate;
              //   ?>
                <tr>
                 <td><?php echo $rowNo; ?></td>
                 <td><?php echo $dataBiodata['full_name']; ?></td>
                 <td></td>
                 <td><?php echo $row['dept']; ?></td>
                 <td><?php echo $dataBiodata['emp_position']; ?></td>
                 <td><?php echo $dataBiodata['marital_status']; ?></td>
                 <td><?php echo $anak; ?></td>
                 <td><?php echo $dataBiodata['placement']; ?></td>
                 <td><?php echo $dataSalary['status_payroll']; ?></td>
                 <td><?php echo $umk; ?></td>
                 <td></td>
                 <td>10%</td>
                 <td>15%</td>
                 <?php
              // }
                  for ($x = 1; $x <= $minday; $x++) {
                    echo '<td><center></center></td>';
                    echo '<td><center></center></td>';
                    echo '<td><center></center></td>';
                    echo '<td><center></center></td>';
                    echo '<td><center></center></td>';
                    echo '<td><center></center></td>';
                    echo '<td><center></center></td>';
                  }
                  
                  foreach ($data as $rowDate) {
                    echo '<td><center>'.$rowDate['shift_day'].'</center></td>';
                    echo '<td><center>'.$rowDate['normal_time'].'</center></td>';
                    echo '<td><center>'.$rowDate['total_ot'].'</center></td>';
                    echo '<td><center>'.$rowDate['ot_1'].'</center></td>';
                    echo '<td><center>'.$rowDate['ot_2'].'</center></td>';
                    echo '<td><center>'.$rowDate['ot_3'].'</center></td>';
                    echo '<td><center>'.$rowDate['ot_4'].'</center></td>';
                  }

                  for ($x = 1; $x <= $maxday; $x++) {
                    echo '<td><center></center></td>';
                    echo '<td><center></center></td>';
                    echo '<td><center></center></td>';
                    echo '<td><center></center></td>';
                    echo '<td><center></center></td>';
                    echo '<td><center></center></td>';
                    echo '<td><center></center></td>';
                  }
              //     foreach ($dataBySlipId as $row) {
                 ?>
                
            <?php
            echo '<td><center>'.$row['total_nt'].'</center></td>';
            echo '<td><center>'.$row['jumlah_leave'].'</center></td>';
            echo '<td><center>'.$row['jumlah_off_day_shift'].'</center></td>';
            echo '<td><center>'.$row['jumlah_off_afternoon'].'</center></td>';
            echo '<td><center>'.$row['jumlah_off_night'].'</center></td>';
            echo '<td><center>'.$row['total_ds'].'</center></td>';
            echo '<td><center>'.$row['total_as'].'</center></td>';
            echo '<td><center>'.$row['total_ns'].'</center></td>';
            echo '<td><center>'.$row['total_wd'].'</center></td>';
            echo '<td><center>'.$row['total_ot1'].'</center></td>';
            echo '<td><center>'.$row['total_ot2'].'</center></td>';
            echo '<td><center>'.$row['total_ot3'].'</center></td>';
            echo '<td><center>'.$row['total_ot4'].'</center></td>';
            echo '<td><center>'.$row['total_ot_sum'].'</center></td>';
            }
            ?>
          
          
      </table>
  </body>
</html>