<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Laravel\Cashier\Checkout;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use StingBo\Mengine\Services\MengineService;
use Stripe\Charge;
use Stripe\Stripe;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $a = [5, 4, 1, 2, 3, 4, 5];
    $n = $a[0];
    $x = $a[1];// tràn mảng

    $count = count($a);
    if($count - 2 !== $n) {
        throw new Exception('Mảng không hợp lệ');
    }

    $b = $a;
    unset($b[0]);
    unset($b[1]);

    $c = [];
    $countB = count($b);

    $b = array_values($b);
    foreach ($b as $k => $value) {
        $index = $k - $x;
        if($index < 0) {
            $index += $countB;
        }
        $c[$index] = $value;
    }

    return [$c, $b];
});
//
//Route::get('/a', function () {
//    //return view('welcome');
//    $a = "1111223333777777";
////    if ($a >= 2 * pow(10,9)) {
////        throw new Exception('So khong hop le');
////    }
//    // Kiểm tra độ dài ký tự của số
//
//    // Sắp xếp các trường hợp có thể
//
//    // Tìm các số đối xứng
//
//    // Không tìm thấy thì trả về No
//
//    // Tìm số lớn nhất của số đối xứng
//
//    // In ra kết quả
//
//    $arr = str_split($a);
//
//    if (count($arr) == 0) {
//        return "Please insert number";
//    }
//
//    if (count($arr) == 1) {
//        return $a;
//    }
//
//    // Convert value to int
//    $arr = array_map(function ($value) {
//        return intval($value);
//    }, $arr);
//
//    arsort($arr);
//
//    $arrSorted = array_values($arr);
//    $pair = [];
//    $singe = [];
//    $count = count($arrSorted);
//
//    for ($i = 0; $i < $count - 1; $i++) {
//        $nextI = $i + 1;
//        if ($nextI > $count - 1) { break; }
//
//        if ($arrSorted[$i] === $arrSorted[$nextI]) {
//            $pair[] = $arrSorted[$i];
//            $i++;
//        } else {
//            $singe[] = $arrSorted[$i];
//        }
//    }
//
//    $countSinger = count($singe);
//
//    if ($countSinger > 1) {
//        return "No";
//    }
//
//    $pre = "";
//    $suf = "";
//
//    foreach ($pair as $num) {
//        $pre .= $num;
//        $suf = $num . $suf;
//    }
//
//    $middle = count($singe) === 1 ? $singe[0] : "";
//
//    return [$pre . $middle . $suf, $pair];
//});
//
//function b( $v )
//{
//    $v = $v + 1;
//
//    if ( $v == 10000 )
//    {
//        return $v;
//    }
//    return a( $v );
//}
//
//function a( $v )
//{
//    JMP:
//    $v = $v + 1;
//    logger($v);
//    if ( $v == 10000 )
//    {
//        return $v;
//    }
//    goto JMP;
//}
//
//function rutime($ru, $rus, $index){
//    return ($ru["ru_$index.tv_sec"]*1000 + intval($ru["ru_$index.tv_usec"]/1000))
//        -  ($rus["ru_$index.tv_sec"]*1000 + intval($rus["ru_$index.tv_usec"]/1000));
//}
//
//Route::get('a', function () {
//    $b = memory_get_usage();
//    $cpu_before = getrusage();
//    $a = b(0);
//    $cpu_after = getrusage();
//    echo "<div>Took ".rutime($cpu_after, $cpu_before, "utime")." ms CPU usage</div>";
//    return memory_get_usage() - $b;
//});

Route::get('billing', function () {
    dd(request()->all());
   return 'billing';
})->name('home');

Route::get('stripe', function () {
    $user = \App\Models\User::query()->find(1);
    /**
     * Create customer with stripe
     */
    $user->createOrGetStripeCustomer();
    $user->createSetupIntent();
    /**
     * Set payment method
     */
    $user->defaultPaymentMethod();
    $user->updateDefaultPaymentMethodFromStripe();
    $amount = 123456 * 100;

    return $user->checkoutCharge($amount, 'Deposite');

    /**
     * user_id stripe_customer_id session_id amount_total status
     */

    dd($data->toArray());
//    Checkout::create($user, [
//        'line_items' => [[
//            # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
//            'price' => '{{PRICE_ID}}',
//            'quantity' => 1,
//        ]],
//        'mode' => 'payment'
//    ]);
//    dd($paymentMethod);

//    $user->newSubscription('default', 'price_premium')->create($paymentMethod);

//    dd($paymentMethod->toArray());

//    if ($user->hasDefaultPaymentMethod()) {
//        //
//    }
//    $user->newSubscription('default', 'price_monthly')
//        ->quantity(5)
//        ->create($paymentMethod);

    //dd($stripeCustomer->id);
//
//    Stripe::setApiKey(env('STRIPE_SECRET'));
//
//    $charge = Charge::create(array(
//        'customer' => $stripeCustomer->id,
//        'amount'   => 1999,
//        'currency' => 'usd'
//    ));

    return $user->redirectToBillingPortal(route('billing'));
});

use App\Mail\TestEmail;

Route::get('email', function () {
    $email = 'cuong.pham@sotatek.com';
    $data = ['message' => 'This is a test!'];
    $re = Mail::to($email)->send(new TestEmail($data));
    dd($re);
});

Route::group(['prefix' => 'li'], function () {
   Route::get('add', function () {
       $pair = 'BTCUSDT';
       $rate = 17;

       if($pair) {
           $amount = 1;
           $amountBTC = 1;
           $amountUSDT = 1;
       } else {
           $amountOnlyBTC = 2;
       }

       $fee = 1/11;
       return $rate * 2;
   });
});


