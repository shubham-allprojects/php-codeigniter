<?php
namespace App\Libraries;

class EnumTable
{
	static $attrResidentType    = array('0'=>'Main Resident', '1'=>'Co-Resident');
	static $attrDirectoryListed = array('0'=>'No', '1'=>'Yes');
	/* ADD CJMOON 2017.03.21 */
    static $attrVacationMode = array('0'=>'No', '1'=>'Yes');
    static $attrDisturb = array('0'=>'No', '1'=>'Yes');
	static $attrPostalLock = array('0'=>'No', '1'=>'Yes');
	/* END ADD */
	
    static $attrChildType       = array('1'=>'Sub Region', '2'=>'Child Region');
	static $attrRegionDepth 	= array('1'=>'Class 1', '2'=>'Class 2', '3'=>'Class 3', '4'=>'Class 4', '5'=>'Class 5');
	static $attrViolationTypes 	= array('0'=>'None', '1'=>'Soft', '2'=>'Hard');

	static $attrNVRRecordType	= array('0'=>'No Recording', '1'=>'Continuous Recording', '2'=>'Schedule Recording', '3'=>'Event Recording');

	static $attrDataExists	= array('0'=>'Skip', '1'=>'Overwrite', '2'=>'Flush & Overwrite');
	static $attrDataExistsAPI	= array('0'=>'Overwrite', '1'=>'Skip');

    static $attrADA         = array('0'=>'No', '1'=>'Yes');
    static $attrExempt      = array('0'=>'No', '1'=>'Yes');
    static $attrWebLevel    = array('0'=>'None','1'=>'Admin','2'=>'User');
    static $attrGroup       = array('1'=>'Individual', '2'=>'Group');
    static $attrGroup2      = array('1'=>'Individual', '2'=>'Group', '4'=>'AUX Output');
    static $attrGroup3      = array('1'=>'Door Individual', '2'=>'Door Group', '3'=>'Elevator', '4'=>'AUX Output');
    static $attrHoliday     = array('0'=>'none', '1'=>'Holiday 1', '2'=>'Holiday 2', '3'=>'Holiday 3', '4'=>'Holiday 4');

    static $attrReaderType  = array('1'=> 'Keypad or Card', '4'=>'Keypad and Card');
	static $attrReaderFunc  = array('0'=>'In Reader Only', '1'=>'In and Out Readers');
    static $attrState       = array('1' => 'Energized', '0' => 'De-Energized');
    //static $attrLockMode    = array('0'=>'Normal', '1'=>'Locked', '2'=>'Locked with Rex', '3'=>'Unlocked', '4'=>'Man-Trap');
    static $attrLockMode    = array('0'=>'Normal', '1'=>'Locked', '2'=>'Locked with Rex', '3'=>'Unlocked');
    static $attrManTrapMode    = array('0'=>'Unlock', '1'=>'Secure Entry/Free Egress', '2'=>'Restricted Entry and Exit');

    static $attrTimeZone    = array('-5'=>'Eastern','-6'=>'Central','-7'=>'Mountain','-8'=>'Pacific','-9'=>'Alaska','-10'=>'Hawaii','9'=>'Korea, Seoul');

    static $attrHourList    = array( '0'=>'00', '1'=>'01', '2'=>'02', '3'=>'03', '4'=>'04', '5'=>'05',
                                     '6'=>'06', '7'=>'07', '8'=>'08', '9'=>'09', '10'=>'10','11'=>'11',
                                     '12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17',
                                     '18'=>'18','19'=>'19','20'=>'20','21'=>'21','22'=>'22','23'=>'23','24'=>'24');
    static $attrMinList     = array( '0'=>'00','10'=>'10','20'=>'20','30'=>'30','40'=>'40','50'=>'50');
    static $attrSecList     = array( '0'=>'00','10'=>'10','20'=>'20','30'=>'30','40'=>'40','50'=>'50');
    static $attrTimeList    = array( '0'=>'00:00', '1'=>'01:00', '2'=>'02:00', '3'=>'03:00', '4'=>'04:00', '5'=>'05:00',
                                     '6'=>'06:00', '7'=>'07:00', '8'=>'08:00', '9'=>'09:00', '10'=>'10:00','11'=>'11:00',
                                     '12'=>'12:00','13'=>'13:00','14'=>'14:00','15'=>'15:00','16'=>'16:00','17'=>'17:00',
                                     '18'=>'18:00','19'=>'19:00','20'=>'20:00','21'=>'21:00','22'=>'22:00','23'=>'23:00');

