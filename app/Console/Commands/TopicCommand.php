<?php

namespace App\Console\Commands;

use PhpAmqpLib\Channel\AMQPChannel;

class TopicCommand extends RabbitMQCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:topic {routing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    /**
     * @param AMQPChannel $channel
     */
    public function main(AMQPChannel $channel): void {
        $routing = $this->argument('routing');
        echo $routing;
        $exchange_name = 'topic_logs';
        /**
         * Exchange
         */
        $channel->exchange_declare($exchange_name, 'topic', false, false, false);

        list($queue_name, ,) = $channel->queue_declare("", false, false, true, false);

        $channel->queue_bind($queue_name, $exchange_name, $routing);

        echo " [*] Waiting for logs. To exit press CTRL+C\n";

        $callback = function ($msg) {
            echo ' [x] ', $msg->delivery_info['routing_key'], ':', $msg->body, "\n";
        };

        $channel->basic_consume($queue_name, '', false, true, false, false, $callback);
        while ($channel->is_open()) {
            $channel->wait();
        }

    }
}
