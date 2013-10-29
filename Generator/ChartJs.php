<?php

namespace BoyHagemann\Waveform\Generator;

use BoyHagemann\Waveform\Waveform;

/**
 * Description of Png
 *
 * @author Boy
 */
class ChartJs implements GeneratorInterface
{
    /**
     *
     * @var Waveform $waveform
     */
    protected $waveform;

    /**
     * Hexadecimal color of the waveform.
     *
     * @var string $color Hexadecimal color
     */
    protected $fillColor = array(
		'red' 	=> 220,
		'green' => 220,
		'blue' 	=> 220,
		'alpha' => 0.5,
	);

	/**
	 * RGBA color of the waveform.
	 *
	 * @var string $color Hexadecimal color
	 */
	protected $strokeColor = array(
		'red' 	=> 220,
		'green' => 220,
		'blue' 	=> 220,
		'alpha' => 1,
	);

	/**
	 * RGBA color of the waveform.
	 *
	 * @var string $color Hexadecimal color
	 */
	protected $pointColor = array(
		'red' 	=> 220,
		'green' => 220,
		'blue' 	=> 220,
		'alpha' => 1,
	);

	/**
	 * RGBA color of the waveform.
	 *
	 * @var string $color RGBA color
	 */
	protected $pointStrokeColor = array(
		'red' 	=> 255,
		'green' => 255,
		'blue' 	=> 255,
		'alpha' => 1,
	);
    
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
	 * @param string $hex
	 * @param float $alpha 	float between 0 and 1
	 */
	public function setFillColor($hex, $alpha = 1) {
		$this->fillColor = $this->getRgbFromHex($hex, $alpha);
	}

	/**
	 * @return string
	 */
	public function getFillColor() {
		return $this->fillColor;
	}

	/**
	 * @param string $hex
	 * @param float $alpha 	float between 0 and 1
	 */
	public function setPointColor($hex, $alpha = 1) {
		$this->pointColor = $this->getRgbFromHex($hex, $alpha);;
	}

	/**
	 * @return string
	 */
	public function getPointColor() {
		return $this->pointColor;
	}


	/**
	 * @param string $hex
	 * @param float $alpha 	float between 0 and 1
	 */
	public function setPointStrokeColor($hex, $alpha = 1) {
		$this->pointStrokeColor = $this->getRgbFromHex($hex, $alpha);;
	}

	/**
	 * @return string
	 */
	public function getPointStrokeColor() {
		return $this->pointStrokeColor;
	}

	/**
	 * @param string $strokeColor
	 * @param float $alpha 	float between 0 and 1
	 */
	public function setStrokeColor($hex, $alpha = 1) {
		$this->strokeColor = $this->getRgbFromHex($hex, $alpha);;
	}

	/**
	 * @return string
	 */
	public function getStrokeColor() {
		return $this->strokeColor;
	}


    /**
     * 
     * @param string $hex
     * @return array with keys 'red', 'green', 'blue'
     */
    protected function getRgbFromHex($hex, $alpha = 1)
    {
        return array(            
            'red'   => hexdec(substr($hex, 0, 2)),
            'green' => hexdec(substr($hex, 2, 2)),
            'blue'  => hexdec(substr($hex, 4, 2)),
			'alpha' => $alpha,
        );
    }

	/**
	 * @param array $colorData
	 * @return string
	 */
	protected function toRgbaColorString(Array $colorData)
	{
		return sprintf('rgba(%d,%d,%d,%s)', $colorData['red'], $colorData['green'], $colorData['blue'], $colorData['alpha']);
	}

    /**
     * interface GeneratorInterface
     * 
     * @return string
     */
    public function generate()
    {
        $waveform = $this->getWaveform();
        $width = $waveform->getWidth();
        $height = $waveform->getHeight();

		$data['datasets'][0] = array(
			'fillColor' 		=> $this->toRgbaColorString($this->getFillColor()),
			'strokeColor' 		=> $this->toRgbaColorString($this->getStrokeColor()),
			'pointColor' 		=> $this->toRgbaColorString($this->getPointColor()),
			'pointStrokeColor' 	=> $this->toRgbaColorString($this->getPointStrokeColor()),
		);

		foreach($waveform->toArray() as $position => $amplitude) {
			$data['labels'][] = '';
			$data['datasets'][0]['data'][] = $amplitude;
		}

		return json_encode($data);
    }
    
}
