<?PHP echo view('common/header.php'); ?>
<style>

#wrap { background:url("<?=base_url();?>assets/img/menu/bg_top_wizard.jpg") repeat-x #710000; }
#lnb { background:url(); }
#lnb td { vertical-align:middle; }

.wizard-title img	{ margin: 30px 0 0 25px; }
.wizard-menu		{
	font-family:Arial, Helvetica, sans-serif;
	font-weight:bold;
	font-size:14px;
	color:#ffffff;
	height:46px;
	background:url("<?=base_url();?>assets/img/menu/checkbox_nonchk.png") no-repeat 25px;
	/*padding:18px 0 0 50px;*/
}
.wizard-menu div	{
	margin: 0 0 0 50px;
}
.wizard-menu-select { color:#710000; background-color:#ffffff; }
.wizard-menu-checked { background:url("<?=base_url();?>assets/img/menu/checkbox_chk.png") no-repeat 25px #710000; }
.wizard-menu-checked.wizard-menu-select { background:url("<?=base_url();?>assets/img/menu/checkbox_chk.png") no-repeat 25px #ffffff; }

</style>
<tr>
    <td>
        <table id="wrap_contents">
            <tr>
                <td id="lnb">

                    <table>
                        <tr>
                            <td class="wizard-title"><img src="<?=base_url()?>assets/img/menu/title_wizard.png" /></td>
                        </tr>
                        <tr>
                            <td class="wizard-menu wizard1" onclick="">
                                <div>Language</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="wizard-menu wizard2" onclick="">
                                <div>License</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="wizard-menu wizard3" onclick="">
                                <div>Card Format</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="wizard-menu wizard4" onclick="">
                                <div>Holiday Group</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="wizard-menu wizard5" onclick="">
                                <div>Schedule</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="wizard-menu wizard6" onclick="">
                                <div>Door</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="wizard-menu wizard7" onclick="">
                                <div>Access Level</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="wizard-menu wizard8" onclick="">
                                <div>Resident</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="wizard-menu wizard9" onclick="">
                                <div>Card</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="wizard-menu wizard10" onclick="">
                                <div>Network</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="wizard-menu wizard11" onclick="">
                                <div>Start Save</div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td id="contents">

                    <div style="height:30px; padding-left:690px;">
                        <a href="" class="wizard-close"></a>
                    </div>

                    <table>
                        <tr>
                            <td width="6" height="3"
                                background="<?=base_url()?>assets/img/menu/quick_window_top_left.png"></td>
                            <td bgcolor="#ffffff"></td>
                            <td width="6" height="3"
                                background="<?=base_url()?>assets/img/menu/quick_window_top_right.png"></td>
                        </tr>
                        <tr>
                            <td bgcolor="#ffffff"></td>
                            <td height="500" bgcolor="#ffffff" style="padding-left:30px;">
                                <div class="wizard-location">
                                    <img src="<?=base_url()?>assets/img/menu/icon_info.png" valign="absmiddle">
                                    <span id="wizard-location-text"></span>
                                </div>
                                <iframe class="box01" id="wizard_body" src="<?PHP echo base_url(); ?>wizard-language"
                                    frameborder="no" border="0" framespacing="0" scrolling="auto"
                                    style="width:707px; height:100%;"></iframe>
                                <br><br>
                                <table style="width:707px;">
                                    <tr>
                                        <td><a class="prev-wizard hide" href="javascript:prev_wizard()"></a></td>
                                        <td align="right"><a class="next-wizard hide"
                                                href="javascript:next_wizard()"></a></td>
                                    </tr>
                                </table>
                                <br>
                            </td>
                            <td bgcolor="#ffffff"></td>
                        </tr>
                        <tr>
                            <td width="6" height="3"
                                background="<?=base_url()?>assets/img/menu/quick_window_bottom_left.png"></td>
                            <td bgcolor="#ffffff"></td>
                            <td width="6" height="3"
                                background="<?=base_url()?>assets/img/menu/quick_window_bottom_right.png"></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>
<?PHP echo view('common/footer.php'); ?>

<script type="text/javascript">
    var current_wizard = 1;

function prev_wizard()
{
	if( current_wizard > 1 ) {
		current_wizard--;
		set_wizard();
	}
}

function next_wizard()
{
	if( current_wizard < 11 ) {
//	if( current_wizard < 12 ) {
		current_wizard++;
		set_wizard();
	}
}

function set_wizard()
{
	var wtitle = "", url = "";

	switch( current_wizard ) {
		case 1: wtitle = "<?=lang('Message.Menu.language_title')?>";	url = "<?PHP echo base_url(); ?>wizard-language"; break;
		case 2: wtitle = "<?=lang('Message.Menu.license_title')?>";		url = "<?PHP echo base_url(); ?>license"; break;
		case 3: wtitle = "<?=lang('Message.Menu.card_format_title')?>";	url = "<?PHP echo base_url(); ?>cardformat/?wizard=1"; break;
		case 4: wtitle = "<?=lang('Message.Menu.holiday_title')?>";		url = "/?c=holiday&wizard=1"; break;
		case 5: wtitle = "<?=lang('Message.Menu.schedule_title')?>";	url = "/?c=schedule&wizard=1"; break;
		case 6: wtitle = "<?=lang('Message.Menu.door_title')?>";		url = "/?c=door&wizard=1"; break;
		case 7: wtitle = "<?=lang('Message.Menu.alevel_title')?>";		url = "/?c=alevel&wizard=1"; break;
		case 8: wtitle = "<?=lang('Message.Menu.card_holder_title')?>";	url = "/?c=card_holder&wizard=1"; break;
		case 9: wtitle = "<?=lang('Message.Menu.card_title')?>";		url = "/?c=card&wizard=1"; break;
		case 10: wtitle = "<?=lang('Message.Menu.network_title')?>";		url = "/?c=ipset&wizard=1"; break;
		/** DELETE CJMOON 2017.03.20
        case 11: wtitle = "<?=lang('Message.Menu.dealer_registration')?>";		url = "/?c=wizard&m=dealer&wizard=1"; break;
		case 12: wtitle = "<?=lang('Message.Menu.saverestart_title')?>";	url = "/?c=wizard&m=restart&wizard=1"; break;
		**/
		case 11: wtitle = "<?=lang('Message.Menu.saverestart_title')?>";	url = "/?c=wizard&m=restart&wizard=1"; break;
	}

	$("#wizard_body").attr("src", url);

	$.getScript("<?PHP echo base_url(); ?>check-data");
	$(".wizard-menu").removeClass("wizard-menu-select");
	$(".wizard-menu.wizard"+current_wizard).addClass("wizard-menu-select");
	$("#wizard-location-text").text(wtitle);

	if( current_wizard == 1 )	$(".prev-wizard").hide();
	else						$(".prev-wizard").show();

	if( current_wizard == 12 )	$(".next-wizard").hide();
	else						$(".next-wizard").show();
}

function hide_loading()
{
	document.getElementById("wizard_body").contentWindow.hide_loading();
}

function load_list(page, field, word, view)
{
	document.getElementById("wizard_body").contentWindow.load_list(page, field, word, view);
}

$(function() {
	set_wizard();
	confirm_server_time();
});

function update_server_time()
{
	var client_time = new Date();
	var time_str = client_time.getFullYear()+","+(client_time.getMonth()+1)+","+client_time.getDate()+","+client_time.getHours()+","+client_time.getMinutes()+","+client_time.getSeconds();
	//alert(time_str);
	submit_link("<?PHP echo base_url(); ?>update-server-time/"+time_str);
}

function confirm_server_time()
{
	var server_time = new Date(<?=date('Y')?>, <?=(date('m')-1)?>, <?=date('d')?>, <?=date('H')?>, <?=date('i')?>, <?=date('s')?>);
	var client_time = new Date();
	var msg = "<?=lang('Message.addmsg.msg_servertime_sync')?>\n<?=lang('Message.addmsg.msg_clienttime')?>: "+client_time.toString()+"\n<?=lang('Message.addmsg.msg_servertime')?>: "+server_time.toString();

	if( Math.abs(server_time.valueOf() - client_time.valueOf()) > (1000*60*1) )
	{
		if( confirm(msg) )
		{
			update_server_time();
		}
	}
}
</script>