<?php

include_once 'helpers/cnx.php';

$msg = array('mod' => null,'data' => null, 
             'title' => null, 'icon' => null);

class mainController{
    function __construct(){
        $cnx = (new Cnx) -> connect();
        $query = $cnx -> query("DELETE FROM User WHERE id = '".$_POST['id']."' ");
        if($query){
            $msg['title'] = "Se ha eliminado correctamente";
            $msg['icon'] = "success";
            echo json_encode($msg);
        }else{
            $msg['title'] = "Ah ocurrido un error";
            $msg['icon'] = "error";
            echo json_encode($msg);
        }
    }
}

include_once 'helpers/valid_token.php';