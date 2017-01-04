<?php
// NICHT IM SVN-REPOSITORY, Änderungen werden nicht auf das Livesystem übernommen
/**
 * Class for caching an output into Filesystem
 *
 * Usage:
 * ======
 * Put this before:
 *
 * if(FileCache::cacheApplies()) {
 * 	FileCache::printCache();
 * } else {
 * 	FileCache::startRecord();
 *
 * And this after your code:
 * 	FileCache::endRecord();
 * }
 *
 */
class FileCache {
	/**
	 * default options:
	 * tmpDir  => Where to store, uses sys_get_temp_dir() if "default"
	 * tmpName => Name of tempfile, uses
	 * 		md5($_SERVER["SCRIPT_NAME"].$_SERVER["QUERY_STRING"]),
	 * 		if "default"
	 * mageAgeSec => maximum age in sec
	 */
	
	private static $options = array(
		"tmpDir" => "default",
		"tmpName"=>"default",
		"maxAgeSec"=>300
	);
	/**
	 * True, if tempfile is older than maxAgeSec
	 */
	public static function cacheApplies(array $customOptions = null) {
		$options = self::getOptions($customOptions);
		if(!is_file(self::getTmpFile($customOptions))) {
			return false;
		} else {
			return (time() - filemtime(self::getTmpFile($customOptions)))
				< intval($options["maxAgeSec"]);

		}
	}
	/**
	 * Starts recording
	 */
	public static function startRecord(array $customOptions = null) {
		ob_start();
	}
	/**
	 * Ends recording
	 */
	public static function endRecord(array $customOptions = null) {
		$res = ob_get_clean();
		echo $res;
		file_put_contents(self::getTmpFile($customOptions), $res);
	}
	/**
	 * Prints content of cache-file
	 */
	public static function printCache(array $customOptions = null) {
		echo file_get_contents(self::getTmpFile($customOptions));
	}
	private static function getTmpFile(array $customOptions = null) {
		$options = self::getOptions($customOptions);
		return $options["tmpDir"].
				DIRECTORY_SEPARATOR.
				$options["tmpName"];
	}
	/**
	 * Manufactures  default-options and customOptions
	 */
	private static function getOptions(array $customOptions = null) {
		$options = self::$options;
		if(!is_null($customOptions)) {
			$options = array_merge($options, $customOptions);
		}
		foreach($options as $k=>$v) {
			switch($k) {
				case "tmpDir":
					if($v == "default") {
						$options[$k] = sys_get_temp_dir().DIRECTORY_SEPARATOR.$_SERVER["HTTP_HOST"];
					}
					break;
				case "tmpName":
					if($v == "default") {
						$string = "";
						if(array_key_exists("SCRIPT_NAME", $_SERVER)) {
							$string = $_SERVER["SCRIPT_NAME"];
						}
						if(array_key_exists("QUERY_STRING", $_SERVER)) {
							$string .= $_SERVER["QUERY_STRING"];
						}
						$options[$k] = md5($string);
					}
					break;
			}
		}
		return $options;
	}
}
