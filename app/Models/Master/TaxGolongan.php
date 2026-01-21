<?php

namespace App\Models\Master;

use CodeIgniter\Model;

class TaxGolongan extends Model
{
    protected $table = 'mst_tax_golongan';
    protected $primaryKey = 'id'; // sesuaikan dengan PK kamu
    protected $returnType = 'array';

    /**
     * Ambil data tarif pajak berdasarkan brutto dan status marital
     */
    public function getDataTer($brutto, $status)
    {
        $db = \Config\Database::connect();

        $sql = "
        SELECT 
            mst_tax_golongan.marital AS status,
            mst_tax_config.tarif,
            mst_tax_golongan.golongan
        FROM mst_tax_golongan
        JOIN mst_tax_config 
            ON mst_tax_golongan.golongan = mst_tax_config.golongan
        WHERE 
            mst_tax_golongan.marital = ?
            AND ? BETWEEN mst_tax_config.start_brutto AND mst_tax_config.end_brutto
        LIMIT 1
    ";

        $query = $db->query($sql, [$status, $brutto]);
        $result = $query->getRowArray();

        return $result;
    }


    /**
     * Ambil golongan berdasarkan status marital
     */
    public function getGolonganByMarital($status)
    {
        return $this->select('golongan')
            ->where('marital', $status)
            ->first();
    }
}