    static $attrHourList00  = array( '00'=>'00', '01'=>'01', '02'=>'02', '03'=>'03', '04'=>'04', '05'=>'05', '06'=>'06', '07'=>'07', '08'=>'08', '09'=>'09',
                                     '10'=>'10', '11'=>'11', '12'=>'12', '13'=>'13', '14'=>'14', '15'=>'15', '16'=>'16', '17'=>'17', '18'=>'18', '19'=>'19',
                                     '20'=>'20', '21'=>'21', '22'=>'22', '23'=>'23' );
    static $attrMinList00   = array( '00'=>'00', '01'=>'01', '02'=>'02', '03'=>'03', '04'=>'04', '05'=>'05', '06'=>'06', '07'=>'07', '08'=>'08', '09'=>'09',
                                     '10'=>'10', '11'=>'11', '12'=>'12', '13'=>'13', '14'=>'14', '15'=>'15', '16'=>'16', '17'=>'17', '18'=>'18', '19'=>'19',
                                     '20'=>'20', '21'=>'21', '22'=>'22', '23'=>'23', '24'=>'24', '25'=>'25', '26'=>'26', '27'=>'27', '28'=>'28', '29'=>'29',
                                     '30'=>'30', '31'=>'31', '32'=>'32', '33'=>'33', '34'=>'34', '35'=>'35', '36'=>'36', '37'=>'37', '38'=>'38', '39'=>'39',
                                     '40'=>'40', '41'=>'41', '42'=>'42', '43'=>'43', '44'=>'44', '45'=>'45', '46'=>'46', '47'=>'47', '48'=>'48', '49'=>'49',
                                     '50'=>'50', '51'=>'51', '52'=>'52', '53'=>'53', '54'=>'54', '55'=>'55', '56'=>'56', '57'=>'57', '58'=>'58', '59'=>'59' );
	
	static $apiattrHourList00  = array( '00'=>'00', '01'=>'01', '02'=>'02', '03'=>'03', '04'=>'04', '05'=>'05', '06'=>'06', '07'=>'07', '08'=>'08', '09'=>'09',
                                     '10'=>'10', '11'=>'11', '12'=>'12', '13'=>'13', '14'=>'14', '15'=>'15', '16'=>'16', '17'=>'17', '18'=>'18', '19'=>'19',
                                     '20'=>'20', '21'=>'21', '22'=>'22', '23'=>'23', 'NA'=>'NA');
    static $apiattrMinList00   = array( '00'=>'00', '01'=>'01', '02'=>'02', '03'=>'03', '04'=>'04', '05'=>'05', '06'=>'06', '07'=>'07', '08'=>'08', '09'=>'09',
                                     '10'=>'10', '11'=>'11', '12'=>'12', '13'=>'13', '14'=>'14', '15'=>'15', '16'=>'16', '17'=>'17', '18'=>'18', '19'=>'19',
                                     '20'=>'20', '21'=>'21', '22'=>'22', '23'=>'23', '24'=>'24', '25'=>'25', '26'=>'26', '27'=>'27', '28'=>'28', '29'=>'29',
                                     '30'=>'30', '31'=>'31', '32'=>'32', '33'=>'33', '34'=>'34', '35'=>'35', '36'=>'36', '37'=>'37', '38'=>'38', '39'=>'39',
                                     '40'=>'40', '41'=>'41', '42'=>'42', '43'=>'43', '44'=>'44', '45'=>'45', '46'=>'46', '47'=>'47', '48'=>'48', '49'=>'49',
                                     '50'=>'50', '51'=>'51', '52'=>'52', '53'=>'53', '54'=>'54', '55'=>'55', '56'=>'56', '57'=>'57', '58'=>'58', '59'=>'59', 'NA'=>'NA' );

    static $attrTrueFalse   = array('0'=>'false', '1'=>'true');
    static $attrYesNo       = array('0'=>'No', '1'=>'Yes');
    static $attrOnOff       = array('0'=>'On', '1'=>'Off');

    static $attrAntiType    = array('0'=>'Deny', '1'=>'Grant');

    static $attrAuxOutMode  = array('1' => 'Single Pulse', '2' => 'Repeating', '3' => 'E-On', '4' => 'E-Off', '5' => 'Follow AuxIn');

