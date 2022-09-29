<?php
$root= dirname( __FILE__ ,2);
$vendor= $root.'/vendor/autoload.php';
require($vendor);
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Connection\AMQPSSLConnection;
use PhpAmqpLib\Message\AMQPMessage;
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
class AMQP{
    public $channel;
    public $connection;
    public function __construct(){
        $this->connection = new AMQPStreamConnection($_ENV['HOSTNAME'], 5672, $_ENV['USER_NAME'], $_ENV['PASSWORD']);
        $this->channel = $this->connection->channel();
    }
    public function setupQueue($queue="hello"){
        $this->channel->queue_declare($queue, false, false, false, false);
    }
    public function sendMessage($queue,$messages){
        for ($i=0; $i <100 ; $i++) { 
            $msg = new AMQPMessage($messages);
            $this->channel->basic_publish($msg, '', $queue);
            echo " [$i] Sent 'Hello World!'\n";
        }
    }
    public function recieveMessage($queue,$messages){             
        $retrived_msg = $this->channel->basic_consume($queue, '', false, true, false, false,array($this, 'processOrder'));
        // echo "<pre>";print_r($retrived_msg);echo"</pre>";
        while ($this->channel->is_open()) {
            $this->channel->wait();
        }
    }
    public function processOrder($msg){
        echo ' [x] Received ', $msg->body, "\n";
    }
    public function closeChannel(){
        $this->channel->close();
        $this->connection->close();
    }

}