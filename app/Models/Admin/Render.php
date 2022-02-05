<?php namespace App\Models\Admin;

use App\Models\Admin\Auth;

class Render
{
    public function __construct($auth){
        $this->auth=$auth;
    }
    public $auth;
    public $layout='main';
    function view($view,$data=[]){
        $data['auth']=$this->auth;
        return view(ADMIN_DIR.'layout/'.$this->layout,['content'=>view(ADMIN_DIR.$view,$data),$data]);
    }

    function partial($view,$data=[]){
        $data['auth']=$this->auth;
        return view($view,$data);
    }
}