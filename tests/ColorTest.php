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

    public function testInvalidHueLow()
    {
        $color = Color::hsl(-170, 0.5, 0.5);
        $this->assertEquals(190, $color->h);
    }

    public function testInvalidHueHigh()
    {
        $this->expectException(Exception::class);
        $color = Color::hsl(480, 2, 0);
        $this->assertEquals(120, $color->h);
    }

    public function testInvalidSatLow()
    {
        $this->expectException(Exception::class);
        $color = Color::hsl(0, -5, 0);
    }

    public function testInvalidSatHigh()
    {
        $this->expectException(Exception::class);
        $color = Color::hsl(0, 2, 0);
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
          [85, 255, 204, '#5fC'],
          [123, 169, 71, '#7Ba947'],
          [4, 35, 37, '#042325'],
          [0, 0, 0, '#000000'],
          [255, 255, 255, '#fffFFf'],
          [0, 0, 0, '#000'],
          [255, 255, 255, '#fff'],
          [255, 136, 0, '#f80'],
          [4, 35, 37, '042325'],
          [255, 255, 255, 'fff'],
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