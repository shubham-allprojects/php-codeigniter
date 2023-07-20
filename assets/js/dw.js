/*
 * setScreenMode
 * @param _mode : 1,4,9,16 valid value
 *
*/

function setScreenMode( _mode )
{
	if( ActiveXInstalled('ACSSDK.ACSSDKControl.1') == false )
		return;
	document.getElementById('ACSSDK').setScreenMode( _mode );
}

/*
 * set control size(width, height)
 * @param _width
 * @param _height
 *
*/

function setControlRect( _width , _height )
{
	if( ActiveXInstalled('ACSSDK.ACSSDKControl.1') == false )
		return;
	document.getElementById('ACSSDK').setControlRect( _width , _height );
}

/*
 * open/close channel
 *
 *
*/

function openCloseChannel( channelObj, ch )
{
	if( ActiveXInstalled('ACSSDK.ACSSDKControl.1') == false )
		return;

	var channels = document.getElementById(channelObj);

	if( channels.checked )
	{
		document.getElementById('ACSSDK').openChannel( ch);
	}
	else
	{
		document.getElementById('ACSSDK').closeChannel( ch);
	}
}

/*
 * live connect
 *
*/
function connectSite(ip, port, userid, password, ch)
{
	if( ActiveXInstalled('ACSSDK.ACSSDKControl.1') == false )
		return;

	document.getElementById('ACSSDK').connect( ip, port, userid, password, ch );
}

/*
 * search connect
 *
*/
function searchConnectSite(ip, port, userid, password, ch)
{
	if( ActiveXInstalled('ACSSDK.ACSSDKControl.1') == false )
		return;

	document.getElementById('ACSSDK').searchConnect( ip, port, userid, password, ch );
}

function disconnectSite()
{
	if( ActiveXInstalled('ACSSDK.ACSSDKControl.1') == false )
		return;

	document.getElementById('ACSSDK').disconnectAll();
}


/*
 * show/time timetable
 *
*/

function showTimeTable( show )
{
	if( ActiveXInstalled('ACSSDK.ACSSDKControl.1') == false )
		return;

	document.getElementById('ACSSDK').showTimeTable = show;
}

/*
 * request PTZ
 *
*/
function requestPTZ( start , ptzCmd )
{
	if( ActiveXInstalled('ACSSDK.ACSSDKControl.1') == false )
		return;

	if(start == true) {
		document.getElementById('ACSSDK').requestPTZStart(ptzCmd);
	} else {
		document.getElementById('ACSSDK').requestPTZStop(ptzCmd);
	}
}

/*
 * requestpreset
 *
*/
function requestPreset( presetCmd, presetno )
{
	if( ActiveXInstalled('ACSSDK.ACSSDKControl.1') == false )
		return;

	if(presetCmd==0) {
		document.getElementById('ACSSDK').requestPreset(presetno);
	} else {
		document.getElementById('ACSSDK').requestPresetSet(presetno);
	}
}

function searchGoto(searchDate, searchTime, dst)
{
	if( ActiveXInstalled('ACSSDK.ACSSDKControl.1') == false )
		return;

	if( dst == undefined )
		dst = false;

	document.getElementById('ACSSDK').gotoTime( searchDate, searchTime, dst );
}

function setAudioEnable(enable)
{

	if( ActiveXInstalled('ACSSDK.ACSSDKControl.1') == false )
		return;

	document.getElementById('ACSSDK').enableAudio = enable;
}

function setTwoWayAudioEnable(enable)
{

	if( ActiveXInstalled('ACSSDK.ACSSDKControl.1') == false )
		return;

	document.getElementById('ACSSDK').enableTwowayAudio = enable;
}

function requestPlayMode(playMode)
{
	if( ActiveXInstalled('ACSSDK.ACSSDKControl.1') == false )
		return;

	document.getElementById('ACSSDK').requestPlaymode( playMode );
}

function doPrint()
{
	if( ActiveXInstalled('ACSSDK.ACSSDKControl.1') == false )
		return;

	document.getElementById('ACSSDK').printCurImage();
}

function exportJpeg(path)
{
	if( ActiveXInstalled('ACSSDK.ACSSDKControl.1') == false )
		return;

	document.getElementById('ACSSDK').jpegExportToPath(path);
}

function exportMov(path)
{
	if( ActiveXInstalled('ACSSDK.ACSSDKControl.1') == false )
		return;

	document.getElementById('ACSSDK').movieExportToPath(path);
}

function startEventServer(port)
{
	if( ActiveXInstalled('ACSSDK.ACSSDKControl.1') == false )
		return;

	document.getElementById('ACSSDK').startEventServer(port);
}

function stopEventServer()
{
	if( ActiveXInstalled('ACSSDK.ACSSDKControl.1') == false )
		return;

	document.getElementById('ACSSDK').stopEventServer();
}

function setMaxScreen(max)
{
	if( ActiveXInstalled('ACSSDK.ACSSDKControl.1') == false )
		return;

	document.getElementById('ACSSDK').maxCh = max;
}

function requsetSDKRecinfo() {
	if (ActiveXInstalled('ACSSDK.ACSSDKControl.1') == false)
		return;
	
	document.getElementById('ACSSDK').requsetSDKRecinfo();
}

function requestDVRInfo()
{
	if( ActiveXInstalled('ACSSDK.ACSSDKControl.1') == false )
		return;

	var result = document.getElementById('ACSSDK').requestSDKInfo();

	if(result == "NotYet")
	{
		return false;
	}
	else
	{
		return result;
	}
}

function ActiveXInstalled(ProgId)
{
	var isInstall = false;

	try
	{
		var obj = new ActiveXObject(ProgId);
		if (obj) return true;
	}
	catch (e)
	{
		return false;
	}

	return false;
}
