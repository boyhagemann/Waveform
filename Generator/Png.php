<?php

namespace BoyHagemann\Waveform\Generator;

use BoyHagemann\Waveform\Waveform;

/**
 * Description of Png
 *
 * @author Boy
 */
class Png implements GeneratorInterface
{
    /**
     *
     * @var Waveform $waveform
     */
    protected $waveform;
    
    /**
     *
     * @var boolean 
     */
    protected $useHeader = true;
    
    /**
     *
     * @var string $color Hexadecimal color
     */
    protected $color = 'dddddd';
    
    /**
     * 
     * @return Waveform
     */
    public function getWaveform() {
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
     * @return boolean
     */
    public function useHeader() 
    {
        return $this->useHeader;
    }

    /**
     * 
     * @param boolean $useHeader
     */
    public function setUseHeader($useHeader) 
    {
        $this->useHeader = (bool) $useHeader;
    }

    /**
     * 
     * @return string
     */
    public function getColor() {
        return $this->color;
    }

    /**
     * 
     * @param string $color
     */
    public function setColor($color) 
    {        
        $this->color = ltrim($color, '#');
    }

    /**
     * 
     * @return string
     */
    public function generate()
    {
        if($this->useHeader()) {
            header('Content-type: image/png'); 
        }
        
        $waveform = $this->getWaveform();
        $width = $waveform->getWidth();
        $height = $waveform->getHeight();
        
        $red = hexdec(substr($this->getColor(), 0, 2));
        $green = hexdec(substr($this->getColor(), 2, 2));
        $blue = hexdec(substr($this->getColor(), 4, 2));
        
        $image = imagecreatetruecolor($width, $height);
        $color = imagecolorallocate($image, $red, $green, $blue);
        $black = imagecolorallocate($image, 0, 0, 0);
        
        imagecolortransparent($image, $black);
        imageantialias($image, true);
        
        foreach($waveform->toArray() as $position => $value) {            
            $y = round(($height - $value) / 2);
            imageline($image, $position, $y, $position, $y + $value, $color);
        }
        
        imagepng($image);    
        imagedestroy($image);
    }
    
}
