<?php
ini_set("display_errors",1);
include('amqp.php');
class Reciever extends AMQP{
    public function __construct($queue){
        parent::__construct();
        $this->setupQueue($queue);
    }
}
$sender= new Reciever("DesignQueue");
$sender->recieveMessage($queue="DesignQueue",$messages="");