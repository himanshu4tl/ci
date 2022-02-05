<?php namespace App\Controllers\Admin;

use App\Models\Admin\Page;

class PageController extends BaseController
{
    public function index() {
        if ($this->auth->isGuest()) {
            return $this->response->redirect(base_url(ADMIN_LOGIN_URL));
        }
        
        $this->data['title']='Page';
        return $this->render->view('page/index',$this->data);
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
        $query['query']="from page";
        $query['queryParams']=[];
        /**/
        $result['searchText']=isset($_GET['search']['value']) ? $_GET['search']['value']: '';
        if(strlen($result['searchText'])>2){
            $result['searchText']='%'.$result['searchText'].'%';
            $query['queryParams'][]=$result['searchText'];
            $query['query'].=" where (title like ?)";
        }
        /**/
        $result=getPagination($query);
        foreach($result['data'] as $key=>$row){
            $result['data'][$key]['action']='<a target="_blank" href="'.$row['slug'].'" class="btn btn-info" title="View"><i class="fa fa-eye"></i></a>&nbsp;<a href="'.ADMIN_DIR.'page/update?id='.$row['id'].'" class="btn btn-info" title="Update"><i class="fa fa-edit"></i></a>&nbsp;<button onclick="datatableDeleteAction(this);" data-action="'.ADMIN_DIR.'page/delete" data-id="'.$row['id'].'" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></button>';
        }

        return $this->respond($result);
    }

    public function create(){
        if ($this->auth->isGuest()) {
            return $this->response->redirect(base_url(ADMIN_LOGIN_URL));
        }
        $this->data['title']='Page Create';
        return $this->render->view('page/create',$this->data);
    }

    public function update(){
        if ($this->auth->isGuest()) {
            return $this->response->redirect(base_url(ADMIN_LOGIN_URL));
        }
        $id=$this->request->getGet('id');
        $pageModel=new Page();
        $this->data['data']=$pageModel->getOne(['id'=>$id]);
        if(empty($this->data['data'])){
            $this->session->setFlashData('error', 'Page not found');

        }      
        $this->data['title']='Page Update';
        return $this->render->view('page/update',$this->data);
    }
    public function save(){
        if ($this->auth->isGuest()) {
            return $this->respond(['status'=>0,'message'=>'You are not authorized']);
        }
       
        if ($this->request->getMethod()== 'post') {
            $this->validation->setRule('title', 'Title', 'required');
            $this->validation->setRule('body', 'Body', 'required');
            if ($this->validation->withRequest($this->request)->run()) {
                $pageData=[
                    // 'type'=>$this->request->getPost('type'),
                    'slug'=>url_title($this->request->getPost('title'), '-', TRUE),
                    'title'=>$this->request->getPost('title'),
                    'body'=>$this->request->getPost('body'),
                ]; 
                $pageModel=new Page();
                $id=$this->request->getPost('id');
                if($id){
                    $pageModel->update($id,$pageData);
                    return $this->respond(['status'=>1,'message'=>'Page updated successfully.']);
                }else{
                    $pageData['slug']=url_title($this->request->getPost('title'), '-', TRUE);
                    $pageModel->insert($pageData);
                    return $this->respond(['status'=>1,'message'=>'Page created successfully.']);
                }
            }else{
                return $this->respond(['status'=>0,'message'=>getFirstError($this->validation->getErrors())]);
            }
        }
    }

    public function delete(){
        if ($this->auth->isGuest()) {
            return $this->respond(['status'=>0,'message'=>'You are not authorized']);
        }
        $this->validation->setRule('id', 'Id', 'required');
        if ($this->validation->withRequest($this->request)->run()) {
            $id=$this->request->getPost('id');
            $user=new Page();
            $user->delete(['id'=>$id]);
            return $this->respond(['status'=>1,'message'=>'Page removed successfully.']);
        }else{
            return $this->respond(['status'=>0,'message'=>getFirstError($this->validation->getErrors())]);
        }                   
    }

}
