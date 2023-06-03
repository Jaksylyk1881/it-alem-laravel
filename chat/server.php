<?php

namespace Websocket;
require_once "../vendor/autoload.php";
require_once "./database.php";
require_once "./auth.php";
require_once "./additional.php";


use App\Models\ChatMessageVote;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Channel\Client;
use Websocket\DB;
use Workerman\Worker;
use Workerman\Lib\Timer;
use Channel\Server;


use function GuzzleHttp\describe_type;

//
//$context = array(
//    'ssl' => array(
//        'local_cert'  => '/etc/letsencrypt/live/icomment.life/fullchain.pem',
//        'local_pk'    => '/etc/letsencrypt/live/icomment.life/privkey.pem',
//        'verify_peer' => false,
//    )
//);


$channel_server = new Server('0.0.0.0', 2206);

$worker = new Worker('websocket://0.0.0.0:1995');
//$worker->transport = 'ssl';
$connections = [];

// 4 processes
$worker->count = 4;


$worker->onConnect = function ($connection) use (&$connections) {


    $connection->onWebSocketConnect = function ($connection) use (&$connections ,&$worker ) {
        if (isset($_GET['user_id'])) {
            $connections = signIn($connections, $connection);

            Client::connect('127.0.0.1', 2206);
            $event_name = $connection->id;
            Client::on($event_name, function($event_data)use($worker , $connection){


               // $to_connection_id = $event_data['to_connection_id'];
                $message = $event_data['content'];
                $connection->send(json_encode($message));
//                if(!isset($connections[$to_connection_id]))
//                {
//                    echo $to_connection_id;
//                    dump($message);
//                    echo "connection not exists\n";
//                    return;
//                }
//                $to_connection = $connections[$to_connection_id];
//                $connection->send(json_encode($message));
            });










        } else {
            send([
                'action' => 'notAuthorized',
            ], $connection);
            $connection->destroy();
            return;
        }

//        $connection['events']['action'] = function() {
//        };
//        $connection['events']['action']();
    };
};

$worker->onMessage = function ($connection, $message) use (&$connections, &$worker) {
    $messageData = json_decode($message, true);


    switch ($messageData['action']) {
        case 'pong':
            $connection->pingWithoutResponseCount = 0;
            break;
        case 'read':
            $messageData['action'] = 'read';

            DB::table('chat_messages')
                ->where('user_id', '!=', $connection->user_id)
                ->where('chat_id', $messageData['chat_id'])
                ->update(['read' => 1]);
            Client::publish($messageData['to'], array(
                'to_connection_id' => $connection->id,
                'content'          => $messageData
            ));
            break;
        case 'message':
            dump($connection->id);

//            $event_name =  $messageData['to'];
//            $to_connection_id = $connection->id;
//            $content = $messageData;
//            $connection->send(json_encode($content));
//            Client::publish($event_name, array(
//                'to_connection_id' => $to_connection_id,
//                'content'          => $content
//            ));
            message($messageData, $connection);
            break;
        case 'file':
            fileMessage($messageData, $connection);
            break;
        case 'vote':
            $message_vote = ChatMessageVote::query()
                ->where('message_id', $messageData['message_id'])
                ->first();
            $message_vote->update(['is_accept' => $messageData['is_accept']]);
            Order::find($message_vote->order_id)->update([
                'delivery_start' => $message_vote->new_delivery_start,
                'delivery_end' => $message_vote->new_delivery_end,
            ]);
            message($messageData, $connection);
            break;
    }


};


$worker->onWorkerStart = function ($worker) use (&$connections) {
    $interval = 10; // пингуем каждые 5 секунд

    Timer::add($interval, function() use(&$connections) {
        foreach ($connections as $c) {
            // Если ответ от клиента не пришел 3 раза, то удаляем соединение из списка
            // и оповещаем всех участников об "отвалившемся" пользователе
            if ($c->pingWithoutResponseCount >= 3) {
//                $messageData = [
//                    'action' => 'ConnectionLost',
//                    'id' => $c->id,
//                    'name' => $c->name,
//                    'login' => $c->login,
//                    'avatar' => $c->avatar
//                ];
//                $message = json_encode($messageData);

                unset($connections[$c->id]);

//                $c->send('{"action":"destroy"}');
//                $c->destroy(); // уничтожаем соединение
//                Client::onRemoteClose();

                // рассылаем оповещение
//                foreach ($connections as $c) {
//                    $c->send($message);
//                }
            }
            else {
                $c->send('{"action":"ping"}');
                $c->pingWithoutResponseCount++; // увеличиваем счетчик пингов
            }
        }
    });
};

$worker->onClose = function ($connection) use (&$connections) {
    // Эта функция выполняется при закрытии соединения
    if (!isset($connections[$connection->id])) {
        return;
    }

    // Удаляем соединение из списка
    unset($connections[$connection->id]);

    // Оповещаем всех пользователей о выходе участника из чата
//    $messageData = [
//        'action' => 'disconnected',
//        'id' => $connection->id,
//        'name' => $connection->name,
////        'login' => $connection->login,
////        'avatar' => $connection->avatar
//    ];
//    $message = json_encode($messageData);
//
//    foreach ($connections as $c) {
//        $c->send($message);
//    }
    User::where('id', $connection->id)->update(['is_online' => 0]);
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:8001/api/user/online/$connection->id");

    curl_exec($ch);
    dump("disconnect :  $connection->id");

};


Worker::runAll();

