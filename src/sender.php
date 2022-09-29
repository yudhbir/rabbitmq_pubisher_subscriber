<?php
ini_set("display_errors",1);
include('amqp.php');
class Sender extends AMQP{
    public function __construct($queue){
        parent::__construct();
        $this->setupQueue($queue);
    }
}
$sender= new Sender("DesignQueue");
$sender->sendMessage($queue="DesignQueue",$messages="Initialization Testing Process");