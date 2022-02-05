<?php namespace App\Models\Api;

use CodeIgniter\Model;

class User extends Model
{
    protected $table         = 'user';
    protected $allowedFields = [
        'name','email', 'phone', 'password','password_reset_token','auth_token','created_at','updated_at'
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