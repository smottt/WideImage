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

use WideImage\Mapper\BMP;
use WideImage\Mapper\GIF;
use WideImage\Mapper\JPEG;
use WideImage\Mapper\PNG;
use WideImage\MapperFactory;
use Test\WideImageTestCase;

class MapperFactoryTest extends WideImageTestCase
{
    public function testMapperPNGByURI(): void
    {
        $mapper = MapperFactory::selectMapper('uri.png');
        $this->assertInstanceOf(PNG::class, $mapper);
    }
    
    public function testMapperGIFByURI(): void
    {
        $mapper = MapperFactory::selectMapper('uri.gif');
        $this->assertInstanceOf(GIF::class, $mapper);
    }
    
    public function testMapperJPGByURI(): void
    {
        $mapper = MapperFactory::selectMapper('uri.jpg');
        $this->assertInstanceOf(JPEG::class, $mapper);
    }
    
    public function testMapperBMPByURI(): void
    {
        $mapper = MapperFactory::selectMapper('uri.bmp');
        $this->assertInstanceOf(BMP::class, $mapper);
    }
}
