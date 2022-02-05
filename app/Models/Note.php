<?php namespace App\Models;

use CodeIgniter\Model;

class Note extends Model
{
    protected $table         = 'note';
    protected $allowedFields = [
        'user_id','title','date','note','created_at','updated_at'
    ];
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $dateFormat='int';
    protected $useTimestamps=true;

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function getAll($condition){
        return $this->where($condition)->findAll();
    }

    public function getOne($condition){
        return $this->where($condition)->first();
    }

}