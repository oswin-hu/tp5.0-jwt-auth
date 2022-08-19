<?php


namespace jwt\parser;

use jwt\contract\Parser as ParserContract;
use think\Request;

class Cookie implements ParserContract
{
    use KeyTrait;

    public function parse(Request $request)
    {
        return \think\Cookie::get($this->key);
    }
}
