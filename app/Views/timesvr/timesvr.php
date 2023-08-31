<div id="location">
<?php
echo $lang->menu->networks . '&nbsp;&gt;&nbsp;' . $lang->menu->timesvr;
?>
	<button class="btn_help" onclick="openHelp('timesvr', '<?=$lang->_lang?>')">Help</button>
</div>

<div id="edit_section" class="">
    <h2>:: <?=$lang->menu->systemtimes?></h2>
    <div class="box01">

        <form id="form_edit4" method="post" action="/?c=timesvr&m=update4">
        <?=Form::hidden("No")?>
        <?=Form::hidden("IPType")?>
        <?=Form::hidden("DNS1")?>
        <?=Form::hidden("DNS2")?>
        <h3><?=$lang->menu->basic?></h3>
        <table class="tbl_view">
        <tr>
            <!-- <th colspan="2"></th> -->
            <td colspan="3" align="center">
				<?=Form::radio('NTPSync', '', array('1' => EnumTable::$attrNTPServer[1]), '', array('onclick' => 'enable_form_edit4()'))?> <label>(<?=$lang->network->require_dns?>)</label>
				&nbsp;&nbsp;&nbsp;
				<?=Form::radio('NTPSync', '', array('0' => EnumTable::$attrNTPServer[0]), '', array('onclick' => 'enable_form_edit4()'))?>
			</td>
        </tr>
        <tr>
            <th width="150"><?=$lang->network->NTPServer?></th>
            <td width="1">:</td>
            <td><?=Form::select('NTPServerSel', '', EnumTable::$attrTimeServer, array('onchange' => 'change_server();'), $lang->network->msg_user_server_name)?>&nbsp;<?=Form::input('NTPServer', '')?></td>
        </tr>
        <tr>
            <th><?=$lang->network->NTPSyncTime?></th>
            <td width="1">:</td>
            <td><?=Form::select('NTPSyncTime', '', $this->arr_sync_time)?></td>
        </tr>
        <tr>
            <th><?=$lang->network->NTPSyncTimeZone?></th>
            <td width="1">:</td>
            <td><?=Form::select('NTPSyncTimeZone', '', EnumTable::$attrTimeZone)?></td>
        </tr>
        <tr>
            <th width="150"><?=$lang->network->Date?></th>
            <td width="1">:</td>
            <td>
                <?=Form::input('SetDateStr', '', array('class' => 'date', 'size' => 10, 'readonly' => 'readonly'))?>&nbsp;&nbsp;&nbsp;
                Time&nbsp;&nbsp;
                <?=Form::input('SetTimeStr', '', array('size' => 10))?>
            </td>
        </tr>
        </table>
        <!-- NXG-3809 -->
        <h3>Sync All Client Timezone</h3>
        <table class="tbl_view">
        <tr>
            <th width="150">Time Zone Sync with Client</th>
            <td width="1">:</td>
            <td>

                <input type="checkbox" id="timezonesync_checkbox" name="timezonesync_check" onchange="time_zone_sync_client();" value="1" checked/>
                <label>Sync</label>
				<?//=Form::input('DSTStart', '', array('class'=>'date', 'size'=>10, 'readonly'=>'readonly'))?>
				<?//=Form::input('DSTEnd', '', array('class'=>'date', 'size'=>10, 'readonly'=>'readonly'))?>
                <div id="timezonesync_msg" style="margin-left:5px; display:inline;">**(Server and Client(Connected) Timezone and DST will change.)</div>

			</td>
        </tr>
		</table>
        <!-- NXG-3809 -->

        <h3><?=$lang->addmsg->dst_title?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->addmsg->dst_title?></th>
            <td width="1">:</td>
            <td>
				<?=Form::radio('DSTEnable', '', $this->arr_plug, '&nbsp;&nbsp;', array('onclick' => 'enable_dst_date();'))?>
				<?//=Form::input('DSTStart', '', array('class'=>'date', 'size'=>10, 'readonly'=>'readonly'))?>
				<?//=Form::input('DSTEnd', '', array('class'=>'date', 'size'=>10, 'readonly'=>'readonly'))?>
			</td>
        </tr>
		</table>

        <div class="button_set">
            <button type="button" onclick="$('#form_edit4').submit()"><?=$lang->button->save?></button>&nbsp;&nbsp;
            <button type="button" onclick="open_edit(_seq); init_edit();"><?=$lang->button->reset?></button>&nbsp;&nbsp;
            <button type="button" onclick="close_edit()"><?=$lang->button->cancel?></button>
        </div>
        </form>
    </div>
