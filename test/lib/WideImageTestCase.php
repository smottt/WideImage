<?php

declare(strict_types = 1);

namespace Test;

use PHPUnit\Framework\TestCase;
use WideImage\Image;
use WideImage\WideImage;

abstract class WideImageTestCase extends TestCase
{
    public function load(string $file): Image
    {
        return WideImage::load(IMG_PATH . $file);
    }

    public function assertValidImage($image): void
    {
        /** @var Image $image */
        $this->assertInstanceOf(Image::class, $image);
        $this->assertTrue($image->isValid());
    }

    public function assertDimensions(Image $image, int $width, int $height): void
    {
        $this->assertEquals($width, $image->getWidth());
        $this->assertEquals($height, $image->getHeight());
    }

    public function assertTransparentColorMatch(Image $img1, Image $img2): void
    {
        $tc1 = $img1->getTransparentColorRGB();
        $tc2 = $img2->getTransparentColorRGB();

        $this->assertEquals($tc1, $tc2);
    }

    public function assertTransparentColorAt(Image $img, int $x, int $y): void
    {
        $this->assertEquals($img->getTransparentColor(), $img->getColorAt($x, $y));
    }

    /**
     * @param array $rec
     * @param array|int|null $r
     * @param int|null $g
     * @param int|null $b
     * @param int|float|null $a
     * @param int|null $margin
     */
    public function assertRGBWithinMargin(array $rec, $r, ?int $g, ?int $b, $a, ?int $margin): void
    {
        if (is_array($r)) {
            $a = $r['alpha'];
            $b = $r['blue'];
            $g = $r['green'];
            $r = $r['red'];
        }

        $result =
            abs($rec['red'] - $r) <= $margin &&
            abs($rec['green'] - $g) <= $margin &&
            abs($rec['blue'] - $b) <= $margin;

        $result = $result && ($a === null || abs($rec['alpha'] - $a) <= $margin);

        $this->assertTrue(
            $result,
            "RGBA [{$rec['red']}, {$rec['green']}, {$rec['blue']}, {$rec['alpha']}] " .
            "doesn't match RGBA [$r, $g, $b, $a] within margin [$margin]."
        );
    }

    /**
     * @param Image $img
     * @param int $x
     * @param int $y
     * @param int|array $rgba
     */
    public function assertRGBAt(Image $img, int $x, int $y, $rgba): void
    {
        if (is_array($rgba)) {
            $cmp = $img->getRGBAt($x, $y);
        } else {
            $cmp = $img->getColorAt($x, $y);
        }

        $this->assertSame($cmp, $rgba);
    }

    public function assertRGBNear(array $rec, $r, ?int $g = null, ?int $b = null, $a = null): void
    {
        $this->assertRGBWithinMargin($rec, $r, $g, $b, $a, 2);
    }

    public function assertRGBEqual(array $rec, $r, ?int $g = null, ?int $b = null, $a = null): void
    {
        $this->assertRGBWithinMargin($rec, $r, $g, $b, $a, 0);
    }
}
