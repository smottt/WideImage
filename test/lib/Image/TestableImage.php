<?php

namespace WideImage\Image;

use WideImage\TrueColorImage;

class TestableImage extends TrueColorImage
{
    public static bool $destructCalled = false;

    public array $headers = [];

    public function __destruct()
    {
        self::$destructCalled = true;
    }

    public function writeHeader($name, $data)
    {
        $this->headers[$name] = $data;
    }
}
