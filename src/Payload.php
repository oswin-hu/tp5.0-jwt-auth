<?php


namespace jwt;

use jwt\claim\Factory;
use jwt\claim\Issuer;
use jwt\claim\Audience;
use jwt\claim\Expiration;
use jwt\claim\IssuedAt;
use jwt\claim\JwtId;
use jwt\claim\NotBefore;
use jwt\claim\Subject;

class Payload
{
    protected $factory;

    protected $classMap
        = [
            'aud' => Audience::class,
            'exp' => Expiration::class,
            'iat' => IssuedAt::class,
            'iss' => Issuer::class,
            'jti' => JwtId::class,
            'nbf' => NotBefore::class,
            'sub' => Subject::class,
        ];

    protected $claims;

    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    public function customer(array $claim = [])
    {
        foreach ($claim as $key => $value) {
            $this->factory->customer(
                $key,
                is_object($value) ? $value->getValue() : $value
            );
        }

        return $this;
    }

    public function get()
    {
        $claim = $this->factory->builder()->getClaims();

        return $claim;
    }

    public function check($refresh = false)
    {
        $this->factory->validate($refresh);

        return $this;
    }
}
