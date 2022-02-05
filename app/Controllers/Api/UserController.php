<?php
namespace App\Controllers\Api;

use App\Models\Api\User;

class UserController extends BaseController
{

	public function profile()
	{
        return $this->respond(['status'=>1,'message'=>'','data'=>$this->auth->setLoginData($this->auth->identity())]);
	}

}
