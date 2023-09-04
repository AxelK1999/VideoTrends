<?php

namespace App\Models;

use CodeIgniter\Model;

class Library extends Model
{

   public function testFindAllMovie() : array {

        $query = $this->db->query("select * from library");
        return $query->getResult();
        
    }

    public function getIdMovies(int $idUser) : array {

        $query = $this->db->table('library')
                ->select('idmovie')
                ->where('iduser', $idUser)
                ->get();

        if ($query->getNumRows() > 0) {
            return $query->getResult(); // Retorna el resultado como un array de objetos
        }
            
        return []; // Retorna un array vacío si no hay resultados

    }

    public function existenceOfCombination(int $idMovie, int $idUser) : int{
        $query = $this->db->table('library')
                ->select('iduser')
                ->where('idmovie', $idMovie)
                ->where('iduser', $idUser)
                ->get();

        if ($query->getNumRows() > 0) {
            return 1;
        }

        return 0;

      /*   $query = $this->db->query("select iduser from library where idmovie=".$idMovie." and iduser=".$idUser);

        if( count($query->getResult()) > 0 ){
            return 1;
        }

        return 0; */
        
        //log_message("info","movie en ususario : ".$query->getResult()[0]->iduser);
    }

    protected $table      = 'library';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true; //Id de registro(en tabla) auto incremental ante insercion

    protected $returnType     = 'array';
    //protected $useSoftDeletes = true;

    protected $allowedFields = ['idmovie', 'iduser', 'id'];

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