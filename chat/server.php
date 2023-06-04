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



$channel_server = new Server('0.0.0.0', 2206);

$worker = new Worker('websocket://0.0.0.0:1995');
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
                $message = $event_data['content'];
                $connection->send(json_encode($message));
            });
        } else {
            send([
                'action' => 'notAuthorized',
            ], $connection);
            $connection->destroy();
            return;
        }
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
            message($messageData, $connection);
            break;
    }
};


$worker->onWorkerStart = function ($worker) use (&$connections) {
    $interval = 10; // пингуем каждые 5 секунд

    Timer::add($interval, function() use(&$connections) {
        foreach ($connections as $c) {
            if ($c->pingWithoutResponseCount >= 3) {
                unset($connections[$c->id]);

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

    dump("disconnect :  $connection->id");

};


Worker::runAll();

