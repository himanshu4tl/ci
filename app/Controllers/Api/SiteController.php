<?php
namespace App\Controllers\Api;

use App\Models\Api\User;
use App\Models\Api\Otp;

class SiteController extends BaseController
{

	public function index()
	{
		return $this->render->partial('api/site/index');
	}

	public function login()
	{
        if ($this->request->getMethod() != 'post') {
			return $this->respond(['status'=>0,'message'=>'No Data']);
		}

		$this->validation->setRule('phone', 'Phone', 'required');
		$this->validation->setRule('password', 'Password', 'required');
		$this->validation->setRule('device_id', 'Device id', 'required');
		$this->validation->setRule('device_type', 'Device type', 'required');
		if (!$this->validation->withRequest($this->request)->run()) {
			return $this->respond(['status'=>0,'message'=>getFirstError($this->validation->getErrors())]);
		}

		return $this->respond($this->auth->login($this->request->getPost()));
	}

	public function register()
	{
        if ($this->request->getMethod() != 'post') {
			return $this->respond(['status'=>0,'message'=>'No Data']);
		}

		$this->validation->setRule('phone', 'Phone', 'required');
		$this->validation->setRule('email', 'Email', 'valid_email');
		$this->validation->setRule('password', 'Password', 'required');
		$this->validation->setRule('device_id', 'Device id', 'required');
		$this->validation->setRule('device_type', 'Device type', 'required');
		$this->validation->setRule('verification_token', 'Verification Token', 'required');
		if (!$this->validation->withRequest($this->request)->run()) {
			return $this->respond(['status'=>0,'message'=>getFirstError($this->validation->getErrors())]);
		}

		if(!(new Otp())->checkToken($this->request->getPost('phone'),$this->request->getPost('verification_token'))){
			return $this->respond(['status'=>0,'message'=>'Phone is not verified']);
		}

		return $this->respond($this->auth->register($this->request->getPost()));
	}

	public function forgot_password()
	{
        if ($this->request->getMethod() != 'post') {
			return $this->respond(['status'=>0,'message'=>'No Data']);
		}
		$this->validation->setRule('phone', 'Phone', 'required');
		if (!$this->validation->withRequest($this->request)->run()) {
			return $this->respond(['status'=>0,'message'=>getFirstError($this->validation->getErrors())]);
		}

		$phone=$this->request->getPost('phone');
        $key=$this->request->getPost('key');
		$userModel=new User();
		$userData=$userModel->getOne(['phone'=>$phone,'status'=>1]);
		if(!empty($userData)){
			$result=(new Otp())->sendOtp($userData['phone'],$key);
			if($result['status']){
				$userModel->update($userData['id'],['password_reset_token'=>$result['token']]);
				return $this->respond(['status'=>1,'message'=>'Otp sent successfully']);
			}else{
				return $this->respond($result);
			}
		}else{
			return $this->respond(['status'=>0,'message'=>'Phone is not valid']);
		}
	}

	public function reset_password()
	{
        if ($this->request->getMethod() != 'post') {
			return $this->respond(['status'=>0,'message'=>'No Data']);
		}

		$this->validation->setRule('phone', 'Phone', 'required');
		$this->validation->setRule('verification_token', 'Verification Token', 'required');
		$this->validation->setRule('password', 'Password', 'required');
		if (!$this->validation->withRequest($this->request)->run()) {
			return $this->respond(['status'=>0,'message'=>getFirstError($this->validation->getErrors())]);
		}
		$verification_token=$this->request->getPost('verification_token');
		if(!(new Otp())->checkToken($this->request->getPost('phone'),$verification_token)){
			return $this->respond(['status'=>0,'message'=>'Verification fail']);
		}
		
		$phone=$this->request->getPost('phone');
		$userModel=new User();
		$userData=$userModel->getOne(['password_reset_token'=>$verification_token]);
		if(!empty($userData)){
			$userModel->update($userData['id'],['password_reset_token'=>'','password'=>$this->auth->encryptPassword($this->request->getPost('password'))]);
			return $this->respond(['status'=>1,'message'=>'Password reset successfull']);
		}else{
			return $this->respond(['status'=>0,'message'=>'Verification failed']);
		}
	}

	public function page()
	{
		$page=$this->request->uri->getSegment(4);
		$page=(new \App\Models\Api\Page())->getOne(['slug'=>$page]);
		$this->data['title'] = $page['title'];
		$this->data['body'] = $page['body'];
		return $this->render->partial('api/site/page',$this->data);
	}

}
