<?php

namespace BoyHagemann\Waveform;

use BoyHagemann\Wave\Wave;
use BoyHagemann\Waveform\Generator\GeneratorInterface;

/**
 * This class can generate a waveform based on analyzed wave data. It can
 * have different generators to render a waveform at need.
 *
 * @author boyhagemann
 */
class Waveform 
{    
    /**
     * The width of the waveform in pixels
     *
     * @var integer
     */
    protected $width = 500;
    
    /**
     * The height of the waveform in pixels
     *
     * @var integer
     */
    protected $height = 200;
    
    /**
     * The wave object, containing the analyzed data and metadata
     *
     * @var Wave
     */
    protected $wave;
    
    /**
     * The generator that is used to generate the waveform
     *
     * @var GeneratorInterface $generator
     */
    protected $generator;
    
    /**
     * Get the peak volume of the wave file and adjust the original wave data
     * to fit exactly to the max height.
     *
     * @var boolean
     */
    protected $maximized = true;
    
    /**
     * Constructor
     * 
     * @param Wave $wave
     */
    public function __construct(Wave $wave) 
    {
        $this->setWave($wave);
    }
    
    /**
     * Get the generator that is used to generate the waveform.
     * 
     * If no generator is set, than by default the html generator is used.
     * 
     * @uses Generator\Html
     * @return GeneratorInterface
     */
    public function getGenerator() 
    {
        if(!$this->generator) {
            $this->generator = new Generator\Html;
            $this->generator->setWaveform($this);
        }
        
        return $this->generator;
    }

    /**
     * Set the generator that is used to generate the waveform.
     * 
     * Directly after setting the generator, it will be injected this
     * Waveform object itself.
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
     * Get an instance of the Waveform object by simply setting the filename.
     * 
     * It constructs a new Wave object, sets the filename and returns a
     * Waveform instance with that Wave object injected.
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
     * Get the Wave object, containing all the analyzed data and metadata
     * 
     * @return Wave
     */
    public function getWave() 
    {
        return $this->wave;
    }

    /**
     * Set the Wave object
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
     * Get the width of the waveform in pixels
     * 
     * @return integer
     */
    public function getWidth() 
    {
        return $this->width;
    }

    /**
     * Set the width of the waveform in pixels
     * 
     * @param integer $width
     * @return Waveform
     */
    public function setWidth($width) 
    {
        $this->width = $width;
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
     * @return Waveform
     */
    public function setHeight($height) 
    {
        $this->height = $height;
        return $this;
    }
    
    /**
     * Does the waveform has to fit the maximum height?
     * 
     * @return boolean
     */
    public function isMaximized() 
    {
        return $this->maximized;
    }

    /**
     * Set if the waveform has to fit the maximum height
     * 
     * @param boolean $maximized
     * @return Waveform
     */
    public function setMaximized($maximized) 
    {
        $this->maximized = (bool) $maximized;
        return $this;
    }
    
    /**
     * Get the analyzed waveform data as values per pixel
     * 
     * @return array
     */
    public function toArray()
    {
        $data       = $this->getWave()->getWaveformData();
        $size       = $data->getSize();
        $channel    = $data->getChannel(0);
        $width      = $this->getWidth();
        $height     = $this->getHeight();
        $sum        = array();
        $bits       = $this->getWave()->getMetadata()->getBitsPerSample();        
        $range      = pow(2, $bits) / 2;
        
        foreach($channel->getValues() as $position => $amplitude) {

            $pixel = floor($position / $size * $width);
            
            if($amplitude >= $range) {
                $amplitude -= 2 * $range;        
            }

            $sum[$pixel][] = $amplitude;

        }

        $summary = array();
        foreach($sum as $pixel => $values) {
            $summary[$pixel] = floor((max($values) / $range) * $height);
        }
        
        // Do we have to maximize the data?
        if($this->isMaximized()) {
            $max = max($summary);
            $difference = ($height / 2) / $max;
            
            foreach($summary as &$value) {
                $value = round($value * $difference);
            }
        }
                
        return $summary;
    }
    
    /**
     * Generate the waveform based on the chosen generator
     * 
     * @return mixed
     */
    public function generate()
    {
        return $this->getGenerator()->generate();
    }
}