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

namespace Test\WideImage\Operation;

use Test\WideImageTestCase;
use WideImage\PaletteImage;
use WideImage\TrueColorImage;

class GetChannelsTest extends WideImageTestCase
{
    public function testCopyChannel8bit(): void
    {
        $img = $this->load('100x100-color-hole.gif');
        
        $copy = $img->getChannels('red', 'alpha');
        $this->assertInstanceOf(PaletteImage::class, $copy);
        $this->assertTrue($copy->isValid());
        $this->assertFalse($copy->isTrueColor());
        $this->assertTrue($copy->isTransparent());
        
        $this->assertRGBEqual($copy->getRGBAt(15, 15), 255, 0, 0);
        $this->assertRGBEqual($copy->getRGBAt(85, 15), 0, 0, 0);
        $this->assertRGBEqual($copy->getRGBAt(85, 85), 0, 0, 0);
        $this->assertRGBEqual($copy->getRGBAt(15, 85), 255, 0, 0);
        $this->assertEquals($copy->getTransparentColor(), $copy->getColorAt(50, 50));
        
        $copy = $img->getChannels('red');
        $this->assertInstanceOf(PaletteImage::class, $copy);
        $this->assertTrue($copy->isValid());
        $this->assertFalse($copy->isTrueColor());
        $this->assertTrue($copy->isTransparent());
        
        $this->assertRGBEqual($copy->getRGBAt(15, 15), 255, 0, 0);
        $this->assertRGBEqual($copy->getRGBAt(85, 15), 0, 0, 0);
        $this->assertRGBEqual($copy->getRGBAt(85, 85), 0, 0, 0);
        $this->assertRGBEqual($copy->getRGBAt(15, 85), 255, 0, 0);
        $this->assertEquals($copy->getTransparentColor(), $copy->getColorAt(50, 50));
        
        $copy = $img->getChannels('green');
        $this->assertInstanceOf(PaletteImage::class, $copy);
        $this->assertTrue($copy->isValid());
        $this->assertFalse($copy->isTrueColor());
        $this->assertTrue($copy->isTransparent());
        
        $this->assertRGBEqual($copy->getRGBAt(15, 15), 0, 255, 0);
        $this->assertRGBEqual($copy->getRGBAt(85, 15), 0, 0, 0);
        $this->assertRGBEqual($copy->getRGBAt(85, 85), 0, 255, 0);
        $this->assertRGBEqual($copy->getRGBAt(15, 85), 0, 0, 0);
        $this->assertEquals($copy->getTransparentColor(), $copy->getColorAt(50, 50));
    }
    
    public function testCopySingleChannel(): void
    {
        $img = $this->load('100x100-rgbyg.png');
        
        $copy = $img->getChannels('red');
        $this->assertNotSame($img->getHandle(), $copy->getHandle());
        $this->assertInstanceOf(TrueColorImage::class, $copy);
        $this->assertTrue($copy->isValid());
        $this->assertTrue($copy->isTrueColor());
        $this->assertRGBEqual($copy->getRGBAt(15, 15), 0, 0, 0, 0);
        $this->assertRGBEqual($copy->getRGBAt(85, 15), 255, 0, 0, 0);
        $this->assertRGBEqual($copy->getRGBAt(85, 85), 255, 0, 0, 0);
        $this->assertRGBEqual($copy->getRGBAt(15, 85), 0, 0, 0, 0);
        $this->assertRGBEqual($copy->getRGBAt(50, 50), 127, 0, 0, 0);
    }
    
    public function testCopyCombinedChannels(): void
    {
        $img = $this->load('100x100-blue-alpha.png');
        $copy = $img->getChannels('blue', 'alpha');
        $this->assertRGBNear($copy->getRGBAt(25, 25), 0, 0, 255, 0.25 * 127);
        $this->assertRGBNear($copy->getRGBAt(75, 25), 0, 0, 255, 0.5 * 127);
        $this->assertRGBNear($copy->getRGBAt(75, 75), 0, 0, 255, 0.75 * 127);
        $this->assertRGBNear($copy->getRGBAt(25, 75), 0, 0, 0, 127);
    }
}