    static $attrCardStatus  = array('0'=>'Active','1'=>'Lost','2'=>'Stolen','3'=>'Inactive');

// Fix 2017.09.06 CJMOON
//	static $attrCardType	= array('0'=>'Normal', '1'=>'Guard tour', '2'=>'Toggle', '3'=>'Passage', '4'=>'Relock', '5'=>'One time', '6'=>'Hazmat Unlock', '8'=>'Latch', '7'=>'DeadMan Check');
	static $attrCardType	= array('0'=>'Normal', '2'=>'Toggle', '3'=>'Passage', '4'=>'Relock', '5'=>'One time', '8'=>'Latch');

    //static $attrLanguage    = array('en' => 'English','fr' => 'French','sp' => 'Spanish','pt' => 'Portuguese');
    static $attrLanguage    = array('en' => 'English','sp' => 'Spanish');
    static $attrCountry     = array('1' =>  'United States','2' => 'Canada','3' => 'Brazil','4' => 'Mexico');
    static $attrCountryCode = array('1' =>  'us','2' => 'canada','3' => 'brazil','4' => 'mexico');

    static $attrThreatLevel = array(
								'1' => array(),
								'2' => array( '1'=>'Threat Level  1', '2'=>'Threat Level  2', '3'=>'Threat Level  3', '4'=>'Threat Level  4', '5'=>'Threat Level  5' ),
								'3' => array( '1'=>'Threat Level  1', '2'=>'Threat Level  2', '3'=>'Threat Level  3', '4'=>'Threat Level  4', '5'=>'Threat Level  5',
                                     '6'=>'Threat Level  6', '7'=>'Threat Level  7', '8'=>'Threat Level  8', '9'=>'Threat Level  9','10'=>'Threat Level 10',
                                    '11'=>'Threat Level 11','12'=>'Threat Level 12','13'=>'Threat Level 13','14'=>'Threat Level 14','15'=>'Threat Level 15',
                                    '16'=>'Threat Level 16','17'=>'Threat Level 17','18'=>'Threat Level 18','19'=>'Threat Level 19','20'=>'Threat Level 20',
                                    '21'=>'Threat Level 21','22'=>'Threat Level 22','23'=>'Threat Level 23','24'=>'Threat Level 24','25'=>'Threat Level 25' ),
								'8' => array( '1'=>'Threat Level  1', '2'=>'Threat Level  2', '3'=>'Threat Level  3', '4'=>'Threat Level  4', '5'=>'Threat Level  5',
                                     '6'=>'Threat Level  6', '7'=>'Threat Level  7', '8'=>'Threat Level  8', '9'=>'Threat Level  9','10'=>'Threat Level 10',
                                    '11'=>'Threat Level 11','12'=>'Threat Level 12','13'=>'Threat Level 13','14'=>'Threat Level 14','15'=>'Threat Level 15',
                                    '16'=>'Threat Level 16','17'=>'Threat Level 17','18'=>'Threat Level 18','19'=>'Threat Level 19','20'=>'Threat Level 20',
                                    '21'=>'Threat Level 21','22'=>'Threat Level 22','23'=>'Threat Level 23','24'=>'Threat Level 24','25'=>'Threat Level 25' ),
								'9' => array( '1'=>'Threat Level  1', '2'=>'Threat Level  2', '3'=>'Threat Level  3', '4'=>'Threat Level  4', '5'=>'Threat Level  5',
                                     '6'=>'Threat Level  6', '7'=>'Threat Level  7', '8'=>'Threat Level  8', '9'=>'Threat Level  9','10'=>'Threat Level 10',
                                    '11'=>'Threat Level 11','12'=>'Threat Level 12','13'=>'Threat Level 13','14'=>'Threat Level 14','15'=>'Threat Level 15',
                                    '16'=>'Threat Level 16','17'=>'Threat Level 17','18'=>'Threat Level 18','19'=>'Threat Level 19','20'=>'Threat Level 20',
                                    '21'=>'Threat Level 21','22'=>'Threat Level 22','23'=>'Threat Level 23','24'=>'Threat Level 24','25'=>'Threat Level 25' )
							);

    static $attrUserType    = array('1'=>'User', '2'=>'Super User', '3'=>'Admin');
    static $attrEventType   = array('1'=>'Aux Output', '2'=>'Door', '3'=>'System');

    static $attrReportTable = array('Door'=>'Door', 'Elevator'=>'Elevator', 'AuxInput'=>'Aux Input', 'AuxOutput'=>'Aux Output',
                                    'User'=>'Card Holder', 'Card'=>'Card' );
    static $attrReportTableForEssential = array('Door'=>'Door', 'AuxInput'=>'Aux Input', 'AuxOutput'=>'Aux Output',
                                    'User'=>'Card Holder', 'Card'=>'Card' );

