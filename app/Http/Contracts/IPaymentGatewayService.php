<?php

namespace App\Contracts;

use App\Models\OrderUpgrade;
use App\Models\User;

interface IPaymentGatewayService
{
    public function createPaymentToken($payload);
    public function isSignatureKeyVerified();
    public function isSuccess();
    public function isExpire();
    public function isCancelled();
    public function getNotification();
    public function getOrder();
    public function setOrder(OrderUpgrade $order, $payload);
}
