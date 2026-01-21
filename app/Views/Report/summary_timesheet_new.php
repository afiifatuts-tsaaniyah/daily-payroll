<?php
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    setlocale(LC_TIME, 'Indonesian_Indonesia');
} else {
    setlocale(LC_TIME, 'id_ID.UTF-8');
}

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=SummaryTimesheet$monthPeriod-$yearPeriod.xls");
use App\Models\Transaction\M_tr_timesheet;

$model = new M_tr_timesheet();
$dataId = $model->getDataInvoiceAllDept($yearPeriod, $monthPeriod, $dept); 

$data_dept          = array();
$data_dept_detail   = array();

foreach ($dataId as $key => $value) {
  $data_dept[$value['dept']]   = $value;
  $data_dept_detail[$value['dept']][]   = $value;
}

// $dataOtAll = $model->getAllOtDateProcess($startDate, $endDate); 
// dd($dataId);
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
              <th rowspan="2"><center>NO</center></th>
              <th rowspan="2"><center>NO. Urut</center></th>
              <th rowspan="2"><center>NAMA</center></th>
              <th rowspan="2"><center>ID<br>BADGE</center></th>
              <th rowspan="2"><center>DEPARTMENT</center></th>
              <th rowspan="2"><center>POSITION</center></th>
              <th rowspan="2"><center>MARITAL STATUS</center></th>
              <th rowspan="2"><center>ANAK</center></th>
              <th rowspan="2"><center>LOCATION</center></th>
              <th rowspan="2"><center>WORKING<br>STATUS</center></th>
              <th rowspan="2"><center>BASIC SALARY (IDR)</center></th>
              <th rowspan="2"><center>COST CODE</center></th>
              <th rowspan="2"><center>AFTERNOON<br>SHIFT<br>ALLOWANCE</center></th>
              <th rowspan="2"><center>NIGHT<br>SHIFT<br>ALLOWANCE</center></th>
              <th rowspan="2"><center>KET</center></th>
              <?php
              $new_start_date     = $yearPeriod.'-'.$monthPeriod.'-16';
              $new_end_date       = date('Y-m-15', strtotime('+1 month', strtotime($new_start_date))); 
              $selisih            = date_diff( date_create($new_start_date), date_create($new_end_date) )->days +1;

              $view_start_date    = $new_start_date;
              for ($x = 1; $x <= $selisih; $x++) {
                ?>
                <th><center><?= date('d-F-Y', strtotime($view_start_date)); ?></center></th>
                <?php
                $view_start_date  = date('Y-m-d', strtotime('+1 days', strtotime($view_start_date)));
              }
              ?>
              <th rowspan="2" style="background-color:rgb(254, 246, 1)"><center>NT <br> Regular Time<br>(Hours)</center></th>
              <th rowspan="2" style="background-color:rgb(254, 246, 1)"><center>Leave</center></th>
              <th rowspan="2" style="background-color:rgb(254, 246, 1)"><center>PH / <br>RO</center></th>
              <th rowspan="2" style="background-color:rgb(0, 205, 117)"><center>HA /<br>OA</center></th>
              <th rowspan="2" style="background-color:rgb(254, 246, 1)"><center>HN / <br>ON</center></th>
              <th rowspan="2" style="background-color:rgb(0, 205, 117)"><center>D/S</center></th>
              <th rowspan="2" style="background-color:rgb(0, 205, 117)"><center>A/S</center></th>  
              <th rowspan="2" style="background-color:rgb(0, 205, 117)"><center>N/S</center></th>
              <th rowspan="2" style="background-color:rgb(254, 246, 1)"><center>DAY</center></th>
              <th rowspan="2" style="background-color:rgb(0, 205, 117)"><center>1,5 <br>*</center></th>
              <th rowspan="2" style="background-color:rgb(0, 205, 117)"><center>2,0<br>*</center></th>
              <th rowspan="2" style="background-color:rgb(227, 1, 13)"><center>3,0 <br>*</center></th>
              <th rowspan="2" style="background-color:rgb(227, 1, 13)"><center>4,0 <br>*</center></th>
              <th rowspan="2" style="background-color:rgb(227, 1, 13)"><center>TOTAL <br>Over Time</center></th>

          </tr>
          <tr>
            <?php
            $new_start_date     = $yearPeriod.'-'.$monthPeriod.'-16';
            $new_end_date       = date('Y-m-15', strtotime('+1 month', strtotime($new_start_date))); 
            $selisih            = date_diff( date_create($new_start_date), date_create($new_end_date) )->days +1;

            $view_start_date    = $new_start_date;
            for ($x = 1; $x <= $selisih; $x++) {
              ?>
            <th><center><?= strftime('%A', strtotime($view_start_date)); ?></center></th>
              <?php
              $view_start_date  = date('Y-m-d', strtotime('+1 days', strtotime($view_start_date)));
            }
            ?>
          </tr>
          <?php
          $rowDept  = 0;
          $rowNo    = 0;
          $umk      = 0;

          foreach ($data_dept as $row) {
          ?>
          <tr>
            <td bgcolor="#99FF33" colspan="13"><strong><?= $row['dept']; ?></strong></td>
            <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
            <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
            <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
          </tr>
          <?php
            foreach ($data_dept_detail[$row['dept']] as $key => $val1) {
              // dd($val1);
              $rowNo++;
              $rowDept++;

              $total_wd     = $val1['total_work_day']*8;
              $dataBiodata = $model->getBiodataNameInvoicePrint($val1['biodata_id']);
              $dataSalary  = $model->getSalaryNameInvoicePrint($val1['biodata_id']);

              if ($dataSalary['status_payroll'] == 'daily' || $dataSalary['status_payroll'] == 'Daily') {
                $umk = $dataSalary['daily'];
              }else if ($dataSalary['status_payroll'] == 'daily p') {
                $umk = $dataSalary['daily'];
              }else if ($dataSalary['status_payroll'] == 'month' || $dataSalary['status_payroll'] == 'Monthly'){
                $umk = $dataSalary['monthly'] ;
              }

              $total_ot     = $val1['total_ot1']+$val1['total_ot2']+$val1['total_ot3']+$val1['total_ot4'];
              ?>
              <tr>
                <td><?= $rowDept; ?></td>
                <td><?= $rowNo; ?></td>
                <td><?= $dataBiodata['full_name']; ?></td>
                <td></td>
                <td><?= $row['dept']; ?></td>
                <td><?= $dataBiodata['emp_position']; ?></td>
                <td><?= $dataBiodata['marital_status']; ?></td>
                <td><?= substr($dataBiodata['marital_status'],-1); ?></td>
                <td><?= $dataBiodata['placement']; ?></td>
                <td><?= $dataSalary['status_payroll']; ?></td>
                <td><?= $umk; ?></td>
                <td></td>
                <td>10%</td>
                <td>15%</td>
                <td>SHIFTDAY</td>
                <?php
                $view_start_date    = $new_start_date;
                for ($x = 1; $x <= $selisih; $x++) {
                  $data_ot   = $model->getOtDateProcess($view_start_date,$val1['biodata_id']);
                ?>
                <td>
                  <?php 
                  if($data_ot){
                    if($data_ot['shift_day']!=''){
                      echo $data_ot['shift_day'];
                    }else if($data_ot['po_ph']){
                      echo $data_ot['po_ph'];
                    }
                  }
                  ?>
                </td>
                <?php
                $view_start_date  = date('Y-m-d', strtotime('+1 days', strtotime($view_start_date)));
                }
                ?>
                <td><?= $total_wd; ?></td>
                <td><?= $val1['jumlah_leave']; ?></td>
                <td><?= $val1['jumlah_off_day_shift']; ?></td>
                <td><?= $val1['jumlah_off_afternoon']; ?></td>
                <td><?= $val1['jumlah_off_night']; ?></td>
                <td><?= $val1['total_ds']; ?></td>
                <td><?= $val1['total_as']; ?></td>
                <td><?= $val1['total_ns']; ?></td>
                <td><?= $val1['total_work_day']; ?></td>
                <td><?= $val1['total_ot1']; ?></td>
                <td><?= $val1['total_ot2']; ?></td>
                <td><?= $val1['total_ot3']; ?></td>
                <td><?= $val1['total_ot4']; ?></td>
                <td><?= $val1['total_ot_sum']; ?></td>
              </tr>
              <tr>
                <td colspan="14"></td>
                <td>NT</td>
                <?php
                $view_start_date    = $new_start_date;
                for ($x = 1; $x <= $selisih; $x++) {
                  $data_otNt   = $model->getOtDateProcess($view_start_date,$val1['biodata_id']);
                ?>
                <td>
                <?php 
                if($data_otNt){
                  if($data_otNt['shift_day']!=''){
                    echo 8;
                  }else{
                    echo "";
                  }
                }
                ?>
                </td>
                <?php
                $view_start_date  = date('Y-m-d', strtotime('+1 days', strtotime($view_start_date)));
                }
                ?>
                <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
              </tr>
              <tr>
                <td colspan="14"></td>
                <td>1 x(1.5)</td>
                <?php
                $view_start_date    = $new_start_date;
                for ($x = 1; $x <= $selisih; $x++) {
                  $data_ot1   = $model->getOtDateProcess($view_start_date,$val1['biodata_id']);
                  // dd($data_ot['ot_1']);
                ?>
                <td><?= (isset($data_ot1['ot_1']))? $data_ot1['ot_1'] : ''; ?></td>
                <?php
                $view_start_date  = date('Y-m-d', strtotime('+1 days', strtotime($view_start_date)));
                }
                ?>
                <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
              </tr>
              <tr>
                <td colspan="14"></td>
                <td>2 x(2)</td>
                <?php
                $view_start_date    = $new_start_date;
                for ($x = 1; $x <= $selisih; $x++) {
                  $data_ot2   = $model->getOtDateProcess($view_start_date,$val1['biodata_id']);
                ?>
                <td><?= (isset($data_ot2['ot_2']))? $data_ot2['ot_2'] : ''; ?></td>
                <?php
                $view_start_date  = date('Y-m-d', strtotime('+1 days', strtotime($view_start_date)));
                }
                ?>
                <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
              </tr>
              <tr>
                <td colspan="14"></td>
                <td>3 x(2)</td>
                <?php
                $view_start_date    = $new_start_date;
                for ($x = 1; $x <= $selisih; $x++) {
                  $data_ot3   = $model->getOtDateProcess($view_start_date,$val1['biodata_id']);
                ?>
                <td><?= (isset($data_ot3['ot_3']))? $data_ot3['ot_3'] : ''; ?></td>
                <?php
                $view_start_date  = date('Y-m-d', strtotime('+1 days', strtotime($view_start_date)));
                }
                ?>
                <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
              </tr>
              <tr>
                <td colspan="14"></td>
                <td>4 x(2)</td>
                <?php
                $view_start_date    = $new_start_date;
                for ($x = 1; $x <= $selisih; $x++) {
                  $data_ot4   = $model->getOtDateProcess($view_start_date,$val1['biodata_id']);
                ?>
                <td><?= (isset($data_ot4['ot_4']))? $data_ot4['ot_4'] : ''; ?></td>
                <?php
                $view_start_date  = date('Y-m-d', strtotime('+1 days', strtotime($view_start_date)));
                }
                ?>
                <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
              </tr>
              <?php
            }
            $rowDept    = 0;
          }
          ?>           
      </table>
  </body>
</html>