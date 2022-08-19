<?php
/**
 * Author: oswin
 * Time: 2022/8/19-11:12
 * Description:
 * Version: v1.0
 */

namespace jwt\facade;


use jwt\Blacklist;
use jwt\claim\Factory;
use jwt\Manager;
use jwt\parser\AuthHeader;
use jwt\parser\Cookie;
use jwt\parser\Param;
use jwt\Payload;
use jwt\provider\JWT\Lcobucci;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;

class JWTAuth
{
    private $request;

    private $config;

    private $jwt;

    public function __construct()
    {
        $this->request = request();
        $config        = require __DIR__.'/../../config/config.php';
        $this->config  = array_merge($config, \think\Config::get('jwt') ?? []);
    }


    protected function registerBlacklist()
    {
        $storage   = new $this->config['blacklist_storage'];
        $blacklist = new Blacklist($storage);
        $blacklist->setRefreshTTL($this->config['refresh_ttl'])->setGracePeriod($this->config['blacklist_grace_period']);
    }

    protected function registerProvider()
    {
        $keys = $this->config['secret'] ?? [
            'public'   => $this->config['public_key'],
            'private'  => $this->config['private_key'],
            'password' => $this->config['password'],
        ];

        $builder  = new Builder();
        $parser   = new Parser();
        $lcobucci = new Lcobucci($builder, $parser, $this->config['algo'], $keys);
        return $lcobucci;
    }

    protected function registerFactory()
    {
        return new Factory($this->request, $this->config['ttl'], $this->config['refresh_ttl']);
    }

    protected function registerPayload(Factory $factory)
    {
        return new Payload($factory);
    }

    protected function registerManager(Blacklist $blacklist, Payload $payload, Lcobucci $lcobucci)
    {
        return new Manager($blacklist, $payload, $lcobucci);
    }


    protected function registerJWTAuth(Manager $manager)
    {
        $chains = [
            'header' => new AuthHeader(),
            'cookie' => new Cookie(),
            'param'  => new Param()
        ];

        $mode     = $this->config['token_mode'];
        $setChain = [];

        foreach ($mode as $key => $chain) {
            if (isset($chains[$chain])) {
                $setChain[$key] = $chains[$chain];
            }
        }

        $parser    = new \jwt\parser\Parser($this->request, $setChain);
        $this->jwt = new \jwt\JWTAuth($manager, $parser);
    }

    public function init()
    {
        $blacklist = $this->registerBlacklist();
        $lcobucci  = $this->registerProvider();
        $factory   = $this->registerFactory();
        $payload   = $this->registerPayload($factory);
        $manager   = $this->registerManager($blacklist, $payload, $lcobucci);
        $this->registerJWTAuth($manager);
        return $this;
    }

    public static function __callStatic($name, $arguments)
    {
        return (new self())->init()->$name($arguments);
    }
}
