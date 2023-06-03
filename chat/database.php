<?php
namespace Websocket;

//use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Cache\CacheManager as CacheManager;

use Illuminate\Support\Facades;

$Capsule = new Manager();
$Capsule->addConnection([
    "driver" => "mysql",
    "host" => "127.0.0.1",
    "database" => "arendos",
    "username" => "root",
    "password" => "BZy59%dud76f",
    "charset" => "utf8mb4",
    "collation" => "utf8mb4_bin",
    "prefix" => "",
    'timezone'  => '+06:00'
]);
$Capsule->setAsGlobal();  //this is important
$Capsule->bootEloquent();





class DB extends Manager{

}







































