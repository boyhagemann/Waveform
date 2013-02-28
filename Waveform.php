<?php

namespace BoyHagemann\Waveform;

use BoyHagemann\Wave\Wave;
use BoyHagemann\Waveform\Generator\GeneratorInterface;

/**
 * Description of Waveform
 *
 * @author boyhagemann
 */
class Waveform 
{    
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
     * @var Wave
     */
    protected $wave;
    
    /**
     *
     * @var GeneratorInterface $generator
     */
    protected $generator;
    
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
     * @throws Exception
     * @return GeneratorInterface
     */
    public function getGenerator() 
    {
        if(!$this->generator) {
            throw new Exception('Please add a generator of type GeneratorInterface to render the waveform');
        }
        
        return $this->generator;
    }

    /**
     * 
     * @param GeneratorInterface $generator
     * @return Waveform
     */
    public function setGenerator(GeneratorInterface $generator) 
    {
        $generator->setWaveform($this);
        $this->generator = $generator;
        return $this;
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
        $data       = $this->getWave()->getWaveformData();
        $size       = $data->getSize();
        $channel    = $data->getChannel(0);
        $width      = $this->getWidh();
        $height     = $this->getHeight();
        $sum        = array();
        $bits       = $this->getWave()->getMetadata()->getBitsPerSample();        
        $range      = pow(2, $bits) / 2;
        
        foreach($channel->getValues() as $position => $amplitude) {

            $pixel = floor($position / $size * $width);
            
//            $value = ($amplitude - $range) / $range * $height;
//
//            if($value <= 0) {
//                $value += 2 * $height;
//            }       
//            
//            $values[$pixel][] = $value;
//        }
//        
//        $summary = array();
//        foreach($values as $pixel => $amplitudes) {
//            $summary[$pixel] = floor(min($amplitudes) + max($amplitudes) / 2);
//        }
        
        
//        $left = current(unpack('v', fread($fh, 2)));

//        if($channels == 2) {
//            $right = current(unpack('v', fread($fh, 2)));       
//        }

            if($amplitude >= $range) {
                $amplitude -= 2 * $range;        
            }

    //        $iPosition += $steps * $blockSize;

    //        fread($fh, $steps * $blockSize);


            $sum[$pixel][] = $amplitude;

        }

        $summary = array();
        foreach($sum as $pixel => $values) {
            $summary[$pixel] = floor((max($values) / $range) * $height);
        }
                
//        var_dump($summary); exit;
                
        return $summary;
    }
    
    /**
     * 
     * @return mixed
     */
    public function generate()
    {
        return $this->getGenerator()->generate();
    }
}