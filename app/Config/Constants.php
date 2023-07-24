<?php

/*
|--------------------------------------------------------------------
|AppNamespace
|--------------------------------------------------------------------
|
|ThisdefinesthedefaultNamespacethatisusedthroughout
|CodeIgnitertorefertotheApplicationdirectory.Change
|thisconstanttochangethenamespacethatallapplication
|classesshoulduse.
|
|NOTE:changingthiswillrequiremanuallymodifyingthe
|existingnamespacesofApp\*namespaced-classes.
*/
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
|--------------------------------------------------------------------------
|ComposerPath
|--------------------------------------------------------------------------
|
|ThepaththatComposer'sautoloadfileisexpectedtolive.Bydefault,
|thevendorfolderisintheRootdirectory,butyoucancustomizethathere.
*/
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
|--------------------------------------------------------------------------
|TimingConstants
|--------------------------------------------------------------------------
|
|ProvidesimplewaystoworkwiththemyriadofPHPfunctionsthat
|requireinformationtobeinseconds.
*/
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR') || define('HOUR', 3600);
defined('DAY') || define('DAY', 86400);
defined('WEEK') || define('WEEK', 604800);
defined('MONTH') || define('MONTH', 2_592_000);
defined('YEAR') || define('YEAR', 31_536_000);
defined('DECADE') || define('DECADE', 315_360_000);

/*
|--------------------------------------------------------------------------
|ExitStatusCodes
|--------------------------------------------------------------------------
|
|Usedtoindicatetheconditionsunderwhichthescriptisexit()ing.
|Whilethereisnouniversalstandardforerrorcodes,therearesome
|broadconventions.Threesuchconventionsarementionedbelow,for
|thosewhowishtomakeuseofthem.TheCodeIgniterdefaultswere
|chosenfortheleastoverlapwiththeseconventions,whilestill
|leavingroomforotherstobedefinedinfutureversionsanduser
|applications.
|
|Thethreemainconventionsusedfordeterminingexitstatuscodes
|areasfollows:
|
|StandardC/C++Library(stdlibc):
|http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|(ThislinkalsocontainsotherGNU-specificconventions)
|BSDsysexits.h:
|http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|Bashscripting:
|http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS') || define('EXIT_SUCCESS', 0); //noerrors
defined('EXIT_ERROR') || define('EXIT_ERROR', 1); //genericerror
defined('EXIT_CONFIG') || define('EXIT_CONFIG', 3); //configurationerror
defined('EXIT_UNKNOWN_FILE') || define('EXIT_UNKNOWN_FILE', 4); //filenotfound
defined('EXIT_UNKNOWN_CLASS') || define('EXIT_UNKNOWN_CLASS', 5); //unknownclass
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); //unknownclassmember
defined('EXIT_USER_INPUT') || define('EXIT_USER_INPUT', 7); //invaliduserinput
defined('EXIT_DATABASE') || define('EXIT_DATABASE', 8); //databaseerror
defined('EXIT__AUTO_MIN') || define('EXIT__AUTO_MIN', 9); //lowestautomatically-assignederrorcode
defined('EXIT__AUTO_MAX') || define('EXIT__AUTO_MAX', 125); //highestautomatically-assignederrorcode

/**
 *@deprecatedUse\CodeIgniter\Events\Events::PRIORITY_LOWinstead.
 */
define('EVENT_PRIORITY_LOW', 200);

/**
 *@deprecatedUse\CodeIgniter\Events\Events::PRIORITY_NORMALinstead.
 */
define('EVENT_PRIORITY_NORMAL', 100);

/**
 *@deprecatedUse\CodeIgniter\Events\Events::PRIORITY_HIGHinstead.
 */
define('EVENT_PRIORITY_HIGH', 10);


//SPIDERCONSTANTS
define('BASE_URL', '.');
define('TARGET_BOARD', 'EVB');
define('FLOOR_DIR', BASE_URL . '/floor_img');
define('FLOOR_URL', BASE_URL . '/floor_img');
define('USER_IMG', BASE_URL . '/user_img');
define('TMPDATA_URL', BASE_URL . '/tmp_data');
define('LANGUAGE_DIR', WRITEPATH . '/language');

define('SPIDER_COMM', "/spider/sicu/spider-cgi");
define('IMPORT_CSV', "/spider/importCSV.py");
define('SPIDER_TITLE', "LineareMerge");
define('SECRET_PASSWORD', 'ZGF2ZXN0eWxl');
define('BADGING_DIR', BASE_URL . '/badging');
define('PAGE_CONFIG', array('current_page', 'total_row'));

define('EXT', '.php');
define('BR', '<br/>');
define('DS', '/');
define('LF', "\n");
define('CR', "\r");


//Modified-21-07-2023
DEFINE('INDIVIDUAL', '1');
DEFINE('GROUP', '2');
DEFINE('GROUP_CARDHOLDER', '1');
DEFINE('GROUP_DOOR', '2');
DEFINE('GROUP_FLOOR', '3');
DEFINE('GROUP_SCHEDULE', '4');
DEFINE('GROUP_ACCESS_LEVEL', '5');
DEFINE('GROUP_AUXINPUT', '6');
DEFINE('GROUP_AUXOUTPUT', '7');
DEFINE('GROUP_CLIENT', '8');
DEFINE('GROUP_READER', '9');
DEFINE('GROUP_USER', '10');
DEFINE('GROUP_ELEVATOR', '11');
DEFINE('GROUP_CAMERA', '12');

