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

use WideImage\Mapper\BMP;
use WideImage\WideImage;
use WideImage\MapperFactory;
use WideImage\vendor\de77;
use Test\WideImageTestCase;

class BMPTest extends WideImageTestCase
{
    protected BMP $mapper;

    /**
     * @before
     */
    public function setUpMapper(): void
    {
        $this->mapper = MapperFactory::selectMapper(null, 'bmp');
    }

    public function imageProvider(): array
    {
        return [
            [IMG_PATH . 'fgnl.bmp', 174, 287],
            [IMG_PATH . 'bmp' . DIRECTORY_SEPARATOR . 'favicon.ico', 30, 30]
        ];
    }

    /**
     * @test
     * @dataProvider imageProvider
     *
     * @param string $image
     * @param int $width
     * @param int $height
     */
    public function testLoad(string $image, int $width, int $height): void
    {
        $handle = $this->mapper->load($image);
        $this->assertTrue(WideImage::isValidImageHandle($handle));
        $this->assertEquals($width, imagesx($handle));
        $this->assertEquals($height, imagesy($handle));
        imagedestroy($handle);
    }

    public function testSaveToString(): void
    {
        $handle = de77\BMP::imagecreatefrombmp(IMG_PATH . 'fgnl.bmp');
        ob_start();
        $this->mapper->save($handle);
        $string = ob_get_clean();
        $this->assertGreaterThan(0, strlen($string));
        imagedestroy($handle);

        // string contains valid image data
        $handle = $this->mapper->loadFromString($string);
        $this->assertTrue(WideImage::isValidImageHandle($handle));
        imagedestroy($handle);
    }

    public function testSaveToFile(): void
    {
        $handle = imagecreatefromgif(IMG_PATH . '100x100-color-hole.gif');
        $this->mapper->save($handle, IMG_PATH . 'temp' . DIRECTORY_SEPARATOR . 'test.bmp');
        $this->assertGreaterThan(0, filesize(IMG_PATH . 'temp' . DIRECTORY_SEPARATOR . 'test.bmp'));
        imagedestroy($handle);

        // file is a valid image
        $handle = $this->mapper->load(IMG_PATH . 'temp' . DIRECTORY_SEPARATOR . 'test.bmp');
        $this->assertTrue(WideImage::isValidImageHandle($handle));
        imagedestroy($handle);

        unlink(IMG_PATH . 'temp' . DIRECTORY_SEPARATOR . 'test.bmp');
    }
}
