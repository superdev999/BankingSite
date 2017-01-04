<?php
class Timespan {
	/**
	 * @var Date
	 */
	public $from;
	/**
	 * @var Date
	 */
	public $to;
	/**
	 * Enter description here...
	 *
	 * @param mixed $from
	 * @param mixed $to
	 * @return Timespan
	 */
	public function Timespan($from, $to="") {
		$this->from = $this->getADateFromMixed($from);
		if($to == "") $this->to = Date::convertString("31.12.2032");
		else $this->to = $this->getADateFromMixed($to);
	}
	public static function convert($string) {
		$parts = explode("-", $string);
		return new Timespan(Date::convertString($parts[0]), Date::convertString($parts[1]));
	}
	protected function getADateFromMixed($input) {
		if(is_a($input,"Date")) return $input;
		else return Date::convertString($input);
	}
	public function isIn($input) {
		$check = $this->getADateFromMixed($input);
		return ($this->from->toUnix() <= $check->toUnix()) && ($check->toUnix() <= $this->to->toUnix());
	}
	/**
	 * A random date in this timespan
	 *
	 * @return Date
	 */
	public function randomInBetween() {
		return new Date(rand($this->from->toUnix(), $this->to->toUnix()));
	}
	/**
	 * Create Timespan for this year
	 *
	 * @return Timespan
	 */
	public static function thisYear() {
		return new Timespan("1.1.".("Y"), "31.12.".date("Y"));
	}
	/**
	 * Vom 00:00 to 23:59
	 *
	 * @return Timespan
	 */
	public static function today() {
		return new Timespan(mktime(0,0,0), mktime(23,59,59));
	}
	public static function week($no, $year="") {
	}
	/**
	 * Create Timespan for next year
	 *
	 * @return Timespan
	 */
	public static function nextYear() {
		return new Timespan("1.1.".(date("Y")+1), "31.12.".(date("Y")+1));
	}
	/**
	 *
	 * @param Date $beginn
	 * @param int $weeks
	 * @return Timespan
	 */
	public static function weeksFrom(Date $beginn, $weeks) {
		return new Timespan($beginn, $beginn->addIntvalDay(7*$weeks));
	}
	public function toString($seperator = " - ", $format="SHORT.NUMBERS") {
		switch($format) {
			case "SHORT.NUMBERS":
				if($this->from->phpDate("Ymd") == $this->to->phpDate("Ymd")) {
					return $this->from->phpDate("d.m.Y");
				} elseif($this->from->phpDate("Ym") == $this->to->phpDate("Ym")) {
					return $this->from->phpDate("d.").$seperator.$this->to->phpDate("d.m.Y");
				} elseif($this->from->phpDate("Y") == $this->to->phpDate("Y")) {
					return $this->from->phpDate("d.m.").$seperator.$this->to->phpDate("d.m.Y");
				} else {
					return $this->from->phpDate("d.m.Y").$seperator.$this->to->phpDate("d.m.Y");
				}
				break;
			case "SHORT.NUMBERS.us":
				if($this->from->phpDate("Ymd") == $this->to->phpDate("Ymd")) {
					return $this->from->phpDate("m/d/Y");
				} elseif($this->from->phpDate("Ym") == $this->to->phpDate("Ym")) {
					return $this->from->phpDate("m/d").$seperator.$this->to->phpDate("d/Y");
				} elseif($this->from->phpDate("Y") == $this->to->phpDate("Y")) {
					return $this->from->phpDate("m/d").$seperator.$this->to->phpDate("m/d/Y");
				} else {
					return $this->from->phpDate("m/d/Y").$seperator.$this->to->phpDate("m/d/Y");
				}
				break;

			default:
				return $this->from->phpDate("d.m.Y").$seperator.$this->to->phpDate("d.m.Y");
				break;
		}
	}
	/**
	 * Returns a string for the time-lenght
	 * $formated means format D HH:MM:SS
	 *
	 * @param boolean $formated
	 */
	public function getTimeSpan($formated=true, $cut=false) {
		$secs = $this->to->toUnix() - $this->from->toUnix();
		if($formated) {
			$min = floor($secs/60);
			$secs = $secs-($min*60);
			$hours = floor($min/60);
			$min = $min-($hours*60);
			$days = floor($hours/24);
			$hours = $hours-($days*24);
			if(!$cut || $days > 0) {
				return $days." ".$hours.":".str_pad($min,2,"0",STR_PAD_LEFT).":".str_pad($secs,2,"0",STR_PAD_LEFT);
			} else {
				if($hours > 0) {
					return $hours.":".str_pad($min,2,"0",STR_PAD_LEFT).":".str_pad($secs,2,"0",STR_PAD_LEFT);
				} else {
					return str_pad($min,2,"0",STR_PAD_LEFT).":".str_pad($secs,2,"0",STR_PAD_LEFT);
				}
			}
		} else {
			return $secs;
		}
	}
	public function getMySQLCondition($fieldName, $in = true) {
		if($in) {
			return $fieldName." >= ".$this->from->toUnix()." AND ".$fieldName." <= ".$this->to->toUnix();
		} else {
			throw new Exception ("Not implemented yet");
		}
	}
	public function getLengthInDays() {
		return ($this->to->toUnix() - $this->from->toUnix()) / (60*60*24);
	}
}
?>
