<?php

ini_set('max_execution_time', 300);

class Uploader
{
    var $form_name          = 'upload';
    var $upload_dir         = NULL;
    var $make_upload_dir    = FALSE;
    var $allowed_types      = 'jpg|jpeg|gif|png|bmp';
    var $max_size           = 153600;
    var $max_width          = 0;
    var $max_height         = 0;
    var $file_name          = NULL;
    var $overwrite          = FALSE;
    var $encrypt_name       = FALSE;
    var $remove_spaces      = TRUE;

    var $errors         = 'Upload Error';
    var $org_file       = NULL;
    var $save_file      = NULL;
    
    var $lang;

    public function do_upload( $form_name=NULL, $set_max_size=153600 )
    {
    	$this->lang         = new Language();
    	$this->max_size = $set_max_size;
        if( ! $this->vaild_upload($form_name) )     return FALSE;

        $file_name  = $this->upload_dir.'/'.$this->file_name.'.'.$this->org_file['ext'];
        if( move_uploaded_file($_FILES[$this->form_name]['tmp_name'], $file_name) )
        {
            $this->save_file    = pathinfo($file_name);
            return TRUE;
        }

        $this->set_error($lang->uploader->fail_upload);
        return FALSE;
    }

    public function vaild_upload( $form_name=NULL )
    {
        if( ! empty($form_name) )       $this->form_name = $form_name;

        if( ! is_uploaded_file($_FILES[$this->form_name]['tmp_name']) )
        {
            $this->set_error($this->lang->uploader->fail_upload);
            return FALSE;
        }

        $this->org_file         = $_FILES[$this->form_name];
        $this->org_file['ext']  = pathinfo($this->org_file['name'], PATHINFO_EXTENSION);

        if( empty($this->upload_dir) )
        {
            $this->set_error($this->lang->uploader->invalid_dir);
            return FALSE;
        }

        $this->upload_dir   = trim($this->upload_dir);
        $this->upload_dir   = rtrim($this->upload_dir, '/');
        
        if( $this->make_upload_dir )
        {
            if( ! $this->mkdir($this->upload_dir) )
            {
                $this->set_error($this->lang->uploader->not_exist_dir);
                return FALSE;
            }
        }
        else
        {
            if( ! is_dir($this->upload_dir) )
            {
            	mkdir($this->upload_dir);
            	
	            if( ! is_dir($this->upload_dir) )
	            {
    	            $this->set_error($this->lang->uploader->not_exist_dir);
        	        return FALSE;
        	    }
            }
        }
        if( empty($this->allowed_types) )
        {
            $this->set_error($this->lang->uploader->not_define_file);
            return FALSE;
        }
        else if( $this->allowed_types != '*' )
        {
            $types  = explode('|', strtolower($this->allowed_types));
            if( ! in_array(strtolower($this->org_file['ext']), $types) )
            {
                $this->set_error($this->lang->uploader->not_allow_file);
                return FALSE;
            }
        }
        if( $this->max_size > 0 )
        {
            if( $this->max_size < $this->org_file['size'] )
            {
                //if( method_exists('Formatter', 'bytes') ) $max_size = Formatter::bytes($this->org_file['size']);
                //else                                      $max_size = number_format($this->max_size).'bytes';

                $this->set_error($this->lang->uploader->over_max_file_size.$this->max_size.' Bytes.');
                return FALSE;
            }
        }
/*
        if( function_exists('getimagesize') )
        {
            if( FALSE !== ($image_info = @getimagesize($path)) )
            {
                $types = array(1 => 'gif', 2 => 'jpeg', 3 => 'png');

                $this->org_file['image_width']      = $image_info[0];
                $this->org_file['image_height']     = $image_info[1];
                $this->org_file['image_type']       = ( ! isset($types[$image_info['2']])) ? 'unknown' : $types[$image_info['2']];
                $this->org_file['image_size_str']   = $image_info[3];

                if( $this->max_width < $this->org_file['image_width'] )
                {
                    $this->set_error($this->lang->uploader->over_image_width.$this->max_width);
                    return FALSE;
                }

                if( $this->max_height < $this->org_file['image_height'] )
                {
                    $this->set_error($this->lang->uploader->over_image_height.$this->max_height);
                    return FALSE;
                }
            }
        }
*/
        if( empty($this->file_name) )
        {
            if( $this->encrypt_name )   $this->file_name = md5( $this->org_file['name'].time() );
            else                        $this->file_name = pathinfo($this->org_file['name'], PATHINFO_FILENAME);

            if( $this->remove_spaces )      str_replace(' ', '_', $this->file_name);

            if( $this->overwrite === FALSE )
            {
                if( file_exists($this->upload_dir.'/'.$this->file_name.'.'.$this->org_file['ext']) )
                {
                    $this->file_name    = $this->file_name.'_'.time();
                }
            }
        }
        
        return TRUE;
    }

    public function set_error( $error )
    {
        $this->errors = $error;
    }

    public function get_errors()
    {
   		return $this->errors;
    }

    public function mkdir( $dir )
    {
        $dir    = trim($dir);
        $dir    = rtrim($dir, '/');

        if( is_dir($dir) )
        {
            return TRUE;
        }
        else
        {
            $pos    = strrpos($dir, '/');
            $sub    = substr($dir, 0, $pos);
            if( $this->mkdir($sub) )
            {
                return mkdir($dir);
            }
        }

        return FALSE;
    }

}


/* End of file Uploader.php */