    static $attrSkinFolder = array('Default'=>'Default', 'Metrix'=>'Metrix', 'RedTone'=>'RedTone' );
	static $attrIPType     = array('0'=>'DHCP', '1'=>'Static');
	static $attrNVRIPType  = array('0'=>'Static', '1'=>'DHCP');
	static $attrDVRMode    = array('0'=>'Live Viewer', '1'=>'Search Viewer');

	static $attrTimeServer = array(
								'0.pool.ntp.org'=>'0.pool.ntp.org',
								'1.pool.ntp.org'=>'1.pool.ntp.org',
								'2.pool.ntp.org'=>'2.pool.ntp.org',
								'time.windows.com'=>'time.windows.com' );

	static $attrCameraType = array("Axis : 2120"=>"Axis : 2120","Axis : 232D"=>"Axis : 232D","Axis : 214 PTZ"=>"Axis : 214 PTZ","Vivotek : IP2111"=>"Vivotek : IP2111",
          				           "Sony : SNC-RZ30N"=>"Sony : SNC-RZ30N","Sony : SNC-P1"=>"Sony : SNC-P1",
          				           "Sony : SNC-DF40N/DF40P"=>"Sony : SNC-DF40N/DF40P","PANASONIC : WV-ns324"=>"PANASONIC : WV-ns324",
				                   "PANASONIC : WV-NM100"=>"PANASONIC : WV-NM100","IQeye : 301"=>"IQeye : 301","Linear : IP Camera"=>"Linear : IP Camera","ONVIF"=>"ONVIF","Other"=>"Other");

	static $attrDvrType			= array('Digital Watchdog'=>'Digital Watchdog');
	static $attrDvrViewerType	= array('1'=>'ActiveX');
    static $attrNvrType			= array('e3 NVR'=>'e3 NVR');
    static $attrNvrViewerType	= array('2'=>'Java Applet');

	static $attrLogType    = array( '0'=>'WEB','1'=>'Reader','2'=>'Door Contact','6'=>'Door Lock','3'=>'Rex','9'=>'Elevator',
	                                '10'=>'Elevator Out','4'=>'Aux Output','5'=>'Aux Input','7'=>'System','8'=>'Network');
	static $attrLogItem    = array( '1'=>'Date','2'=>'Datetime','3'=>'Time','4'=>'Event Name','5'=>'User Name','6'=>'User Define Field','7'=>'Card Number','8'=>'Message');

//  2017.08.09 CJMOON NXG-2799
//	static $attrDefaultPage = array( 'alarm_map'=>'Dashboard','log_report'=>'Log','sitemap'=>'Site map','report'=>'Report','log_viewer'=>'Log Report','card_holder'=>'Card Holder');
//	static $attrDefaultPageAdmin = array( 'alarm_map'=>'Dashboard','log_report'=>'Log','sitemap'=>'Site map','report'=>'Report','log_viewer'=>'Log Report','card_holder'=>'Card Holder','wizard'=>'Wizard');

	static $attrDefaultPage = array( 'card_holder'=>'Card Holder','log_report'=>'Log','sitemap'=>'Site map','report'=>'Report','log_viewer'=>'Log Report');
	static $attrDefaultPageAdmin = array( 'card_holder'=>'Card Holder','log_report'=>'Log','sitemap'=>'Site map','report'=>'Report','log_viewer'=>'Log Report','wizard'=>'Wizard');


	static $attrSMTPTLS = array( '0'=>'Not Used','1'=>'Used');

	static $attrAutoDisconnectTime = array( '1'=>'01:00','4'=>'04:00','8'=>'08:00','12'=>'12:00','24'=>'24:00');

	static $attrNTPServer = array('0'=>'Manual Time Setting', '1'=>'NTP Server Synchronization');

	static $attrDeviceType	= array('1'=>'Door 1', '2'=>'Door 2', '3'=>'Door 3', '4'=>'Door 4', '5'=>'Elevator' );
    static $attrDeviceKind	= array('1'=>'Door','2'=>'Elevator','3'=>'Aux Input','4'=>'Aux Output');

	static $attrModel		= array('1'=>'Essential', '2'=>'Elite', '3'=>'Enterprise', '4'=>'Elite Client', '5'=>'Enterprise Client', '6'=>'Elite Elevator', '7'=>'Enterprise Elevator','8'=>'TE Standalone','9'=>'TE Server','10'=>'TE Client');
    static $attrModelKind	= array('1'=>'Server','2'=>'Door 4','3'=>'Door 2','4'=>'Elevator');

