<?php

namespace App\Models\Transaction;

use CodeIgniter\Model;

class M_tax extends Model
{
    protected $table = 'mst_tax_golongan';

    public function getDataTer($brutto, $status)
    {
        $query = $this->db->table($this->table)
            ->select('mst_tax_golongan.marital AS STATUS, mst_tax_config.tarif, mst_tax_golongan.golongan')
            ->join('mst_tax_config', 'mst_tax_golongan.golongan = mst_tax_config.golongan')
            ->where("{$brutto} BETWEEN mst_tax_config.start_brutto AND mst_tax_config.end_brutto", null, false)
            ->where('mst_tax_golongan.marital', $status)
            ->get()
            ->getRow();

        return $query;
    }


}
