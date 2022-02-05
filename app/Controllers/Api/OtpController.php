<?php
namespace App\Controllers\Api;

use App\Models\Api\Otp;

class OtpController extends BaseController
{

	public function send()
	{
        if ($this->request->getMethod() != 'post') {
			return $this->respond(['status'=>0,'message'=>'No Data']);
		}

		$this->validation->setRule('phone', 'Phone', 'required');
		if (!$this->validation->withRequest($this->request)->run()) {
			return $this->respond(['status'=>0,'message'=>getFirstError($this->validation->getErrors())]);
		}
        $result=(new Otp())->sendOtp($this->request->getPost('phone'),$this->request->getPost('key'));
        if($result['status']){
            return $this->respond(['status'=>1,'message'=>'Otp sent successfully']);
        }else{
            return $this->respond($result);
        }
	}


	public function verify()
	{
        if ($this->request->getMethod() != 'post') {
			return $this->respond(['status'=>0,'message'=>'No Data']);
		}

		$this->validation->setRule('phone', 'Phone', 'required');
		$this->validation->setRule('otp', 'Otp', 'required');
		if (!$this->validation->withRequest($this->request)->run()) {
			return $this->respond(['status'=>0,'message'=>getFirstError($this->validation->getErrors())]);
		}

        return $this->respond((new Otp())->verifyOtp($this->request->getPost('phone'),$this->request->getPost('otp')));
	}
	
	public function test()
	{
		//echo sendOtpSMS($this->request->getGet('to'),rand(1111,9999),rand(111111,999999));
		exit;
	}

}
