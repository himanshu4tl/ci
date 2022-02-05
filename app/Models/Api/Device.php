<?php namespace App\Models\Api;

use CodeIgniter\Model;

class Device extends Model
{
    protected $table         = 'device';
    protected $allowedFields = [
        'device_id','type', 'user_id','auth_token', 'ip','ip_info','client_data','created_at','updated_at'
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