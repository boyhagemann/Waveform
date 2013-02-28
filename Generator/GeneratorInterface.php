<?php

namespace BoyHagemann\Waveform\Generator;

use BoyHagemann\Waveform\Waveform;

/**
 *
 * @author boyhagemann
 */
interface GeneratorInterface 
{
    /**
     * 
     * @param Waveform $waveform
     */
    public function setWaveform(Waveform $waveform);
    
    /**
     * 
     * @return Waveform
     */
    public function getWaveform();
    
    /**
     * 
     * @return mixed
     */
    public function generate(); 
}