<?php namespace App\Models\Api;

use CodeIgniter\Model;

class Page extends Model
{
    protected $table         = 'page';
    protected $allowedFields = [
        'title','body'
    ];
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $dateFormat='int';
    protected $useTimestamps=false;

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