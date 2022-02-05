<?php namespace App\Controllers\Admin;

use App\Models\Admin\User;

class UserController extends BaseController
{
    public function index() {
        if ($this->auth->isGuest()) {
            return $this->response->redirect(base_url(ADMIN_LOGIN_URL));
        }
        $this->data['title']='Users';
        return $this->render->view('user/index',$this->data);
    }

    public function list() {
        if ($this->auth->isGuest()) {
            return $this->respond(['status'=>0,'message'=>'You are not authorized.']);
        }
        helper('pagination');
        $id=$this->auth->id();
        $result=[];
        $query=[];
        $query['select']="*";
        $query['query']="from user";
        $query['queryParams']=[];
        /**/
        $result['searchText']=isset($_GET['search']['value']) ? $_GET['search']['value']: '';
        if(strlen($result['searchText'])>2){
            $result['searchText']='%'.$result['searchText'].'%';
            $query['queryParams'][]=$result['searchText'];
            $query['queryParams'][]=$result['searchText'];
            $query['queryParams'][]=$result['searchText'];
            $query['query'].=" where (name like ? "
                . "or email like ?  "
                . "or phone like ? )";
        }
        /**/
        $result=getPagination($query);
        foreach($result['data'] as $key=>$row){
            $result['data'][$key]['status']=$row['status']?'Active':'Inactive';
            $result['data'][$key]['created_at']=date(DATETIME_FORMAT,$row['created_at']);
            $result['data'][$key]['action']='<a href="'.ADMIN_DIR.'user/view?id='.$row['id'].'" class="btn btn-info" title="View"><i class="fa fa-eye"></i></a>&nbsp;<a href="'.ADMIN_DIR.'user/update?id='.$row['id'].'" class="btn btn-info" title="Update"><i class="fa fa-edit"></i></a>&nbsp;<button onclick="datatableDeleteAction(this);" data-action="'.ADMIN_DIR.'user/delete" data-id="'.$row['id'].'" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></button>';
        }
        return $this->respond($result);
    }

    public function view(){
        if ($this->auth->isGuest()) {
            return $this->response->redirect(base_url(ADMIN_LOGIN_URL));
        }
        
        $id=$this->request->getGet('id');
        $userModel=new User();
        $this->data['data']=$userModel->getOne(['id'=>$id]);
        if(empty($this->data['data'])){
            $this->session->setFlashData('error', 'User not found');
            return $this->response->redirect('index');
        }
        $this->data['title']='User View';
        return $this->render->view('user/view',$this->data);
    }

    public function create(){
        if ($this->auth->isGuest()) {
            return $this->response->redirect(base_url(ADMIN_LOGIN_URL));
        }
        $this->data['title']='User Create';
        return $this->render->view('user/create',$this->data);
    }

    public function update(){
        if ($this->auth->isGuest()) {
            return $this->response->redirect(base_url(ADMIN_LOGIN_URL));
        }
        $id=$this->request->getGet('id');
        $userModel=new User();
        $this->data['data']=$userModel->getOne(['id'=>$id]);
        if(empty($this->data['data'])){
            $this->session->setFlashData('error', 'User not found');
            return $this->response->redirect('index');
        }
        $this->data['title']='User Update';
        return $this->render->view('user/update',$this->data);
    }

    public function save(){
        if ($this->auth->isGuest()) {
            return $this->respond(['status'=>0,'message'=>'You are not authorized.']);
        }
        $userModel=new User();
        if ($this->request->getMethod()== 'post') {
            $this->validation->setRule('name', 'Name', 'required'); 
            $id=$this->request->getPost('id');
            $user=$userModel->getOne(['id'=>$id]);

            $userData = [
                'name' => $this->request->getPost('name'),
                'phone' => $this->request->getPost('phone'),
                'email' => $this->request->getPost('email'),
            ];
            $password=$this->request->getPost('password');
            if($id) {
                if(empty($user)){
                    return $this->respond(['status'=>0,'message'=>'User not found']);
                }
                if($user['email']!==$this->request->getPost('email')){
                    $this->validation->setRule('email', 'Email', 'required|valid_email|is_unique[user.email]');
                }
                if($user['phone']!==$this->request->getPost('phone')){
                    $this->validation->setRule('phone', 'Phone', 'required|numeric|is_unique[user.phone]');
                }
                if($password){
                    $userData['password'] =$this->auth->encryptPassword($password);
                }
            }else{
                $this->validation->setRule('password', 'Password', 'required');
                $this->validation->setRule('email', 'Email', 'required|valid_email|is_unique[user.email]');
                $this->validation->setRule('phone', 'Phone', 'required|numeric|is_unique[user.phone]');
                $userData['password'] =$this->auth->encryptPassword($password);
            }

            if ($this->validation->withRequest($this->request)->run()) {
              

                $userModel=new User();
                $id=$this->request->getPost('id');
                if($id){
                    $userModel->update($id,$userData);                 
                    return $this->respond(['status'=>1,'message'=>'User updated successfully.']);
                }else{
                    helper('text');
                    $userData['auth_token']=random_string('alnum', 64);
                    $userModel->insert($userData);
                    return $this->respond(['status'=>1,'message'=>'User created successfully.']);
                }
            }else{
                return $this->respond(['status'=>0,'message'=>getFirstError($this->validation->getErrors())]);
            }
        }
    }

    public function delete(){
        if ($this->auth->isGuest()) {
            return $this->respond(['status'=>0,'message'=>'You are not authorized.']);
        }
        $this->validation->setRule('id', 'Id', 'required');
        if ($this->validation->withRequest($this->request)->run()) {
            $id=$this->request->getPost('id');
            $user=new User();
            $user->delete(['id'=>$id]);
            return $this->respond(['status'=>1,'message'=>'User removed successfully.']);
        }else{
            return $this->respond(['status'=>0,'message'=>getFirstError($this->validation->getErrors())]);
        }                   
    }
    public function profile()
    {
        if ($this->auth->isGuest()) {
            return $this->response->redirect(base_url(ADMIN_LOGIN_URL));
        }
        $this->data['data'] = $this->auth->identity();
        if ($this->request->getMethod() == 'post') {
            
            $this->validation->setRule('name', ' Name', 'trim|required');
            if ($this->data['data'] != $this->request->getPost('email')) {
                $this->validation->setRule('email', 'Email', 'trim|required[user.email]');
            }
            if ($this->validation->withRequest($this->request)->run()) {
                $userData = [
                    'name' => $this->request->getPost('name'),
                    'email' => $this->request->getPost('email'),
                ];
                $user = new User();
                $user->update($this->auth->id(), $userData);
                $this->auth->session->setFlashData('success', 'Profile updated successfully.');
            } else {
                $this->auth->session->setFlashData('error', getFirstError($this->validation->getErrors()));
            }
        }
        $this->data['title'] = 'Profile';
        return $this->render->view('site/profile', $this->data);
    }
    
    public function change_status(){
        if ($this->auth->isGuest()) {
            return $this->respond(['status'=>0,'message'=>'You are not authorized.']);
        }
        $this->validation->setRule('id', 'Id', 'required');
        if ($this->validation->withRequest($this->request)->run()) {
            $id=$this->request->getPost('id');
            $userModel=new User();
            $userData=$userModel->getOne(['id'=>$id]);
            $userModel->update($id,['status'=>$userData['status']?0:1]);
            return $this->respond(['status'=>1,'message'=>'User status updated successfully.']);
        }else{
            return $this->respond(['status'=>0,'message'=>getFirstError($this->validation->getErrors())]);
        }                   
    }

}
