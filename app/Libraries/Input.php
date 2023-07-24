<?php
namespace App\Libraries;

class Input
{
	const CLEAN_HTML = 1;
	const CLEAN_TEXT = 2;
	const CLEAN_RAWDATA = 3;
	const CLEAN_NUM = 4;

	static $never_allowed_regex = array(
		"javascript\s*:" => '',
		"expression\s*(\(|&\#40;)" => '',
		// CSS and IE
		"vbscript\s*:" => '',
		// IE, surprise!
		"Redirect\s+302" => ''
	);

	public function val($str, $default = '', $clean = Input::CLEAN_HTML)
	{
		if (isset($_ENV[$str]))
			return $_ENV[$str];
		if (isset($_SERVER[$str]))
			return $_SERVER[$str];
		if (isset($_FILES[$str]))
			return $_FILES[$str];

		if (isset($_POST[$str])) {
			$val = Input::clean_data($_POST[$str], $clean);
			if (isset($val))
				return $val;
		}

		if (isset($_GET[$str])) {
			$val = Input::clean_data($_GET[$str], $clean);
			if (isset($val))
				return $val;
		}

		return $default;
	}

	static public function get($str, $default = '', $clean = Input::CLEAN_HTML)
	{
		if (isset($_GET[$str])) {
			$val = Input::clean_data($_GET[$str], $clean);
			if (isset($val))
				return $val;
		}

		return $default;
	}

	static public function post($str, $default = '', $clean = Input::CLEAN_HTML)
	{
		if (isset($_POST[$str])) {
			$val = Input::clean_data($_POST[$str], $clean);
			if (isset($val))
				return $val;
		}

		return $default;
	}

	static public function param($str, $default = '', $clean = Input::CLEAN_HTML)
	{
		if (isset($_POST[$str])) {
			$val = Input::clean_data($_POST[$str], $clean);
			if (isset($val))
				return $val;
		}

		if (isset($_GET[$str])) {
			$val = Input::clean_data($_GET[$str], $clean);
			if (isset($val))
				return $val;
		}

		return $default;
	}

	static function clean_data($str, $clean = Input::CLEAN_HTML)
	{
		if (is_array($str)) {
			$new_array = array();
			foreach ($str as $key => $val) {
				$new_array[Input::clean_keys($key)] = Input::clean_data($val);
			}
			return $new_array;
		}

		// if( get_magic_quotes_gpc() )
		// {
		// 	$str = stripslashes($str);
		// }

		if (strpos($str, "\r") !== FALSE) {
			$str = str_replace(array("\r\n", "\r"), "\n", $str);
		}

		foreach (Input::$never_allowed_regex as $key => $val) {
			$str = preg_replace("#" . $key . "#i", $val, $str);
		}

		switch ($clean) {
			case Input::CLEAN_HTML:
				$pattern = array("'<script[^>]*?>.*?</script>'si");
				$replace = array("");
				$str = preg_replace($pattern, $replace, $str);
				break;
			case Input::CLEAN_NUM:
				if (!is_numeric($str))
					$str = '';
				break;
			case Input::CLEAN_RAWDATA:
				// 원본 그대로
				break;
			case Input::CLEAN_TEXT:
			default:
				$str = htmlspecialchars($str);
				$str = nl2br($str);
				break;
		}

		return $str;
	}

	static public function clean_keys($str)
	{
		if (preg_match("/^[a-z0-9:_\/-]+$/i", $str))
			return $str;
		else
			return '';
	}

	function querystring_to_array($vars = array(), $flag = FALSE)
	{
		$params = array();

		foreach ($_GET as $key => $val) {
			if (in_array($key, $vars) == $flag) {
				$params[$key] = $val;
			}
		}

		return $params;
	}

}


/* End of file Input.php */