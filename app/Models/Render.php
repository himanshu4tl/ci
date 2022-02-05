<?php namespace App\Models;

use App\Models\Auth;

class Render
{
    public function __construct($auth){
        $this->auth=$auth;
    }
    public $auth;
    public $layout='main';
    function view($view,$data=[]){
        $data['auth']=$this->auth;
        return view('layout/'.$this->layout,['content'=>view($view,$data),$data]);
    }

    function partial($view,$data=[]){
        $data['auth']=$this->auth;
        return view($view,$data);
    }
}