<?php

namespace Boyhagemann\Waveform;

/**
 * Description of Waveform
 *
 * @author boyhagemann
 */
class Waveform 
{
    /**
     * 
     * @param Wave $wave
     */
    public function __construct(Wave $wave) 
    {
        $this->setWave($wave);
    }
    
    /**
     * 
     * @param string $filename
     * @return Waveform
     */
    static public function fromFilename($filename)
    {
        $wave = new Wave();
        $wave->setFilename($filename);
        
        return new self($wave);
    }

    /**
     *
     * @var Wave
     */
    protected $wave;
    
    /**
     *
     * @var integer
     */
    protected $widh = 500;
    
    /**
     *
     * @var integer
     */
    protected $height = 200;
        
    /**
     * 
     * @return Wave
     */
    public function getWave() 
    {
        return $this->wave;
    }

    /**
     * 
     * @param Wave $wave
     * @return Waveform
     */
    public function setWave(Wave $wave) 
    {
        $this->wave = $wave;
        return $this;
    }
    
    /**
     * 
     * @return integer
     */
    public function getWidh() 
    {
        return $this->widh;
    }

    /**
     * 
     * @param integer $widh
     * @return Waveform
     */
    public function setWidh($widh) 
    {
        $this->widh = $widh;
        return $this;
    }
    
    /**
     * 
     * @return integer
     */
    public function getHeight() {
        return $this->height;
    }

    /**
     * 
     * @param integer $height
     * @return \BoyhagemannWave\Waveform
     */
    public function setHeight($height) 
    {
        $this->height = $height;
        return $this;
    }

    /**
     * 
     * @return array
     */
    public function toArray()
    {
        $data = $this->getWave()->getWaveformData();
        $size = $data->getSize();
        $channel = $data->getChannel(0);
        $width = $this->getWidh();
        $height = $this->getHeight();
        $values = array();
        $bits = $this->getWave()->getFmt()->getBitsPerSample();        
        $range = pow(2, $bits) / 2;
        
        foreach($channel->getValues() as $position => $amplitude) {

            $pixel = floor($position / $size * $width);
            
            $value = ($amplitude - $range) / $range * $height;

            if($value <= 0) {
                $value += 2 * $height;
            }       
            
            $values[$pixel][] = $value;
        }
        
        $summary = array();
        foreach($values as $pixel => $amplitudes) {
            $summary[$pixel] = floor(min($amplitudes) + max($amplitudes) / 2);
        }
                
        return $summary;
    }
}