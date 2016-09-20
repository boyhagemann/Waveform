<?php

namespace BoyHagemann\Waveform\Generator;

use BoyHagemann\Waveform\Waveform;

/**
 * Description of Png
 *
 * @author Boy
 */
class Svg implements GeneratorInterface
{
    /**
     *
     * @var Waveform $waveform
     */
    protected $waveform;

    /**
     * Color of the waveform. https://www.w3.org/TR/SVGColor12/#Color_syntax
     *
     * @var string $color
     */
    protected $color = '#dddddd'; // default to grey

    /**
     *
     * @return Waveform
     */
    public function getWaveform()
    {
        return $this->waveform;
    }

    /**
     *
     * @param Waveform $waveform
     */
    public function setWaveform(Waveform $waveform)
    {
        $this->waveform = $waveform;
    }

    /**
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     *
     * @param string $color
     * @return Svg
     */
    public function setColor($color)
    {
        $this->color = $color;
        return $this;
    }

    /**
     * interface GeneratorInterface
     *
     * @return string
     */
    public function generate()
    {

        $waveform = $this->getWaveform();
        $width    = $waveform->getWidth();
        $height   = $waveform->getHeight();

        $svg = '<?xml version="1.0"?>' . "\n";
        $svg .= '<?xml-stylesheet href="waveform.css" type="text/css"?>' . "\n";
        $svg .= '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">' . "\n";
        $svg .= '<svg width="100%" height="100%" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">' . "\n";

        // the waveform
        $svg .= '<svg viewBox="0 0 100 100" preserveAspectRatio="none"  width="100%" height="100%">' . "\n";

        $waveformdata = $waveform->toArray();
        $datasize     = count($waveformdata);
        $polygon      = '<polygon fill="' . $this->color . '" points ="0,0 ';

        // first pass
        foreach ($waveformdata as $position => $value) {
            // data values can range between 0 and 255
            $x = number_format($position / $datasize * 100, 2);
            $y = number_format($value / 255 * 100, 2);
            $y = 100 - $y;

            $polygon .= $x . ',' . $y . ' ';
        }

        // same again backwards.
        $waveformdata = array_reverse($waveformdata, true);

        foreach ($waveformdata as $position => $value) {
            // data values can range between 0 and 255
            $x = number_format($position / $datasize * 100, 2);
            $y = number_format($value / 255 * 100, 2);

            $polygon .= $x . ',' . $y . ' ';
        }

        $polygon = rtrim($polygon);
        $polygon .= '"/>' . "\n";
        $svg .= $polygon;
        $svg .= "</svg>\n";
        $svg .= "\n</svg>";

        return $svg;
    }

}
