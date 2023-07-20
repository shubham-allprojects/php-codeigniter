<?php


class Form
{

	static public function build_attr($attrs=array())
	{
		$result	= array();

		if( is_array($attrs) )
		{
			foreach( $attrs as $name=>$val )
				$result[]	= $name.'="'.$val.'"';
		}

		return implode(' ', $result);
	}

	static public function input($name, $value='', $attrs=array())
	{
		$attr	= Form::build_attr($attrs);
        return '<input type="text" name="'.$name.'" value="'.$value.'" '.$attr.' />';
	}
    
    static public function inputnum($name, $value='', $attrs=array())
	{
		$attr	= Form::build_attr($attrs);
        return '<input type="text" name="'.$name.'" value="'.$value.'" '.$attr.' onKeypress = "if(event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" style="IME-MODE:disabled;" />';
		
	}

	static public function password($name, $value='', $attrs=array())
	{
		$attr	= Form::build_attr($attrs);
		return '<input type="password" name="'.$name.'" value="'.$value.'" '.$attr.' />';
	}

	static public function hidden($name, $value='', $attrs=array())
	{
		$attr	= Form::build_attr($attrs);
		return '<input type="hidden" name="'.$name.'" value="'.$value.'" '.$attr.' />';
	}

	public function textarea($name, $value='', $attrs=array())
	{
		$attr	= Form::build_attr($attrs);
		return '<textarea name="'.$name.'" '.$attr.'>'.$value.'</textarea>';
	}

	static public function select($name, $value='', $options=array(), $attrs=array(), $first=FALSE)
	{
		$attr		= Form::build_attr($attrs);
		$elements	= array();
		if( $first !== FALSE )
		{
			$elements[] = '<option value="">'.$first.'</option>';
		}
		foreach( $options as $val=>$text )
		{
			if( ( is_array($value) && in_array($val, $value) ) || ( !empty($value) && $val == $value ) )
				$elements[] = '<option value="'.$val.'" selected>'.$text.'</option>';
			else
				$elements[] = '<option value="'.$val.'">'.$text.'</option>';
		}
		return '<select name="'.$name.'" '.$attr.'>'.implode($elements).'</select>';
	}

    public function select2($name, $options=array())
	{
		$elements	= array();
		foreach( $options as $val=>$text )
		{
			$elements[] = '<option value="'.$text.'">'.$text.'</option>';
		}
		return '<select name="'.$name.'" >'.implode($elements).'</select>';
	}
    
	static public function radio($name, $value='', $items=array(), $separator='&nbsp;', $attrs=array())
	{
		$attr		= Form::build_attr($attrs);
		$elements	= array();
		foreach( $items as $val=>$text )
		{			
			$unique	= uniqid();
			if( $value != "" && $val == $value )
 				$elements[] = '<input type="radio" id="'.$unique.'" name="'.$name.'" value="'.$val.'" '.$attr.' checked /><label for="'.$unique.'"> '.$text.'</label>';
			else
				$elements[] = '<input type="radio" id="'.$unique.'" name="'.$name.'" value="'.$val.'" '.$attr.' /><label for="'.$unique.'"> '.$text.'</label>';
		}
		return implode($separator, $elements);
	}

	public function checkbox($name, $text='', $checked=FALSE, $value='1', $attrs=array())
	{
		$attr		= Form::build_attr($attrs);
		$element	= '';

		$unique	= uniqid();
		if( $checked == $value )	$element = '<input type="checkbox" id="'.$unique.'" name="'.$name.'" value="'.$value.'" '.$attr.' checked />';
		else						$element = '<input type="checkbox" id="'.$unique.'" name="'.$name.'" value="'.$value.'" '.$attr.' />';

		if( !empty($text) )			$element .= ' <label for="'.$unique.'">'.$text.'</label>';

		return $element;
	}

	public function checkbox2($name, $text='', $checked=FALSE, $value='1', $attrs=array())
	{
		$attr		= Form::build_attr($attrs);
		$element	= '';

		$unique	= uniqid();
		if( $checked )				$element = '<input type="checkbox" id="'.$unique.'" name="'.$name.'" value="'.$value.'" '.$attr.' checked />';
		else						$element = '<input type="checkbox" id="'.$unique.'" name="'.$name.'" value="'.$value.'" '.$attr.' />';

		if( !empty($text) )			$element .= ' <label for="'.$unique.'">'.$text.'</label>';

		return $element;
	}
}


/* End of file Form.php */