<?php
class Controller{
    protected $table, $db, $mng, $campo, $filter, $nrpp, $order, $page, $op;
    public function __construct(){
        $this->session = new Session();
        $this->db = new DataBase();
        $this->table = Request::req('table')!=null ? Request::req('table') : 'user';
        $this->mng = $this->getManager($this->table);        
        $this->nrpp = (Request::req('nrpp')===null || Request::req('nrpp')=='') ? Constant::_NRPP : Request::req('nrpp');
        $this->order = (Request::req('order')===null || Request::req('order')=="") ? '11' : Request::req('order');
        $this->page = (Request::req('page')===null || Request::req('page')=="") ? '1' : Request::req('page');
        $this->op = (Request::req('op')===null || Request::req('op')=="") ? 'view' : Request::req('op');
        $this->campo = Request::req('campo');
        $this->filter = Request::req('filter');
        $this->arrayWhere =[];
        if($this->filter!=null && $this->filter!=""){
            $this->arrayWhere[$this->campo] = "%".$this->filter."%";
        }
    }    
    function getManager($table){
        $manager = "Manage".ucfirst(strtolower($table));
        if(class_exists($manager)){
            return new $manager($this->db);
        }
        return new ManagePOJO($this->db, $this->table);        
    }    
    function getObject($table){
        $object = ucfirst(strtolower($table));
        if(class_exists($object)){
            return new $object();
        }
        return new POJO($this->table);
    }    
    function checkUser($table="", $op=""){
        if(!$this->session->isLogged()){
            echo '{ "loggedIn" : -1 }';
            exit();
        }
    }    
    function load(){
        $method = $this->op;
        $controller = ucfirst(strtolower($this->table)).'Controller';
        if(class_exists($controller)){
            $ctl = new $controller();
            if(method_exists($ctl, $method)){
                $ctl->$method();
            }else{
                $this->render();
            }
        }else{
            if(method_exists($this, $method)){
                $this->$method();
            }else{
                $this->render();
            }
        }
    }    
    function read(){
        $pages = $this->mng->getNumReg($this->arrayWhere);
        echo '{ "resultset" : ' . $this->mng->getListJSON($this->page, $this->nrpp, $this->order, $this->arrayWhere) . ', "pages" : '.$pages.'}';
    }
    function get(){
        $pkid = Request::req('pkid');
        $obj = $this->mng->get(Settings::getTablePK($this->table), $pkid);
        if($obj != null){
            echo '{ "result" : 1 , "resultset" : '.$obj->toJSON().' }';
        }else{
            echo '{ "result" : -1 }';
        }
    }    
    function insert(){
        $this->checkUser();
        $object = $this->getObject($this->table);
        $object->read();
        $r = $this->mng->insert($object);
        echo '{ "result" : ' . $r . ', "obj" : '.$object->toJSON().', "error" : '.  json_encode($this->db->getQueryError()).' }';
    }    
    function set(){
        $this->checkUser();
        $object = $this->getObject($this->table);
        $object->read();
        $r = $this->mng->set($object, Settings::getTablePK($this->table), Request::req('pkid'));
        echo '{ "result" : ' . $r . ' }';
    }    
    function delete(){
        $this->checkUser();
        $r = $this->mng->delete(Settings::getTablePK($this->table), Request::req('pkid'));
        echo '{ "result" : "' . $r .'"}';
    }
    function uploadFile(){
        $this->checkUser();
        $file = $_FILES['file'];
        $dir = Request::req('dir');        
        /*$up = new UploadFile($file);
        if($dir!=null){
            $up->setDestination('img/'.$dir.'/');
        }
        $r = $up->getError_message();
        echo '{"result" : '.$r.', "filename" : "'.$up->getDestination().'/'.$up->getName().'.'.$up->getExt().'"}';*/
        $r = move_uploaded_file($file['tmp_name'], './img/'.$dir.'/'.$file['name']);
        echo '{"result" : "'.$r.'"}';
    }    
    function render(){
        $head = new View('tpl/head.tpl');
        $scripts = new View('tpl/scripts.tpl');
        $data = [];
        $data['head'] = $head->render();
        $data['scripts'] = $scripts->render();
        $view = new View('tpl/dashboard.tpl', $data);
        echo $view->render();
    }
    
    function login(){
        $login = Request::req('login');
        $pass = Request::req('pass');
        $mng = new ManagePOJO($this->db, 'user');
        $user = $mng->get('login', $login);
        if($user->getPass()===sha1($pass) && $user->getDisabled()==='0'){
            $this->session->set('user', $user);            
            echo '{"result":"ok", "user": '.$user->toJSON().', "modules": '.  json_encode(Settings::$modules).'}';
        }else{
            echo '{"result":"error"}';
        }
    }    
    function logout(){
        $this->session->destroy();
        echo '{"result":"ok"}';
    }
    function checksession(){
        $user = $this->session->get('user');
        if($user!=null){          
            echo '{"result":"ok", "user": '.$user->toJSON().', "modules": '.  json_encode(Settings::$modules).'}';
        }else{
            echo '{"result":"error"}';
        }
    }
}
