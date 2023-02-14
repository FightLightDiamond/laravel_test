<?php

namespace App\Console\Commands;
use PhpAmqpLib\Channel\AMQPChannel;

class DurableCommand extends RabbitMQCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:durable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function main(AMQPChannel $channel) {
        $channel->queue_declare('task_queue', false, true, false, false);
        echo " [*] Waiting for messages. To exit press CTRL+C\n";

        $callback = function ($msg) {
            echo ' [x] Received ', $msg->body, time(), "\n";
            $msg->ack();
            echo " [x] Done\n";
        };

        $channel->basic_consume('hello', '', false, false, false, false, $callback);
    }
}
