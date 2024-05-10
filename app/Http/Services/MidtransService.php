<?php

namespace App\Services;

use App\Contracts\IPaymentGatewayService;
use App\Models\OrderUpgrade;
use App\Models\User;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService implements IPaymentGatewayService
{
    protected $serverKey;
    protected $isProduction;
    protected $isSanitized;
    protected $is3ds;
    protected $generalConfigs;
    protected OrderUpgrade $order;
 
    public function __construct(OrderUpgrade|null $order=null)
    {
        $this->generalConfigs   = config('services.midtrans');
        $this->serverKey        = $this->generalConfigs['server_key'];
        $this->isProduction     = $this->generalConfigs['is_production'];
        $this->isSanitized      = $this->generalConfigs['is_sanitized'];
        $this->is3ds            = $this->generalConfigs['is_3ds'];
        // $this->serverKey        = config('services.sb_midtrans.server_key');
        // $this->isProduction     = config('services.sb_midtrans.is_production');
        // $this->isSanitized      = config('services.sb_midtrans.is_sanitized');
        // $this->is3ds            = config('services.sb_midtrans.is_3ds');
 
        $this->setConfiguration();

        if ($order) {
            $this->order = $order;
        }
    }
 
    private function setConfiguration()
    {
        Config::$serverKey = $this->serverKey;
        Config::$isProduction = $this->isProduction;
        Config::$isSanitized = $this->isSanitized;
        Config::$is3ds = $this->is3ds;
    }

    public function createPaymentToken($payload)
    {
        return Snap::getSnapToken($payload);
    }

    public function isSignatureKeyVerified()
    {
    }


    public function isSuccess()
    {
    }

    public function isExpire()
    {
    }

    public function isCancelled()
    {
    }

    public function getNotification()
    {
    }

    public function getConfigs()
    {
        return $this->generalConfigs;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder(OrderUpgrade $order, $payload)
    {
        $this->order = $order;
        $snapToken = Snap::getSnapToken($payload);
        
        $this->order->snap_token = $snapToken;
        $this->order->save();
    }
}
