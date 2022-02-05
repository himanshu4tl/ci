<?php namespace App\Controllers;

use App\Models\Note;

class NoteController extends BaseController
{
    public function index() {
        if ($this->auth->isGuest()) {
            return $this->response->redirect(base_url(LOGIN_URL));
        }
        
        $this->data['title']='Note';
        return $this->render->view('note/index',$this->data);
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
        $query['query']="from note";
        $query['queryParams']=[];
        /**/
        $result['searchText']=isset($_GET['search']['value']) ? $_GET['search']['value']: '';
        if(strlen($result['searchText'])>2){
            $result['searchText']='%'.$result['searchText'].'%';
            $query['queryParams'][]=$result['searchText'];
            $query['query'].=" where (title like ? )";
        }
        /**/
        $result=getPagination($query);
        foreach($result['data'] as $key=>$row){
            $result['data'][$key]['action']='<a target="_blank" href="note/view?id='.$row['id'].'" class="btn btn-info" title="View"><i class="fa fa-eye"></i></a>&nbsp;<a href="note/update?id='.$row['id'].'" class="btn btn-info" title="Update"><i class="fa fa-edit"></i></a>&nbsp;<button onclick="datatableDeleteAction(this);" data-action="note/delete" data-id="'.$row['id'].'" class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></button>';
        }

        return $this->respond($result);
    }

    public function view(){
        if ($this->auth->isGuest()) {
            return $this->response->redirect(base_url(LOGIN_URL));
        }
        $id=$this->request->getGet('id');
        $noteModel=new Note();
        $this->data['data']=$noteModel->getOne(['id'=>$id,'user_id'=>$this->auth->id()]);
        if(empty($this->data['data'])){
            $this->session->setFlashData('error', 'Note not found');
            return $this->response->redirect('index');
        }
        $this->data['title']='Note View';
        return $this->render->view('note/view',$this->data);
    }

    public function create(){
        if ($this->auth->isGuest()) {
            return $this->response->redirect(base_url(LOGIN_URL));
        }
        $this->data['title']='Note Create';
        return $this->render->view('note/create',$this->data);
    }

    public function update(){
        if ($this->auth->isGuest()) {
            return $this->response->redirect(base_url(ADMIN_LOGIN_URL));
        }
        $id=$this->request->getGet('id');
        $noteModel=new Note();
        $this->data['data']=$noteModel->getOne(['id'=>$id]);
        if(empty($this->data['data'])){
            $this->session->setFlashData('error', 'Note not found');
        }
        $this->data['title']='Note Update';
        return $this->render->view('note/update',$this->data);
    }

    public function save(){
        if ($this->auth->isGuest()) {
            return $this->respond(['status'=>0,'message'=>'You are not authorized']);
        }
       
        if ($this->request->getMethod()== 'post') {
            $this->validation->setRule('title', 'Title', 'required');
            $this->validation->setRule('note', 'Note', 'required');
            $this->validation->setRule('date', 'Date', 'required');
            if ($this->validation->withRequest($this->request)->run()) {
                $noteData=[
                    'user_id'=>$this->auth->id(),
                    'title'=>$this->request->getPost('title'),
                    'date'=>$this->request->getPost('date'),
                    'note'=>$this->request->getPost('note'),
                ]; 
                $noteModel=new Note();
                $id=$this->request->getPost('id');
                if($id){
                    $noteModel->update($id,$noteData);
                    return $this->respond(['status'=>1,'message'=>'Note updated successfully.']);
                }else{
                    $noteData['slug']=url_title($this->request->getPost('title'), '-', TRUE);
                    $noteModel->insert($noteData);
                    return $this->respond(['status'=>1,'message'=>'Note created successfully.']);
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
            $user=new Note();
            $user->delete(['id'=>$id]);
            return $this->respond(['status'=>1,'message'=>'Note removed successfully.']);
        }else{
            return $this->respond(['status'=>0,'message'=>getFirstError($this->validation->getErrors())]);
        }                   
    }

}
