<?php

/**
 * This file is part of WideImage.
 *
 * WideImage is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation; either version 2.1 of the License, or
 * (at your option) any later version.
 *
 * WideImage is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with WideImage; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 **/

declare(strict_types = 1);

namespace Test\WideImage;

use WideImage\Canvas;
use WideImage\WideImage;
use Test\WideImageTestCase;

class CanvasTest extends WideImageTestCase
{
    public function testCreate(): void
    {
        $img = WideImage::createTrueColorImage(10, 10);
        
        $canvas1 = $img->getCanvas();
        $this->assertInstanceOf(Canvas::class, $canvas1);
        
        $canvas2 = $img->getCanvas();
        $this->assertSame($canvas1, $canvas2);
    }
    
    public function testMagicCallDrawRectangle(): void
    {
        $img = WideImage::createTrueColorImage(10, 10);
        $canvas = $img->getCanvas();
        $canvas->filledRectangle(1, 1, 5, 5, $img->allocateColorAlpha(255, 0, 0, 64));
        $this->assertRGBAt($img, 3, 3, ['red' => 255, 'green' => 0, 'blue' => 0, 'alpha' => 64]);
    }
}
