Waveform
========

This package creates a waveform, based on the [BoyHagemann\Wave](http://github.com/boyhagemann/Wave) class. It will have several generators to present the waveform.

![An example of a generated waveform](https://raw.github.com/boyhagemann/Waveform/master/data/waveform-example.png)
## Install

You can install with composer using the following lines in your composer.json file:
```
"minimum-stability": "dev",
"require": {
    "boyhagemann/waveform": "dev-master"
}
```

## How to use

The most basic way to generate a waveform is like this:
```
<?php

use BoyHagemann\Waveform\Waveform;

echo Waveform::fromFilename('the/path/to/the/file.wav');
```

## A simple example

If you want to test it yourself, try the following code to see a HTML based waveform.

```
<?php

use BoyHagemann\Waveform\Waveform;
use BoyHagemann\Waveform\Generator;

$filename = 'the/path/to/your/file.wav';

$waveform =  Waveform::fromFilename($filename);
$waveform->setGenerator(new Generator\Html)
         ->setWidh(960)
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

## Generators

The waveform can be presented in multiple ways:
- Html
- Png (not implemented yet)
- Svg (not implemented yet)


It uses a simple interface so you can build your own generator. 
The interface GeneratorInterface uses three simple methods:
```
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
