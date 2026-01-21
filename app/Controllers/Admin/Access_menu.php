<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use \App\Models\Admin\UserModel;

class Access_menu extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $data['users'] = $userModel->findAll(); // ambil list user

        $data['actView'] = 'Admin/list_user_access';
        return view('home', $data);
    }

    public function detail($userId)
    {

        $db = \Config\Database::connect();

        $builder = $db->table('mst_menu m');
        $builder->select('m.menu_id, m.menu_name, m.menu_group, tum.user_id AS has_access');
        $builder->join('trn_user_menu tum', "tum.menu_id = m.menu_id AND tum.user_id = '$userId'", 'left');
        $builder->where('m.is_active', 1);
        $builder->orderBy('m.menu_no', 'ASC');

        $menus = $builder->get()->getResult();

        $data['userId'] = $userId;
        $data['menus'] = $menus;
        $userId = session()->get('uId');
        $data['username'] = $userId;
        $data['actView'] = 'Admin/access_menu';

        return view('home', $data);
    }


    public function save_access_menu()
    {
        if ($this->request->isAJAX()) {

            $userId   = $this->request->getPost('user_id');
            $menuList = $this->request->getPost('menu_id') ?? [];

            $db = \Config\Database::connect();

            // Hapus semua akses user
            $db->table('trn_user_menu')
                ->where('user_id', $userId)
                ->delete();

            // Insert menu baru
            foreach ($menuList as $menu_id) {
                $db->table('trn_user_menu')->insert([
                    'user_id' => $userId,
                    'menu_id' => $menu_id
                ]);
            }

            return $this->response->setJSON([
                'status' => true,
                'message' => 'Access menu berhasil disimpan! Silahkan Logout dan Login lagi untuk melihat perubahan Access Menu.'
            ]);
        }

        return $this->response->setJSON([
            'status' => false,
            'message' => 'Invalid request'
        ]);
    }
}