	static $attrModelType	= array(
		'1' => array(	// Essential
			'1'		=> 'Door 1',
			'2'		=> 'Door 2',
			'3'		=> 'Door 3',
			'4'		=> 'Door 4'
		),
		'2' => array(	// Elite
			'1'		=> 'Door 1',
			'2'		=> 'Door 2',
			'3'		=> 'Door 3',
			'4'		=> 'Door 4',
			'36'	=> 'Door 36',
			'64'	=> 'Door 64',
            '96'    => 'Door 96',
            '128'   => 'Door 128',
            '200'   => 'Door 200'
		),
		'3' => array(	// Enterprise
			'1'		=> 'Door 1',
			'2'		=> 'Door 2',
			'3'		=> 'Door 3',
			'4'		=> 'Door 4',
			'8'		=> 'Door 8',
			'16'	=> 'Door 16',
			'36'	=> 'Door 36',
			'64'	=> 'Door 64',
			'96'	=> 'Door 96',
			'128'	=> 'Door 128',
			'200'	=> 'Door 200',
			'256'	=> 'Door 256',
			'360'	=> 'Door 360'
		),
		'4' => array(	// Elite Client
			'1'		=> 'Door 1',
			'2'		=> 'Door 2',
			'3'		=> 'Door 3',
			'4'		=> 'Door 4'
		),
		'5' => array(	// Enterprise Client
			'1'		=> 'Door 1',
			'2'		=> 'Door 2',
			'3'		=> 'Door 3',
			'4'		=> 'Door 4'
		),
		'6' => array(	// Elite Elevator
			'1'		=> 'Elevator',
			'2'		=> 'Elevator 2',
			'3'		=> 'Elevator 3',
			'4'		=> 'Elevator 4'
		),
		'7' => array(	// Enterprise Elevator
			'1'		=> 'Elevator',
			'2'		=> 'Elevator 2',
			'3'		=> 'Elevator 3',
			'4'		=> 'Elevator 4'
		),
		'8' => array(   // TE Standalone
			'2'		=> 'Door 2',
			'4'		=> 'Door 4'
		),
		'9' => array(   // TE Server
			'36'	=> 'Door 36',
            '64'    => 'Door 64'
		),
		'10' => array(   // TE Client
			'2'		=> 'Door 2',
			'4'		=> 'Door 4'
		)			
	);
	static $attrModelSpec	= array(
		// [0]Readers per system, [1]Doors per system, [2]Users per system, [3]Access levels per person, [4]Access cards, [5]Cards per person, [6]Card formats, [7]Expansion modules, [8]Alarm Input Points, [9]Output Points, [10]Online Event history log, [11]Access level per system, [12]Schedule per system, [13]Webuser Role per system, [14]One Time Unlock Schedule per system, [15]concurrent user per system

		// 2013-09-27 수정
		'1' => array(	// Essential
			'1'		=> array(  2,   1,  1000,  8,   8000, 32, 32,  0,    5,   2, 15000,  25,  25,   8,   0,  8),
			'2'		=> array(  4,   2,  1000,  8,   8000, 32, 32,  0,    8,   4, 15000,  25,  25,   8,   0,  8),
			'3'		=> array(  6,   3,  1000,  8,   8000, 32, 32,  0,   11,   6, 15000,  25,  25,   8,   0,  8),
			'4'		=> array(  8,   4,  1000,  8,   8000, 32, 32,  0,   14,   8, 15000,  25,  25,   8,   0,  8)
		),
		'2' => array(	// Elite
            '1'     => array(  2,   1,  5000, 16,  80000, 32, 32,  0,    5,   2, 30000, 125, 125,  16,  50, 16),
            '2'     => array(  4,   2,  5000, 16,  80000, 32, 32,  0,    8,   4, 30000, 125, 125,  16,  50, 16),
            '3'     => array(  6,   3,  5000, 16,  80000, 32, 32,  0,   11,   6, 30000, 125, 125,  16,  50, 16),
            '4'     => array(  8,   4,  5000, 16,  80000, 32, 32,  0,   14,   8, 30000, 125, 125,  16,  50, 16),
			'36'	=> array( 72,  36,  5000, 16,  80000, 32, 32,  8,  126,  72, 30000, 125, 125,  16,  50, 16),
			'64'	=> array(128,  64,  5000, 16,  80000, 32, 32, 15,  224, 128, 30000, 125, 125,  16,  50, 16),
            '96'	=> array(192,  96,  5000, 16,  80000, 32, 32, 23,  336, 192, 30000, 125, 125,  16,  50, 16),
            '128'   => array(256, 128,  5000, 16,  80000, 32, 32, 31,  448, 256, 30000, 125, 125,  16,  50, 16),
            '200'   => array(400, 200,  5000, 16,  80000, 32, 32, 49,  700, 400, 30000, 125, 125,  16,  50, 16)
		),
		'3' => array(	// Enterprise
            '1'     => array(  2,   1,  5000, 32, 120000, 32, 32,  0,    5,   2, 100000, 250, 250, 32, 150, 32),
            '2'     => array(  4,   2,  5000, 32, 120000, 32, 32,  0,    8,   4, 100000, 250, 250, 32, 150, 32),
            '3'     => array(  6,   3,  5000, 32, 120000, 32, 32,  0,   11,   6, 100000, 250, 250, 32, 150, 32),
            '4'     => array(  8,   4,  5000, 32, 120000, 32, 32,  0,   14,   8, 100000, 250, 250, 32, 150, 32),
			'8'		=> array( 16,   8,  5000, 32, 120000, 32, 32,  1,   28,  16, 100000, 250, 250, 32, 150, 32),
			'16'	=> array( 32,  16,  5000, 32, 120000, 32, 32,  3,   56,  32, 100000, 250, 250, 32, 150, 32),
			'36'	=> array( 72,  36,  5000, 32, 120000, 32, 32,  8,  126,  72, 100000, 250, 250, 32, 150, 32),
			'64'	=> array(128,  64, 10000, 32, 120000, 32, 32, 15,  224, 128, 100000, 250, 250, 32, 150, 32),
			'96'	=> array(192,  96, 15000, 32, 120000, 32, 32, 23,  336, 192, 100000, 250, 250, 32, 150, 32),
			'128'	=> array(256, 128, 15000, 32, 120000, 32, 32, 31,  448, 256, 100000, 250, 250, 32, 150, 32),
			'200'	=> array(400, 200, 20000, 32, 120000, 32, 32, 49,  700, 400, 100000, 250, 250, 32, 150, 32),
			'256'	=> array(512, 256, 30000, 32, 120000, 32, 32, 63,  896, 512, 100000, 250, 250, 32, 150, 32),
			'360'	=> array(720, 360, 40000, 32, 120000, 32, 32, 89, 1260, 720, 100000, 250, 250, 32, 150, 32)
		),
		/*
		'1' => array(	// Essential
			'1'		=> array(  2,   1,  1000,  8,   8000,  12, 32,  0,    5,   2, 15000,  25,  25,   4),
			'2'		=> array(  4,   2,  1000,  8,   8000,  12, 32,  0,    8,   4, 15000,  25,  25,   4),
			'3'		=> array(  6,   3,  1000,  8,   8000,  12, 32,  0,   11,   6, 15000,  25,  25,   4),
			'4'		=> array(  8,   4,  1000,  8,   8000,  12, 32,  0,   14,   8, 15000,  25,  25,   4)
		),
		'2' => array(	// Elite
			'36'	=> array( 72,  36, 6000, 16,  80000, 120, 32,  8,  126,  72, 30000, 125, 125,  10),
			'64'	=> array(128,  64, 6000, 16,  80000, 120, 32, 15,  224, 128, 30000, 125, 125,  10)
		),
		'3' => array(	// Enterprise
			'4'		=> array(  8,   4, 60000, 32, 120000, 120, 32,  0,   14,   8, 50000, 512, 512, 100),
			'8'		=> array( 16,   8, 60000, 32, 120000, 120, 32,  1,   28,  16, 50000, 512, 512, 100),
			'16'	=> array( 32,  16, 60000, 32, 120000, 120, 32,  3,   56,  32, 50000, 512, 512, 100),
			'36'	=> array( 72,  36, 60000, 32, 120000, 120, 32,  8,  126,  72, 50000, 512, 512, 100),
			'64'	=> array(128,  64, 60000, 32, 120000, 120, 32, 15,  224, 128, 50000, 512, 512, 100),
			'96'	=> array(192,  96, 60000, 32, 120000, 120, 32, 23,  336, 192, 50000, 512, 512, 100),
			'128'	=> array(256, 128, 60000, 32, 120000, 120, 32, 31,  448, 256, 50000, 512, 512, 100),
			'200'	=> array(400, 200, 60000, 32, 120000, 120, 32, 49,  700, 400, 50000, 512, 512, 100),
			'256'	=> array(512, 256, 60000, 32, 120000, 120, 32, 63,  896, 512, 50000, 512, 512, 100),
			'360'	=> array(720, 360, 60000, 32, 120000, 120, 32, 89, 1260, 720, 50000, 512, 512, 100)
		)
		*/
		'8' => array( // TE STANDALONE
		    '2'     => array(  4,   2,  1000, 32, 5000, 32, 32,  0,    8,   4, 10000, 250, 250, 32, 150, 32)
		),
		'9' => array( // TE Server
			'36'	=> array( 72,  36,  10000, 32, 120000, 32, 32,  8,  126,  72, 50000, 250, 250, 32, 150, 32),
			'64'	=> array( 128,  64,  10000, 32, 120000, 32, 32,  8,  224,  128, 50000, 250, 250, 32, 150, 32)
		)
	);
	static $attrModelOptionIndex	= array(
		'1'	 => array(	// Essential
			'1'=>'RMR',
			'9'=>'Linear/SICUNET',
			'10'=>'4-CH Video',
			'34'=>'Linear Burg Panel',
			'57'=>'Linear',
			'61'=>'Linear',
			'73'=>'Linear',
			'77'=>'Linear',
			'81'=>'Linear',
			'85'=>'Linear'
		),
		'2' => array(	// Elite
			'1'=>'STD',
			'2'=>'API',
			'9'=>'Linear/SICUNET',
			'11'=>'DW VMS',
			'12'=>'EXACQ VMS',
			'33'=>'DMP License',
			'34'=>'Linear Burg Panel',
			'41'=>'Linear',
			'49'=>'Linear',
			'53'=>'Linear',
			'57'=>'Linear',
			'61'=>'Linear',
			'73'=>'Linear',
			'77'=>'Linear',
			'81'=>'Linear',
			'85'=>'Linear'
		),
		'3' => array(	// Enterprise
			'1'=>'STD',
			'2'=>'API',
			'9'=>'Linear/SICUNET',
			'13'=>'MILESTONE VMS',
			'14'=>'OSSI',
			'33'=>'DMP License',
			'34'=>'Linear Burg Panel',
			'41'=>'Linear',
			'49'=>'Linear',
			'53'=>'Linear',
			'57'=>'Linear',
			'61'=>'Linear',
			'73'=>'Linear',
			'77'=>'Linear',
			'81'=>'Linear',
			'85'=>'Linear'
		),
//ADD CJMOON 2017.04.20
		'8' => array(	// TE Standalone
			'1'=>'STD',
			'2'=>'API',
			'9'=>'Linear/SICUNET',
			'13'=>'MILESTONE VMS',
			'14'=>'OSSI',
			'33'=>'DMP License',
			'34'=>'Linear Burg Panel',
			'41'=>'Linear',
			'49'=>'Linear',
			'53'=>'Linear',
			'57'=>'Linear',
			'61'=>'Linear',
			'73'=>'Linear',
			'77'=>'Linear',
			'81'=>'Linear',
			'85'=>'Linear'
		),
		'9' => array(	// TE Server
			'1'=>'STD',
			'2'=>'API',
			'9'=>'Linear/SICUNET',
			'13'=>'MILESTONE VMS',
			'14'=>'OSSI',
			'33'=>'DMP License',
			'34'=>'Linear Burg Panel',
			'41'=>'Linear',
			'49'=>'Linear',
			'53'=>'Linear',
			'57'=>'Linear',
			'61'=>'Linear',
			'73'=>'Linear',
			'77'=>'Linear',
			'81'=>'Linear',
			'85'=>'Linear'
		)
	);
	static $attrModelOption	= array(
		'1'	 => array(	// Essential
			'RMR'					=> array('1'=>'RMR'),
			'Video Licenses'		=> array('9'=>'Linear/SICUNET', '10'=>'4-CH Video'),
			'Burg Panel'			=> array('34'=>'Linear Burg Panel'),
			'Telephone Entry'		=> array(),
			'Asset Tracking'		=> array(),
			'Asset Management'		=> array(),
			'Energy Management'		=> array('57'=>'Linear'),
			'Intercom'				=> array('61'=>'Linear'),
			'Bio-Readers'			=> array('73'=>'Linear'),
			'Visitor Management'	=> array('77'=>'Linear'),
			'Badging'				=> array('81'=>'Linear'),
			'Time & Attendance'		=> array('85'=>'Linear')
		),
		'2' => array(	// Elite
			'RMR'					=> array('1'=>'STD'),
			'API'					=> array('2'=>'API'),
			'Video Licenses'		=> array('9'=>'Linear/SICUNET', '11'=>'DW VMS', '12'=>'EXACQ VMS'),
			'Burg Panel'			=> array('33'=>'DMP License', '34'=>'Linear Burg Panel'),
			'Telephone Entry'		=> array('41'=>'Linear'),
			'Asset Tracking'		=> array('49'=>'Linear'),
			'Asset Management'		=> array('53'=>'Linear'),
			'Energy Management'		=> array('57'=>'Linear'),
			'Intercom'				=> array('61'=>'Linear'),
			'Bio-Readers'			=> array('73'=>'Linear'),
			'Visitor Management'	=> array('77'=>'Linear'),
			'Badging'				=> array('81'=>'Linear'),
			'Time & Attendance'		=> array('85'=>'Linear')
		),
		'3' => array(	// Enterprise
			'RMR'					=> array('1'=>'STD'),
			'API'					=> array('2'=>'API'),
			'Video Licenses'		=> array('9'=>'Linear/SICUNET', '13'=>'MILESTONE VMS', '14'=>'OSSI'),
			'Burg Panel'			=> array('33'=>'DMP License', '34'=>'Linear Burg Panel'),
			'Telephone Entry'		=> array('41'=>'Linear'),
			'Asset Tracking'		=> array('49'=>'Linear'),
			'Asset Management'		=> array('53'=>'Linear'),
			'Energy Management'		=> array('57'=>'Linear'),
			'Intercom'				=> array('61'=>'Linear'),
			'Bio-Readers'			=> array('73'=>'Linear'),
			'Visitor Management'	=> array('77'=>'Linear'),
			'Badging'				=> array('81'=>'Linear'),
			'Time & Attendance'		=> array('85'=>'Linear')
		),
//ADD CJMOON 2017.04.20
		'8' => array(	// Standalone
			'RMR'					=> array('1'=>'STD'),
			'API'					=> array('2'=>'API'),
			'Video Licenses'		=> array('9'=>'Linear/SICUNET', '13'=>'MILESTONE VMS', '14'=>'OSSI'),
			'Burg Panel'			=> array('33'=>'DMP License', '34'=>'Linear Burg Panel'),
			'Telephone Entry'		=> array('41'=>'Linear'),
			'Asset Tracking'		=> array('49'=>'Linear'),
			'Asset Management'		=> array('53'=>'Linear'),
			'Energy Management'		=> array('57'=>'Linear'),
			'Intercom'				=> array('61'=>'Linear'),
			'Bio-Readers'			=> array('73'=>'Linear'),
			'Visitor Management'	=> array('77'=>'Linear'),
			'Badging'				=> array('81'=>'Linear'),
			'Time & Attendance'		=> array('85'=>'Linear')
		),
		'9' => array(	// TE Server
			'RMR'					=> array('1'=>'STD'),
			'API'					=> array('2'=>'API'),
			'Video Licenses'		=> array('9'=>'Linear/SICUNET', '13'=>'MILESTONE VMS', '14'=>'OSSI'),
			'Burg Panel'			=> array('33'=>'DMP License', '34'=>'Linear Burg Panel'),
			'Telephone Entry'		=> array('41'=>'Linear'),
			'Asset Tracking'		=> array('49'=>'Linear'),
			'Asset Management'		=> array('53'=>'Linear'),
			'Energy Management'		=> array('57'=>'Linear'),
			'Intercom'				=> array('61'=>'Linear'),
			'Bio-Readers'			=> array('73'=>'Linear'),
			'Visitor Management'	=> array('77'=>'Linear'),
			'Badging'				=> array('81'=>'Linear'),
			'Time & Attendance'		=> array('85'=>'Linear')
		)
	);

	static function toArrayUpgradeModel($model)
	{
		switch( $model ) {
			case '1' :
				return array('1'=>'Essential', '2'=>'Elite');
			break;
			case '2' :
				return array('2'=>'Elite');
			break;
			case '3' :
				return array('3'=>'Enterprise');
// ADD CJMOON 2017.04.20 TE는 Upgrade Model이 없음.
			case '8' :
				return array('8'=>'TE Standalone');
			case '9' :
				return array('8'=>'TE Server');
			break;
		}

		return array();
	}

	static function toArrayUpgradeType($model, $type)
	{
		$arr = array();
		foreach( EnumTable::$attrModelSpec[$model] as $key=>$val ) {
			if( $key < $type )	continue;
			$arr[$key] = $key;
		}

		return $arr;
	}
}

?>