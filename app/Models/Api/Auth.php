<?php namespace App\Models\Api;

use CodeIgniter\Model;
use App\Models\Api\User;
use App\Models\Api\Device;

class Auth extends Model
{

    protected $table         = 'user';
    public $session;
    public $device=false;
    public $user=false;
    public $user_id=false;

    public function checkApiKey($apiKey){
        $skipUrls=[
            'api/invoice/pdf',
            'api/site/page/terms',
            'api/site/page/privacy',
            'api/site/page/refund',
            'api/otp/test',
            'api/site/index'
        ];
        if(!in_array(uri_string(),$skipUrls)){
            if($apiKey!=API_KEY){
                l([
                    'URL'=>uri_string(),
                    'POST'=>$_POST,
                    'RESPONSE'=>['status'=>0,'message'=>'Api key not valid']
                ]);
                echo json_encode(['status'=>0,'message'=>'Api key not valid']);exit;	
            }
        }
    }

    public function checkAuthToken($authToken){
        $skipUrls=[
            'api/site/login',
            'api/site/register',
            'api/site/forgot_password',
            'api/site/reset_password',
            'api/otp/send',
            'api/otp/verify',
            'api/otp/test',
            'api/invoice/pdf',
            'api/site/page/terms',
            'api/site/page/privacy',
            'api/site/page/refund',
            'api/site/index'
        ];
        if(!in_array(uri_string(),$skipUrls)){
            $device=new Device();
            $deviceData=$device->getOne(['auth_token'=>$authToken]);
            if(!empty($deviceData)){
                $this->device=$deviceData;
                $this->user_id=$deviceData['user_id'];
            }else{
                l([
                    'URL'=>uri_string(),
                    'POST'=>$_POST,
                    'RESPONSE'=>['status'=>0,'message'=>'You are not authorized']
                ]);
                echo json_encode(['status'=>0,'message'=>'You are not authorized']);exit;	
            }
		}
    }

    public function setLoginData($user){
        return [
            'id'=>$user['id'],
            'name'=>$user['name'],
            'phone'=>$user['phone'],
            'email'=>$user['email']
        ];
    }

    public function login($data) {
        $user=new User();
        $userData=$user->getOne(['phone'=>$data['phone'],'status'=>1]);
        if(!empty($userData) && $this->checkPassword($data['password'],$userData['password'])){
            $authToken=$this->setDevice($userData['id'],$data);
            return ['status'=>1,'message'=>'Login succcess','auth_token'=>$authToken,'data'=>$this->setLoginData($userData)];
        }else{
            return ['status'=>0,'message'=>'Phone or password is invalid'];
        }
    }

    public function register($data) {
        $user=new User();
        $userData=$user->getOne(['phone'=>$data['phone']]);
        if(!empty($userData)){
            return ['status'=>0,'message'=>'Phone is already exist'];
        }
        $userData=$user->getOne(['email'=>$data['email']]);
        if(!empty($userData)){
            return ['status'=>0,'message'=>'Email is already exist'];
        }
        helper('text');
        $userData=[
            'name'=>$data['name'],
            'phone'=>$data['phone'],
            'email'=>$data['email'],
            'password'=>$this->encryptPassword($data['password']),
            'auth_token'=>random_string('alnum', 64),
        ];
        $userId=$user->insert($userData);
        if($userId){
            $authToken=$this->setDevice($userId,$data);
            $userData=$user->getOne(['id'=>$userId]);
            return ['status'=>1,'message'=>'Registration succcessful','auth_token'=>$authToken,'data'=>$this->setLoginData($userData)];
            //TODO : send welcome mail
        }else{
            return ['status'=>0,'message'=>'Something went wrong'];
        }
    }

    public function checkPassword($password,$encryptedPassword) {
        return ($encryptedPassword==$this->encryptPassword($password));
    }

    public function encryptPassword($password) {
        return sha1($password);
    }

    public function setDevice($userId,$data) {
        helper('text');
        $auth_token=random_string('alnum', 64);
        $device=new Device();
        $deviceData=$device->getOne(['device_id'=>$data['device_id'],'type'=>$data['device_type']]);
        $updateData=[
            'user_id'=>$userId,
            'auth_token'=>$auth_token,
            'ip'=>getClientIp(),
            'client_data'=>@$_SERVER['HTTP_USER_AGENT']
        ];
        $updateData['ip_info']=getIpInfo($updateData['ip']);
        if(!empty($deviceData)){
            $device->update($deviceData['id'],$updateData);
        }else{
            $updateData['device_id']=$data['device_id'];
            $updateData['type']=$data['device_type'];
            $device->insert($updateData);
        }
        return $auth_token;
    }

    
    // public function logout() {
    //     $this->removeSession();
    // }

    // public function removeSession() {
    //     $user=new User();
    //     $user->update(['id'=>$this->id()],['auth_token'=>'']);
    //     helper('cookie');
    //     delete_cookie("society_auth_token");
    //     $this->session->set([
    //         'society_admin_id'=>'',
    //         'society_admin_role'=>'',
    //         'society_id'=>'',
    //     ]);
    // }

    public function identity() {
        if(!$this->isGuest()){
            if($this->user){
                return $this->user;
            }else{
                return $this->user=(new User())->getOne(['id'=>$this->id()]);
            }
        }
    }

    public function isGuest() {
        return $this->user_id?false:true;
    }


    public function id() {
        return $this->user_id;
    }
}
