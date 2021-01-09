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

namespace Test\WideImage\Mapper;

use WideImage\Exception\Exception;
use WideImage\Mapper\TGA;
use WideImage\WideImage;
use Test\WideImageTestCase;
use WideImage\MapperFactory;
use WideImage\vendor\de77;

class TGATest extends WideImageTestCase
{
    protected TGA $mapper;

    /**
     * @before
     */
    public function setUpMapper(): void
    {
        $this->mapper = MapperFactory::selectMapper(null, 'tga');
    }

    public function testLoad(): void
    {
        $handle = $this->mapper->load(IMG_PATH . 'splat.tga');
        $this->assertTrue(WideImage::isValidImageHandle($handle));
        $this->assertEquals(100, imagesx($handle));
        $this->assertEquals(100, imagesy($handle));
        imagedestroy($handle);
    }
    
    public function testSaveToStringNotSupported(): void
    {
        $this->expectException(Exception::class);

        $handle = de77\BMP::imagecreatefrombmp(IMG_PATH . 'splat.tga');
        $this->mapper->save($handle);
    }
    
    public function testSaveToFileNotSupported()
    {
        $this->expectException(Exception::class);

        $handle = imagecreatefromgif(IMG_PATH . '100x100-color-hole.gif');
        $this->mapper->save($handle, IMG_PATH . 'temp' . DIRECTORY_SEPARATOR . 'test.bmp');
    }
}
