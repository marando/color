<?php

/*
 * Copyright (C) 2017 Ashley Marando
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

namespace Marando\Color;

use PHPUnit\Framework\Constraint\Exception;

/**
 * Represents an 8-bit color.
 *
 * @package Marando\Color
 *
 * @property int[]    $rgb RGB array, 0-255
 * @property double[] $hsl HSL array, h=0-360°, s=0-1, l=0-1
 * @property int      $r   Red, 0-255
 * @property int      $g   Green, 0-255
 * @property int      $b   Blue, 0-255
 * @property int      $h   Hue, 0-360°
 * @property double   $s   Saturation, 0-1
 * @property double   $l   Luminance, 0-1
 * @property string   $hex 6-digit RGB Hex
 */
class Color
{
    //--------------------------------------------------------------------------
    // Variables
    //--------------------------------------------------------------------------

    /**
     * The state of this instance is stored as an RGB array of values ranging
     * from 0 to 255.
     *
     * @var array
     */
    private $rgb = [];

    //--------------------------------------------------------------------------
    // Constructors
    //--------------------------------------------------------------------------

    /**
     * Private Color constructor. Creates a new Color instance from RGB values
     * ranging from 0 to 255.
     *
     * @param $r
     * @param $g
     * @param $b
     */
    private function __construct($r, $g, $b)
    {
        // Check if values are in range.
        $this->validateRGB('R', $r);
        $this->validateRGB('G', $g);
        $this->validateRGB('B', $b);

        $this->r = $r;
        $this->g = $g;
        $this->b = $b;
    }

    // Static

    /**
     * Creates a new Color instance from RGB components ranging from 0 to 255.
     *
     * @param int $r Red, 0-255
     * @param int $g Green, 0-255
     * @param int $b Blue, 0-255
     *
     * @return static
     */
    public static function rgb($r = 255, $g = 255, $b = 255)
    {
        return new static($r, $g, $b);
    }

    /**
     * Creates a new Color instance from HSL components. Hue is specified in
     * degrees from 0 to 360°, and saturation and lightness are expressed as a
     * value between 0 and 1.
     *
     * @param int $h Hue, 0-360°
     * @param int $s Saturation, 0-1
     * @param int $l Lightness, 0-1
     *
     * @return Color
     */
    public static function hsl($h = 360, $s = 1, $l = 1)
    {
        // HSL -> RGB
        static::hsl2rgb($h, $s, $l, $r, $g, $b);

        return static::rgb($r, $g, $b);
    }

    /**
     * Creates a new Color instance from an RGB hex color code. Both siz digit
     * and 3 digit hex codes are supported.
     *
     * @param $hex
     *
     * @return Color
     */
    public static function hex($hex)
    {
        static::hex2rgb($hex, $r, $g, $b);

        return static::rgb($r, $g, $b);
    }

    //--------------------------------------------------------------------------
    // Properties
    //--------------------------------------------------------------------------

    function __get($name)
    {
        switch ($name) {
            case 'rgb':
                return $this->rgb;

            case 'r':
                return $this->rgb[0];

            case 'g':
                return $this->rgb[1];

            case 'b':
                return $this->rgb[2];

            case 'hsl':
                return $this->calcHSL();

            case 'h':
                return $this->calcHSL()[0];

            case 's':
                return $this->calcHSL()[1];

            case 'l':
                return $this->calcHSL()[2];

            case 'hex':
                return $this->calcHex();

        }
    }

    function __set($name, $value)
    {
        switch ($name) {
            case 'r':
                $this->validateRGB('R', $value);
                $this->rgb[0] = round($value);

            case 'g':
                $this->validateRGB('G', $value);
                $this->rgb[1] = round($value);

            case 'b':
                $this->validateRGB('B', $value);
                $this->rgb[2] = round($value);
        }
    }

    //--------------------------------------------------------------------------
    // Functions
    //--------------------------------------------------------------------------

    private function calcHSL()
    {
        self::rgb2hsl($this->r, $this->g, $this->b, $h, $s, $l);

        return [$h, $s, $l];
    }

    private function validateRGB($component, $value)
    {
        if ($value < 0 || $value > 255) {
            throw new \Exception("{$component} value {$value} must be 0-255");
        }
    }

