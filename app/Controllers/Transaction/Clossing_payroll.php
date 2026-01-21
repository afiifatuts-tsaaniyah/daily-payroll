<?php

namespace App\Controllers\Transaction;

use CodeIgniter\I18n\Time;
use App\Controllers\BaseController;
use App\Helpers\ConfigurationHelper;
use App\Helpers\ResponseFormatter;
use App\Models\Master\Client;
use App\Models\Master\Config;
use App\Models\Master\ConfigAllowance;
use App\Models\Master\M_mt_salary;
use App\Models\Master\MtBiodata01;
use App\Models\Master\ProcessClossing;
use App\Models\Master\MtAllowance;
use App\Models\Master\RosterHist;
use App\Models\Master\TaxGolongan;
use App\Models\Transaction\M_tr_overtime;
use App\Models\Transaction\M_tr_timesheet;
use App\Models\Transaction\SalarySlip;
use App\Models\Transaction\Tax;

class Clossing_payroll extends BaseController
{
    public function index()
    {
        $session = session();
        $clients = $session->get('userClients');

        $data['actView'] = 'Transaction/clossing_payroll';
        $data['clients'] = $clients;
        return view('home', $data);
    }

    public function getDataClossing()
    {
        $clientName = $this->request->getGet('clientName');

        $model = new ProcessClossing();
        // var_dump($model);
        // exit();
        // Query dasar
        $query = $model->orderBy('closing_id', 'ASC');

        if ($clientName !== 'All') {
            $query->where('client_name', $clientName);
        }

        $results = $query->findAll();

        $dataClossing = [];

        foreach ($results as $result) {

            $status = $result['is_active'] == 0 ? 'Open' : 'Close';
            $action = $result['is_active'] == 0 ? 'Clossing' : 'Open';

            $button = "<button class='btn btn-warning btn-sm btnClossingData' type='button' 
            data-client='" . htmlspecialchars($result['client_name'], ENT_QUOTES, 'UTF-8') . "' 
            data-tahun='" . htmlspecialchars($result['close_year'], ENT_QUOTES, 'UTF-8') . "' 
            data-bulan='" . htmlspecialchars($result['close_month'], ENT_QUOTES, 'UTF-8') . "'>
            " . $action . "
           </button>";


            $dataClossing[] = [
                $result['client_name'],
                $result['close_year'],
                $result['close_month'],
                $status,
                $button
            ];
        }

        return ResponseFormatter::success($dataClossing, "Data successfully retrieved");
    }

    public function insertClossing()
    {
        $request = service('request'); // Ambil instance request
        $validation = \Config\Services::validation();


        // Validasi input (asumsikan sudah ada rules di app/Config/Validation.php)
        $rules = [
            'client_name' => 'required',
            'yearPeriod'  => 'required|integer',
            'monthPeriod' => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => $validation->getErrors()
            ]);
        }

        $client_name = $request->getPost('client_name');
        $yearPeriod  = $request->getPost('yearPeriod');
        $monthPeriod = $request->getPost('monthPeriod');
        $is_active   = 1;

        $processClossingModel = new ProcessClossing();

        // Cek data existing
        $existing = $processClossingModel
            ->where('client_name', $client_name)
            ->where('close_year', $yearPeriod)
            ->where('close_month', $monthPeriod)
            ->countAllResults();

        if ($existing > 0) {
            return $this->response->setStatusCode(409)->setJSON([
                'error' => 'Data Closing Sudah Ada.'
            ]);
        }

        // Insert data baru
        $processClossingModel->insert([
            'client_name' => $client_name,
            'close_year'  => $yearPeriod,
            'close_month' => $monthPeriod,
            'is_active'   => $is_active,
        ]);


        return $this->response->setJSON([
            'message' => 'Data has been closed successfully.'
        ]);
    }


    public function updateClosingPayroll()
    {
        if ($this->request->isAJAX()) {
            $clientName = $this->request->getPost('clientName');
            $yearPeriod = $this->request->getPost('yearPeriod');
            $monthPeriod = $this->request->getPost('monthPeriod');

            $model = new ProcessClossing();

            // ambil data existing
            $data = $model->where('client_name', $clientName)
                ->where('close_year', $yearPeriod)
                ->where('close_month', $monthPeriod)
                ->first();

            if ($data) {
                $newStatus = $data['is_active'] == 0 ? 1 : 0;

                $model->update($data['closing_id'], [
                    'is_active' => $newStatus
                ]);

                return $this->response->setJSON(['status' => 'success']);
            }

            return $this->response->setJSON(['status' => 'error', 'message' => 'Data not found']);
        }
    }
}
