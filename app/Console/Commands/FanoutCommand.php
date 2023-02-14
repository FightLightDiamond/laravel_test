<?php

namespace App\Console\Commands;

use PhpAmqpLib\Channel\AMQPChannel;

class FanoutCommand extends RabbitMQCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:fanout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @param AMQPChannel $channel
     */
    public function main(AMQPChannel $channel) {
        $exchange = 'fanout_queue';
        $type = 'fanout';

        /**
         * Exchange
         */
        $channel->exchange_declare($exchange, $type, false, false, false);

        list($queue_name, ,) = $channel->queue_declare("", false, false, true, false);

        $channel->queue_bind($queue_name, $exchange);

        echo " [*] Waiting for logs. To exit press CTRL+C\n";

        $callback = function ($msg) {
            echo ' [x] ', $msg->body, "\n";
        };

        $channel->basic_consume($queue_name, '', false, true, false, false, $callback);

        while ($channel->is_open()) {
            $channel->wait();
        }
    }
}
