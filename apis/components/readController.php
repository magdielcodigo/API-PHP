<?php
include_once 'helpers/cnx.php';

$msg = array('mod' => null,'data' => null, 
             'title' => null, 'icon' => null);

class mainController{
    function __construct(){
        $cnx = (new Cnx) -> connect();
        $query = $cnx -> query("SELECT * FROM User");
        if(!empty($query) && $query->num_rows > 0){
            $result = array();
            while($row = $query->fetch_assoc()){
                $result[] = array("id" => $row['id'],"name" => $row['name'],
                                  "last" => $row['last'],"years" => $row['years'],
                                  "email" => $row['email'], "pass" => $row['pass']);
            }
            $msg['mod'] = 'data';
            $msg['data'] = $result;
        }
        echo json_encode($msg);
    }
}

include_once 'helpers/valid_token.php';