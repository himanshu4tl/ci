<?php namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    protected $table         = 'user';
    protected $allowedFields = [
        'name','email','phone','password','password_reset_token','auth_token','email_verified','login_attempt','login_attempt_time','status','created_at','updated_at'
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