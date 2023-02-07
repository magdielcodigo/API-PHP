<?php

include_once 'helpers/cnx.php';

$msg = array('mod' => null,'data' => null, 
             'title' => null, 'icon' => null);

class mainController{
    function __construct(){
        $data = $_POST['data'];
        $data['year'] = (int) $data['year'];
        $cnx = (new Cnx)->connect();
        $query = $cnx->query("INSERT INTO User (name, last, years, pass, email) VALUES ('".$data['name']."', '".$data['last']."', ".$data['year'].", '".$data['pass']."', '".$data['email']."' )");
        if($query){
            $msg['title'] = 'Se ha guardado correctamente';
            $msg['icon'] = 'success';
            echo json_encode($msg);
        }else{
            $msg['title'] = 'Ah ocurrido un error';
            $msg['icon'] = 'error';
            echo json_encode($msg);
        }
    }
}

include_once 'helpers/valid_token.php';