<?php

    function notfound(){
        $data = ["code" => 404, "message" => "404 not found"];
        return($data);
    }

    function test($data1,$data2){
        $users = [
            ["id" => 1, "name" => "John Doe"],
            ["id" => 2, "name" => "Jane Smith"],
            ["id" => 3, "name" => "David Johnson"]
        ];

        return ['code'=>200,'users'=>$users, "message1" => $data1, "message2" => $data2];
    }

    function test_id($id){
        $user = ["id" => 1, "name" => "John Doe"];
        return ['code'=>200,'users'=>$user];
    }

    function test_post(){
        return ['code'=>200,'data'=>$_POST];
    }

    function test_get(){
        return ['code'=>200,'data'=>$_POST];
    }

    function get_auth_token(){
        $data = ["code" => 404, "message" => "404 not found"];

        if($_POST['email']=="giofahreza2000@gmail.com" && $_POST['password']=="asdasd"){
            $token = bin2hex(random_bytes(32));
            $data = ["code" => 200, "message" => "Successfully get auth token", "token" => $token];

            file_put_contents("token.txt", $token);
        }

        return($data);
    }
?>