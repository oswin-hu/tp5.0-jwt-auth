<?php
/**
 * Author: oswin
 * Time: 2022/8/19-12:12
 * Description:
 * Version: v1.0
 */

if (!defined("APP_PATH")) {
    define('APP_PATH', __DIR__ . '/../application/');
}

\think\Console::addDefaultCommands([
    '\\jwt\\command\\SecretCommand',
]);
