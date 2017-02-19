Color for PHP
=============

## Installation

```shell
composer require marando/color
```


## Usage/Examples

### Import
```php
use Marando/Color/Color;
```

### Creating a Color

**From RGB**

```php
$color = Color::rgb(0, 255, 0);
```

**From HSL**

```php
$color = Color::hsl(180, 0.5, 0.5);
```

**From Hex**

```php
$color = Color::hex('#f80');
$color = Color::hex('#7c60e2');
```

### Conversions

**To RGB**

```php
$color = Color::hsl(180, 0.5, 0.5);

$color->r;  // 64
$color->g;  // 191
$color->b;  // 191
```
*To get an array of the above:*
```php
$color->rgb;  // [64, 191, 191]
```

**To HSL**

```php
$color = Color::rgb(0, 255, 0);

$color->h;  // 120
$color->s;  // 1
$color->l;  // 0.5
```
*To get an array of the above:*
```php
$color->hsl;  // [120, 1, 0.5]
```

**To Hex**

```php
$color = Color::hsl(180, 50, 50);

$color->hex;  // #40bfbf
```
