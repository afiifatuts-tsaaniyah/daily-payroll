<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use \App\Models\Admin\UserModel;
use Config\Database;

class Access_client extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $data['users'] = $userModel->findAll(); // ambil list user

        $data['actView'] = 'Admin/list_user_access_client';
        return view('home', $data);
    }

    public function detail($userId)
    {
        $db = Database::connect();

        $user = $db->table('mst_user')
            ->where('user_id', $userId)
            ->get()->getRow();

        // Query client + access (left join)
        $clients = $db->table('mt_client c')
            ->select('c.client_id, c.client_name, uc.user_id AS has_access')
            ->join('trn_user_client uc', "uc.client_id = c.client_id AND uc.user_id = '$userId'", 'left')
            ->where('c.is_active', 1)
            ->orderBy('c.client_name', 'ASC')
            ->get()->getResult();

        $data = [
            'userId'  => $userId,
            'username' => $user->user_name,
            'clients'  => $clients
        ];
        $data['actView'] = 'Admin/access_client';

        return view('home', $data);
    }

    public function save_access_client()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Invalid request'
            ]);
        }

        $db = Database::connect();

        $userId    = $this->request->getPost('user_id');
        $clientIds = $this->request->getPost('client_id'); // array checkbox

        if (empty($userId)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'User ID tidak ditemukan'
            ]);
        }

        // Hapus akses lama
        $db->table('trn_user_client')->where('user_id', $userId)->delete();

        // Insert baru jika ada pilihan
        if (!empty($clientIds)) {
            foreach ($clientIds as $cid) {
                $db->table('trn_user_client')->insert([
                    'user_id'   => $userId,
                    'client_id' => $cid
                ]);
            }
        }

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Akses client berhasil diperbarui'
        ]);
    }
}
