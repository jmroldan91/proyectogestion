<?php

class DataBase {
    private $con, $query;
    
    function __construct($host = Constant::_HOSTNAME, $database = Constant::_DATABASE, $user = Constant::_DBUSER, $pass = Constant::_DBPASS) {
        try{
            $this->con = new PDO(
                'mysql:host=' . $host . ';dbname=' . $database,
                $user,
                $pass,
                array(
                PDO::ATTR_PERSISTENT => true,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8')
            );
        } catch (Exception $e) {
            return null;
        }
    }
    
    function getCon(){
        return $this->con;
    }
            
    function getCount(){
        return $this->query->rowCount();
    }
    
    function count($table, $params = []){
        $this->query($table, 'count(*)', $params);
        return $this->getRow()[0];
    }
    
    function getId(){
        return $this->con->lastInsertId();
    }
    
    function getConectionError(){
        return $this->con->errorInfo();
    }
    
    function getQueryError(){
        return $this->query->errorInfo();
    }
            
    function close(){
        $this->con = null;
    }
    
    function send($sql, $params = array()){
        $this->query = $this->con->prepare($sql);
        foreach ($params as $key => $value) {
            $this->query->bindValue($key, $value);
        }
        return $this->query->execute();
    }
    
    function getRow(){
        $r = $this->query->fetch();
        if($r === null){
            $this->query->closeCursor();
        }
        return $r;
    }
    
    function erase($table, $where, $params = array()){
        $sql = "delete from $table where $where";
        if($this->send($sql, $params)){
            return $this->getCount();
        }
        return -1;
    }
    
    function delete($table, $params = array()){        
        $where = "where ";
        foreach ($params as $key => $values) {
            $where .= "$key = :$key and ";
        }
        $where = substr($where, 0, -4);
        $sql = "delete from $table $where";
        if($this->send($sql, $params)){
            return $this->getCount();
        }
        return -1;
    }
    
    function insert($table, $params = array(), $auto = true){
        $sql = "insert into $table ";
        $keys = "(";
        $values = "values (";
        foreach ($params as $key => $value) {
            $keys .= $key.',';
            $values .= ':'.$key.',';
        }
        $keys = substr($keys, 0, -1) . ")";
        $values = substr($values, 0, -1) . ')';
        $sql .= $keys . ' ' . $values;
        if($this->send($sql, $params)){
            if($auto){
                return $this->getId();
            }
            return $this->getCount();
        }
        return -1;
    }
    
    function update($table, $paramsSet = array(), $paramsWhere = array()){
        $sql = "update $table ";
        $set = "set ";
        $paramsSend = array();
        foreach ($paramsSet as $key => $value) {
            $set .= "$key = :$key ,";
            $paramsSend[$key] = $value;
        }
        $set = substr($set, 0, -1);
        $where = "where ";
        foreach ($paramsWhere as $key => $value) {
            $where .= "$key = :_$key and ";
            $paramsSend['_'.$key] = $value;
        }
        $where = substr($where, 0, -4);
        $sql .= $set . ' ' . $where;
        if($this->send($sql, $paramsSend)){
            return $this->getCount();
        }
        return -1;
    }
    
    function query($table, $proyection='*', $params = array(), $order='11', $limit=''){
        if(count($params)>=1){
            $where = "where ";
            foreach ($params as $key => $values) {
                $where .= "$key like :$key and ";
            }
            $where = substr($where, 0, -4);
        }else{
            $where ="";   
        }
        if($limit != ''){
            $limit = 'LIMIT ' . $limit;
        }
        $order = $this->getOrderParams($table, $order);
        $sql = "select $proyection "
                . "from $table "
                . "$where "
                . "order by $order "
                . "$limit";
        if($this->send($sql, $params)){
            return $this->getCount();
        }
        return -1;
    }
    
    function select($table, $proyection='*', $condicion = "1=1", $params = array(), $order='11', $limit=''){
        $limite = "";
        if($limit!==''){
            $limit = "LIMIT $limit";
        }
        $order = $this->getOrderParams($table, $order);
        $sql = "select $proyection from $table where $condicion order by $order $limit";
        if($this->send($sql, $params)){
            return $this->getCount();
        }
        return -1;
    }
    
    function getOrderParams($table, $cod){
        if(!is_int($cod)){
            return "1 DESC";
        }
        $colum = substr($cod, 0, strlen($cod));
        $order = substr($cod, -1);
        $ord = $order == '1' ? 'ASC' : 'DESC';        
        if(class_exists(Settings)){
            return Settings::$tableColums[$table][$colum] . ' ' . $ord;
        }
        return $colum . ' ' . $ord;
    }
    
    function runScript($sql){
        return $this->con->exec($sql);
    }
    
    function getDataBases(){
        $this->send('show databases;');
        $r = [];
        while($row = $this->getRow()){
            $r[] = $row;
        }
        return $r;
    }
}
