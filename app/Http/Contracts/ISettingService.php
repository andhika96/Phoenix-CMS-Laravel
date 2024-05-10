<?php

namespace App\Contracts;

interface ISettingService
{
    public function getAll();
    public function getByKey($key);
    public function getUpgradePrice();
    public function setUpgradePrice($price);
    public function getSliders();
    public function setSliders(array $sliders);
}
