<?php

declare(strict_types = 1);

namespace WideImage\Image;

use WideImage\TrueColorImage;

class ImageForOutput extends TrueColorImage
{
    public array $headers = [];

    public function writeHeader($name, $data)
    {
        $this->headers[$name] = $data;
    }
}
