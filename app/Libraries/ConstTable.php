<?php


class ConstTable
{
    const INDIVIDUAL        	 = '1';
    const GROUP                  = '2';

    const GROUP_CARDHOLDER       = '1';
    const GROUP_DOOR          	 = '2';
    const GROUP_FLOOR         	 = '3';
    const GROUP_SCHEDULE      	 = '4';
    const GROUP_ACCESS_LEVEL     = '5';
    const GROUP_AUXINPUT      	 = '6';
    const GROUP_AUXOUTPUT     	 = '7';
    const GROUP_CLIENT        	 = '8';
    const GROUP_READER           = '9';
    const GROUP_USER          	 = '10';
    const GROUP_ELEVATOR    	 = '11';
    const GROUP_CAMERA    	     = '12';

    const DEVICE_DOOR         	 = '1';
    const DEVICE_ELEVATOR        = '2';
    const DEVICE_AUXINPUT        = '3';
    const DEVICE_AUXOUTPUT       = '4';

	const MODEL_ESSENTIAL    		= '1';
	const MODEL_ELITE    			= '2';
	const MODEL_ENTERPRISE    		= '3';
	const MODEL_CLIENT    			= '4';
	const MODEL_ELITE_CLIENT		= '4';
	const MODEL_ENTERPRISE_CLIENT	= '5';
	const MODEL_ELITE_ELEVATOR		= '6';
	const MODEL_ENTERPRISE_ELEVATOR	= '7';
// ADD CJMOON 2017.04.19
	const MODEL_TE_STANDALONE		= '8';
	const MODEL_TE_SERVER			= '9';
	const MODEL_TE_CLIENT			= '10';

	const CONTROLLER_TYPE_ESSENTIAL_1    	 = '1';
	const CONTROLLER_TYPE_ESSENTIAL_2    	 = '2';
	const CONTROLLER_TYPE_ESSENTIAL_3    	 = '3';
	const CONTROLLER_TYPE_ESSENTIAL_4    	 = '4';
	const CONTROLLER_TYPE_ELITE_36    		 = '4';

    const MAX_CONNECT_1		 	= 8;
    const MAX_CONNECT_2			= 16;
    const MAX_CONNECT_3			= 32;

	const MAX_USER_1           	= 1000;
    const MAX_USER_2           	= 20000;
    const MAX_USER_3           	= 60000;

    const MAX_CARD_1           	= 8000;
    const MAX_CARD_2           	= 8000;
    const MAX_CARD_3           	= 120000;

    const MAX_USERCARD_1       	= 12;
    const MAX_USERCARD_2       	= 120;
    const MAX_USERCARD_3       	= 120;

    const MAX_CARDFORMAT_1		= 32;
    const MAX_CARDFORMAT_2  	= 32;
    const MAX_CARDFORMAT_3  	= 32;

    const MAX_ACCESSLEVEL_1		= 8;
    const MAX_ACCESSLEVEL_2		= 16;
    const MAX_ACCESSLEVEL_3		= 32;

    const MAX_USERDEFINE_1		= 5;
    const MAX_USERDEFINE_2  	= 10;
    const MAX_USERDEFINE_3  	= 20;

    const MAX_SCHEDULE_1		= 100;
    const MAX_SCHEDULE_2  		= 512;
    const MAX_SCHEDULE_3  		= 512;

    const MAX_HOLIDAY_1			= 30;
    const MAX_HOLIDAY_2  		= 60;
    const MAX_HOLIDAY_3  		= 60;

    const MAX_TRANSACTION_1     = 15000;
    const MAX_TRANSACTION_2     = 30000;
    const MAX_TRANSACTION_3    	= 50000;

    const OPTION_PARTITION      = 2;
    const OPTION_VMS_DW  		= 10;
    const OPTION_RMR	  		= 0;
    const OPTION_NVR	  		= 8;
    const OPTION_BADGING        = 80;
    
    /* Maxlength validation constants for input fields */
    
    const max_name_char = 30;
    const max_password_char = 12;
    const max_admin_id_char = 12;
    const max_first_name_char = 30;
    const max_middle_name_char = 30;
    const max_last_name_char = 30;
    const max_phone_number_char = 20;
    const max_cell_phone_char = 20;
    const max_email_char = 80;
    const max_card_key_number_char = 10;
    const max_directory_code_char = 20;
    const max_entry_code_char = 8;
    
    const max_card_format_name_char = 20;
    const max_description_char = 30;
    const max_total_bit_length_char = 3;
    const max_faciltity_code_char = 10;
    const max_facility_code_start_bit_char = 3;
    const max_faciltity_code_length_char = 3;
    const max_card_number_start_bit_char = 3;
    const max_card_number_length_char = 3;
    const max_access_level_name_char = 30;
    const max_schedule_day_char = 2;
    
    const max_threat_level_char = 30;
    const max_door_held_open_time_char = 3;
    const max_door_ada_open_time_char = 3;
    const max_door_unlock_time_char = 3;
    const max_door_timeanti_passback_char = 3;
    const max_door_roomanti_passback_char = 3;
    
    const max_controller_location_char = 30;
    const max_automaticbackup_percentage_char = 2;
    const max_filter_door_no_char = 30;
    const max_filter_door_floorname_char = 30;
    const max_filter_door_name_char = 30;
}


?>