DEFINE('DEVICE_DOOR', '1');
DEFINE('DEVICE_ELEVATOR', '2');
DEFINE('DEVICE_AUXINPUT', '3');
DEFINE('DEVICE_AUXOUTPUT', '4');

DEFINE('MODEL_ESSENTIAL', '1');
DEFINE('MODEL_ELITE', '2');
DEFINE('MODEL_ENTERPRISE', '3');
DEFINE('MODEL_CLIENT', '4');
DEFINE('MODEL_ELITE_CLIENT', '4');
DEFINE('MODEL_ENTERPRISE_CLIENT', '5');
DEFINE('MODEL_ELITE_ELEVATOR', '6');
DEFINE('MODEL_ENTERPRISE_ELEVATOR', '7');
//ADDCJMOON2017.04.19
DEFINE('MODEL_TE_STANDALONE', '8');
DEFINE('MODEL_TE_SERVER', '9');
DEFINE('MODEL_TE_CLIENT', '10');

DEFINE('CONTROLLER_TYPE_ESSENTIAL_1', '1');
DEFINE('CONTROLLER_TYPE_ESSENTIAL_2', '2');
DEFINE('CONTROLLER_TYPE_ESSENTIAL_3', '3');
DEFINE('CONTROLLER_TYPE_ESSENTIAL_4', '4');
DEFINE('CONTROLLER_TYPE_ELITE_36', '4');

DEFINE('MAX_CONNECT_1', 8);
DEFINE('MAX_CONNECT_2', 16);
DEFINE('MAX_CONNECT_3', 32);

DEFINE('MAX_USER_1', 1000);
DEFINE('MAX_USER_2', 20000);
DEFINE('MAX_USER_3', 60000);

DEFINE('MAX_CARD_1', 8000);
DEFINE('MAX_CARD_2', 8000);
DEFINE('MAX_CARD_3', 120000);

DEFINE('MAX_USERCARD_1', 12);
DEFINE('MAX_USERCARD_2', 120);
DEFINE('MAX_USERCARD_3', 120);

DEFINE('MAX_CARDFORMAT_1', 32);
DEFINE('MAX_CARDFORMAT_2', 32);
DEFINE('MAX_CARDFORMAT_3', 32);

DEFINE('MAX_ACCESSLEVEL_1', 8);
DEFINE('MAX_ACCESSLEVEL_2', 16);
DEFINE('MAX_ACCESSLEVEL_3', 32);

DEFINE('MAX_USERDEFINE_1', 5);
DEFINE('MAX_USERDEFINE_2', 10);
DEFINE('MAX_USERDEFINE_3', 20);

DEFINE('MAX_SCHEDULE_1', 100);
DEFINE('MAX_SCHEDULE_2', 512);
DEFINE('MAX_SCHEDULE_3', 512);

DEFINE('MAX_HOLIDAY_1', 30);
DEFINE('MAX_HOLIDAY_2', 60);
DEFINE('MAX_HOLIDAY_3', 60);

DEFINE('MAX_TRANSACTION_1', 15000);
DEFINE('MAX_TRANSACTION_2', 30000);
DEFINE('MAX_TRANSACTION_3', 50000);

DEFINE('OPTION_PARTITION', 2);
DEFINE('OPTION_VMS_DW', 10);
DEFINE('OPTION_RMR', 0);
DEFINE('OPTION_NVR', 8);
DEFINE('OPTION_BADGING', 80);


DEFINE('max_name_char', 30);
DEFINE('max_password_char', 12);
DEFINE('max_admin_id_char', 12);
DEFINE('max_first_name_char', 30);
DEFINE('max_middle_name_char', 30);
DEFINE('max_last_name_char', 30);
DEFINE('max_phone_number_char', 20);
DEFINE('max_cell_phone_char', 20);
DEFINE('max_email_char', 80);
DEFINE('max_card_key_number_char', 10);
DEFINE('max_directory_code_char', 20);
DEFINE('max_entry_code_char', 8);

DEFINE('max_card_format_name_char', 20);
DEFINE('max_description_char', 30);
DEFINE('max_total_bit_length_char', 3);
DEFINE('max_faciltity_code_char', 10);
DEFINE('max_facility_code_start_bit_char', 3);
DEFINE('max_faciltity_code_length_char', 3);
DEFINE('max_card_number_start_bit_char', 3);
DEFINE('max_card_number_length_char', 3);
DEFINE('max_access_level_name_char', 30);
DEFINE('max_schedule_day_char', 2);

DEFINE('max_threat_level_char', 30);
DEFINE('max_door_held_open_time_char', 3);
DEFINE('max_door_ada_open_time_char', 3);
DEFINE('max_door_unlock_time_char', 3);
DEFINE('max_door_timeanti_passback_char', 3);
DEFINE('max_door_roomanti_passback_char', 3);

DEFINE('max_controller_location_char', 30);
DEFINE('max_automaticbackup_percentage_char', 2);
DEFINE('max_filter_door_no_char', 30);
DEFINE('max_filter_door_floorname_char', 30);
DEFINE('max_filter_door_name_char', 30);