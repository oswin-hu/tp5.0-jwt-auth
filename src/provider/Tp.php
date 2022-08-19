<?php
namespace jwt\provider;

use jwt\contract\Storage;
use think\Cache;

class Tp implements Storage
{

    public function delete($key)
    {
       return Cache::rm($key);
    }

    public function get($key)
    {
        return Cache::get($key);
    }

    public function set($key, $val, $time = 0)
    {
        return Cache::set($key, $val, $time);
    }
}
