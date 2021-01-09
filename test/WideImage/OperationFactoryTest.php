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

use WideImage\Exception\UnknownImageOperationException;
use WideImage\OperationFactory;
use WideImage\Operation\MyOperation;
use Test\WideImageTestCase;

class OperationFactoryTest extends WideImageTestCase
{
    public function testFactoryReturnsCached(): void
    {
        $op1 = OperationFactory::get('Mirror');
        $op2 = OperationFactory::get('Mirror');
        $this->assertSame($op1, $op2);
    }

    public function testNoOperation(): void
    {
        $this->expectException(UnknownImageOperationException::class);

        OperationFactory::get('NoSuchOp');
    }

    public function testUserDefinedOp(): void
    {
        $op = OperationFactory::get('MyOperation');
        $this->assertInstanceOf(MyOperation::class, $op);
    }
}
