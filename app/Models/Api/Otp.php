<?php namespace App\Models\Api;

use CodeIgniter\Model;

class Otp extends Model
{
    protected $table         = 'otp';
    protected $allowedFields = [
        'phone','otp','token','created_at','updated_at'
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

    public function checkToken($phone,$token){
        $otpData=$this->getOne(['phone'=>$phone]);
        if(!empty($otpData) && $otpData['token']==$token){
            $this->update($otpData['id'],['otp'=>'','token'=>'']);
            return true;
        }else{
            return false;
        }
    }

    public function verifyOtp($phone,$otp){
        $otpData=$this->getOne(['phone'=>$phone]);
        if(!empty($otpData) && $otpData['otp']==$otp){
            if($otpData['updated_at']<(time()-(10*60))){
                return ['status'=>0,'message'=>'Otp is expired'];
            }
            return ['status'=>1,'message'=>'Otp is valid','token'=>$otpData['token']];
        }else{
            return ['status'=>0,'message'=>'Otp is not valid'];
        }
    }
    public function sendOtp($phone,$key){
        helper('text');
        $updateData=[
            'otp'=>rand(0000,9999),
            'token'=>random_string('alnum', 32)
        ];
        $otpData=$this->getOne(['phone'=>$phone]);
        if(!empty($otpData)){
            $this->update($otpData['id'],$updateData);
            $updateData['phone']=$phone;
        }else{
            $updateData['phone']=$phone;
            $this->insert($updateData);
        }
        sendOtpSMS($updateData['phone'],$updateData['otp'],$key);
        return ['status'=>1,'message'=>'Otp sent successfully','token'=>$updateData['token']];
    }

}