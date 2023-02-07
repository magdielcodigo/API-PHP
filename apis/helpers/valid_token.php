<?php

if(is_csrf_valid()){
    $headers = getallheaders();
    if(!isset($headers['X-AUTH-TOKEN'])){
        echo json_encode(array("title" => "Header faltante","icon" => "error"));
        return;
    }
    if($headers['X-AUTH-TOKEN'] == $tokenSys){
        new mainController();
    }else{
        echo json_encode(array("title" => "Token inválido","icon" => "error"));
    }

}else{
    echo json_encode(array("title" => "csrf inválido","icon" => "error"));
}