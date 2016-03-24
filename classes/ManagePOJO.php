<?php
class ManagePOJO {      
    protected $db = null;
    protected  $table = "";
            
    function __construct(DataBase $db, $table = "") {
        $this->db = $db;
        $this->table = $table;
    }   
    function insert(POJO $pojo){
        return $this->db->insert($this->table, $pojo->toArray(), false);
    }    
    function set(POJO $pojo, $pkname, $id){
        $params = [];
        if(is_array($pkname)){
            foreach ($pkname as $key => $value) {
                $params[$value] = $id[$key]; 
            }
        }else{
           $params[$pkname] = $id; 
        }        
        return $this->db->update($this->table, $pojo->toArray(), $params);
    }
    public function get($pkname, $id) {
        $params = [];
        if(is_array($pkname)){
            foreach ($pkname as $key => $value) {
                $params[$value] = $id[$key]; 
            }
        }else{
           $params[$pkname] = $id; 
        }   
        $r = $this->db->query($this->table, '*', $params);
        if($r!=-1){
            $row = $this->db->getRow();
            $object = new POJO($this->table, $row);
            return $object;
        }       
        return null;
    }
    function delete($pkname, $id){
        $params = [];
        if(is_array($pkname)){
            foreach ($pkname as $key => $value) {
                $params[$value] = $id[$key]; 
            }
        }else{
           $params[$pkname] = $id; 
        }
        $result = $this->db->delete($this->table, $params);    
        return $result;
    }    
    function erase(POJO $pojo){
        return $this->delete($pojo->getID());
    } 
    public function getNumReg($params = array()){
        return $this->db->count($this->table, $params);
    }   
    function getListJSON($page = "1", $nrpp = Settings::_NRPP, $order = "11", $params = []) {
        $list = $this->getList($page, $nrpp, $order, $params);
        foreach ($list as $value) {
            $r .= $value->toJSON() . ",";
        }
        $r = "[" . substr($r, 0,-1) . "]";
        return $r;
    }  
     function getList($page = "1", $nrpp = Settings::_NRPP, $order = "11", $params = []) {
        $limit = ($page-1)*$nrpp . ',' . $nrpp;
        $this->db->query($this->table, "*", $params, $order, $limit);
        $r = [];
        while($row=$this->db->getRow()){
            $tmp = new POJO($this->table);
            $tmp->set($row);
            $r[] = $tmp;
        }
        return $r;
    }
}