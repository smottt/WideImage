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

use WideImage\Mapper\JPEG;
use WideImage\WideImage;
use WideImage\MapperFactory;
use Test\WideImageTestCase;

class JPEGTest extends WideImageTestCase
{
    protected JPEG $mapper;

    /**
     * @before
     */
    public function setUpMapper(): void
    {
        $this->mapper = MapperFactory::selectMapper(null, 'jpg');
    }

    /**
     * @after
     */
    public function removeTestFiles(): void
    {
        if (file_exists(IMG_PATH . 'temp' . DIRECTORY_SEPARATOR . 'test.jpg')) {
            unlink(IMG_PATH . 'temp' . DIRECTORY_SEPARATOR . 'test.jpg');
        }
    }
    
    public function testLoad(): void
    {
        $handle = $this->mapper->load(IMG_PATH . 'fgnl.jpg');
        $this->assertTrue(WideImage::isValidImageHandle($handle));
        $this->assertEquals(174, imagesx($handle));
        $this->assertEquals(287, imagesy($handle));
        imagedestroy($handle);
    }
    
    public function testSaveToString(): void
    {
        $handle = imagecreatefromjpeg(IMG_PATH . 'fgnl.jpg');
        ob_start();
        $this->mapper->save($handle);
        $string = ob_get_clean();
        $this->assertGreaterThan(0, strlen($string));
        imagedestroy($handle);
        
        // string contains valid image data
        $handle = imagecreatefromstring($string);
        $this->assertTrue(WideImage::isValidImageHandle($handle));
        imagedestroy($handle);
    }
    
    public function testSaveToFile(): void
    {
        $handle = imagecreatefromjpeg(IMG_PATH . 'fgnl.jpg');
        $this->mapper->save($handle, IMG_PATH . 'temp' . DIRECTORY_SEPARATOR . 'test.jpg');
        $this->assertGreaterThan(0, filesize(IMG_PATH . 'temp' . DIRECTORY_SEPARATOR . 'test.jpg'));
        imagedestroy($handle);
        
        // file is a valid image
        $handle = imagecreatefromjpeg(IMG_PATH . 'temp' . DIRECTORY_SEPARATOR . 'test.jpg');
        $this->assertTrue(WideImage::isValidImageHandle($handle));
        imagedestroy($handle);
    }
    
    public function testQuality(): void
    {
        $handle = imagecreatefromjpeg(IMG_PATH . 'fgnl.jpg');
        
        ob_start();
        $this->mapper->save($handle, null, 100);
        $hq = ob_get_clean();
        
        ob_start();
        $this->mapper->save($handle, null, 10);
        $lq = ob_get_clean();
        
        $this->assertGreaterThan(0, strlen($hq));
        $this->assertGreaterThan(0, strlen($lq));
        $this->assertGreaterThan(strlen($lq), strlen($hq));
        imagedestroy($handle);
    }
}
