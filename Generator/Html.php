<?php

namespace BoyHagemann\Waveform\Generator;

use BoyHagemann\Waveform\Waveform;

/**
 * Description of Html
 *
 * @author boyhagemann
 */
class Html implements GeneratorInterface
{
    /**
     *
     * @var Waveform $waveform
     */
    protected $waveform;
    
    /**
     *
     * @var string $containerHtml
     */
    protected $containerHtml = '<div class="waveform">{{pixels}}</div>';
    
    /**
     *
     * @var string
     */
    protected $pixelHtml = '<span style="{{style}}"></span>';
    
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
     * @return string
     */
    public function getContainerHtml() 
    {
        return $this->containerHtml;
    }

    /**
     * 
     * @param string $containerHtml
     */
    public function setContainerHtml($containerHtml) 
    {
        $this->containerHtml = $containerHtml;
    }
    
    /**
     * 
     * @return string
     */
    public function getPixelHtml() 
    {
        return $this->pixelHtml;
    }

    /**
     * 
     * @param string $pixelHtml
     */
    public function setPixelHtml($pixelHtml) 
    {
        $this->pixelHtml = $pixelHtml;
    }

            
    /**
     * 
     * @return string
     */
    public function generate()
    {
        $values = $this->getWaveform()->toArray();
        $pixels = '';
        
        foreach($values as $pixel => $value) {            
            $height = floor($value);
            $bottom = floor(($this->getWaveform()->getHeight() - $value) / 2);
            $style = sprintf('left: %dpx; bottom: %dpx; height: %dpx', $pixel, $bottom, $height);
            $pixels .= str_replace('{{style}}', $style, $this->getPixelHtml());
        }
        
        $container = str_replace('{{pixels}}', $pixels, $this->getContainerHtml());
        
        return $container;
    }
}