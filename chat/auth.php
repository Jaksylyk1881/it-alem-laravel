<?php
namespace Websocket;
use App\Models\User;

require_once "./database.php";
require_once "./additional.php";



function signIn ($connections,$connection){

    $id = $_GET['user_id'];
    dump("GET USER  ID : $id");

    $user = DB::table('users')->where('id', $_GET['user_id'])->first();
    if (!$user){
        $messageData = [
            'action' => 'notAuthorized',
        ];
        $connection->send(json_encode($messageData));
        return;
    }else{
        $connection->id = $user->id;
        $connection->user_id = $user->id;
        $connection->name = $user->name;
        $connection->avatar = $user->avatar;
        $connection->pingWithoutResponseCount = 0;


        $connections[$connection->id] = $connection;
//        $users = [];
//        foreach ($connections as $c) {
//            $users[] = [
//                'id' => $c->id,
//                'user_id' => $c->user_id,
//                'name' => $c->name,
//                'avatar' => $c->avatar
//            ];
//        }
        User::where('id', $_GET['user_id'])->update(['is_online' => 1]);

        send([
            'action' => 'authorized',
            'id' => $connection->id,
            'user_id' => $connection->user_id,
            'name' => $connection->name,
            'avatar' => $connection->avatar,
        ],$connection);

//        sendAll([
//            'action' => 'connected',
//            'id' => $connection->id,
//            'user_id' => $connection->user_id,
//            'name' => $connection->name,
//            'avatar' => $connection->avatar,
//        ],$connections);

//        dump("connection user_".$connection->user_id,);
//        dump('connect role'. $connection->role);


//        $ch = curl_init();
//
//        curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:8001/api/user/online/$id");
//
//        curl_exec($ch);

        return $connections;
    }

}

