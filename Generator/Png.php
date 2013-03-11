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
     * Hexadecimal color of the waveform. 
     *
     * @var string $color Hexadecimal color
     */
    protected $color = 'dddddd';
    
    /**
     * Hexadecimal background color. 
     * Use null to get a transparent background.
     *
     * @var string $background 
     */
    protected $background;
    
    /**
     * Use this file path to save the generated waveform to disk
     *
     * @var string
     */
    protected $filename;
    
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
     * @return Png
     */
    public function setUseHeader($useHeader) 
    {
        $this->useHeader = (bool) $useHeader;
        return $this;
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
     * @return Png
     */
    public function setColor($color) 
    {        
        $this->color = ltrim($color, '#');
        return $this;
    }
    
    /**
     * Get the background hexadecimal color. Can be null for transparency.
     * 
     * @return string|null
     */
    public function getBackground() {
        return $this->background;
    }

    /**
     * Set the hexadecimal background color. For a transparent background
     * set a null value.
     * 
     * @param string|null $background
     * @return Png
     */
    public function setBackground($background) {
        $this->background = $background;
        return $this;
    }

        
    /**
     * Get the path where the png waveform is to be saved
     * 
     * @return string
     */
    public function getFilename() {
        return $this->filename;
    }

    /**
     * Set the path where the generated waveform needs to be saved
     * 
     * @param string $filename
     * @return Png
     */
    public function setFilename($filename) {
        $this->filename = $filename;
        return $this;
    }
    
    /**
     * 
     * @param string $hex
     * @return array with keys 'red', 'green', 'blue'
     */
    protected function getRgbFromHex($hex)
    {
        return array(            
            'red'   => hexdec(substr($hex, 0, 2)),
            'green' => hexdec(substr($hex, 2, 2)),
            'blue'  => hexdec(substr($hex, 4, 2))
        );
    }


    /**
     * interface GeneratorInterface
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
        $image = imagecreatetruecolor($width, $height);
        
        $rgb = $this->getRgbFromHex($this->getColor());
        $color = imagecolorallocate($image, $rgb['red'], $rgb['green'], $rgb['blue']);
                        
        if($this->getBackground()) {
            $rgb = $this->getRgbFromHex($this->getBackground());
            $bg = imagecolorallocate($image, $rgb['red'], $rgb['green'], $rgb['blue']);
            imagefill($image, 0, 0, $bg);
        }
        else {
            $bg = imagecolorallocate($image, 0, 0, 0);
            imagecolortransparent($image, $bg);            
        }
        
        foreach($waveform->toArray() as $position => $value) {            
            $y = round(($height - $value) / 2);
            imageline($image, $position, $y, $position, $y + $value, $color);
        }
                
        imageantialias($image, true);
        imagepng($image, $this->getFilename());    
        imagedestroy($image);
    }
    
}
