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

use WideImage\Mapper\GD;
use WideImage\WideImage;
use WideImage\MapperFactory;
use Test\WideImageTestCase;

class GDTest extends WideImageTestCase
{
    protected GD $mapper;

    /**
     * @before
     */
    public function setUpMapper(): void
    {
        $this->mapper = MapperFactory::selectMapper(null, 'gd');
    }

    /**
     * @after
     */
    public function removeTestFiles(): void
    {
        if (file_exists(IMG_PATH . 'temp' . DIRECTORY_SEPARATOR . 'test.gd')) {
            unlink(IMG_PATH . 'temp' . DIRECTORY_SEPARATOR . 'test.gd');
        }
    }
    
    public function testSaveAndLoad(): void
    {
        $handle = imagecreatefromgif(IMG_PATH . '100x100-color-hole.gif');
        $this->mapper->save($handle, IMG_PATH . 'temp' . DIRECTORY_SEPARATOR . 'test.gd');
        $this->assertGreaterThan(0, filesize(IMG_PATH . 'temp' . DIRECTORY_SEPARATOR . 'test.gd'));
        imagedestroy($handle);
        
        // file is a valid image
        $handle = $this->mapper->load(IMG_PATH . 'temp' . DIRECTORY_SEPARATOR . 'test.gd');
        $this->assertTrue(WideImage::isValidImageHandle($handle));
        imagedestroy($handle);
    }
}
