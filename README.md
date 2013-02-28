Waveform
========

This package creates a waveform, based on the BoyHagemann\Wave class. It will have several generators to present the waveform.

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
