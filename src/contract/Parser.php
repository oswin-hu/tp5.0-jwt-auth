<?php


namespace jwt\contract;

use think\Request;

interface Parser
{
    public function parse(Request $request);
}
