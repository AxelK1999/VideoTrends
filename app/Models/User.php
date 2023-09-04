<?php

namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{

   public function testFindAllUser(){

        $query = $this->db->query("select * from users");
        return $query->getResult();
        
    }

    protected $table      = 'users';
    protected $primaryKey = 'email';

    protected $useAutoIncrement = false; //Id de registro(en tabla) auto incremental ante insercion

    protected $returnType     = 'array';
    //protected $useSoftDeletes = true;

    protected $allowedFields = ['email', 'password', 'username', 'name', 'lastname', 'phonenumber', 'gender', 'birthdate', 'address','validated', 'id'];

    // Dates
    //protected $useTimestamps = false;
    //protected $dateFormat    = 'datetime';
    //protected $createdField  = 'created_at';
    //protected $updatedField  = 'updated_at';
    //protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    //protected $allowCallbacks = true;
    //protected $beforeInsert   = [];
    //protected $afterInsert    = [];
    //protected $beforeUpdate   = [];
    //protected $afterUpdate    = [];
    //protected $beforeFind     = [];
    //protected $afterFind      = [];
    //protected $beforeDelete   = [];
    //protected $afterDelete    = [];

}