    //--------------------------------------------------------------------------
    // Static Functions
    //--------------------------------------------------------------------------

    private static function hsl2rgb($h, $s, $l, &$r, &$g, &$b)
    {
        if ($s == 0) {
            $r = round($l * 255);
            $g = $r;
            $b = $r;

            return;
        }

        $temp1;
        if ($l < 0.5) {
            $temp1 = $l * (1.0 + $s);
        } else {
            $temp1 = $l + $s - $l * $s;
        }

        $temp2 = 2 * $l - $temp1;

        $h = $h / 360;

        $tempR = $h + (1 / 3);
        $tempG = $h;
        $tempB = $h - (1 / 3);

        if ($tempR < 0) {
            $tempR++;
        } elseif ($tempR > 1) {
            $tempR--;
        }

        if ($tempG < 0) {
            $tempG++;
        } elseif ($tempG > 1) {
            $tempG--;
        }

        if ($tempB < 0) {
            $tempB++;
        } elseif ($tempB > 1) {
            $tempB--;
        }

        if (6 * $tempR < 1) {
            $r = $temp2 + ($temp1 - $temp2) * 6 * $tempR;
        } elseif (2 * $tempR < 1) {
            $r = $temp1;
        } elseif (3 * $tempR < 2) {
            $r = $temp2 + ($temp1 - $temp2) * ((2 / 3) - $tempR) * 6;
        } else {
            $r = $temp2;
        }

        if (6 * $tempG < 1) {
            $g = $temp2 + ($temp1 - $temp2) * 6 * $tempG;
        } elseif (2 * $tempG < 1) {
            $g = $temp1;
        } elseif (3 * $tempG < 2) {
            $g = $temp2 + ($temp1 - $temp2) * ((2 / 3) - $tempG) * 6;
        } else {
            $g = $temp2;
        }

        if (6 * $tempB < 1) {
            $b = $temp2 + ($temp1 - $temp2) * 6 * $tempB;
        } elseif (2 * $tempB < 1) {
            $b = $temp1;
        } elseif (3 * $tempB < 2) {
            $b = $temp2 + ($temp1 - $temp2) * ((2 / 3) - $tempB) * 6;
        } else {
            $b = $temp2;
        }

        $r = round($r * 255);
        $g = round($g * 255);
        $b = round($b * 255);
    }

    private static function rgb2hsl($r, $g, $b, &$h, &$s, &$l)
    {
        $r /= 255;
        $g /= 255;
        $b /= 255;

        $min = min([$r, $g, $b]);
        $max = max([$r, $g, $b]);

        $l = ($min + $max) / 2;
        $l = round($l, 2);

        if ($min == $max) {
            $s = 0;
            $h = 0;

            return;
        }

        if ($l < 0.5) {
            $s = ($max - $min) / ($max + $min);
        } else {
            $s = ($max - $min) / (2.0 - $max - $min);
        }

        if ($r == $max) {
            $h = ($g - $b) / ($max - $min);
        } elseif ($g == $max) {
            $h = 2.0 + ($b - $r) / ($max - $min);
        } else {
            $h = 4.0 + ($r - $g) / ($max - $min);
        }

        $h *= 60;
        $h = $h < 0 ? $h + 360 : $h;
        $h = (int)round($h);

        $s = round($s, 2);
    }

    private static function rgb2hex($r, $g, $b)
    {
        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }

    private static function hex2rgb($hex, &$r, &$g, &$b)
    {
        // Remove hash if present
        $hex = preg_replace('/[^a-fA-F0-9]/', '', $hex);

        if (strlen($hex) == 6) {
            $r = substr($hex, 0, 2);
            $g = substr($hex, 2, 2);
            $b = substr($hex, 4, 2);
        } else {
            $r = $hex[0] . $hex[0];
            $g = $hex[1] . $hex[1];
            $b = $hex[2] . $hex[2];
        }

        $r = hexdec($r);
        $g = hexdec($g);
        $b = hexdec($b);
    }

    private function calcHex()
    {
        return static::rgb2hex($this->r, $this->g, $this->b);
    }

    function __toString()
    {
        return $this->hex;
    }

}