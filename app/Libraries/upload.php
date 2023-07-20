<<script type="text/javascript">

</script>


<?php
		$fp = fopen('D:\zend_workspace\New_Spider\spider\web\webroot\floor_img\aaaa.txt', "w"); 
//		fwrite($fp, "aaaa".$_FILES[$this->form_name]['tmp_name']); 
		fwrite($fp, "aaaa"); 
		fclose($fp);

class Uploader
{

	var $form_name			= 'upload';
	var $upload_dir			= NULL;
	var $make_upload_dir	= FALSE;
	var $allowed_types		= 'jpg|jpeg|gif|png|bmp';
	var $max_size			= 0;
	var $max_width			= 0;
	var $max_height			= 0;
	var $file_name			= NULL;
	var $overwrite			= FALSE;
	var $encrypt_name		= FALSE;
	var $remove_spaces		= TRUE;

	var $errors			= array();
	var $org_file		= NULL;
	var $save_file		= NULL;

	
	public function do_upload( $form_name=NULL )
	{
		$fp = fopen('D:\zend_workspace\New_Spider\spider\web\webroot\floor_img\aaaa.txt', "w"); 
//		fwrite($fp, "aaaa".$_FILES[$this->form_name]['tmp_name']); 
		fwrite($fp, "aaaa"); 
		fclose($fp);
		
		
		if( ! $this->vaild_upload($form_name) )		return FALSE;

		$file_name	= 'D:\zend_workspace\New_Spider\spider\webroot\floor_img\aaaa.jpg';
//		$file_name	= $this->upload_dir.'/'.$this->file_name.'.'.$this->org_file['ext'];
		
		if( move_uploaded_file($_FILES[$this->form_name]['tmp_name'], $file_name) )
		{
			$this->save_file	= pathinfo($file_name);
			return TRUE;
		}

		$this->set_error('파일업로드에 실패 하였습니다.1');
		return FALSE;
	}

	public function vaild_upload( $form_name=NULL )
	{
		if( ! empty($form_name) )		$this->form_name = $form_name;

		if( ! is_uploaded_file($_FILES[$this->form_name]['tmp_name']) )
		{
			$this->set_error('파일업로드에 실패 하였습니다.2');
			return FALSE;
		}

		$this->org_file			= $_FILES[$this->form_name];
		$this->org_file['ext']	= pathinfo($this->org_file['name'], PATHINFO_EXTENSION);

		if( empty($this->upload_dir) )
		{
			$this->set_error('업로드 디렉토리가 올바르지 않습니다.');
			return FALSE;
		}

		$this->upload_dir	= trim($this->upload_dir);
		$this->upload_dir	= rtrim($this->upload_dir, '/');
		if( $this->make_upload_dir )
		{
			if( ! $this->mkdir($this->upload_dir) )
			{
				$this->set_error('업로드 디렉토리가 존재하지 않습니다.');
				return FALSE;
			}
		}
		else
		{
			if( ! is_dir($this->upload_dir) )
			{
				$this->set_error('업로드 디렉토리가 존재하지 않습니다.');
				return FALSE;
			}
		}

		if( empty($this->allowed_types) )
		{
			$this->set_error('업로드 파일형식이 정의되지 않았습니다.');
			return FALSE;
		}
		else if( $this->allowed_types != '*' )
		{
			$types	= explode('|', $this->allowed_types);
			if( ! in_array($this->org_file['ext'], $types) )
			{
				$this->set_error('허가되지 않은 파일형식 입니다.');
				return FALSE;
			}
		}

		if( $this->max_size > 0 )
		{
			if( $this->max_size > $this->org_file['size'] )
			{
				//if( method_exists('Formatter', 'bytes') )	$max_size = Formatter::bytes($this->org_file['size']);
				//else										$max_size = number_format($this->max_size).'bytes';

//				$this->set_error('파일 용량은 '.$this->max_size.'bytes 까지만 가능 합니다.');
				return FALSE;
			}
		}
/*
		if( function_exists('getimagesize') )
		{
			if( FALSE !== ($image_info = @getimagesize($path)) )
			{
				$types = array(1 => 'gif', 2 => 'jpeg', 3 => 'png');

				$this->org_file['image_width']		= $image_info[0];
				$this->org_file['image_height']		= $image_info[1];
				$this->org_file['image_type']		= ( ! isset($types[$image_info['2']])) ? 'unknown' : $types[$image_info['2']];
				$this->org_file['image_size_str']	= $image_info[3];

				if( $this->max_width < $this->org_file['image_width'] )
				{
					$this->set_error('이미지 가로 크기는 '.$this->max_width.' 까지만 가능 합니다.');
					return FALSE;
				}

				if( $this->max_height < $this->org_file['image_height'] )
				{
					$this->set_error('이미지 세로 크기는 '.$this->max_height.' 까지만 가능 합니다.');
					return FALSE;
				}
			}
		}
*/
		if( empty($this->file_name) )
		{
			if( $this->encrypt_name )	$this->file_name = md5( $this->org_file['name'].time() );
			else						$this->file_name = pathinfo($this->org_file['name'], PATHINFO_FILENAME);

			if( $this->remove_spaces )		str_replace(' ', '_', $this->file_name);

			if( $this->overwrite === FALSE )
			{
				if( file_exists($this->upload_dir.'/'.$this->file_name.'.'.$this->org_file['ext']) )
				{
					$this->file_name	= $this->file_name.'_'.time();
				}
			}
		}

		return TRUE;
	}

	public function set_error( $error )
	{
		$this->errors[]	= $error;
	}

	public function get_errors()
	{
		return $this->errors;
	}

	public function mkdir( $dir )
	{
		$dir	= trim($dir);
		$dir	= rtrim($dir, '/');

		if( is_dir($dir) )
		{
			return TRUE;
		}
		else
		{
			$pos	= strrpos($dir, '/');
			$sub	= substr($dir, 0, $pos);
			if( $this->mkdir($sub) )
			{
				return mkdir($dir);
			}
		}

		return FALSE;
	}

}

		$uploader					= new Uploader();
		$uploader->upload_dir		= FLOOR_DIR;
 		$uploader->do_upload('Filedata');
		if( $uploader->do_upload('Filedata') )
		{
			echo $uploader->save_file['basename'];
		}
		else
		{
			Util::alert( $uploader->get_errors() );
		}
/* End of file Uploader.php */