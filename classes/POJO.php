<?php

class POJO {    
    function __construct($table, $params = []) {
        $fields = Settings::$tableColums[$table];
        if(count($params) > 0){            
            foreach($fields as $key => $value) {  
                $this->$value = $params[$value];
            }
        }else{
            foreach($fields as $key => $value) {  
                $this->$value = null;
            }
        }        
    }
    public function __call($name, $value) {
        $op = substr($name, 0, 3);
        $field = ucfirst(substr($name, 3));
        if($op == 'set'){
            $this->$field = $value;
        }else if($op == 'get'){
            return $this->$field;
        }
    }

    public function set($values, $init=0) {   
        $i=0;
        foreach($this as $key => $value) {  
            $this->$key = $values[$i+$init];
            $i++;
        }
    }    
    public function read(){
        foreach($this as $key => $value) {  
            $this->$key = Request::req($key);
        }
    }
    public function toJSON(){
        $str="";
        foreach ($this as $key => $value) {            
            $str.='"'.$key.'" : '.json_encode($value).', ';
        }
        return "{".substr($str,0,-2)."}";
    }    
    public function toArray(){
        $array = array();
        foreach ($this as $key => $value) {            
            $array[$key] = $value;
        }
        return $array;
    }     
    public function __toString() {
        $str="";
        foreach ($this as $key => $value) {            
            $str.='"'.$key.'" : "'.$value.'", ';
        }
        return substr($str,0,-2);
    }

}
