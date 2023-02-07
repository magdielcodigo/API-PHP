<?php

include_once 'helpers/cnx.php';

$msg = array('mod' => null,'data' => null, 
             'title' => null, 'icon' => null);

class mainController{
    function __construct(){
        $data = $_POST['data'];
        $data['year'] = (int) $data['year'];
        $cnx = (new Cnx)->connect();
        $query = $cnx->query("UPDATE User SET name = '".$data['name']."', last = '".$data['last']."', years = ".$data['year'].", pass = '".$data['pass']."', email = '".$data['email']."' WHERE id = ".$data['id']." ");
        if($query){
            $msg['title'] = 'Se ha actualizado correctamente';
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