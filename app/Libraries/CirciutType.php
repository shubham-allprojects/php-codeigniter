<?php


class CirciutType
{

	static $attrs	= array(
		'1' => 'NC,NO Dual Resistor',
		'2' => 'NC Parallel Resistor',
		'3' => 'NC Series Resistor',
		'4' => 'NC Unsupervised',
		'5' => 'NO Parallel Resistor',
		'6' => 'NO Series Resistor',
		'7' => 'NO Unsupervised',
		'8' => 'Custom'
	);

	static $sub_attrs	= array(
		'1' => array('name'=>'NC,NO Dual Resistor'
			,'nomal_low'	=> 462
			,'nomal_high'	=> 562
			,'T_open_low'	=> 923
			,'T_open_high'	=> 1023
			,'T_short_low'	=> 0
			,'T_short_high'	=> 100
			,'A_1_low'		=> 291
			,'A_1_high'		=> 391
			,'A_2_low'		=> 633
			,'A_2_high'		=> 733
		),
		'2' => array('name'=>'NC Parallel Resistor'
			,'nomal_low'	=> 0
			,'nomal_high'	=> 100
			,'T_open_low'	=> 923
			,'T_open_high'	=> 1023
			,'T_short_low'	=> 0
			,'T_short_high'	=> 0
			,'A_1_low'		=> 462
			,'A_1_high'		=> 562
			,'A_2_low'		=> 0
			,'A_2_high'		=> 0
		),
		'3' => array('name'=>'NC Series Resistor'
			,'nomal_low'	=> 462
			,'nomal_high'	=> 562
			,'T_open_low'	=> 0
			,'T_open_high'	=> 0
			,'T_short_low'	=> 0
			,'T_short_high'	=> 0
			,'A_1_low'		=> 923
			,'A_1_high'		=> 1023
			,'A_2_low'		=> 0
			,'A_2_high'		=> 0
		),
		'4' => array('name'=>'NC Unsupervised'
			,'nomal_low'	=> 0
			,'nomal_high'	=> 100
			,'T_open_low'	=> 0
			,'T_open_high'	=> 0
			,'T_short_low'	=> 0
			,'T_short_high'	=> 0
			,'A_1_low'		=> 923
			,'A_1_high'		=> 1023
			,'A_2_low'		=> 0
			,'A_2_high'		=> 0
		),
		'5' => array('name'=>'NO Parallel Resistor'
			,'nomal_low'	=> 462
			,'nomal_high'	=> 562
			,'T_open_low'	=> 923
			,'T_open_high'	=> 1023
			,'T_short_low'	=> 0
			,'T_short_high'	=> 0
			,'A_1_low'		=> 0
			,'A_1_high'		=> 100
			,'A_2_low'		=> 0
			,'A_2_high'		=> 0
		),
		'6' => array('name'=>'NO Series Resistor'
			,'nomal_low'	=> 923
			,'nomal_high'	=> 1023
			,'T_open_low'	=> 0
			,'T_open_high'	=> 0
			,'T_short_low'	=> 0
			,'T_short_high'	=> 0
			,'A_1_low'		=> 462
			,'A_1_high'		=> 562
			,'A_2_low'		=> 0
			,'A_2_high'		=> 0
		),
		'7' => array('name'=>'NO Unsupervised'
			,'nomal_low'	=> 923
			,'nomal_high'	=> 1023
			,'T_open_low'	=> 0
			,'T_open_high'	=> 0
			,'T_short_low'	=> 0
			,'T_short_high'	=> 0
			,'A_1_low'		=> 0
			,'A_1_high'		=> 100
			,'A_2_low'		=> 0
			,'A_2_high'		=> 0
		),
		'8' => array('name'=>'Custom'
			,'nomal_low'	=> 0
			,'nomal_high'	=> 0
			,'T_open_low'	=> 0
			,'T_open_high'	=> 0
			,'T_short_low'	=> 0
			,'T_short_high'	=> 0
			,'A_1_low'		=> 0
			,'A_1_high'		=> 0
			,'A_2_low'		=> 0
			,'A_2_high'		=> 0
		)
	);

}


?>