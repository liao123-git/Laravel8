<?php


namespace App\Servers;


use App\Jobs\OrderUnpaidTime;

class OrderServers extends BaseServers
{
    public function submit($order_id)
    {
        dispatch(new OrderUnpaidTime($order_id));
    }

    public function cancel($order_id)
    {
        var_dump($order_id);
        return;
    }
}
