<?php

declare(strict_types = 1);

namespace WideImage\Operation;

class CustomOp
{
    public static array $args;

    public function execute()
    {
        static::$args = func_get_args();

        return static::$args[0]->copy();
    }
}
