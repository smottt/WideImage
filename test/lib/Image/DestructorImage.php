<?php

declare(strict_types = 1);

namespace Test\Image;

use WideImage\TrueColorImage;

class DestructorImage extends TrueColorImage
{
    /**
     * There is no good way to test destructors in PHPUnit.
     * So we throw an exception here and test that to confirm that the method was indeed called.
     */
    public function destroy()
    {
        throw new \RuntimeException('Destroy was called');
    }
}
