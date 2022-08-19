<?php

/**
 * Author: oswin
 * Time: 2022/8/19-11:12
 * Description:
 * Version: v1.0
 */
class JWTAuth
{
    private $request;

    private $config;

    public function __construct()
    {
        $this->request = request();

    }

    public static function __callStatic($name, $arguments)
    {
    }
}
