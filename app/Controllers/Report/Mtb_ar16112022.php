<?php namespace App\Controllers\Report;
use CodeIgniter\Controller;
use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Transaction\M_tr_timesheet;
use App\Models\Transaction\M_upload;
use App\Models\Transaction\M_proses;
use App\Models\Transaction\M_tr_overd;
use App\Models\Transaction\M_tr_slip;
use App\Models\Master\M_dept;
use App\Models\Transaction\M_rp_db;


class Mtb_ar extends BaseController
{
    public function index()
    {
        /* ***Using Valid Path */
        $data['actView'] = 'Report/v_mtb_ar';
        return view('home', $data);
    }

    public function mtbAr($year)
    {
    	$data['year'] = $year;
        return view('Report/mtbar_download',$data);
    }
}