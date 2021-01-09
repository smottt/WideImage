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

use Test\WideImageTestCase;
use WideImage\PaletteImage;
use WideImage\TrueColorImage;

class PaletteImageTest extends WideImageTestCase
{
    public function testCreate(): void
    {
        $img = PaletteImage::create(10, 10);
        $this->assertInstanceOf(PaletteImage::class, $img);
        $this->assertTrue($img->isValid());
        $this->assertFalse($img->isTrueColor());
    }
    
    public function testCopy(): void
    {
        $img = $this->load('100x100-color-hole.gif');
        $this->assertInstanceOf(PaletteImage::class, $img);
        $this->assertTrue($img->isValid());
        $this->assertFalse($img->isTrueColor());
        $this->assertTrue($img->isTransparent());
        $this->assertRGBEqual($img->getRGBAt(15, 15), 255, 255, 0);
        $this->assertRGBEqual($img->getRGBAt(85, 15), 0, 0, 255);
        $this->assertRGBEqual($img->getRGBAt(85, 85), 0, 255, 0);
        $this->assertRGBEqual($img->getRGBAt(15, 85), 255, 0, 0);
        $this->assertSame($img->getTransparentColor(), $img->getColorAt(50, 50));
        
        $copy = $img->copy();
        $this->assertNotSame($img->getHandle(), $copy->getHandle());
        
        $this->assertInstanceOf(PaletteImage::class, $copy);
        $this->assertTrue($copy->isValid());
        $this->assertFalse($copy->isTrueColor());
        $this->assertTrue($copy->isTransparent());
        $this->assertRGBEqual($copy->getRGBAt(15, 15), 255, 255, 0);
        $this->assertRGBEqual($copy->getRGBAt(85, 15), 0, 0, 255);
        $this->assertRGBEqual($copy->getRGBAt(85, 85), 0, 255, 0);
        $this->assertRGBEqual($copy->getRGBAt(15, 85), 255, 0, 0);
        $this->assertSame($copy->getTransparentColor(), $copy->getColorAt(50, 50));
        
        $this->assertSame($img->getTransparentColorRGB(), $copy->getTransparentColorRGB());
    }
    
    public function testCopyNoAlpha(): void
    {
        $img = $this->load('100x100-color-hole.gif');
        $this->assertRGBEqual($img->getRGBAt(85, 85), 0, 255, 0);
        $copy = $img->copyNoAlpha();
        $this->assertNotSame($img->getHandle(), $copy->getHandle());
        $this->assertInstanceOf(PaletteImage::class, $copy);
        $this->assertTrue($copy->isValid());
        $this->assertFalse($copy->isTrueColor());
        $this->assertRGBEqual($copy->getRGBAt(85, 85), 0, 255, 0);
    }
    
    public function testAsTrueColor(): void
    {
        $img = $this->load('100x100-color-hole.gif');
        $this->assertInstanceOf(PaletteImage::class, $img);
        $this->assertTrue($img->isValid());
        
        $copy = $img->asTrueColor();
        $this->assertNotSame($img->getHandle(), $copy->getHandle());
        
        $this->assertInstanceOf(TrueColorImage::class, $copy);
        $this->assertTrue($copy->isValid());
        $this->assertTrue($copy->isTrueColor());
        $this->assertTrue($copy->isTransparent());
        $this->assertRGBEqual($copy->getRGBAt(15, 15), 255, 255, 0);
        $this->assertRGBEqual($copy->getRGBAt(85, 15), 0, 0, 255);
        $this->assertRGBEqual($copy->getRGBAt(85, 85), 0, 255, 0);
        $this->assertRGBEqual($copy->getRGBAt(15, 85), 255, 0, 0);
        
        $this->assertEquals($copy->getRGBAt(50, 50), $copy->getTransparentColorRGB());
        $rgb = $copy->getTransparentColorRGB();
        $this->assertRGBEqual($img->getTransparentColorRGB(), $rgb['red'], $rgb['green'], $rgb['blue']);
    }
}
