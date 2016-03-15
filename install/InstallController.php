<?php
class InstallController extends Controller{
    function checkConn(){ 
        $db = new DataBase(Request::get('dbhost'), Request::get('database'), Request::get('dbuser'), Request::get('dbpass'));
        if(is_object($db->getCon())){
            echo json_encode($db->getDataBases());
        }else{
            echo '{ "result" : "Conection error", "status" : "error" }';
        }            
    }      
    function installP1(){
        $arrayConn = [];
        $arrayAdmin = [];
        foreach ($_GET as $key => $value) {
            if(substr($key, 0,2) === "db"){
                $arrayConn[$key] = $value; 
            }else{
                $arrayAdmin[$key] = $value; 
            }
        }
        Settings::saveSettings($arrayConn, $arrayAdmin);         
        echo '{ "result" : "next", "status" : "ok" }';
    }    
    function installP2(){
        $r = Settings::genTables('system');
        echo '{ "result" : "'.$r.'", "status" : "ok" }';
    }
    function installP3(){
        Settings::addModule('system');
        $r = Settings::loadModules();
        Settings::saveSettings();
        echo $r;
    }
    function installP4(){
        Settings::loadConfig();
        $params = $_GET;
        unset($params['op']);
        $params['pass'] = sha1($params['pass']);
        $db = new DataBase();
        $mng = new ManagePOJO($db, 'user');
        $user = new POJO('user', $params);
        $r = $mng->insert($user);
        echo '{ "result" : "'.$r.'", "status" : "ok" }';
    }
}
