<?php


namespace jwt\claim;

use jwt\exception\TokenExpiredException;

class Expiration extends Claim
{
    protected $name = 'exp';

    /**
     * @throws TokenExpiredException
     */
    public function validatePayload()
    {
        if (time() >= (int)$this->getValue()) {
            throw new TokenExpiredException('The token is expired.');
        }
    }
}
