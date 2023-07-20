<?php
namespace App\Libraries;

class Pagination
{

	const TYPE_JUMP		= 1;
	const TYPE_SLIDE	= 2;
	const TYPE_DEFAULT	= 1;

	var $current_page	= 1;
	var $total_row		= 0;
	var $total_page		= 0;
	var $row_size		= 20;
	var $page_size		= 10;
	var $offset			= 0;

	function init($options=array(), $set_page=20)
	{
		if(is_array($options))
		{
			foreach($options as $key => $val)		$this->$key = $val;
		}

		$this->row_size     = $set_page;
		$this->total_page	= (int)ceil($this->total_row / $this->row_size);
		$this->current_page	= min($this->current_page, $this->total_page);
		$this->current_page	= max($this->current_page, 1);
		$this->offset		= ($this->current_page-1) * $this->row_size;
	}

	function get_prev_page()
	{
		$page	= $this->current_page - 1;
		if($page < 1)		return FALSE;
		else				return $page;
	}

	function get_next_page()
	{
		$page	= $this->current_page + 1;
		if($page > $this->total_page)	return FALSE;
		else							return $page;
	}

	function get_pages($type=Pagination::TYPE_DEFAULT)
	{
		$pages	= array();

		if($type == Pagination::TYPE_SLIDE)
		{
			$begin	= max($this->current_page-floor($this->page_size/2), 1);
			$end	= min($this->current_page+floor($this->page_size/2), $this->total_page);
		}
		else
		{
			$begin	= max(($this->current_page-(($this->current_page-1)%$this->page_size)), 1);
			$end	= min(($begin+$this->page_size-1), $this->total_page);
		}

		$prev	= $this->get_prev_page();
		if( $prev != FALSE )
			$pages['prev']	= $prev;

		for($i=$begin; $i<=$end; $i++)
			$pages[$i]	= $i;

		$next	= $this->get_next_page();
		if( $next != FALSE )
			$pages['next']	= $next;

		return $pages;
	}

	function debug()
	{
		echo('<pre>');
		echo("===== debug =====\n");
		echo("row_size :\t".$this->row_size."\n");
		echo("page_size :\t".$this->page_size."\n");
		echo("total_row :\t".$this->total_row."\n");
		echo("total_page :\t".$this->total_page."\n");
		echo("current_page :\t".$this->current_page."\n");
		echo('</pre>');
	}

}


/* End of file Pagination.php */