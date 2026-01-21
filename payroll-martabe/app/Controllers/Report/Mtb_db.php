<?php namespace App\Controllers\Report;

use App\Models\Transaction\M_rp_db;
use App\Controllers\BaseController;
use App\Models\Master\M_dept;


class Mtb_db extends BaseController
{
    public function index()
     {
         /* ***Using Valid Path */
         $mtDept = new M_dept();
         $data['data_dept'] = $mtDept->get_dept();
         $data['actView'] = 'Report/v_mtb_db';
         return view('home', $data);
     }

    public function getDbList($yearPeriod, $monthPeriod)
    {
    // $depart = str_replace("%20"," ",$dept);
        $rpDb = new M_rp_db();
        $monthend = $monthPeriod + 1;
        $yearend = $yearPeriod;
        if ($monthend > 12) {
            $monthend = $monthend - 12;
            $yearend = $yearPeriod + 1;
        }
        if ($monthend < 10) {
            $monthend = '0'.$monthend;
        }
        $startDate = $yearPeriod.'-'.$monthPeriod.'-16';
        $endDate = $yearend.'-'.$monthend.'-15';
        $rpDb->setstartDate($startDate);
        $rpDb->setendDate($endDate);
        $dataId = $rpDb->getSlipIdByDate();
        
        // echo $this->db->last_query(); exit(0);
        // echo $this->db->last_query(); exit(0);
        /*return json_encode($query);*/
            $myData = array();
            foreach ($dataId as $key => $row) 
            {
                   $myData[] = array(
                    $row['slip_id'],
                    $row['full_name'],         
                    // $row['production_bonus'],         
                    // $row['workday_adj'],         
                    // $row['adjust_in'],         
                    // $row['adjust_out'],         
                    $row['dept'],         
                    $row['position']        
                    // $row['attendance_bonus'],         
                    // $row['other_allowance1'],         
                    // $row['other_allowance2'],         
                    // $row['cc_payment'],         
                    // $row['thr'],         
                    // $row['debt_burden'],
                    // $row['debt_explanation']
                );            
            }
        
          

        echo json_encode($myData);  
        // echo $this->db->last_query(); 
    }

    public function getDbListSM($yearPeriod, $monthPeriod, $SM)
    {
    // $depart = str_replace("%20"," ",$dept);
        $rpDb = new M_rp_db();
        $monthend = $monthPeriod + 1;
        $yearend = $yearPeriod;
        if ($monthend > 12) {
            $monthend = $monthend - 12;
            $yearend = $yearPeriod + 1;
        }
        if ($monthend < 10) {
            $monthend = '0'.$monthend;
        }
        $startDate = $yearPeriod.'-'.$monthPeriod.'-16';
        $endDate = $yearend.'-'.$monthend.'-15';
        $rpDb->setstartDate($startDate);
        $rpDb->setendDate($endDate);
        $dataId = $rpDb->getSlipIdByDateSM($SM);
        
        // echo $this->db->last_query(); exit(0);
        // echo $this->db->last_query(); exit(0);
        /*return json_encode($query);*/
            $myData = array();
            foreach ($dataId as $key => $row) 
            {
                   $myData[] = array(
                    $row['slip_id'],
                    $row['full_name'],         
                    // $row['production_bonus'],         
                    // $row['workday_adj'],         
                    // $row['adjust_in'],         
                    // $row['adjust_out'],         
                    $row['dept'],         
                    $row['position']        
                    // $row['attendance_bonus'],         
                    // $row['other_allowance1'],         
                    // $row['other_allowance2'],         
                    // $row['cc_payment'],         
                    // $row['thr'],         
                    // $row['debt_burden'],
                    // $row['debt_explanation']
                );            
            }
        
          

        echo json_encode($myData);  
        // echo $this->db->last_query(); 
    }

    public function exportDbForm($yearPeriod, $monthPeriod)
    {

        $rpDb = new M_rp_db();
        $monthend = $monthPeriod + 1;
        $yearend = $yearPeriod;
        if ($monthend > 12) {
            $monthend = $monthend - 12;
            $yearend = $yearPeriod + 1;
        }
        if ($monthend < 10) {
            $monthend = '0'.$monthend;
        }
        $startDate = $yearPeriod.'-'.$monthPeriod.'-16';
        $endDate = $yearend.'-'.$monthend.'-15';
        $rpDb->setstartDate($startDate);
        $rpDb->setendDate($endDate);
        // $dataId = $rpDb->getSlipIdByDate(); 
        $tgl3 = strtotime($startDate); 
        $tgl4 = strtotime($endDate); 
        $jarak = $tgl3 - $tgl4;
        $hari = $jarak / 60 / 60 / 24;
        $hari = str_replace("-","",$hari);
        $daycount =  $hari+16;
        // echo $endDate;
        // echo $daycount;
        // exit();
        // foreach ($dataId as $row) {
            // $slipId = $row['slip_id'];
            $data['start'] = 16;
            $data['dataId'] = $rpDb->getSlipIdByDate(); 
            $data['yearPeriod'] = $yearPeriod;
            $data['SM'] = '';
            $data['monthPeriod'] = $monthPeriod;
            $data['startDate'] = $startDate;
            $data['endDate'] = $endDate;
            $data['daycount'] = $daycount;
            // $data['dataBySlipId'] = $rpDb->getDataBySlipId($slipId);
            // $data['dataDbByDate'] = $rpDb->getDataDbByDate($slipId);
            return view('Transaction/download_templet',$data);
        // }
    }

    public function exportDbFormSM($yearPeriod, $monthPeriod, $SM)
    {

        $rpDb = new M_rp_db();
        $monthend = $monthPeriod + 1;
        $yearend = $yearPeriod;
        if ($monthend > 12) {
            $monthend = $monthend - 12;
            $yearend = $yearPeriod + 1;
        }
        if ($monthend < 10) {
            $monthend = '0'.$monthend;
        }
        $dataDate = $rpDb->getDateProcessSM($monthPeriod, $yearPeriod, $SM);
        $startDate = $dataDate['date_process'];
        $dateOnly = date('d', strtotime($startDate));
        echo $startDate;
        
        $nextThirtyDays = date('Y-m-d', strtotime($startDate . ' +31 days'));
        $endDate = $nextThirtyDays;
        $start = 
        $rpDb->setstartDate($startDate);
        $rpDb->setendDate($endDate);
        // $dataId = $rpDb->getSlipIdByDate(); 
        $tgl3 = strtotime($startDate); 
        $tgl4 = strtotime($endDate); 
        $jarak = $tgl3 - $tgl4;
        $hari = $jarak / 60 / 60 / 24;
        $hari = str_replace("-","",$hari);
        $daycount =  $hari+16;
        // echo $endDate;
        // echo $daycount;
        // exit();
        // foreach ($dataId as $row) {
            // $slipId = $row['slip_id'];
            $data['start'] = $dateOnly;
            $data['dataId'] = $rpDb->getSlipIdByDateSM($SM); 
            $data['SM'] = $SM;
            $data['yearPeriod'] = $yearPeriod;
            $data['monthPeriod'] = $monthPeriod;
            $data['startDate'] = $startDate;
            $data['endDate'] = $endDate;
            $data['daycount'] = $daycount;
            // $data['dataBySlipId'] = $rpDb->getDataBySlipId($slipId);
            // $data['dataDbByDate'] = $rpDb->getDataDbByDate($slipId);
            return view('Transaction/download_templet',$data);
        // }
    }  


}


?>