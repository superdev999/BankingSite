<?php
class Date {
	private $unixTimestamp;
	public $originalString;
	/**
	 * @var CSV
	 */
	protected $monthNameMatrix = null;
	public function Date($unixtime="") {
		if($unixtime == "") $unixtime = time();
		$this->unixTimestamp = $unixtime;
	}
	/**
	 * @return Date
	 */
	public static function now() {
		return new Date(time());
	}
	public function toUnix() {
		return $this->unixTimestamp;
	}
	public function phpDate($format) {
		return date($format, $this->unixTimestamp);
	}
	public static function fromGermanDate($date) {
		if(ereg("([0-9]{2})\.([0-9]{2})\.([0-9]{4})", $date, $m)) {
			return new Date(mktime(0,0,0,$m[2],$m[1],$m[3]));
		} else return new Date(0);
	}
	/**
	 * Tries to convert datestring
	 *
	 * @param string $datestring
	 * @return Date
	 */
	public static function convertString($datestring, $throwsExcexption=false) {
		$unix = $datestring;
		if(ereg("^[0-9]+$", $datestring)) {
			if(ereg("^([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})$", $datestring, $m)) {
				$unix = mktime($m[4],$m[5],$m[6],$m[2],$m[3],$m[1]);
			} elseif(ereg("^([0-9]{4})([0-9]{2})([0-9]{2})$", $datestring, $m)) {
				$unix = mktime(0,0,0,$m[2],$m[3],$m[1]);
			} else {
				$unix = $datestring;
			}
		} elseif(ereg("^([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})$", $datestring, $m)) {
		  	$unix = mktime(0,0,0,$m[1],$m[2],$m[3]);
		} elseif(ereg("^([0-9]{1,2}).([0-9]{1,2}).([0-9]{2,4})$", $datestring, $m)) {
			$unix = mktime(0,0,0,$m[2],$m[1],$m[3]);
		} elseif(ereg("^([0-9]{1,2})\-([0-9]{1,2})\-([0-9]{2,4})$", $datestring, $m)) {
			$unix = mktime(0,0,0,$m[2],$m[1],$m[3]);
		} elseif(ereg("^([0-9]{1,2})/([0-9]{1,2})", $datestring, $m)) {
			$unix = mktime(0,0,0,$m[1],$m[2],date("Y"));
		} elseif(ereg("^([0-9]{1,2}).([0-9]{1,2})$", $datestring, $m)) {
			$unix = mktime(0,0,0,$m[2],$m[1],date("Y"));
		} elseif(ereg("^([0-9]{2})\. ([A-z]{3}) ([0-9]{4}) ([0-9]{2}):([0-9]{2}):([0-9]{2})$", $datestring, $m)) {
			$unix = $unix = mktime($m[4], $m[5], $m[6], self::getMonthInt($m[2]), $m[1], $m[3]);
		} elseif(ereg("^([0-9]{4})\-([0-9]{2})\-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})$", $datestring, $m)) {
			$unix = mktime($m[4],$m[5],$m[6],$m[2],$m[3],$m[1]);
		} elseif(ereg("^([0-9]{2})\.([0-9]{2})\.([0-9]{4}) ([0-9]{2}):([0-9]{2}):([0-9]{2})$", $datestring, $m)) {
			$unix = mktime($m[4],$m[5],$m[6],$m[2],$m[1],$m[3]);
		} elseif(ereg("^([0-9]{4})\-([0-9]{2})\-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})$", $datestring, $m)) {
			$unix = mktime($m[4],$m[5],$m[6],$m[2],$m[3],$m[1]);
		} elseif(ereg("^([0-9]{4})\-([0-9]{2})\-([0-9]{2})$", $datestring, $m)) {
			$unix = mktime(0,0,0,$m[2],$m[3],$m[1]);
		} elseif(ereg("[A-z]{3} ([A-z]{3}) ([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2}) ([0-9]{4})", $datestring, $m)) {
			//Timestamp from Apache error_log
			$month = "";
			$month = self::getMonthInt($m[1]);
			$unix = mktime($m[3], $m[4], $m[5], $month, $m[2], $m[6]);
		} else {
			if($throwsExcexption) throw new Exception("Can't interpred $datestring");
		}
		$r = new Date($unix);
		$r->originalString = $datestring;
		return $r;
	}
	protected function getMonthInt($threeCharsMonth) {
		switch($threeCharsMonth) {
				case "Jan": $month = 1; break;
				case "Feb": $month = 2; break;
				case "Mar": $month = 3; break;
				case "Apr": $month = 4; break;
				case "May": $month = 5; break;
				case "Jun": $month = 6; break;
				case "Jul": $month = 7; break;
				case "Aug": $month = 8; break;
				case "Sep": $month = 9; break;
				case "Oct": $month = 10; break;
				case "Nov": $month = 11; break;
				case "Dec": $month = 12; break;
			}
		return $month;
	}
	public static function nextMonth() {
		return self::nextNMonth(1);
	}
	public static function overNextMonth() {
		return self::nextNMonth(2);
	}
	public static function nextNMonth($add) {
		$year  = date("Y");
		$nextMonth = date("n")+$add;
		if($nextMonth > 12) {
			$nextMonth = $nextMonth - (floor($nextMonth/12)*12);
			$year = $year+(floor($add/12));
		}
		return new Date(mktime(0,0,0,$nextMonth,1,$year));
	}
	/**
	 * Returns a new Date-Object added $sec
	 *
	 * @param int $sec
	 * @return Date
	 */
	public function addIntval($sec) {
		return new Date(intval($this->toUnix())+$sec);
	}
	/**
	 * Returns a new Date-Object added $day days
	 *
	 * @param int $day
	 * @return Date
	 */
	public function addIntvalDay($day) {
		$summerTimeBefore = $this->phpDate("I");
		$r = $this->addIntval($day * 60 * 24 * 60);
		$summerTimeAfter = $r->phpDate("I");
		if($summerTimeBefore > $summerTimeAfter) {
			$r = $r->addIntval(60*60);
		} elseif($summerTimeBefore < $summerTimeAfter) {
			$r = $r->addIntval(-60*60);
		}
		return $r;
	}
	/**
	 * Returns a new Date-Object added $day days
	 *
	 * @param int $day
	 * @return Date
	 */
	public function addIntvalDayCeil($day) {
		return $this->addIntval($day*60*24*60-1);
	}
	/**
	 * Returns a new Date-Object added $sec
	 *
	 * @param int $sec
	 * @return Date
	 */
	public function addYears($years) {
		$hour = intval($this->phpDate("H"));
		$min = intval($this->phpDate("i"));
		$sec = intval($this->phpDate("s"));
		$month = intval($this->phpDate("m"));
		$day = intval($this->phpDate("d"));
		$year = intval($this->phpDate("Y"))+$years;
		return new Date(mktime($hour, $min, $sec, $month, $day, $year));
	}
	public function getMonthNameMatrix() {
		$r = new CSV();
	}
	public function getGermanWeekDay() {
		$wd = array(
			"Sonntag",
			"Montag",
			"Dienstag",
			"Mittwoch",
			"Donnerstag",
			"Freitag",
			"Samstag"
		);
		return $wd[$this->phpDate("w")];
	}
}
?>