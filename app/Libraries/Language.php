<?php


if( !defined('LANGUAGE_DIR') )		define('LANGUAGE_DIR',	'language');

class Language
{
	var $_lang		= 'en';
	var $messages	= array();

	public function Language($lang='')
	{
		if( !empty($lang) )			$this->_lang	= $lang;
		elseif( defined('LANG') )	$this->_lang	= LANG;
	}

	public function __set($name, $value)
	{
		$this->messages[$name]	= $value;
	}

	public function __get($name)
	{
		if( !array_key_exists($name, $this->messages) )
			$this->$name	= new Language_Message($name, $this->_lang);

		return $this->messages[$name];
	}

	public function __isset($name)
	{
		return isset($this->messages[$name]);
	}

	public function __unset($name)
	{
		unset($this->messages[$name]);
	}

}


class Language_Message
{
	var $dir	= 'language';
	var $kind;
	var $_lang;
	var $messages	= array();

	public function Language_Message($kind, $_lang)
	{
		if( defined('LANGUAGE_DIR') )	$this->dir	= LANGUAGE_DIR;
		$this->init($kind, $_lang);
	}

	public function init($kind, $_lang)
	{
		$this->kind		= $kind;
		$this->_lang		= $_lang;

		$path	= $this->dir.DS.$this->_lang.DS.$this->kind.'.json';

		if( file_exists($path))
		{
			$this->messages	= json_decode(file_get_contents($path), TRUE);
		}
			


//		echo $path;
	}

	public function __set($name, $value)
	{
		$this->messages[$name]	= $value;
	}

	public function __get($name)
	{
		if( array_key_exists($name, $this->messages) )
			return $this->messages[$name];
		else
			return $name;
	}

	public function __isset($name)
	{
		return isset($this->messages[$name]);
	}

	public function __unset($name)
	{
		unset($this->messages[$name]);
	}

	public function getMessages()
	{
		return $this->messages;
	}

}


/* End of file Language.php */