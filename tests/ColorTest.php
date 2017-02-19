<?php

use Marando\Color\Color;

class ColorTest extends PHPUnit_Framework_TestCase
{

    public function testGetSetRGB()
    {
        $color = Color::rgb(0, 127, 255);

        $this->assertEquals([0, 127, 255], $color->rgb);
        $this->assertEquals(0, $color->r);
        $this->assertEquals(127, $color->g);
        $this->assertEquals(255, $color->b);
    }

    public function testInvalidRGBlow()
    {
        $this->expectException(Exception::class);
        $color = Color::rgb(-1, 0, 0);
    }

    public function testInvalidRGBhigh()
    {
        $this->expectException(Exception::class);
        $color = Color::rgb(256, 0, 0);
    }

    public function testGetHSL()
    {
        $colors = [
          [255, 0, 127, 330, 1, 0.5],
          [0, 255, 127, 150, 1, 0.5],
          [4, 34, 36, 184, 0.8, 0.08],
          [48, 48, 48, 0, 0, 0.19],
        ];

        foreach ($colors as $c) {
            $r = $c[0];
            $g = $c[1];
            $b = $c[2];
            $h = $c[3];
            $s = $c[4];
            $l = $c[5];

            $color = Color::rgb($r, $g, $b);

            $this->assertEquals($h, $color->h, "{$r}, {$g}, {$b} -> H");
            $this->assertEquals($s, $color->s, "{$r}, {$g}, {$b} -> S");
            $this->assertEquals($l, $color->l, "{$r}, {$g}, {$b} -> L");
        }
    }

    public function testHSLtoRGB()
    {
        $colors = [
          [255, 0, 128, 330, 1, 0.5],
          [0, 255, 128, 150, 1, 0.5],
          [4, 35, 37, 184, 0.8, 0.08],
          [48, 48, 48, 0, 0, 0.19],
        ];

        foreach ($colors as $c) {
            $r = $c[0];
            $g = $c[1];
            $b = $c[2];
            $h = $c[3];
            $s = $c[4];
            $l = $c[5];

            $color = Color::hsl($h, $s, $l);

            $this->assertEquals($r, $color->r, "{$h}°, {$s}, {$l} -> R");
            $this->assertEquals($g, $color->g, "{$h}°, {$s}, {$l} -> G");
            $this->assertEquals($b, $color->b, "{$h}°, {$s}, {$l} -> B");
        }
    }

    public function testRGBtoHex()
    {
        $colors = [
          [123, 169, 71, '#7ba947'],
          [4, 35, 37, '#042325'],
          [0, 0, 0, '#000000'],
          [255, 255, 255, '#ffffff'],
        ];

        foreach ($colors as $c) {
            $r   = $c[0];
            $g   = $c[1];
            $b   = $c[2];
            $hex = $c[3];

            $color = Color::rgb($r, $g, $b);

            $this->assertEquals($hex, $color->hex, "{$r}, {$g}, {$b} -> hex");
        }
    }

    public function testHex2rgb()
    {
        $colors = [
          [85, 255, 204, '#5fc'],
          [123, 169, 71, '#7ba947'],
          [4, 35, 37, '#042325'],
          [0, 0, 0, '#000000'],
          [255, 255, 255, '#ffffff'],
        ];

        foreach ($colors as $c) {
            $r   = $c[0];
            $g   = $c[1];
            $b   = $c[2];
            $hex = $c[3];

            $color = Color::hex($hex);

            $this->assertEquals($r, $color->r, "{$hex} -> R");
            $this->assertEquals($g, $color->g, "{$hex} -> G");
            $this->assertEquals($b, $color->b, "{$hex} -> B");
        }
    }

}