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
use WideImage\TrueColorImage;

class ApplyFilterTest extends WideImageTestCase
{
    public function testApplyFilter(): void
    {
        $img = $this->load('100x100-color-hole.gif');
        $result = $img->applyFilter(IMG_FILTER_EDGEDETECT);
        
        $this->assertInstanceOf(TrueColorImage::class, $result);
        $this->assertTrue($result->isTransparent());
        
        $this->assertEquals(100, $result->getWidth());
        $this->assertEquals(100, $result->getHeight());
    }
}
