<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=DB".$SM.$monthPeriod.$yearPeriod.".xls");
use App\Models\Transaction\M_rp_db;
?>
 
<!DOCTYPE html>
<html>
  <head>
    <title>Export Data Ke Excel </title>
  </head>
  <body>
    <h1>PERHITUNGAN GAJI PER BULAN</h1>
      <table border='1'>
        <tr>
            <th style="background-color:rgb(1, 95, 255)"><center>NO</center></th>
            <th style="background-color:rgb(1, 95, 255)"><center>PERIODE</center></th>
            <th style="background-color:rgb(1, 95, 255)"><center>NAME</center></th>
            <th style="background-color:rgb(1, 95, 255)"><center>NOMOR<br>Badge</center></th>
            <th style="background-color:rgb(1, 95, 255)"><center>Position</center></th>
            <th style="background-color:rgb(1, 95, 255)"><center>Client<br>Location</center></th>
            <th style="background-color:rgb(1, 95, 255)"><center>TANGGAL<br>MULAI<br>KERJA</center></th>
            <th style="background-color:rgb(1, 95, 255)"><center>END<br>KONTRAK<br>KERJA</center></th>
            <th style="background-color:rgb(1, 95, 255)"><center>NOMOR<br>NPWP</center></th>
            <th style="background-color:rgb(1, 95, 255)"><center>UMK</center></th>
            <th style="background-color:rgb(1, 95, 255)"><center>Status</center></th>
            <th style="background-color:rgb(1, 95, 255)"><center>Jumlah<br>Anak</center></th>
            <th style="background-color:rgb(1, 95, 255)"><center>STATUS<br>PEMBAYARAN<br>BPJS</center></th>
            <?php
            $tanggal = 0;
            // $start = 16;
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
                  echo '<th style="background-color:rgb(68, 255, 0)"><center>'.$tgl.'-'. $monthNow.'-'.$yearsnow.'<br><br>IN</center></th>';
                  echo '<th style="background-color:rgb(254, 222, 156)"><center>'.$rdData.'<br><br>OUT</center></th>';
                  echo '<th style="background-color:rgb(254, 222, 156)"><center>SHIFT DAY<br>D/S - NS</center></th>';
                  echo '<th style="background-color:rgb(254, 222, 156)"><center>RO<br>PH<br></center></th>';
                  echo '<th style="background-color:rgb(254, 222, 156)"><center>WD<br></center></th>';
                $tanggal++;
                }
            ?> 
            <th style="background-color:rgb(254, 246, 1)"><center>Nama<br>Dept</center></th>
            <th style="background-color:rgb(254, 246, 1)"><center>STATUS<br>KARYAWAN</center></th>
            <th style="background-color:rgb(254, 246, 1)"><center>SITE<br>PROJECT</center></th>
            <th style="background-color:rgb(0, 205, 117)"><center>COST CODE</center></th>
        </tr>
          
         
            <?php
              $rowNo = 0;
              $rpDb = new M_rp_db;

              foreach ($dataId as $data) {
                $totalWd = $data['total_wd'];
                if ($totalWd > 0) {

                  $slipId = $data['slip_id'];
                  $bioId = $data['biodata_id'];
                  $minDate = $data['mindate'];
                  $maxDate = $data['maxdate'];
                  $rpDb->setstartDate($startDate);
                  $rpDb->setendDate($maxDate);
                  $dataBySlipId = $rpDb->getDataBySlipId($slipId);
                  $dataDbDate = $rpDb->getDataDbByDate($bioId);
                  // dd($dataDbDate);
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

                  foreach ($dataBySlipId as $row) {
                    $rowNo++;
                    // echo $rowNo;
                    $dayss = 31;
                    $umk = 0;
                    // $endDate = date('Y-m-d', strtotime('+'.$dayss.'days', strtotime($start)));
                    $status = $row['status_payroll'];
                    $anak = 0;
                    $Mstatus = '';
                    $statuss = $row['marital_status'];
                    if ($status == 'daily' || $status == 'Daily') {
                        $umk = $row['daily'];
                    }
                    else if ($status == 'daily p') {
                        $umk = $row['daily'];
                    }
                    else if ($status == 'month' || $status == 'Monthly'){
                        $umk = $row['monthly'];
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
                    $bpjsCheck = $row['bpjs_check'];
                    if ($bpjsCheck == 1) {
                        $statusBpjs = 'YA+';
                    } else {
                        $statusBpjs = 'TIDAK';
                    }
                      // echo $endDate;
                    ?>
                     <tr>
                    <td><?php echo $rowNo; ?></td>
                    <td><?php echo date('d - F - Y', strtotime($startDate)).'-'.date('d - F - Y', strtotime($endDate)); ?></td>
                    <td><?php echo $row['full_name']; ?></td>
                    <td><?php echo ''; ?></td>
                    <td><?php echo $row['position']; ?></td>
                    <td><?php echo $row['client_name']; ?></td>
                    <td><?php echo $row['join_date']; ?></td>
                    <td><?php echo $row['end_date']; ?></td>
                    <td><?= ($row['tax_no']!='')? "'".$row['tax_no'] : ''; ?></td>
                    <td class='number-cell'><center><?php echo number_format($row['monthly'], 2, ',', '.')?></center></td>
                    <td><?php echo $Mstatus; ?></td>
                    <td><?php echo $anak; ?></td>
                    <td><?php echo $statusBpjs; ?></td>
                    <?php

                    $new_start_date     = $yearPeriod.'-'.$monthPeriod.'-16';
                    $new_end_date       = date('Y-m-15', strtotime('+1 month', strtotime($new_start_date))); 
                    $selisih            = date_diff( date_create($new_start_date), date_create($new_end_date) )->days +1;

                    $view_start_date    = $new_start_date;
                    for ($x = 1; $x <= $selisih; $x++) {
                      $dataDbDateNew = $rpDb->getDataDbByPerDate($bioId,$view_start_date);
                      // dd($dataDbDateNew['in_db']);
                      // echo $status;
                      // exit();
                      if ($status == 'Daily' || $status == 'daily') {
                        if(isset($dataDbDateNew)){
                          if ($dataDbDateNew['in_db'] > 0 && $dataDbDateNew['po_ph'] == 'PH') {
                            echo '<td class="number-cell"><center>'.number_format($dataDbDateNew['in_db'], 2, '.', ',').'</center></td>';
                            echo '<td class="number-cell"><center>'.number_format($dataDbDateNew['out_db'], 2, '.', ',').'</center></td>';

                            echo '<td><center>WH</center></td>';
                            echo '<td><center>'.$dataDbDateNew['po_ph'].'</center></td>';
                            echo '<td><center>0</center></td>';
                          } else if ($dataDbDateNew['in_db'] > 0 && $dataDbDateNew['po_ph'] == 'HN') {
                            echo '<td class="number-cell"><center>'.number_format($dataDbDateNew['in_db'], 2, '.', ',').'</center></td>';
                            echo '<td class="number-cell"><center>'.number_format($dataDbDateNew['out_db'], 2, '.', ',').'</center></td>';

                            echo '<td><center>WHN</center></td>';
                            echo '<td><center>PH</center></td>';
                            echo '<td><center>0</center></td>';
                          } else if ($dataDbDateNew['in_db'] > 0 && $dataDbDateNew['po_ph'] == 'RO') {
                            echo '<td class="number-cell"><center>'.number_format($dataDbDateNew['in_db'], 2, '.', ',').'</center></td>';
                            echo '<td class="number-cell"><center>'.number_format($dataDbDateNew['out_db'], 2, '.', ',').'</center></td>';

                            echo '<td><center>WF</center></td>';
                            echo '<td><center>'.$dataDbDateNew['po_ph'].'</center></td>';
                            echo '<td><center>0</center></td>';
                          } else if ($dataDbDateNew['in_db'] > 0 && $dataDbDateNew['po_ph'] == 'ON') {
                            echo '<td class="number-cell"><center>'.number_format($dataDbDateNew['in_db'], 2, '.', ',').'</center></td>';
                            echo '<td class="number-cell"><center>'.number_format($dataDbDateNew['out_db'], 2, '.', ',').'</center></td>';

                            echo '<td><center>WFN</center></td>';
                            echo '<td><center>RO</center></td>';
                            echo '<td><center>0</center></td>';
                          } else {
                            echo '<td class="number-cell"><center>'.number_format($dataDbDateNew['in_db'], 2, '.', ',').'</center></td>';
                            echo '<td class="number-cell"><center>'.number_format($dataDbDateNew['out_db'], 2, '.', ',').'</center></td>';

                            echo '<td><center>'.$dataDbDateNew['shift_day'].'</center></td>';
                            echo '<td><center>'.$dataDbDateNew['po_ph'].'</center></td>';
                            echo '<td><center>'.$dataDbDateNew['work_day'].'</center></td>';
                          }
                        }else{
                            echo '<td class="number-cell"><center></center></td>';
                            echo '<td class="number-cell"><center></center></td>';

                            echo '<td><center></center></td>';
                            echo '<td><center></center></td>';
                            echo '<td><center></center></td>';
                        }
                      } else {
                        if(isset($dataDbDateNew)){
                          if ($dataDbDateNew['in_db'] > 0 && $dataDbDateNew['po_ph'] == 'PH') {
                            echo '<td class="number-cell"><center>'.number_format($dataDbDateNew['in_db'], 2, '.', ',').'</center></td>';
                            echo '<td class="number-cell"><center>'.number_format($dataDbDateNew['out_db'], 2, '.', ',').'</center></td>';
                            echo '<td><center>WH</center></td>';
                            echo '<td><center>'.$dataDbDateNew['po_ph'].'</center></td>';
                            echo '<td><center>1</center></td>';
                          } else if ($dataDbDateNew['in_db'] > 0 && $dataDbDateNew['po_ph'] == 'HN') {
                            echo '<td class="number-cell"><center>'.number_format($dataDbDateNew['in_db'], 2, '.', ',').'</center></td>';
                            echo '<td class="number-cell"><center>'.number_format($dataDbDateNew['out_db'], 2, '.', ',').'</center></td>';
                            echo '<td><center>WHN</center></td>';
                            echo '<td><center>PH</center></td>';
                            echo '<td><center>1</center></td>';
                          } else if ($dataDbDateNew['in_db'] > 0 && $dataDbDateNew['po_ph'] == 'RO') {
                            echo '<td class="number-cell"><center>'.number_format($dataDbDateNew['in_db'], 2, '.', ',').'</center></td>';
                            echo '<td class="number-cell"><center>'.number_format($dataDbDateNew['out_db'], 2, '.', ',').'</center></td>';
                            echo '<td><center>WF</center></td>';
                            echo '<td><center>'.$dataDbDateNew['po_ph'].'</center></td>';
                            echo '<td><center>1</center></td>';
                          } else if ($dataDbDateNew['in_db'] > 0 && $dataDbDateNew['po_ph'] == 'ON') {
                            echo '<td class="number-cell"><center>'.number_format($dataDbDateNew['in_db'], 2, '.', ',').'</center></td>';
                            echo '<td class="number-cell"><center>'.number_format($dataDbDateNew['out_db'], 2, '.', ',').'</center></td>';
                            echo '<td><center>WFN</center></td>';
                            echo '<td><center>RO</center></td>';
                            echo '<td><center>1</center></td>';
                          } else {
                            echo '<td class="number-cell"><center>'.number_format($dataDbDateNew['in_db'], 2, '.', ',').'</center></td>';
                            echo '<td class="number-cell"><center>'.number_format($dataDbDateNew['out_db'], 2, '.', ',').'</center></td>';
                            echo '<td><center>'.$dataDbDateNew['shift_day'].'</center></td>';
                            echo '<td><center>'.$dataDbDateNew['po_ph'].'</center></td>';
                            echo '<td><center>'.$dataDbDateNew['work_day'].'</center></td>';
                          }
                        }else{
                            echo '<td class="number-cell"><center></center></td>';
                            echo '<td class="number-cell"><center></center></td>';

                            echo '<td><center></center></td>';
                            echo '<td><center></center></td>';
                            echo '<td><center></center></td>';
                        }
                        // dd($dataDbDateNew);
                      }

                      $view_start_date  = date('Y-m-d', strtotime('+1 days', strtotime($view_start_date)));
                    }
                    ?>

                    <td><?php echo $row['dept']; ?></td>
                    <td><?php echo $row['status_payroll']; ?></td>
                    <td><?php echo $row['client_name']; ?></td>
                    <td><?php echo ''; ?></td>
                  </tr>
                  <?php 
                  }
                }
              }
            ?>
      </table>
  </body>
</html>