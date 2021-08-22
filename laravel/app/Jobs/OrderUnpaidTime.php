<?php

namespace App\Jobs;

use App\Servers\OrderServers;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrderUnpaidTime implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $order_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order_id)
    {
        //
        $this->order_id = $order_id;
        $this->delay(now()->addMinutes(30)); //延迟的时间
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        OrderServers::getInstance()->cancel($this->order_id);
    }
}
