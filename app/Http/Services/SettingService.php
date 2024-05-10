<?php

namespace App\Services;

use App\Contracts\ISettingService;
use App\Models\Setting;

class SettingService implements ISettingService
{
    public function getAll()
    {
        return Setting::all();
    }

    public function getByKey($key)
    {
        return Setting::where("key", $key)->first() ?: Setting::find($key);
    }

    public function createSetting(array $attr)
    {
        $data = new Setting($attr);
        $data->save();
        $data->refresh();

        return $data;
    }

    public function updateByKey($key, $data): Setting
    {
        return Setting::updateOrCreate(['key' => $key], ['value' => $data]);
    }

    public function getUpgradePrice(): float
    {
        $data = Setting::firstOrCreate(
            array('key' => 'upgradePrice'),
            array('value' => '500000')
        );

        return floatval($data->value);
    }

    public function setUpgradePrice($price)
    {
        return Setting::updateOrCreate(
            array('key' => 'upgradePrice'),
            array('value' => "$price")
        );
    }

    public function getSliders(): Setting
    {
        return Setting::firstOrCreate(
            array('key' => 'homeSliders'),
            array('value' => serialize([]))
        );
    }

    public function getBanner(): Setting
    {
        return Setting::firstOrCreate(
            array('key' => 'homeBanner'),
            array(
                'value' => serialize(array(
                    'image'=> asset('img/banner-content-01-1920x250px.webp'),
                    'link' => '#'
                ))
            )
        );
    }

    public function setSliders(array $values)
    {
        return Setting::updateOrCreate(
            array('key' => 'homeSliders'),
            array('value' => serialize($values))
        );
    }
}