Route::get('/send', function () {
    $connection = new AMQPStreamConnection('rabbitmq', 5672, 'user', 1);
    $channel = $connection->channel();
    $channel->queue_declare('hello', false, false, false, false);
    $msg = new AMQPMessage('Hello..... World!');
    $channel->basic_publish($msg, '', 'hello');
    echo " [x] Sent 'Hello World!'\n";
    $channel->close();
    $connection->close();
});

Route::get('/task_queue', function () {
    $connection = new AMQPStreamConnection('rabbitmq', 5672, 'user', 1);
    $channel = $connection->channel();
    $channel->queue_declare('task_queue', false, true, false, false);

    $msg = new AMQPMessage('task_queue..... World!', ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);

    $channel->basic_publish($msg, '', 'hello');
    echo " [x] Sent 'task_queue!'\n";
    $channel->close();
    $connection->close();
});

Route::get('/fanout', function () {
    $exchange = 'fanout_queue';
    $type = 'fanout';

    $connection = new AMQPStreamConnection('rabbitmq', 5672, 'user', 1);
    $channel = $connection->channel();

    $channel->exchange_declare($exchange, $type, false, false, false);

    $msg = new AMQPMessage('fanout... World!');

    $channel->basic_publish($msg, $exchange, 'hello');
    echo " [x] Sent 'task_queue!'\n";

    $channel->close();
    $connection->close();
});

Route::get('/direct', function () {
    $exchange_name = 'direct_logs';
    $rand = rand(0, 1);
    $binding_key = ['black', 'white'][$rand];

    $connection = new AMQPStreamConnection('rabbitmq', 5672, 'user', 1);
    $channel = $connection->channel();

    /**
     * Define queue declare
     */
    $channel->exchange_declare($exchange_name, 'direct', false, false, false);

    $msg = new AMQPMessage($binding_key . ' direct Here!!');

    $channel->basic_publish($msg, $exchange_name, $binding_key);

    echo ' [x] Sent ', $binding_key, "\n";

    $channel->close();
    $connection->close();
});

Route::get('/topic', function () {
    $connection = new AMQPStreamConnection('rabbitmq', 5672, 'user', 1);
    $channel = $connection->channel();

    /**
     * Define queue declare
     */
    $channel->exchange_declare('topic_logs', 'topic', false, false, false);

    $routing_key = ['black.com', 'white.com', 'black.white.com'][rand(0, 2)];

    $data = "Hello World!";

    $msg = new AMQPMessage($data);

    $channel->basic_publish($msg, 'topic_logs', $routing_key);

    echo ' [x] Sent ', $routing_key, ':', $data, "\n";

    $channel->close();
    $connection->close();
});

Route::get('sort', function () {
    $fruits = [
         ["lemon21", "orange", "banana", "apple"],
         ["lemon3", "orange", "banana", "apple"],
         ["lemon6", "orange", "banana", "apple"],
         ["lemon", "orange", "banana", "apple"]
    ];
    function quickSortMultiDimensional($array, $chave) {
        if( count( $array ) < 2 ) {
            return $array;
        }
        $left = $right = array( );
        reset( $array );
        $pivot_key    = key( $array );
        $pivot    = array_shift( $array );
        foreach( $array as $k => $v ) {
            if( $v[$chave] < $pivot[$chave] )
                $left[$k][$chave] = $v[$chave];
            else
                $right[$k][$chave] = $v[$chave];
        }
        return array_merge(
            quickSortMultiDimensional($left, $chave),
            array($pivot_key => $pivot),
            quickSortMultiDimensional($right, $chave)
        );
    }
//    sort($fruits);
    $a = quickSortMultiDimensional($fruits, 1);
    dd($a);
});

use StingBo\Mengine\Core\Order;
Route::get('buy', function () {
    $uuid = uniqid(); // 用户唯一标识
    $symbol = 'abc2usdt'; // 交易对
    $transaction = 'buy'; // 交易方向，buy/sell
    $price = 4; // 交易价格，会根据设置精度转化为整数
    $volume = rand(1, 10); // 交易数量，会根据设置精度转化为整数

    $order = \App\Models\Order::query()->create([
        'symbol' => $symbol,
        'transaction' => $symbol,
        'volume' => $volume,
        'price' => $price
    ]);

    $oid = $order->get('id');

    $order = new Order($uuid, $oid, $symbol, $transaction, $volume, $price);

    $ms = new MengineService();
    //Hash: price => volume
    //Sorted set: price => price
    //laravel_database_abc2usdt:link:40000000
    $ms->pushQueue($order);
    dd($order);
});

Route::get('sell', function () {
    $uuid = rand(1, 10); // 用户唯一标识
    $oid = rand(1, 10); // 订单唯一标识
    $symbol = 'abc2usdt'; // 交易对
    $transaction = 'sell'; // 交易方向，buy/sell
    $price = 4; // 交易价格，会根据设置精度转化为整数
    $volume = rand(1, 10); // 交易数量，会根据设置精度转化为整数

    $order = new Order($uuid, $oid, $symbol, $transaction, $volume, $price);

    $ms = new MengineService();
    //Hash: price => volume
    //Sorted set: price => price
    //laravel_database_abc2usdt:link:40000000
    //laravel_database_abc2usdt:buy
    //laravel_database_abc2usdt:depth
    $ms->pushQueue($order);
    dd($order);
});
