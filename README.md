Waveform
========

This package creates a waveform, based on the [BoyHagemann\Wave](http://github.com/boyhagemann/Wave) class. It will have several generators to present the waveform.

![An example of a generated waveform](https://raw.github.com/boyhagemann/Waveform/master/data/waveform-example.png)
## Install

You can install with composer using the following lines in your composer.json file:
```json
"minimum-stability": "dev",
"require": {
    "boyhagemann/waveform": "dev-master"
}
```

## How to use

The most basic way to generate a waveform is like this. It has a default width of 500 pixels and a height of 200 pixels.
```php
<?php

use BoyHagemann\Waveform\Waveform;

echo Waveform::fromFilename('the/path/to/the/file.wav');
```

## Generators

The waveform can be presented in multiple ways:
- Html
- Png 
- Svg (not implemented yet)

It uses a simple interface so you can build your own generator. 
The interface GeneratorInterface uses three simple methods:
```php
<?php

namespace BoyHagemann\Waveform\Generator;

use BoyHagemann\Waveform\Waveform;

interface GeneratorInterface
{
    public function setWaveform(Waveform $waveform);
    public function getWaveform();
    public function generate();
}
```

## Examples

If you want to test it yourself, try the following codes.

### Generate a png image
```php
<?php

use BoyHagemann\Waveform\Waveform;
use BoyHagemann\Waveform\Generator;

$filename = 'the/path/to/your/file.wav';

$waveform =  Waveform::fromFilename($filename);
$waveform->setGenerator(new Generator\Png)
         ->setWidth(960)
         ->setHeight(400);

// Will display the image, including setting the read image/png header
echo $waveform->generate();
```

### Generate a html/css based waveform

```php
<?php

use BoyHagemann\Waveform\Waveform;

$filename = 'the/path/to/your/file.wav';

$waveform =  Waveform::fromFilename($filename);
$waveform->setGenerator(new Generator\Html)
         ->setWidth(960)
         ->setHeight(400);

$waveformHtml = $waveform->generate();

?>

<html>
    <head>        
        <style>

        #waveform {
            float: left;            
            position: relative;
            height: 400px;
            width: 960px;
        }
        span {
            position: absolute;
            display: block;
            width: 1px;
            background: #ddd;
            float: left;
            bottom: 0;
        }

        </style>
    </head>
    <body>        
        
        <div id="waveform">
            <?php echo $waveformHtml ?>
        </div>
        
    </body>
</html>

```
## Maximize
By default, the wave amplitude data is maximized to match the height you set for the waveform. 
This means that if you set the height to 200, then the waveform is maximized to 200 pixels.
The result is a nice looking waveform regardless of the overall loudness.
Don't want this nifty feature? You can change the behaviour with one simple line of code:
```php
$waveform->setMaximized(false); // defaults to true
```

## Get wave metadata

You have access to all the wave metadata. 
The only thing you have to do is retrieving the original Wave object and get the desired information.
Form more detailed information see [BoyHagemann\Wave documentation](https://github.com/boyhagemann/Wave/blob/master/README.md).
```php
$wave = $waveform->getWave();
$metadata = $wave->getMetadata();
$metadata->getSampleRate();
$metadata->getBitsPerSample();
```

## To do

- [X] Add png generator
- [ ] Add svg generator
- [ ] Allow different bits per sample (8, 16, 24, 32)
