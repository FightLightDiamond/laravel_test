<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:receiving';

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

	/**
	 * Execute the console command.
	 *
	 * @return void
	 * @throws \Exception
	 */
    public function handle()
    {
	    $connection = new AMQPStreamConnection('rabbitmq', 5672, 'user', 1);
	    $channel = $connection->channel();

	    $this->main($channel);

	    while ($channel->is_open()) {
		    $channel->wait();
	    }
    }

    public function main(AMQPChannel $channel) {
        $channel->queue_declare('hello', false, false, false, false);

        echo " [*] Waiting for messages. To exit press CTRL+C\n";

        $callback = function ($msg) {
            echo ' [x] Received ', $msg->body, time(), "\n";
            $msg->ack();
            echo " [x] Done\n";
        };

        $channel->basic_consume('hello', '', false, true, false, false, $callback);
    }
}
