<?php

namespace Websocket;

use App\Models\ChatMessageFile;
use App\Models\User;
//use App\Service\Push;
use Carbon\Carbon;
use Channel\Client;
use Illuminate\Support\Facades\Cache;
//use phpDocumentor\Reflection\Type;
use Workerman\Connection\TcpConnection;
use Workerman\Worker;

function sendTo($id, $messageData, $connections ,  $worker)
{
    if (isset($worker->connections[$id])){
        $worker->connections[$id]->send(json_encode($messageData));
    }else{
        dump('not_found2');
    };
}
function send($messageData, $connection)
{
    $connection->send(json_encode($messageData));
}

function sendAll($messageData, $connections)
{
    $message = json_encode($messageData);
    foreach ($connections as $c) {
        $c->send($message);
    }
}

function chat($fromId, $toId )
{
    dump("user_id: $fromId : $toId");
    if ($fromId == $toId) {
        return false;
    }


        $chatId = DB::table('chat_users')
            ->where('user_id', $fromId)
            ->where('owner_id', $toId)
            ->pluck('chat_id')
            ->first();

        if($chatId == null){
            $chatId = DB::table('chat_users')
                ->where('user_id',$toId )
                ->where('owner_id', $fromId)
                ->pluck('chat_id')
                ->first();
        }

    dump("chat_id: $chatId");

    if ($chatId == null) {
        $chatId = DB::table('chats')->insertGetId([
            'created_at' => now(),
        ]);;

        DB::table('chat_users')->insert([
            'chat_id' => $chatId,
            'user_id' => $fromId,
            'owner_id' => $toId,
//            'role' => $role

        ]);

    }

    return $chatId;

}

function message($messageData, $connection)
{

    if(isset($messageData['chat_id'])){
        $chatId = $messageData['chat_id'];
    }else{
        $chatId = chat($connection->user_id, $messageData['to'] );
    }

    $message['type'] = $messageData['action'];
    $message['role'] = null;
    $message['chat_id'] = $chatId;
    $message['name'] = $connection->name;
    $message['text'] = $messageData['text'];
    $message['created_at'] = Carbon::now()->addHours(6);
    $message['user_id'] = $connection->user_id ?? null;
    $message['avatar'] = $connection->avatar ?? null;

    DB::table('chat_messages')->insertGetId([
        'chat_id' => $chatId,
        'user_id' => $connection->user_id,
        'text' => $messageData['text'] ?? null,
        'read' => 0,
        'created_at' => Carbon::now()->addHours(6)->format('Y-m-d H:i:s'),
        'updated_at' => Carbon::now()->addHours(6)->format('Y-m-d H:i:s'),
    ]);

   // sendTo($messageData['to'], $message, $connections , $worker);

    Client::publish($messageData['to'], array(
        'to_connection_id' => $connection->id,
        'content'          => $message
    ));

    send($message,$connection);

//    if(!DB::table('users')->whereId($messageData['to'])->whereOnline(1)->exists()){
//
//        dump('push');
//        $user = DB::table('users')->whereId($connection->id)->first();
//        $title = "$user->name жаңа хат ";
//        $body = $messageData['text'];
//
//        $device_token = User::whereId($messageData['to'])->pluck('device_token');
//
//
//        $type = ["action"=>"message","chat_id"=>$chatId,"friend_id"=>$connection->id,"friend_name"=>$user->name];
//        if($user->device_type == 'huawei') {
//
//            $tokenKits = User::whereId($user->id)->pluck('device_token')->toArray();
//            $tokenKit = array();
//            foreach ($tokenKits as $tokens) {
//                $tokenKit = $tokens;
//            }
//
//            $baerer = Push::PushKitToken()['access_token'];
//            Push::Pushkit($tokenKit, $baerer, $title, $body, $type);
//        }else{
//            Push::PushFirbase($device_token , $title , $body , $type);
//        }
//    }
//
//    send($message, $connection);
//    DB::table('friends')->whereUserId($connection->user_id)->whereFriendId($messageData['to'])->update(['updated_at' => Carbon::now()->addHours(6)->format('Y-m-d H:i:s')]);

//    DB::table('chats')
//        ->where('id', $chatId)
//        ->update(['updated_at' => Carbon::now()->addHours(5)]);

    return $message;

}




//function sendPushNotification($fields)
//{
//    dump('push', $fields);
//    // Set POST variables
//    $url = 'https://fcm.googleapis.com/fcm/send';
//
//    $headers = array(
//        'Authorization: key=AAAAMiFAybc:APA91bFSjkYIjM5vC94xTmaAi2GZHH_tyWf-kJC-7QC3uabt4YUCT3egKrrdMttl1wwiHoNPZJrXnsx6VJjZAGjyksEwjp9S0p3WhPJ6Y5sBUIV4Ly8K6ZiAzFZdS0x0hbSM7gwRtp2x',
//        'Content-Type: application/json'
//    );
//    // Open connection
//    $ch = curl_init();
//
//    // Set the url, number of POST vars, POST data
//    curl_setopt($ch, CURLOPT_URL, $url);
//
//    curl_setopt($ch, CURLOPT_POST, true);
//    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//
//    // Disabling SSL Certificate support temporarly
//    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//
//    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
//
//    // Execute post
//    $result = curl_exec($ch);
//    // echo "Result".$result;
//    if ($result === FALSE) {
//        die('Curl failed: ' . curl_error($ch));
//    }
//
//    // Close connection
//    curl_close($ch);
//
//    return $result;
//}
