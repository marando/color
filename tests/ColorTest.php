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

    public function testRandColor()
    {
        $colors = [];
        for ($i = 0; $i < 10; $i++) {
            $colors[] = Color::rand();
        }

        $gen    = count($colors);
        $colors = array_unique($colors);
        $unique = count($colors);
        $diff   = $gen - $unique + 1;

        $this->assertEquals($gen, $unique, "There were {$diff} duplicates");

    }

    public function testRandParamsHue()
    {
        $step = 1;
        for ($i = $step; $i < 360; $i += $step) {
            $min = $i - $step;
            $max = $i;

            $color = Color::rand([$min, $max], [0, 1], [0, 1]);

            $this->assertTrue(
              $color->h <= $max && $color->h >= $min,
              "Hue {$color->h} is not between {$min} and {$max}"
            );
        }
    }

    public function testRandParamsSat()
    {
        $step = 0.01;
        for ($i = $step; $i < 1; $i += $step) {
            $min = $i - $step;
            $max = $i;

            $color = Color::rand([0, 360], [$min, $max], [0, 1]);

            $this->assertTrue(
              $color->s <= $max && $color->s >= $min,
              "Sat {$color->s} is not between {$min} and {$max}"
            );
        }
    }

    public function testRandParamsLum()
    {
        $step = 0.01;
        for ($i = $step; $i < 1; $i += $step) {
            $min = $i - $step;
            $max = $i;

            $color = Color::rand([0, 360], [0, 1], [$min, $max]);

            $this->assertTrue(
              $color->l <= $max && $color->l >= $min,
              "Lum {$color->l} is not between {$min} and {$max}"
            );
        }
    }

    public function testDist()
    {
        $black = Color::hex('#000');
        $white = Color::hex('#fff');

        $amethyst = Color::hex('#9668c2');

        $blackDist = $amethyst->dist($black);
        $whiteDist = $amethyst->dist($white);

        $this->assertGreaterThan($whiteDist, $blackDist);
    }

    public function testParseHSL()
    {
        $color = Color::parse('hsl(90,90%,50%)');
        $this->assertEquals('#80f20d', $color->hex, '#80f20d');

        $color = Color::parse('hsl(9, 9%, 5%)');
        $this->assertEquals('#0e0c0c', $color->hex, '#0e0c0c');
    }

    public function testParseRGB()
    {
        $color = Color::parse('rgb(23,217,143)');
        $this->assertEquals('#17d98f', $color->hex, '#17d98f');

        $color = Color::parse('rgb(217, 204, 23)');
        $this->assertEquals('#d9cc17', $color->hex, '#d9cc17');
    }

    public function testParseHex()
    {
        $color = Color::parse('#fff');
        $this->assertEquals('#ffffff', $color->hex, '#ffffff');

        $color = Color::parse('c9189d');
        $this->assertEquals('#c9189d', $color->hex, '#c9189d');
    }

}