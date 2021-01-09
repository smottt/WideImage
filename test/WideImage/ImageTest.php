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

use Test\Image\DestructorImage;
use WideImage\Image\ImageForOutput;
use WideImage\WideImage;
use WideImage\Canvas;
use WideImage\Image;
use WideImage\TrueColorImage;
use WideImage\PaletteImage;
use WideImage\Operation\CustomOp;
use Test\WideImageTestCase;

class ImageTest extends WideImageTestCase
{
    public function testFactories(): void
    {
        $this->assertInstanceOf(TrueColorImage::class, WideImage::createTrueColorImage(100, 100));
        $this->assertInstanceOf(PaletteImage::class, WideImage::createPaletteImage(100, 100));
    }
    
    public function testDestructorUponUnset(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Destroy was called');

        $img = new DestructorImage(imagecreatetruecolor(10, 10));

        $this->assertInstanceOf(TrueColorImage::class, $img);

        unset($img);
    }
    
    public function testDestructorUponNull(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Destroy was called');

        $img = new DestructorImage(imagecreatetruecolor(10, 10));

        $this->assertInstanceOf(TrueColorImage::class, $img);

        $img = null;
    }
    
    public function testAutoDestruct(): void
    {
        $img = TrueColorImage::create(10, 10);
        $handle = $img->getHandle();

        if ($handle instanceof \GdImage) {
            $this->markTestSkipped('As of PHP 8 auto destruct does not work');
        }

        unset($img);
        
        $this->assertFalse(WideImage::isValidImageHandle($handle));
    }
    
    public function testAutoDestructWithRelease(): void
    {
        $img = TrueColorImage::create(10, 10);
        $handle = $img->getHandle();
        
        $img->releaseHandle();
        unset($img);
        
        $this->assertTrue(WideImage::isValidImageHandle($handle));
        imagedestroy($handle);
    }
    
    public function testCustomOpMagic(): void
    {
        $img = TrueColorImage::create(10, 10);
        $result = $img->customOp(123, 'abc');
        $this->assertInstanceOf(Image::class, $result);
        $this->assertSame(CustomOp::$args[0], $img);
        $this->assertSame(CustomOp::$args[1], 123);
        $this->assertSame(CustomOp::$args[2], 'abc');
    }
    
    public function testCustomOpCaseInsensitive(): void
    {
        $img = TrueColorImage::create(10, 10);
        $result = $img->CUSTomOP(123, 'abc');
        $this->assertInstanceOf(Image::class, $result);
        $this->assertSame(CustomOp::$args[0], $img);
        $this->assertSame(CustomOp::$args[1], 123);
        $this->assertSame(CustomOp::$args[2], 'abc');
    }
    
    public function testInternalOpCaseInsensitive(): void
    {
        $img = TrueColorImage::create(10, 10);
        $result = $img->AUTOcrop();
        $this->assertInstanceOf(Image::class, $result);
    }
    
    public function testOutput(): void
    {
        $tmp = $this->load('fgnl.jpg');
        $img = new ImageForOutput($tmp->getHandle());
        
        ob_start();
        $img->output('png');
        $data = ob_get_clean();
        
        $this->assertEquals(['Content-length' => strlen($data), 'Content-type' => 'image/png'], $img->headers);
    }
    
    public function testOutputJPG(): void
    {
        $tmp = $this->load('fgnl.jpg');
        $img = new ImageForOutput($tmp->getHandle());
        ob_start();
        $img->output('jpg');
        $data = ob_get_clean();
        $this->assertEquals(['Content-length' => strlen($data), 'Content-type' => 'image/jpg'], $img->headers);
        
        $tmp = $this->load('fgnl.jpg');
        $img = new ImageForOutput($tmp->getHandle());
        ob_start();
        $img->output('jpeg');
        $data = ob_get_clean();
        $this->assertEquals(['Content-length' => strlen($data), 'Content-type' => 'image/jpg'], $img->headers);
    }
    
    public function testCanvasInstance(): void
    {
        $img = $this->load('fgnl.jpg');
        $canvas1 = $img->getCanvas();
        $this->assertInstanceOf(Canvas::class, $canvas1);
        $canvas2 = $img->getCanvas();
        $this->assertSame($canvas1, $canvas2);
    }
    
    public function testSerializeTrueColorImage(): void
    {
        $img = $this->load('fgnl.jpg');
        $img2 = unserialize(serialize($img));
        $this->assertEquals(get_class($img2), get_class($img));
        $this->assertTrue($img2->isTrueColor());
        $this->assertTrue($img2->isValid());
        $this->assertFalse($img2->isTransparent());
        $this->assertEquals($img->getWidth(), $img2->getWidth());
        $this->assertEquals($img->getHeight(), $img2->getHeight());
    }
    
    public function testSerializePaletteImage(): void
    {
        $img = $this->load('100x100-color-hole.gif');
        $img2 = unserialize(serialize($img));
        $this->assertEquals(get_class($img2), get_class($img));
        $this->assertFalse($img2->isTrueColor());
        $this->assertTrue($img2->isValid());
        $this->assertTrue($img2->isTransparent());
        $this->assertEquals($img->getWidth(), $img2->getWidth());
        $this->assertEquals($img->getHeight(), $img2->getHeight());
    }
}