</div>


<div id="view_section" class="">
    <h2>:: <?=$lang->menu->systemtimes?></h2>
    <div class="box01">
        <h3><?=$lang->menu->basic?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->network->NTPSync?></th>
            <td width="1">:</td>
            <td id="view_NTPSyncStr"></td>
        </tr>
        <tbody id="view_ntp_show">
        <tr>
            <th><?=$lang->network->NTPServer?></th>
            <td width="1">:</td>
            <td id="view_NTPServerStr"></td>
        </tr>
        <tr>
            <th><?=$lang->network->NTPSyncTime?></th>
            <td width="1">:</td>
            <td id="view_NTPSyncTimeStr"></td>
        </tr>
        </tbody>
        <tr>
            <th><?=$lang->network->NTPSyncTimeZone?></th>
            <td width="1">:</td>
            <td id="view_NTPSyncZoneStr"></td>
        </tr>
        </table>

        <h3><?=$lang->addmsg->dst_title?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->addmsg->dst_title?></th>
            <td width="1">:</td>
            <td id="view_DSTEnableStr"></td>
        </tr>
		</table>

        <div class="button_set">
<?php if ($baseController->is_auth(99, 3) != true): ?>
            <button type="button" onclick="alert('<?=$lang->user->error_not_permission?>');"><?=$lang->button->edit?></button>
<?php else: ?>
            <button type="button" onclick="open_edit(_seq); init_edit();"><?=$lang->button->edit?></button>
<?php endif;?>
        </div>
    </div>
</div>


<?PHP echo view('common/js'); ?>
<script type="text/javascript">
function create_list()
{
    open_view(0);

	if(_current['NTPSync'] != '1') {
		$('#view_ntp_show').hide();
	} else {
		$('#view_ntp_show').show();
	}
}

$(document).ready(function() {
    load_list();

    $("#form_edit4 input[name='SetDateStr']").DatePicker({
        format          : "m-d-Y",
        date            : "<?=date("m-d-Y")?>",
        current         : "<?=date("m-d-Y")?>",
        starts          : 0,
        position        : "bottom",
        onBeforeShow    : function() {
            var element = $("#form_edit4 input[name='SetDateStr']");
            if( element.val() == "" )
                element.DatePickerSetDate("<?=date("m-d-Y")?>", true);
            else
                element.DatePickerSetDate(element.val(), true);
        },
        onChange        : function(formated, dates){
            var element = $("#form_edit4 input[name='SetDateStr']");
            if( element.val() != formated ){
                $("#form_edit4 input[name='SetDateStr']").val(formated);
                $("#form_edit4 input[name='SetDateStr']").DatePickerHide();
            }
        }
    });
    /*
    $("#form_edit4 input[name='DSTStart']").DatePicker({
        format          : "m-d-Y",
        date            : "<?=date("m-d-Y")?>",
        current         : "<?=date("m-d-Y")?>",
        starts          : 0,
        position        : "bottom",
        onBeforeShow    : function() {
            var element = $("#form_edit4 input[name='DSTStart']");
            if( element.val() == "" )
                element.DatePickerSetDate("<?=date("m-d-Y")?>", true);
            else
                element.DatePickerSetDate(element.val(), true);
        },
        onChange        : function(formated, dates){
            var element = $("#form_edit4 input[name='DSTStart']");
            if( element.val() != formated ){
                $("#form_edit4 input[name='DSTStart']").val(formated);
                $("#form_edit4 input[name='DSTStart']").DatePickerHide();
            }
        }
    });

    $("#form_edit4 input[name='DSTEnd']").DatePicker({
        format          : "m-d-Y",
        date            : "<?=date("m-d-Y")?>",
        current         : "<?=date("m-d-Y")?>",
        starts          : 0,
        position        : "bottom",
        onBeforeShow    : function() {
            var element = $("#form_edit4 input[name='DSTEnd']");
            if( element.val() == "" )
                element.DatePickerSetDate("<?=date("m-d-Y")?>", true);
            else
                element.DatePickerSetDate(element.val(), true);
        },
        onChange        : function(formated, dates){
            var element = $("#form_edit4 input[name='DSTEnd']");
            if( element.val() != formated ){
                $("#form_edit4 input[name='DSTEnd']").val(formated);
                $("#form_edit4 input[name='DSTEnd']").DatePickerHide();
            }
        }
    });
    */
});

