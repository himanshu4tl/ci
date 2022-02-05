<?php

namespace App\Controllers;

use App\Models\Page;

class FrontController extends BaseController
{
	public function index()
	{
		$data = [];
		$this->render->layout='front';
		$data['title'] = 'Home';
		return $this->render->view('front/index',$data);
	}

	public function page()
	{
		$page=$this->request->uri->getSegment(1);
		$this->render->layout='front';
		$page=(new Page())->getOne(['slug'=>$page]);
		$this->data['title'] = $page['title'];
		$this->data['body'] = $page['body'];
		return $this->render->view('front/page',$this->data);
	}
}