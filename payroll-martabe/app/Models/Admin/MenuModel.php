<?php namespace App\Models\Admin;

use CodeIgniter\Model;
class MenuModel extends Model
{
	protected $DBGroup = 'hris';
	protected $table      = 'mst_menu';
    protected $primaryKey = 'menu_id';
    protected $returnType     = 'array';
} 