// edit data 채우기
function set_edit()
{
    set_edit_network('form_edit4');
    set_default();
}

function set_edit_network(form)
{
    $.each(_current, function(name, value) {
        var element = $("#"+ form +" input[name='"+ name +"']");
        if( element.attr("type") == "checkbox" )
        {
            if( value == "1")   element.attr("checked", true);
            else                element.attr("checked", false);
        }
        else if( element.attr("type") == "radio" )
        {
            $("#"+ form +" input:radio[name='"+ name +"']").filter("input[value='"+ value +"']").attr('checked', true);
        }
        else
        {
            element.val(value);
        }

        if( $("#"+ form +" select[name='"+ name +"']").attr("multiple") == true )
            $("#"+ form +" select[name='"+ name +"']").val(eval(value));
        else
            $("#"+ form +" select[name='"+ name +"']").val(value);
    });
    enable_form_edit4();
}

function enable_form_edit4()
{
    if( $("#form_edit4 input:radio[name='NTPSync']:checked").val() == '1' )
    {
        flag1 = false;      flag2 = true;
    }
    else
    {
        flag1 = true;       flag2 = false;
    }

    $("#form_edit4 input[name='NTPServer']").attr('disabled', flag1);
    $("#form_edit4 select[name='NTPServerSel']").attr('disabled', flag1);
    $("#form_edit4 select[name='NTPSyncTime']").attr('disabled', flag1);
    //$("#form_edit4 select[name='NTPSyncTimeZone']").attr('disabled', flag1);
    //$("#form_edit4 input[name='DSTEnable']").attr('disabled', flag1);
    //$("#form_edit4 input[name='DSTStart']").attr('disabled', flag1);
    //$("#form_edit4 input[name='DSTEnd']").attr('disabled', flag1);

    $("#form_edit4 input[name='SetDateStr']").attr('disabled', flag2);
    $("#form_edit4 input[name='SetTimeStr']").attr('disabled', flag2);

	enable_dst_date();
}

function enable_dst_date()
{
	if( $("#form_edit4 input:radio[name='DSTEnable']:checked").val() == '1' )
	//if( $("#form_edit4 input:radio[name='DSTEnable']:checked").val() == '1' && $("#form_edit4 input:radio[name='NTPSync']:checked").val() == '1' )
		flag3 = false;
	else
		flag3 = true;

    //$("#form_edit4 input[name='DSTStart']").attr('disabled', flag3);
    //$("#form_edit4 input[name='DSTEnd']").attr('disabled', flag3);
}

function set_default()
{
	if( _current['DSTEnable'] == '1' )
		$("#form_edit4 input:radio[name='DSTEnable']").filter("input[value='1']").attr('checked', 'checked');
	else
		$("#form_edit4 input:radio[name='DSTEnable']").filter("input[value='0']").attr('checked', 'checked');

	$("#form_edit4 input[name='SetDateStr']").val(_current['SetDateStr']);
	$("#form_edit4 input[name='SetTimeStr']").val(_current['SetTimeStr']);
}

function change_server()
{
	var val = $("select[name='NTPServerSel']").val();

	if( val == "" ) {
		$("input[name='NTPServer']").val("").show();
	} else {
		$("input[name='NTPServer']").val(val).hide();
	}
}

function init_edit()
{
	var val = $("input[name='NTPServer']").val();

	$("select[name='NTPServerSel'] > option").each(function() {
		if( $(this).val() == val ) {
			$(this).attr({selected:"selected"});
		}
	});

	var val = $("select[name='NTPServerSel']").val();

	if( val == "" ) {
		$("input[name='NTPServer']").show();
	} else {
		$("input[name='NTPServer']").hide();
	}
}
// NXG-3809
function time_zone_sync_client()
{
    var timezonesync_msg_element = document.getElementById('timezonesync_msg');
    var timezonesync_radio_element = document.getElementById('timezonesync_checkbox');
    if(timezonesync_radio_element.checked)
    {
        timezonesync_msg_element.innerHTML = "**(Server and Client(Connected) Timezone and DST will change.)";
    }
    else
    {
        timezonesync_msg_element.innerHTML = "**(Only Server Timezone and DST will change.)";
    }
}
// NXG-3809
</script>