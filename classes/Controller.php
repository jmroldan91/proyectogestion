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
    function checkUser($table, $op){
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
        $obj = $this->mng->get(Settings::$tablePks[$this->table], $pkid);
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
        echo '{ "result" : ' . $r . ', "obj" : '.$object->toJSON().' }';
    }    
    function set(){
        $this->checkUser();
        $object = $this->getObject($this->table);
        $object->read();
        $r = $this->mng->set($object, Settings::$tablePks[$this->table], Request::req('pkid'));
        echo '{ "result" : ' . $r . ' }';
    }    
    function delete(){
        $this->checkUser();
        $r = $this->mng->delete(Settings::$tablePks[$this->table], Request::req('pkid'));
        echo '{ "result" : "' . $r .'"}';
    }
    function uploadFile(){
        $this->checkUser();
        $file = $_FILES['image'];
        $up = new UploadFile($file);
        $r = $up->upload()->getError_message();
        echo '{"result" : '.$r.', "filename" : "'.$up->getName().'.'.$up->getExt().'"}';
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
}
