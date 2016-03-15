<?php
class Settings {
    static  $app, $version, $tableColums = [], $tablePks = [], $modules = [];
    static function addModule($moduleName){
        if(is_dir('../modules/'.$moduleName)){
            $settingsFile = $moduleName . ".json";
            $data = json_decode(Files::read('../modules/'.$moduleName.'/'.$settingsFile), true);
            self::$modules[] = $data['module'];            
            foreach ($data['tableColums'] as $value) {
                self::$tableColums[key($value)] = $value[key($value)];              
            } 
            foreach ($data['tablePks'] as $key => $value) {
                self::$tablePks[] = $value; 
            }            
            self::genTables($moduleName);
            return 'Module '.$data['module'].' addedd';
        }else{
            return 'Error loading '.$data['module'].' module';
        }        
    }    
    static function loadModules(){
        $str = "";
        $modules = Files::getDirContent('../modules');
        foreach ($modules as $key => $value){
            if($value != 'system'){
                $str .= '{ "'.$value.'" : "'. self::addModule($value) .'" },'; 
            }              
        }
        return '{ "result" : ['.substr($str, 0, -1).'] }';        
    }
    static function genTables($module = ""){
        $sqlFile = ($module === "") ? '../modules/system/system.sql' : '../modules/'.$module.'/'.$module.'.sql';
        $db = new DataBase();
        return $db->runScript(Files::read($sqlFile));
    }
    static function loadConfig(){
        if(file_exists('../settings.json')){
            self::genConstants();
            self::genTables();
            self::loadModules();
        }else{
            return '{ "result" : "Settings not found please generate a json settings file" }';
        }
        return '{ "result" : 1 }';
    }
    static function genConstants(){
        $data = Files::read('../settings.json');
        $dataArray = json_decode($data, true);
        $strConstants = "<?php class Constant { Const  ";
        foreach($dataArray as $key => $value){
            if(substr($key, 0, 1) === '_'){
                $strConstants .= "$key = '$value', ";
            }else{
                self::$$key = $value;
            }                
        }
        $strConstants = substr($strConstants, 0, -2) . "; }";
        Files::write('../classes/Constant.php', $strConstants);
    }

    static function saveSettings($arrayConn = [], $arrayAdmin = []){        
        if(count($arrayConn) === 0 || count($arrayAdmin) === 0){
            $string = '{
                "app" : "Gestión",
                "version" : "1.0 alfa",
                "_HOSTNAME" : "'.Constant::_HOSTNAME.'", 
                "_DATABASE" : "'.Constant::_DATABASE.'", 
                "_DBUSER" : "'.Constant::_DBUSER.'", 
                "_DBPASS" : "'.Constant::_DBPASS.'",
                "_NRPP" : "'.Constant::_NRPP.'",
                "_SEED" : "'.Constant::_SEED.'",
                "_MAILFROM" : "'.Constant::_MAILFROM.'",
                "_MAILALIAS" : "'.Constant::_MAILALIAS.'",
                "_ROOT" : "'.Constant::_ROOT.'", 
                "tableColums" : '.json_encode(self::$tableColums).',
                "tablePks" : '.json_encode(self::$tablePks).',
                "modules" : '.json_encode(self::$modules).'
            }';
        }else{
            $root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
            $string = '{
                "app" : "Gestión",
                "version" : "1.0 alfa",
                "_HOSTNAME" : "'.$arrayConn['dbhost'].'", 
                "_DATABASE" : "'.$arrayConn['dbdatabase'].'", 
                "_DBUSER" : "'.$arrayConn['dbuser'].'", 
                "_DBPASS" : "'.$arrayConn['dbpass'].'",
                "_NRPP" : "10",
                "_SEED" : "PfsaQW2312ADSFP342P",
                "_MAILFROM" : "'.$arrayAdmin['email'].'",
                "_MAILALIAS" : "'.$arrayAdmin['login'].'",
                "_ROOT" : "'.$root.'", 
                "tableColums" : [],
                "tablePks" : [],
                "modules" : []
            }';
        }        
        Files::write('../settings.json', $string);
        self::genConstants();
    }
    
}