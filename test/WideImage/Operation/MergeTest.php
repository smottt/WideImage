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

class MergeTest extends WideImageTestCase
{
    public function testMergeOpacityZero(): void
    {
        $img     = $this->load('100x100-color-hole.png');
        $overlay = $this->load('100x100-square-overlay.png');

        $res = $img->merge($overlay, 0, 0, 0);

        $this->assertEquals(100, $res->getWidth());
        $this->assertEquals(100, $res->getHeight());

        $this->assertRGBAt($res, 5, 5, ['red' => 255, 'green' => 255, 'blue' => 0, 'alpha' => 0]);
        $this->assertRGBAt($res, 40, 40, ['red' => 0, 'green' => 0, 'blue' => 0, 'alpha' => 127]);
        $this->assertRGBAt($res, 95, 5, ['red' => 0, 'green' => 0, 'blue' => 255, 'alpha' => 0]);
        $this->assertRGBAt($res, 60, 40, ['red' => 0, 'green' => 0, 'blue' => 0, 'alpha' => 127]);
        $this->assertRGBAt($res, 95, 95, ['red' => 0, 'green' => 255, 'blue' => 0, 'alpha' => 0]);
        $this->assertRGBAt($res, 60, 60, ['red' => 0, 'green' => 0, 'blue' => 0, 'alpha' => 127]);
        $this->assertRGBAt($res, 5, 95, ['red' => 255, 'green' => 0, 'blue' => 0, 'alpha' => 0]);
        $this->assertRGBAt($res, 40, 60, ['red' => 0, 'green' => 0, 'blue' => 0, 'alpha' => 127]);
    }

    public function testMergeOpacityHalf(): void
    {
        $img     = $this->load('100x100-color-hole.png');
        $overlay = $this->load('100x100-square-overlay.png');

        $res = $img->merge($overlay, 0, 0, 50);

        $this->assertEquals(100, $res->getWidth());
        $this->assertEquals(100, $res->getHeight());

        $this->assertRGBAt($res, 5, 5, ['red' => 255, 'green' => 255, 'blue' => 127, 'alpha' => 0]);
        $this->assertRGBAt($res, 40, 40, ['red' => 127, 'green' => 127, 'blue' => 127, 'alpha' => 0]);
        $this->assertRGBAt($res, 95, 5, ['red' => 0, 'green' => 0, 'blue' => 255, 'alpha' => 0]);
        $this->assertRGBAt($res, 60, 40, ['red' => 0, 'green' => 0, 'blue' => 0, 'alpha' => 127]);
        $this->assertRGBAt($res, 95, 95, ['red' => 0, 'green' => 127, 'blue' => 0, 'alpha' => 0]);

        // TODO: these two should definitely pass ...

        #$this->assertRGBAt($res, 60, 60, array('red' => 127, 'green' => 127, 'blue' => 127, 'alpha' => 0));
        $this->assertRGBAt($res, 5, 95, ['red' => 255, 'green' => 0, 'blue' => 0, 'alpha' => 0]);
        #$this->assertRGBAt($res, 40, 60, array('red' => 255, 'green' => 127, 'blue' => 127, 'alpha' => 0));
    }

    public function testMergeOpacityFull(): void
    {
        $img     = $this->load('100x100-color-hole.png');
        $overlay = $this->load('100x100-square-overlay.png');

        $res = $img->merge($overlay, 0, 0, 100);

        $this->assertEquals(100, $res->getWidth());
        $this->assertEquals(100, $res->getHeight());

        $this->assertRGBAt($res, 5, 5, ['red' => 255, 'green' => 255, 'blue' => 255, 'alpha' => 0]);
        $this->assertRGBAt($res, 40, 40, ['red' => 255, 'green' => 255, 'blue' => 255, 'alpha' => 0]);
        $this->assertRGBAt($res, 95, 5, ['red' => 0, 'green' => 0, 'blue' => 255, 'alpha' => 0]);
        $this->assertRGBAt($res, 60, 40, ['red' => 0, 'green' => 0, 'blue' => 0, 'alpha' => 127]);
        $this->assertRGBAt($res, 95, 95, ['red' => 0, 'green' => 0, 'blue' => 0, 'alpha' => 0]);
        $this->assertRGBAt($res, 60, 60, ['red' => 0, 'green' => 0, 'blue' => 0, 'alpha' => 0]);
        $this->assertRGBAt($res, 5, 95, ['red' => 255, 'green' => 0, 'blue' => 0, 'alpha' => 0]);
        $this->assertRGBAt($res, 40, 60, ['red' => 255, 'green' => 0, 'blue' => 0, 'alpha' => 0]);
    }
}
