<?PHP
use App\Libraries\EnumTable;
?>
<div id="">
<div id="location">
<?PHP
echo $lang->menu->devices.'&nbsp;&gt;&nbsp;'.$lang->menu->door;
?>
	<button class="btn_help" onclick="openHelp('help', 'door')">Help</button>
</div>


<div id="edit_section" class="hide">
<form name="form_door" id="form_edit" method="post" action="<?=base_url()?>door-update">
<?=Form::hidden("No")?>
<?=Form::hidden("Port")?>
<?=Form::hidden("HostNo")?>
<?=Form::hidden("MasterReader")?>
<?=Form::hidden("SlaveReader")?>
    <h2>:: <?=$lang->menu->door?></h2>
    <div class="box01">

        <h3><?=$lang->menu->basic?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->door->Name?> * </th>
            <td width="1">:</td>
            <td><?=Form::input('Name', "", array("MAXLENGTH"=>max_name_char))?></td>
        </tr>
        <tr>
            <th><?=$lang->door->Mean?> </th>
            <td width="1">:</td>
            <td><?=Form::input('Mean', "", array("MAXLENGTH"=>max_description_char))?></td>
        </tr>
        <?PHP if( TARGET_BOARD != "EVB") { ?>
        <tr>
            <th><?=$lang->door->Floor?> * </th>
            <td width="1">:</td>
            <td><?=Form::select('Floor', '', $attrFloor)?></td>
        </tr>
        <?PHP } ?>
        </table>
		<div class="space" style="height:5px"></div>
        <h3><?=$lang->door->Reader?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->door->ReaderFunction?> </th>
            <td width="1">:</td>
            <td><?=Form::select('ReaderFunction', '', EnumTable::$attrReaderFunc, array('onchange'=>'enable_s_reader(\'edit\')'))?></td>
			<td></td>
			<td></td>
        </tr>
        <tr>
            <th><?=$lang->door->MasterReaderName?> </th>
            <td width="1">:</td>
            <td><?=Form::input('MasterReaderName', "", array("MAXLENGTH"=>max_name_char))?></td>
			<td></td>
			<td></td>
        </tr>
        <tr>
            <th style="vertical-align: middle;"><?=$lang->door->MasterReaderType?> </th>
            <td width="1">:</td>
			<td>
				<?=Form::select('MasterReaderType', '', EnumTable::$attrReaderType, array('onchange'=>'change_mreader_type(\'edit\')'))?>
			</td>
			<td>
				<p class="master_cardformat_message"><?=$lang->door->message_select_cardformat?></p>
			</td>
			<td>
				<?=Form::select('MasterCardFormat', '', $attrCardFormat)?>
			</td>
        </tr>
        <!-- By SUN07 - 2016.03.08 : #2073 -->
		<!-- DELETE CJMOON 2017.03.20
        <?PHP if($_SESSION['spider_model'] == MODEL_ENTERPRISE|| $_SESSION['spider_model'] == MODEL_TE_STANDALONE || $_SESSION['spider_model'] == MODEL_TE_SERVER) { ?>
        <tr>
            <th><?=$lang->door->MasterReaderRegion?> </th>
            <td width="1">:</td>
            <td><?=Form::select('MasterReaderRegionNo', '', $attrRegion)?></td>
			<td></td>
			<td></td>
        </tr>
        <?PHP } ?>
		DELETE END  -->
        <tbody id='slave_reader'>
        <tr>
            <th><?=$lang->door->SlaveReaderName?> </th>
            <td width="1">:</td>
            <td><?=Form::input('SlaveReaderName', "", array("MAXLENGTH"=>max_name_char))?></td>
			<td></td>
			<td></td>
        </tr>
        <tr>
            <th style="vertical-align: middle;"><?=$lang->door->SlaveReaderType?> </th>
            <td width="1">:</td>
            <td>
				<?=Form::select('SlaveReaderType', '', EnumTable::$attrReaderType, array('onchange'=>'change_sreader_type(\'edit\')'))?>
			</td>
			<td>
				<p class="slave_cardformat_message"><?=$lang->door->message_select_cardformat?></p>
			</td>
			<td>
				<?=Form::select('SlaveCardFormat', '', $attrCardFormat)?>
			</td>
        </tr>
        
        <!-- By SUN07 - 2016.03.08 : #2073 -->
		<!-- DELETE CJMOON 2017.03.20
        <?PHP if($_SESSION['spider_model'] == MODEL_ENTERPRISE|| $_SESSION['spider_model'] == MODEL_TE_STANDALONE || $_SESSION['spider_model'] == MODEL_TE_SERVER) { ?>
        <tr>
            <th><?=$lang->door->SlaveReaderRegion?> </th>
            <td width="1">:</td>
            <td><?=Form::select('SlaveReaderRegionNo', '', $attrRegion)?></td>
			<td></td>
			<td></td>
        </tr>
        <?PHP } ?>
		DELETE END  -->
        </tbody>
        </table>

		<div class="space" style="height:5px"></div>
        <h3><?=$lang->door->dcontact?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=Form::checkbox('DoorContactEnable', $lang->common->enable, FALSE, '1', array('onclick'=>'enable_doorcontact(\'edit\')'))?></th>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <th><?=$lang->door->DoorContactName?> </th>
            <td width="1">:</td>
            <td><?=Form::input('DoorContactName', "", array("MAXLENGTH"=>max_name_char))?></td>
        </tr>
        <tr>
            <th width="150"><?=$lang->door->CircuitType?></th>
            <td width="1">:</td>
            <td><?=Form::select('CircuitType', '', CirciutType::$attrs, array('onchange'=>"change_circiut_type('CircuitType', 'edit')"))?></td>
        </tr>
        <tr>
            <th><?=$lang->door->HeldOpenTime?> </th>
            <td width="1">:</td>
            <td><?=Form::inputnum('HeldOpenTime', "", array("MAXLENGTH"=>max_door_held_open_time_char))?> (sec)</td>
        </tr>
        <tr>
            <th><?=$lang->door->AdaOpenTime?> </th>
            <td width="1">:</td>
            <td><?=Form::inputnum('AdaOpenTime', "", array("MAXLENGTH"=>max_door_ada_open_time_char))?> (sec)</td>
        </tr>
<!--        <tr>
            <th><?=$lang->door->OpenAlarmTime?> </th>
            <td width="1">:</td>
            <td><?=Form::inputnum('OpenAlarmTime', "", array("MAXLENGTH"=>"3"))?> (sec)</td>
        </tr>-->
        </table>
		<!-- ADD CJMOON 2007.03.28 -->
		<div class="space" style="height:5px"></div>
        <h3><?=$lang->door->postallock?></h3>
        <table class="tbl_view">
        <tr colspan="4">
            <td><?=Form::checkbox('PostalLockEnable', $lang->common->enable, FALSE, '1', array('onclick'=>'enable_P_man_En(\'edit\')'))?></td>
            <th width="75"><?=$lang->door->Schedule?></th>
            <td width="1">:</td>
            <td><?=Form::select('PostalLockSchedule', '', $arr_schedule)?></td>
        </tr>
		</table>
		
		<!-- ADD END ---------------->

		<div class="space" style="height:5px"></div>
        <h3><?=$lang->door->RexCircuitType?></h3>
        <table class="tbl_view">
        <tr>
            <th><?=$lang->door->DoorRexName?> </th>
            <td width="1">:</td>
            <td><?=Form::input('DoorRexName', "", array("MAXLENGTH"=>max_name_char))?></td>
        </tr>
        <tr>
            <th width="150"><?=$lang->door->RexCircuitType?></th>
            <td width="1">:</td>
            <td><?=Form::select('RexCircuitType', '', CirciutType::$attrs, array('onchange'=>"change_circiut_type('RexCircuitType', 'edit')"))?></td>
        </tr>
        <tr>
            <th><?=$lang->door->rex_act_door_lock?> </th>
            <td width="1">:</td>
            <td><?=Form::checkbox('RexAlarm', '', FALSE, '0')?></td>
        </tr>
        </table>
		<div class="space" style="height:5px"></div>
        <h3><?=$lang->door->LockMode?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->door->DoorLockName?> </th>
            <td width="1">:</td>
            <td><?=Form::input('DoorLockName', "", array("MAXLENGTH"=>max_name_char))?></td>
        </tr>
        <tr>
            <th width="150"><?=$lang->door->LockMode?></th>
            <td width="1">:</td>
            <td>
                <?=Form::select('LockMode', '', EnumTable::$attrLockMode, array('onchange'=>'change_lockmode(this)'))?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <span class="man-trap-option"><?=Form::checkbox('IsExterior', $lang->door->IsExterior, FALSE, '1')?></span>
            </td>
        </tr>
        </table>
        <table class="tbl_view man-trap-option">
        <tr>
            <th width="150"><?=$lang->door->ManTrapMode?></th>
            <td width="1">:</td>
            <td><?=Form::select('ManTrapMode', '', EnumTable::$attrManTrapMode)?></td>
            <th width="100"><?=$lang->door->PairDoor?></th>
            <td width="1">:</td>
            <td><?=Form::select('PairDoorNo', '', array())?></td>
        </tr>
        </table>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->door->DoorContactState?> * </th>
            <td width="1">:</td>
            <td><?=Form::select('DoorContactState', '', EnumTable::$attrState)?></td>
        </tr>
        <!--<tr>
            <th><?=$lang->door->StrikeTime?> </th>
            <td width="1">:</td>
            <td><?=Form::inputnum('StrikeTime', "", array("MAXLENGTH"=>max_name_char))?> (sec)</td>
        </tr>-->
        <tr>
            <th><?=$lang->addmsg->relock_on_open?> </th>
            <td width="1">:</td>
            <td><?=Form::checkbox('ReLockValue', '', FALSE, '1')?></td>
        </tr>
        <tr>
            <th><?=$lang->door->LockTime?> </th>
            <td width="1">:</td>
            <td><?=Form::inputnum('LockTime', "", array("MAXLENGTH"=>max_door_unlock_time_char))?> (sec)</td>
        </tr>
        </table>

		<div class="space" style="height:5px"></div>
        <h3><?=$lang->door->DoorStatusAlarmOutput?></h3>
        <table class="tbl_view">
        <tr>
            <th width="90"><?=$lang->common->enable?> </th>
            <td width="1">:</td>
            <td>
                <?=Form::checkbox('ForcedEnable', $lang->door->forced_door, FALSE, '1')?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <?=Form::checkbox('HeldEnable', $lang->door->held_door, FALSE, '1')?>
            </td>
            <th width="90"><?=$lang->common->enable?> </th>
            <td width="1">:</td>
            <td><?=Form::checkbox('ShuntEnable', $lang->door->alarm_shunt, FALSE, '1')?></td>
        </tr>
        <tr>
            <th><?=$lang->door->DefaultState?> </th>
            <td width="1">:</td>
            <td><?=Form::select('PF_AuxOutputState', '', EnumTable::$attrState)?></td>
            <th><?=$lang->door->DefaultState?> </th>
            <td width="1">:</td>
            <td><?=Form::select('AS_AuxOutputState', '', EnumTable::$attrState)?></td>
        </tr>
        <tr>
            <th><?=$lang->door->output?> </th>
            <td width="1">:</td>
            <td><?=Form::select('PF_AuxOutputNo', '', array())?></td>
            <th><?=$lang->door->output?> </th>
            <td width="1">:</td>
            <td><?=Form::select('AS_AuxOutputNo', '', array())?></td>
        </tr>
        </table>

        <?PHP if ($_SESSION['spider_model'] == MODEL_ELITE || $_SESSION['spider_model'] == MODEL_ENTERPRISE || $_SESSION['spider_model'] == MODEL_TE_STANDALONE || $_SESSION['spider_model'] == MODEL_TE_SERVER) { ?>
        <div class="space" style="height:5px"></div>
        <h3><?=$lang->door->ThreatLevel?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->door->ThreatLevel?> </th>
            <td width="1">:</td>
            <td><?=Form::select('ThreatLevel', '', $attrThreat)?></td>
        </tr>
        <tr>
            <th><?=$lang->door->IgnoreRex?> </th>
            <td width="1">:</td>
            <td><?=Form::checkbox('ThreatIgnoreRex', '', FALSE, '1')?></td>
        </tr>
        </table>
		<?PHP } ?>
		
		<?PHP if ($_SESSION['spider_model'] == MODEL_ELITE || $_SESSION['spider_model'] == MODEL_ENTERPRISE|| $_SESSION['spider_model'] == MODEL_TE_STANDALONE || $_SESSION['spider_model'] == MODEL_TE_SERVER) { ?>
		<div class="space" style="height:5px"></div>
        <h3><?=$lang->door->anti?></h3>
        <table class="tbl_view">
        <tr>
	        <th width="150"><?=$lang->door->tantipassb?> </th>
            <td width="1">:</td>
            <div>
	            <td>
	                <?=Form::checkbox('TimeAnti', $lang->door->TimeAnti, FALSE, '1', array('onclick'=>'enable_T_anti(\'edit\')'))?>
	            </td>
	            <td width="80"><?=$lang->door->TimeAntiTime?></td>
	            <td width="1">:</td>
	            <td>
	            	<?=Form::inputnum('TimeAntiTime', "", array("MAXLENGTH"=>max_door_timeanti_passback_char))?> (sec)
	            </td>
            </div>
        </tr>
        <tr id="roomanti_tr">
	        <th width="150"><?=$lang->door->rantipassb?> </th>
            <td width="1">:</td>
            <div>
	            <td>
	                <?=Form::checkbox('RoomAnti', $lang->door->RoomAnti, FALSE, '1', array('onclick'=>'enable_R_anti(\'edit\')'))?>
	            </td>
	            <td width="80"><?=$lang->door->RoomAntiTime?></td>
	            <td width="1">:</td>
	            <td>
	            	<?=Form::inputnum('RoomAntiTime', "", array("MAXLENGTH"=>max_door_roomanti_passback_char))?> (sec)
	            </td>
	        </div>
        </tr>
        </table>
		<?PHP } ?>
        <div class="space" style="height:5px"></div>
        <h3><?=$lang->door->firstmanin?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=Form::checkbox('FirstManRule', $lang->door->FirstManRule, FALSE, '1', array('onclick'=>'enable_F_man_En(\'edit\')'))?></th>
            <td>&nbsp;</td>
        </tr>
        <!-- <tr>
            <th><?=Form::checkbox('FirstManEveryDay', $lang->door->FirstManEveryDay, FALSE, '1', array('onclick'=>'enable_F_man_Every_Day(\'edit\')'))?></th>
            <td>
                <?=$lang->door->SetDate?> <?=Form::input('FirstManDate', '', array('class'=>'date', 'readonly'=>'readonly'))?>
                <?=$lang->door->SetTime?> 
                <?=Form::select('FirstManStartHour', '', EnumTable::$attrHourList)?>:<?=Form::select('FirstManStartMin', '', EnumTable::$attrMinList)?> - 
                <?=Form::select('FirstManEndHour', '', EnumTable::$attrHourList)?>:<?=Form::select('FirstManEndMin', '', EnumTable::$attrMinList)?>
            </td>
        </tr> -->
		<tr>
            <th><?=$lang->door->GracePeriod?></th>
            <td>
                <?=Form::select('GracePeriod', '', range(0, 60))?>
                <?=$lang->door->GracePeriodHelperMessage?>
            </td>
        </tr>
		<tr>
            <th><?=$lang->door->Schedule?> 1</th>
            <td><?=Form::select('FirstManScheduleNo1', '', $arr_schedule)?></td>
        </tr>
		<tr>
            <th><?=$lang->door->Schedule?> 2</th>
            <td><?=Form::select('FirstManScheduleNo2', '', $arr_schedule)?></td>
        </tr>
		<tr>
            <th><?=$lang->door->Schedule?> 3</th>
            <td><?=Form::select('FirstManScheduleNo3', '', $arr_schedule)?></td>
        </tr>
        <tr>
            <th><?=$lang->door->SelectType?></th>
            <td><?=Form::select('FirstManSelectType', '', EnumTable::$attrGroup, array('onchange'=>"change_selecttype_firstman('edit')"))?></td>
        </tr>
        <tr>
            <th><?=$lang->door->cardholder?></th>
            <td>
                <?=Form::input('f_search_user')?>&nbsp;&nbsp;
                <button name="btn_f_search_user" class="btn_find" type="button" onclick="submit_search_user('edit', 'f_', 0)"></button>
				<select name="f_search_user_offset" class="hide" onchange="submit_search_user('edit', 'f_', $(this).val())"></select><br />
                <div class="left"><?=Form::select('f_find_user[]', '', array(), array('multiple'=>'multiple', 'style'=>'width:200px'))?></div>
                <div class="left text_left">
                    &nbsp;<button name="f_btn_push" class="btn_push" type="button" onclick="push_user('edit', 'f_find_user', 'FirstManUserList')"></button>&nbsp;<br /><br />
                    &nbsp;<button name="f_btn_pop"  class="btn_pop"  type="button" onclick="pop_user('edit', 'FirstManUserList')"></button>&nbsp;
                </div>
                <div class="left"><?=Form::select('FirstManUserList[]', '', array(), array('multiple'=>'multiple', 'style'=>'width:200px'))?></div>
            </td>
        </tr>
        </table>
        <div class="space" style="height:5px"></div>
        <h3><?=$lang->door->ManagerInRule?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=Form::checkbox('ManagerInRule', $lang->door->ManagerIn, FALSE, '1', array('onclick'=>'enable_ManagerIn(\'edit\')'))?></th>
            <td></td>
        </tr>
        <!-- <tr>
            <th><?=$lang->door->ManagerTime?></th>
            <td>
                <div style="width:100px; float:left;"><?=Form::checkbox('ManagerInReverse', $lang->unlockschedule->Reverse)?></div>
                <div>
                    <?=Form::Select('ManagerInBeginHour', '', EnumTable::$attrHourList)?>&nbsp:&nbsp<?=Form::Select('ManagerInBeginMin', '', EnumTable::$attrMinList)?> ~ 
                    <?=Form::Select('ManagerInEndHour', '', EnumTable::$attrHourList)?>&nbsp:&nbsp<?=Form::Select('ManagerInEndMin', '', EnumTable::$attrMinList)?>
                </div>
            </td>
        </tr> -->
		<tr>
            <th><?=$lang->door->Schedule?> 1</th>
            <td><?=Form::select('ManagerInScheduleNo1', '', $arr_schedule)?></td>
        </tr>
		<tr>
            <th><?=$lang->door->Schedule?> 2</th>
            <td><?=Form::select('ManagerInScheduleNo2', '', $arr_schedule)?></td>
        </tr>
		<tr>
            <th><?=$lang->door->Schedule?> 3</th>
            <td><?=Form::select('ManagerInScheduleNo3', '', $arr_schedule)?></td>
        </tr>
        <tr>
            <th><?=$lang->door->SelectType?></th>
            <td><?=Form::select('ManagerInSelectType', '', EnumTable::$attrGroup, array('onchange'=>"change_selecttype_manager('edit')"))?></td>
        </tr>
        <tr>
            <th><?=$lang->door->Manager?></th>
            <td>
                <?=Form::input('m_search_user')?>&nbsp;&nbsp;
                <button name="btn_m_search_user" class="btn_find" type="button" onclick="submit_search_user('edit', 'm_', 0)"></button>
				<select name="m_search_user_offset" class="hide" onchange="submit_search_user('edit', 'm_', $(this).val())"></select><br />
                <div class="left"><?=Form::select('m_find_user[]', '', array(), array('multiple'=>'multiple', 'style'=>'width:200px'))?></div>
                <div class="left text_left">
                    &nbsp;<button name="m_btn_push" class="btn_push" type="button" onclick="push_user('edit', 'm_find_user', 'ManagerList')"></button>&nbsp;<br /><br />
                    &nbsp;<button name="m_btn_pop"  class="btn_pop"  type="button" onclick="pop_user('edit', 'ManagerList')"></button>&nbsp;
                </div>
                <div class="left"><?=Form::select('ManagerList[]', '', array(), array('multiple'=>'multiple', 'style'=>'width:200px'))?></div>
            </td>
        </tr>
		<!-- <tr>
            <th><?=$lang->door->Manager?></th>
            <td>
				<?=Form::select('ManagerList[]', '', $baseController->get_user_to_options())?>
			</td>
		</tr> -->
        </table>
        
        <?PHP if( TARGET_BOARD != "EVB") { ?>
		<div class="space" style="height:5px"></div>
        <h3><?=$lang->door->twomanrule?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=Form::checkbox('TwoManRule', $lang->door->TwoManRule, FALSE, '1', array('onclick'=>'enable_T_man_En(\'edit\')'))?></th>
            <td>
                <?=$lang->door->TimeAntiTime?> : <?=Form::inputnum('TwoManTime', "", array("MAXLENGTH"=>max_name_char))?> (sec)
            </td>
        </tr>

        <!-- <tr>
            <th><?=$lang->door->SelectType?></th>
            <td><?=Form::select('TwoMan1SelectType', '', EnumTable::$attrGroup, array('onchange'=>"change_selecttype_twoman1('edit')"))?></td>
        </tr> -->
        <tr>
            <th><?=$lang->door->cardholder1?></th>
            <td>
                <?=Form::input('t_search_user')?>&nbsp;&nbsp;
                <button name="btn_t_search_user" class="btn_find" type="button" onclick="submit_search_user('edit', 't_', 0)"></button>
				<select name="t_search_user_offset" class="hide" onchange="submit_search_user('edit', 't_', $(this).val())"></select><br />
                <div class="left"><?=Form::select('t_find_user[]', '', array(), array('multiple'=>'multiple', 'style'=>'width:200px'))?></div>
                <div class="left text_left">
                    &nbsp;<button name="t_btn_push" class="btn_push" type="button" onclick="push_user('edit', 't_find_user', 'TwoMan1UserList')"></button>&nbsp;<br /><br />
                    &nbsp;<button name="t_btn_pop"  class="btn_pop"  type="button" onclick="pop_user('edit', 'TwoMan1UserList')"></button>&nbsp;
                </div>
                <div class="left"><?=Form::select('TwoMan1UserList[]', '', array(), array('multiple'=>'multiple', 'style'=>'width:200px'))?></div>
            </td>
        </tr>
        <!-- <tr>
            <th><?=$lang->door->SelectType?></th>
            <td><?=Form::select('TwoMan2SelectType', '', EnumTable::$attrGroup, array('onchange'=>"change_selecttype_twoman2('edit')"))?></td>
        </tr> -->
        <tr>
            <th><?=$lang->door->cardholder2?></th>
            <td>
                <?=Form::input('t2_search_user')?>&nbsp;&nbsp;
                <button name="btn_t2_search_user" class="btn_find" type="button" onclick="submit_search_user('edit', 't2_', 0)"></button>
				<select name="t2_search_user_offset" class="hide" onchange="submit_search_user('edit', 't2_', $(this).val())"></select><br />
                <div class="left"><?=Form::select('t2_find_user[]', '', array(), array('multiple'=>'multiple', 'style'=>'width:200px'))?></div>
                <div class="left text_left">
                    &nbsp;<button name="t2_btn_push" class="btn_push" type="button" onclick="push_user('edit', 't2_find_user', 'TwoMan2UserList')"></button>&nbsp;<br /><br />
                    &nbsp;<button name="t2_btn_pop"  class="btn_pop"  type="button" onclick="pop_user('edit', 'TwoMan2UserList')"></button>&nbsp;
                </div>
                <div class="left"><?=Form::select('TwoMan2UserList[]', '', array(), array('multiple'=>'multiple', 'style'=>'width:200px'))?></div>
            </td>
        </tr>
        </table>
		<?PHP } ?>
		
        <div class="button_set">
            <button type="button" onclick="before_submit('edit'); $('#form_edit').submit();"><?=$lang->button->save?></button>&nbsp;&nbsp;
            <button type="button" onclick="open_edit(_seq); init_edit();"><?=$lang->button->reset?></button>&nbsp;&nbsp;
            <button type="button" onclick="close_edit()"><?=$lang->button->cancel?></button>
        </div>
    </div>
</form>
</div>


<div id="view_section" class="hide">
    <h2>:: <?=$lang->menu->door?></h2>
    <div class="box01">
        <h3><?=$lang->menu->basic?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->door->Name?> * </th>
            <td width="1">:</td>
            <td id="view_Name"></td>
        </tr>
        <tr>
            <th><?=$lang->door->Mean?> </th>
            <td width="1">:</td>
            <td id="view_Mean"></td>
        </tr>
        <?PHP if( TARGET_BOARD != "EVB") { ?>
        <tr>
            <th><?=$lang->door->Floor?> *</th>
            <td width="1">:</td>
            <td id="view_FloorStr"></td>
        </tr>
        <?PHP } ?>
        </table>
		<div class="space" style="height:5px"></div>
        <h3><?=$lang->door->Reader?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->door->ReaderFunction?> </th>
            <td width="1">:</td>
            <td id="view_ReaderFunctionStr"></td>
        </tr>
        <tr>
            <th><?=$lang->door->MasterReaderName?> </th>
            <td width="1">:</td>
            <td id="view_MasterReaderName"></td>
        </tr>
        <tr>
            <th><?=$lang->door->MasterReaderType?> </th>
            <td width="1">:</td>
            <td id="view_MasterReaderTypeStr"></td>
        </tr>
        
        <!-- By SUN07 - 2016.03.08 : #2073 -->
		<!-- DELETE CJMOON 2017.03.20 
        <?PHP if($_SESSION['spider_model'] == MODEL_ENTERPRISE|| $_SESSION['spider_model'] == MODEL_TE_STANDALONE || $_SESSION['spider_model'] == MODEL_TE_SERVER) { ?>
        <tr>
            <th><?=$lang->door->MasterReaderRegion?> </th>
            <td width="1">:</td>
            <td id="view_MasterReaderRegionStr"></td>
        </tr>
        <?PHP } ?>
         DELETE END  -->
        <tbody id='slave_reader_view'>
        <tr>
            <th><?=$lang->door->SlaveReaderName?> </th>
            <td width="1">:</td>
            <td id="view_SlaveReaderName"></td>
        </tr>
        <tr>
            <th><?=$lang->door->SlaveReaderType?> </th>
            <td width="1">:</td>
            <td id="view_SlaveReaderTypeStr"></td>
        </tr>
        
        <!-- By SUN07 - 2016.03.08 : #2073 -->
		<!-- DELETE CJMOON 2017.03.20  
        <?PHP if($_SESSION['spider_model'] == MODEL_ENTERPRISE|| $_SESSION['spider_model'] == MODEL_TE_STANDALONE || $_SESSION['spider_model'] == MODEL_TE_SERVER) { ?>
        <tr>
            <th><?=$lang->door->SlaveReaderRegion?> </th>
            <td width="1">:</td>
            <td id="view_SlaveReaderRegionStr"></td>
        </tr>
        <?PHP } ?>
        DELETE END  -->
        </tbody>
        </table>
		<div class="space" style="height:5px"></div>
        <h3><?=$lang->door->CircuitType?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->common->enable?></th>
            <td width="1">:</td>            
            <td id="view_DoorContactEnableStr"></td>
        </tr>
        <tr>
            <th width="150"><?=$lang->door->DoorContactName?></th>
            <td width="1">:</td>
            <td id="view_DoorContactName"></td>
        </tr>
        <tr>
            <th width="150"><?=$lang->door->CircuitType?></th>
            <td width="1">:</td>
            <td id="view_CircuitTypeStr"></td>
        </tr>
        <tr>
            <th><?=$lang->door->HeldOpenTime?></th>
            <td width="1">:</td>
            <td id="view_HeldOpenTimeStr"></td>
        </tr>
        <tr>
            <th><?=$lang->door->AdaOpenTime?></th>
            <td width="1">:</td>
            <td id="view_AdaOpenTimeStr"></td>
        </tr>
        <!--<tr>
            <th><?=$lang->door->OpenAlarmTime?></th>
            <td width="1">:</td>
            <td id="view_OpenAlarmTimeStr"></td>
        </tr>-->
        </table>

		<!-- ADD CJMOON 2007.03.28 -->
		<div class="space" style="height:5px"></div>
        <h3><?=$lang->door->postallock?></h3>
        <table class="tbl_view">
        <tr colspan="4">
            <th width="150"><?=$lang->common->enable?></th>
            <td width="1">:</td>            
            <td id="view_PostalLockEnableName"></td>

            <th width="75"><?=$lang->door->Schedule?></th>
            <td width="1">:</td>
            <td id="view_PostalLockScheduleName"></td>
        </tr>
		</table>
		
		<!-- ADD END ---------------->

		<div class="space" style="height:5px"></div>
        <h3><?=$lang->door->RexCircuitType?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->door->DoorRexName?></th>
            <td width="1">:</td>
            <td id="view_DoorRexName"></td>
        </tr>
        <tr>
            <th width="150"><?=$lang->door->RexCircuitType?></th>
            <td width="1">:</td>
            <td id="view_RexCircuitTypeStr"></td>
        </tr>
        <tr>
            <th><?=$lang->door->rex_act_door_lock?> </th>
            <td width="1">:</td>
            <td id="view_RexAlarmStr"></td>
        </tr>
        </table>
		<div class="space" style="height:5px"></div>
        <h3><?=$lang->door->LockMode?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->door->DoorLockName?></th>
            <td width="1">:</td>
            <td id="view_DoorLockName"></td>
        </tr>
        <tr>
            <th width="150"><?=$lang->door->LockMode?> </th>
            <td width="1">:</td>
            <td><span id="view_LockModeStr"></span> <span id="view_IsExteriorStr"></span></td>
        </tr>
        </table>




        <table class="tbl_view man-trap-option">
        <tr>
            <th width="150"><?=$lang->door->ManTrapMode?></th>
            <td width="1">:</td>
            <td id="view_ManTrapModeStr"></td>
            <th width="100"><?=$lang->door->PairDoor?></th>
            <td width="1">:</td>
            <td id="view_PairDoorName"></td>
        </tr>
        </table>




        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->door->DoorContactState?></th>
            <td width="1">:</td>
            <td id="view_DoorContactStateStr"></td>
        </tr>
        <!--<tr>
            <th><?=$lang->door->StrikeTime?> </th>
            <td width="1">:</td>
            <td id="view_StrikeTimeStr"></td>
        </tr>-->
        <tr>
            <th><?=$lang->addmsg->relock_on_open?> </th>
            <td width="1">:</td>
            <td id="view_ReLockStr"></td>
        </tr>
        <tr>
            <th><?=$lang->door->LockTime?> </th>
            <td width="1">:</td>
            <td id="view_LockTimeStr"></td>
        </tr>
        </table>
        
		<div class="space" style="height:5px"></div>
        <h3><?=$lang->door->DoorStatusAlarmOutput?></h3>
        <table class="tbl_view">
        <tr>
            <th width="90"><?=$lang->common->enable?> </th>
            <td width="1">:</td>
            <td>
                <?=$lang->door->forced_door?> : <span id="view_ForcedEnableStr"></span>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <?=$lang->door->held_door?> : <span id="view_HeldEnableStr"></span>
            </td>
            <th width="90"><?=$lang->common->enable?> </th>
            <td width="1">:</td>
            <td>
                <?=$lang->door->alarm_shunt?> : <span id="view_ShuntEnableStr"></span>
            </td>
        </tr>
        <tr>
            <th><?=$lang->door->DefaultState?> </th>
            <td width="1">:</td>
            <td id="view_PF_AuxOutputStateStr"></td>
            <th><?=$lang->door->DefaultState?> </th>
            <td width="1">:</td>
            <td id="view_AS_AuxOutputStateStr"></td>
        </tr>
        <tr>
            <th><?=$lang->door->output?> </th>
            <td width="1">:</td>
            <td id="view_PF_AuxOutputName"></td>
            <th><?=$lang->door->output?> </th>
            <td width="1">:</td>
            <td id="view_AS_AuxOutputName"></td>
        </tr>
        </table>

        <?PHP if ($_SESSION['spider_model'] == MODEL_ELITE || $_SESSION['spider_model'] == MODEL_ENTERPRISE|| $_SESSION['spider_model'] == MODEL_TE_STANDALONE || $_SESSION['spider_model'] == MODEL_TE_SERVER) { ?>
        <div class="space" style="height:5px"></div>
        <h3><?=$lang->door->ThreatLevel?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->door->ThreatLevel?> </th>
            <td width="1">:</td>
            <td id="view_ThreatLevelStr"></td>
        </tr>
        <tr>
            <th><?=$lang->door->IgnoreRex?> </th>
            <td width="1">:</td>
            <td id="view_ThreatIgnoreRexStr"></td>
        </tr>
        </table>
        <?PHP } ?>
        
		<?PHP if ($_SESSION['spider_model'] == MODEL_ELITE || $_SESSION['spider_model'] == MODEL_ENTERPRISE|| $_SESSION['spider_model'] == MODEL_TE_STANDALONE || $_SESSION['spider_model'] == MODEL_TE_SERVER) { ?>
		<div class="space" style="height:5px"></div>
        <h3><?=$lang->door->anti?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->door->tantipassb?></th>
            <td width="1">:</td>
            <td>
                <span id="view_TimeAntiStr"></span>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <?=$lang->door->TimeAntiTime?> : <span id="view_TimeAntiTimeStr"></span>
            </td>
            
        </tr>
        <tr id="roomanti_tr_view">
            <th width="150"><?=$lang->door->rantipassb?></th>
            <td width="1">:</td>
            <td>
                <span id="view_RoomAntiStr"></span>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <?=$lang->door->RoomAntiTime?> : <span id="view_RoomAntiTimeStr"></span>
            </td>
        </tr>
        </table>
		<?PHP } ?>
        
        <div class="space" style="height:5px"></div>
        <h3><?=$lang->door->firstmanin?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->door->FirstManRule?></th>
            <td width="1">:</td>
            <td id="view_FirstManRuleStr"></td>
        </tr>
        <!-- <tr>
            <th><?=$lang->door->FirstManEveryDay?></th>
            <td width="1">:</td>
            <td id="view_FirstManEveryDayStr"></td>
            <td></td>
            <td>
                <?=$lang->door->SetDate?> : <span id="view_FirstManDate"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <?=$lang->door->SetTime?> : <span id="view_FirstManTimeStr"></span>
            </td>
        </tr> -->
        <tr>
            <th width="150"><?=$lang->door->GracePeriod?></th>
            <td width="1">:</td>
            <td id="view_GracePeriodStr"></td>
        </tr>
        <tr>
            <th width="150"><?=$lang->door->Schedule?> 1</th>
            <td width="1">:</td>
            <td id="view_FirstManScheduleName1"></td>
        </tr>
        <tr>
            <th width="150"><?=$lang->door->Schedule?> 2</th>
            <td width="1">:</td>
            <td id="view_FirstManScheduleName2"></td>
        </tr>
        <tr>
            <th width="150"><?=$lang->door->Schedule?> 3</th>
            <td width="1">:</td>
            <td id="view_FirstManScheduleName3"></td>
        </tr>
        <tr>
            <th><?=$lang->door->SelectType?></th>
            <td width="1">:</td>
            <td id="view_FirstManSelectTypeName"></td>
        </tr>
        <tr>
            <th><?=$lang->door->cardholder?></th>
            <td width="1">:</td>
            <td id="view_FirstManUserStr"></td>
        </tr>
        </table>
        
        <div class="space" style="height:5px"></div>
        <h3><?=$lang->door->ManagerInRule?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->door->ManagerIn?></th>
            <td width="1">:</td>
            <td id="view_ManagerInStr"></td>
        </tr>
        <!-- <tr>
            <th><?=$lang->door->ManagerTime?></th>
            <td width="1">:</td>
            <td id="view_ManagerInTimeStr"></td>
        </tr> -->
        <tr>
            <th width="150"><?=$lang->door->Schedule?> 1</th>
            <td width="1">:</td>
            <td id="view_ManagerInScheduleName1"></td>
        </tr>
        <tr>
            <th width="150"><?=$lang->door->Schedule?> 2</th>
            <td width="1">:</td>
            <td id="view_ManagerInScheduleName2"></td>
        </tr>
        <tr>
            <th width="150"><?=$lang->door->Schedule?> 3</th>
            <td width="1">:</td>
            <td id="view_ManagerInScheduleName3"></td>
        </tr>
        <tr>
            <th><?=$lang->door->SelectType?></th>
            <td width="1">:</td>
            <td id="view_ManagerInSelectTypeName"></td>
        </tr>
        <tr>
            <th><?=$lang->door->Manager?></th>
            <td width="1">:</td>
            <td id="view_ManagerListStr"></td>
        </tr>
        </table>

		<?PHP if( TARGET_BOARD != "EVB") { ?>
		<div class="space" style="height:5px"></div>
        <h3><?=$lang->door->twomanrule?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->door->TwoManRule?></th>
            <td width="1">:</td>            
            <td id="view_TwoManRuleStr"></td>
        </tr>
        <!-- <tr>
            <th><?=$lang->door->SelectType?></th>
            <td width="1">:</td>
            <td id="view_TwoMan1SelectTypeName"></td>
        </tr> -->
        <tr>
            <th><?=$lang->door->cardholder1?> </th>
            <td width="1">:</td>
            <td id="view_TwoMan1UserStr"></td>
        </tr>
        <!-- <tr>
            <th><?=$lang->door->SelectType?></th>
            <td width="1">:</td>
            <td id="view_TwoMan2SelectTypeName"></td>
        </tr> -->
        <tr>
            <th><?=$lang->door->cardholder2?> </th>
            <td width="1">:</td>
            <td id="view_TwoMan2UserStr"></td>
        </tr>
        </table>
        <?PHP } ?>

        <div class="button_set">
            <button type="button" onclick="open_edit(_seq); init_edit();"><?=$lang->button->edit?></button>&nbsp;&nbsp;
            <button type="button" onclick="close_view()"><?=$lang->button->cancel?></button>
        </div>
    </div>
</div>


<div id="list_section">
    <h2>:: <?=$lang->menu->list?></h2>
    <div class="box01">
        <table class="tbl_list">
        <tr>
            <th><?=$lang->door->No?></th>
            <th><?=$lang->door->Name?></th>
            <th><?=$lang->door->Client?></th>
            <th><?=$lang->door->Mean?></th>
            <?PHP if( TARGET_BOARD != "EVB") { ?>
            <th><?=$lang->door->Floor?></th>
            <?PHP } ?>
            <th><?=$lang->door->LockMode?></th>
        </tr>
        <tbody id="list_body">
        </tbody>
        </table>

        <table class="list_button_set">
		<!-- ADD CJMOON 2017.05.17 Door Controll -->
		<td>
		        <div style="margin-top:1px;width=160px;">
		            <h2><?=$lang->menu->doorc?></h2>
		            <div class="box01">
		                <form id="form_doorc" method="post" action="<?=base_url()?>door-control">
						<input type="hidden" name="lock_mode" value="">
		                <table style="margin-left:15px">
		                <td class="button_set">
		                    <td>
                                <?=Form::select('sel_door', '', $arr_door, array('style'=>'width:100px;'))?>
                                <a href="javascript:$('input[name=lock_mode]', $('#form_doorc')).val('m_unlock'); $('#form_doorc').submit(); void(0)" title="<?=$lang->menu->momentary_unlock?>"><img src="<?=base_url()?>assets/img/button/m_unlock.png"></a>
		                        <a href="javascript:$('input[name=lock_mode]', $('#form_doorc')).val('unlocked'); $('#form_doorc').submit(); void(0)" title="<?=$lang->menu->eternity_unlock?>"><img src="<?=base_url()?>assets/img/button/e_unlock.png"></a>
		                        <a href="javascript:$('input[name=lock_mode]', $('#form_doorc')).val('locked'); $('#form_doorc').submit(); void(0)" title="<?=$lang->menu->lock?>"><img src="<?=base_url()?>assets/img/button/lock.png"></a>
		                    </td>
		                </td>                    
		                </table>                
		                </form>
		            </div>
		        </div>
		    </td>
		<!-- Door Controll End -->
        <tr>
            <td align="center">
            <form id="form_search" method="post" action="<?=base_url()?>door-get" onsubmit="load_list_search('<?=base_url()?>door-get'); return false;" target="_self">
            <?=Form::select('field', '', array('Name'=>$lang->door->Name, 'Mean'=>$lang->door->Mean))?>
            <?=Form::input('word', '')?>&nbsp;
            <button type="button" onclick="load_list_search();"><?=$lang->button->search?></button>
            </form>
            </td>
            <td width="100" align="right"><button type="button" onclick="load_list('<?=base_url()?>door-get')"><?=$lang->button->list?></button></td>
        </tr>
        </table>

        <div id="pagination" class="pagination">[ 1 ]</div>
    </div>
</div>
</div>

<?PHP echo view('common/js'); ?>

<script type="text/javascript">
function create_list()
{
    $("#list_body").html("");
    for(var i=0; i<_data.list.length; i++)
    {
        $("#list_body").append(
            '<tr id="list_'+ i +'" onclick="open_view('+ i +'); init_view()" class="ov">' +
            '   <td>'+ _data.list[i].No +'</td>' +
            '   <td>'+ _data.list[i].Name +'</td>' +
            '   <td>'+ _data.list[i].Client +'</td>' +
            '   <td>'+ _data.list[i].Mean +'</td>' +
            <?PHP if( TARGET_BOARD != "EVB") { ?>
            '   <td>'+ _data.list[i].FloorStr +'</td>' +
            <?PHP } ?>
            '   <td>'+ _data.list[i].LockModeStr +'</td>' +
            '</tr>'
        );
    }

    create_pagination();
}

$(document).ready(function() {
    load_circiut_type();
    load_list('<?=base_url()?>door-get');

    $("#form_edit input[name='FirstManDate']").datepicker({
        format          : "m-d-Y",
        date            : "<?=date("m-d-Y")?>",
        current         : "<?=date("m-d-Y")?>",
        starts          : 0,
        position        : "bottom",
        onBeforeShow    : function() {
            var element = $("#form_edit input[name='FirstManDate']");
            if( element.val() == "" )
                element.DatePickerSetDate("<?=date("m-d-Y")?>", true);
            else
                element.DatePickerSetDate(element.val(), true);
        },
        onChange        : function(formated, dates){
            var element = $("#form_edit input[name='FirstManDate']");
            if( element.val() != formated ){
                $("#form_edit input[name='FirstManDate']").val(formated);
                $("#form_edit input[name='FirstManDate']").DatePickerHide();
            }
        }
    });

});

function init_view()
{
	if( _current.ReaderFunction == "0")
	{
		$("#view_section").find("#slave_reader_view").hide();
		$("#view_section").find("#roomanti_tr_view").hide();
	}
	else
	{
		$("#view_section").find("#slave_reader_view").show();
		$("#view_section").find("#roomanti_tr_view").show();
	}

    // Man-Trap
    if( _current['LockMode'] == '4' ) {
        $("#view_section .man-trap-option").show();
    } else {
        $("#view_section .man-trap-option").hide();
    }
}

function init_edit()
{
    // Man-Trap
    if( _current['Port'] == '1' ) {
        $("#form_edit select[name='LockMode']").attr("disabled", false);
        $("#form_edit select[name='LockMode'] option[value='4']").attr("disabled", false);
        $("#form_edit input[name='IsExterior']").attr("disabled", false);
        $("#form_edit select[name='ManTrapMode']").attr("disabled", false);
        $("#form_edit select[name='PairDoorNo']").attr("disabled", false);
		$("#form_edit input[name='PostalLockEnable']").attr("disabled", false);
		$("#form_edit select[name='PostalLockSchedule']").attr("disabled", false);
    } else {
        $("#form_edit select[name='LockMode'] option[value='4']").attr("disabled", true);
        $("#form_edit input[name='IsExterior']").attr("disabled", true);
        $("#form_edit select[name='ManTrapMode']").attr("disabled", true);
        $("#form_edit select[name='PairDoorNo']").attr("disabled", true);
		$("#form_edit input[name='PostalLockEnable']").attr("disabled", true);
		$("#form_edit select[name='PostalLockSchedule']").attr("disabled", true);

        if( _current['LockMode'] == '4' ) {
            $("#form_edit select[name='LockMode']").attr("disabled", true);
        } else {
            $("#form_edit select[name='LockMode']").attr("disabled", false);
        }
    }
    $("#form_edit select[name='LockMode']").trigger("onchange");

	change_selecttype_firstman('edit');
	change_selecttype_manager('edit');
	//change_selecttype_twoman1('edit');
	//change_selecttype_twoman2('edit');

    disable_form();
    enable_s_reader('edit');
    change_mreader_type('edit');
    change_sreader_type('edit');
    enable_T_anti('edit');
    enable_R_anti('edit');
    enable_Z_anti('edit');
    enable_A_anti('edit');
    enable_T_man_En('edit');
    enable_F_man_En('edit');
    enable_ManagerIn('edit');
//	enable_P_man_En('edit');
    set_user_list('edit');
   

	$("#form_edit select").each(function() {
		dropdownEmptyToFirst($(this));
	});

    $("select[name='PF_AuxOutputNo']").empty();
    $("select[name='AS_AuxOutputNo']").empty();

    $.ajax({
        dataType: "json",
        url: "/?c=aoutput&m=find",
        data: "f=HostNo&w=" + _current["HostNo"],
        success: function(data) {
            $.each(data.list, function() {
                $("select[name='PF_AuxOutputNo']").prepend('<option value="'+ this['No'] +'">'+ this['Name'] +'</option>');
                $("select[name='AS_AuxOutputNo']").prepend('<option value="'+ this['No'] +'">'+ this['Name'] +'</option>');
            });

            $("select[name='PF_AuxOutputNo'] option[value='"+ _current['PF_AuxOutputNo'] +"']").attr('selected', true);
            $("select[name='AS_AuxOutputNo'] option[value='"+ _current['AS_AuxOutputNo'] +"']").attr('selected', true);
        }
    });

    $("select[name='PairDoorNo']").empty();

    $.ajax({
        dataType: "json",
        url: "/?c=door&m=find",
        data: "f=HostNo&w=" + _current["HostNo"],
        success: function(data) {
            $.each(data.list, function() {
                if( this['No'] != _current['No'] ) {
                    $("select[name='PairDoorNo']").prepend('<option value="'+ this['No'] +'">'+ this['Name'] +'</option>');
                }
            });

            $("select[name='PairDoorNo'] option[value='"+ _current['PairDoorNo'] +"']").attr('selected', true);
        }
    });

    submit_search_user('edit', 't_', 0);
    submit_search_user('edit', 't2_', 0);
}

function set_user_list(form)
{
    $("#form_"+ form +" select[name='FirstManUserList\[\]'] option").remove();
    $.each(_current.FirstManUserList, function(name, value) {
		if(_current.FirstManSelectType == '<?=INDIVIDUAL?>') {
	        $("#form_"+ form +" select[name='FirstManUserList\[\]']").append('<option value="'+ value.No +'">'+ value.FirstName +" "+ value.MiddleName +" "+ value.LastName +'</option>');
		} else {
	        $("#form_"+ form +" select[name='FirstManUserList\[\]']").append('<option value="'+ value.No +'">'+ value.Name +'</option>');
		}
    });

    $("#form_"+ form +" select[name='ManagerList\[\]'] option").remove();
    $.each(_current.ManagerList, function(name, value) {
		if(_current.ManagerInSelectType == '<?=INDIVIDUAL?>') {
	        $("#form_"+ form +" select[name='ManagerList\[\]']").append('<option value="'+ value.No +'">'+ value.FirstName +" "+ value.MiddleName +" "+ value.LastName +'</option>');
		} else {
	        $("#form_"+ form +" select[name='ManagerList\[\]']").append('<option value="'+ value.No +'">'+ value.Name +'</option>');
		}
    });

    /*$("#form_"+ form +" select[name='TwoMan1UserList\[\]'] option").remove();
    $.each(_current.TwoMan1UserList, function(name, value) 
    {
		if(_current.TwoMan1SelectType == '<?=INDIVIDUAL?>') {
	        $("#form_"+ form +" select[name='TwoMan1UserList\[\]']").append('<option value="'+ value.No +'">'+ value.FirstName +" "+ value.MiddleName +" "+ value.LastName +'</option>');
		} else {
	        $("#form_"+ form +" select[name='TwoMan1UserList\[\]']").append('<option value="'+ value.No +'">'+ value.Name +'</option>');
		}
    });
    
    $("#form_"+ form +" select[name='TwoMan2UserList\[\]'] option").remove();
    $.each(_current.TwoMan2UserList, function(name, value) 
    {
		if(_current.TwoMan2SelectType == '<?=INDIVIDUAL?>') {
	        $("#form_"+ form +" select[name='TwoMan2UserList\[\]']").append('<option value="'+ value.No +'">'+ value.FirstName +" "+ value.MiddleName +" "+ value.LastName +'</option>');
		} else {
	        $("#form_"+ form +" select[name='TwoMan2UserList\[\]']").append('<option value="'+ value.No +'">'+ value.Name +'</option>');
		}
    });*/
}

function set_reader_function()
{
	if( $("#select[name='ReaderFunction']").val() == '1' )
	{
		$("#slave_reader_view").show();
    	$("#roomanti_tr_view").show();
	}
	else
	{
		$("#slave_reader_view").hide();
		$("#roomanti_tr_view").hide();
	}
}

function enable_s_reader(form)
{
    if( $("#form_"+ form +" select[name='ReaderFunction']").val() == '1' )
    {
    	$("#slave_reader").show();
    	$("#roomanti_tr").show();
        //$("#form_"+ form +" select[name='SlaveReader']").attr('disabled', false);
        $("#form_"+ form +" input[name='RoomAnti']").attr('disabled', false);
        $("#form_"+ form +" input[name='RoomAntiTime']").attr('disabled', false);
    }
    else
    {
    	$("#slave_reader").hide();
    	$("#roomanti_tr").hide();
        //$("#form_"+ form +" select[name='SlaveReader']").attr('disabled', true);
        $("#form_"+ form +" input[name='RoomAnti']").attr('disabled', true);
        $("#form_"+ form +" input[name='RoomAntiTime']").attr('disabled', false);
    }
}

function change_mreader_type(form)
{
	if( $("#form_"+ form +" select[name='MasterReaderType']").val() == '1' )
    {
    	$("#form_"+ form +" select[name='MasterCardFormat']").hide();
    	$("#form_"+ form +" p.master_cardformat_message").hide();
    }
    else
    {
    	$("#form_"+ form +" select[name='MasterCardFormat']").show();
    	$("#form_"+ form +" p.master_cardformat_message").show();
    }
}

function change_sreader_type(form)
{
	if( $("#form_"+ form +" select[name='SlaveReaderType']").val() == '1' )
    {
    	$("#form_"+ form +" select[name='SlaveCardFormat']").hide();
    	$("#form_"+ form +" p.slave_cardformat_message").hide();
    }
    else
    {
        $("#form_"+ form +" select[name='SlaveCardFormat']").show();
    	$("#form_"+ form +" p.slave_cardformat_message").show();
    }
}

function enable_T_anti(form)
{
    var flag = ! $("#form_"+ form +" input[name='TimeAnti']").attr("checked");
    $("#form_"+ form +" select[name='TimeAntiType']").attr('disabled', flag);
    $("#form_"+ form +" input[name='TimeAntiTime']").attr('disabled', flag);
    
    if( (flag == false) && $("#form_"+ form +" input[name='RoomAnti']").attr("checked") ) 
    {
        $("#form_"+ form +" input[name='RoomAnti']").attr("checked", false)
        $("#form_"+ form +" input[name='RoomAntiTime']").attr('disabled', true);
    }
}

function enable_R_anti(form)
{
    var flag = ! $("#form_"+ form +" input[name='RoomAnti']").attr("checked");
    $("#form_"+ form +" select[name='RoomAntiType']").attr('disabled', flag);
    $("#form_"+ form +" input[name='RoomAntiTime']").attr('disabled', flag);
    
    if( (flag == false) && $("#form_"+ form +" input[name='TimeAnti']").attr("checked") ) 
    {
        $("#form_"+ form +" input[name='TimeAnti']").attr("checked", false)
        $("#form_"+ form +" input[name='TimeAntiTime']").attr('disabled', true);
    }
}

function enable_Z_anti(form)
{
    var flag = ! $("#form_"+ form +" input[name='ZoneAnti']").attr("checked");
    $("#form_"+ form +" select[name='ZoneAntiType']").attr('disabled', flag);
    $("#form_"+ form +" select[name='ZoneAntiReader']").attr('disabled', flag);
    $("#form_"+ form +" input[name='ZoneAntiTime']").attr('disabled', flag);
}

function enable_A_anti(form)
{
    /*$("#form_"+ form +" select[name='user_list\[\]'] option").remove();t_element
    $.each(_current.user_list, function(name, value) {
        $("#form_"+ form +" select[name='user_list\[\]']").append('<option value="'+ value.No +'">'+ value.F_name +" "+ value.M_name +" "+ value.L_name +'</option>');
    });*/

    var flag = ! $("#form_"+ form +" input[name='Absence']").attr("checked");
    $("#form_"+ form +" select[name='AbsenceType']").attr('disabled', flag);
}

function enable_T_man_En(form)
{
	/*
    var flag = ! $("#form_"+ form +" input[name='TwoManRule']").attr("checked");
    $("#form_"+ form +" input[name='TwoManTime']").attr('disabled', flag);
    var prefix = "t_";
    $("#form_"+ form +" input[name='"+ prefix +"search_user']").attr('disabled', flag);
    $("#form_"+ form +" button[name='btn_"+ prefix +"search_user']").attr('disabled', flag);
    $("#form_"+ form +" select[name='"+ prefix +"find_user\[\]']").attr('disabled', flag);
    $("#form_"+ form +" button[name='"+ prefix +"btn_push']").attr('disabled', flag);
    $("#form_"+ form +" button[name='"+ prefix +"btn_pop']").attr('disabled', flag);
    $("#form_"+ form +" select[name='TwoMan1UserList\[\]']").attr('disabled', flag);
    $("#form_"+ form +" select[name='TwoMan1SelectType']").attr('disabled', flag);

    var prefix = "t2_";
    $("#form_"+ form +" input[name='"+ prefix +"search_user']").attr('disabled', flag);
    $("#form_"+ form +" button[name='btn_"+ prefix +"search_user']").attr('disabled', flag);
    $("#form_"+ form +" select[name='"+ prefix +"find_user\[\]']").attr('disabled', flag);
    $("#form_"+ form +" button[name='"+ prefix +"btn_push']").attr('disabled', flag);
    $("#form_"+ form +" button[name='"+ prefix +"btn_pop']").attr('disabled', flag);
    $("#form_"+ form +" select[name='TwoMan2UserList\[\]']").attr('disabled', flag);
    $("#form_"+ form +" select[name='TwoMan2SelectType']").attr('disabled', flag);*/
}

function enable_P_man_En(form)
{
	var flag = ! $("#form_"+ form +" input[name='PostalLockEnable']").attr("checked");
	$("#form_"+ form +" select[name='PostalLockSchedule']").attr('disabled', flag);
}

function enable_F_man_En(form)
{
    var flag = ! $("#form_"+ form +" input[name='FirstManRule']").attr("checked");
    //$("#form_"+ form +" input[name='FirstManEveryDay']").attr('disabled', flag);
    //$("#form_"+ form +" input[name='FirstManDate']").attr('disabled', flag);
    //$("#form_"+ form +" select[name='FirstManStartHour']").attr('disabled', flag);
    //$("#form_"+ form +" select[name='FirstManStartMin']").attr('disabled', flag);
    //$("#form_"+ form +" select[name='FirstManEndHour']").attr('disabled', flag);
    //$("#form_"+ form +" select[name='FirstManEndMin']").attr('disabled', flag);
    $("#form_"+ form +" select[name='FirstManScheduleNo']").attr('disabled', flag);

    var prefix = "f_";
    $("#form_"+ form +" input[name='"+ prefix +"search_user']").attr('disabled', flag);
    $("#form_"+ form +" button[name='btn_"+ prefix +"search_user']").attr('disabled', flag);
    $("#form_"+ form +" select[name='"+ prefix +"find_user\[\]']").attr('disabled', flag);
    $("#form_"+ form +" button[name='"+ prefix +"btn_push']").attr('disabled', flag);
    $("#form_"+ form +" button[name='"+ prefix +"btn_pop']").attr('disabled', flag);
    $("#form_"+ form +" select[name='FirstManUserList\[\]']").attr('disabled', flag);
    $("#form_"+ form +" select[name='FirstManSelectType']").attr('disabled', flag);
    $("#form_"+ form +" select[name='FirstManScheduleNo1']").attr('disabled', flag);
    $("#form_"+ form +" select[name='FirstManScheduleNo2']").attr('disabled', flag);
    $("#form_"+ form +" select[name='FirstManScheduleNo3']").attr('disabled', flag);

    //enable_F_man_Every_Day(form);
}

function enable_ManagerIn(form)
{
    var flag = ! $("#form_"+ form +" input[name='ManagerInRule']").attr("checked");
    //$("#form_"+ form +" select[name='ManagerInBeginHour']").attr('disabled', flag);
    //$("#form_"+ form +" select[name='ManagerInBeginMin']").attr('disabled', flag);
    //$("#form_"+ form +" select[name='ManagerInEndHour']").attr('disabled', flag);
    //$("#form_"+ form +" select[name='ManagerInEndMin']").attr('disabled', flag);
	$("#form_"+ form +" select[name='ManagerInScheduleNo']").attr('disabled', flag);

    $("#form_"+ form +" input[name='m_search_user']").attr('disabled', flag);
    $("#form_"+ form +" button[name='btn_m_search_user']").attr('disabled', flag);
    $("#form_"+ form +" select[name='m_find_user\[\]']").attr('disabled', flag);
    $("#form_"+ form +" button[name='m_btn_push']").attr('disabled', flag);
    $("#form_"+ form +" button[name='m_btn_pop']").attr('disabled', flag);
    $("#form_"+ form +" select[name='ManagerList\[\]']").attr('disabled', flag);
    $("#form_"+ form +" select[name='ManagerInSelectType']").attr('disabled', flag);
    $("#form_"+ form +" select[name='ManagerInScheduleNo1']").attr('disabled', flag);
    $("#form_"+ form +" select[name='ManagerInScheduleNo2']").attr('disabled', flag);
    $("#form_"+ form +" select[name='ManagerInScheduleNo3']").attr('disabled', flag);
}

function disable_all_rule(form)
{
    $("#form_"+ form +" input[name='TimeAnti']").attr("checked", false);        enable_T_anti(form);
    $("#form_"+ form +" input[name='RoomAnti']").attr("checked", false);        enable_R_anti(form);
    //$("#form_"+ form +" input[name='TwoManRule']").attr("checked", false);      enable_T_man_En(form);
    $("#form_"+ form +" input[name='FirstManRule']").attr("checked", false);    enable_F_man_En(form);
}

function enable_F_man_Every_Day(form)
{
    var flag = ! $("#form_"+ form +" input[name='FirstManRule']").attr("checked");
    if( !flag ) flag = $("#form_"+ form +" input[name='FirstManEveryDay']").attr("checked");
    $("#form_"+ form +" input[name='FirstManDate']").attr('disabled', flag);
}

function submit_search_user(form, prefix, offset)
{
	var selecttype = "<?=INDIVIDUAL?>";
	switch(prefix) {
		case 'f_':
			selecttype	= $("#form_"+ form +" select[name='FirstManSelectType']").val();
			break;
		case 'm_':
			selecttype	= $("#form_"+ form +" select[name='ManagerInSelectType']").val();
			break;
		case 't_':
			//selecttype	= $("#form_"+ form +" select[name='TwoMan1SelectType']").val();
			break;
		case 't2_':
			//selecttype	= $("#form_"+ form +" select[name='TwoMan2SelectType']").val();
			break;
	}

    if(selecttype == "<?=GROUP?>")
    {
		$.getJSON(
			"/?c=groupuser&m=find&f=Name&w="+ $("#form_"+ form +" input[name='"+ prefix +"search_user']").val(),
			function(data) {
				if( check_error(data) ) {
					var element = $("#form_"+ form +" select[name='"+ prefix +"find_user\[\]']");
					element.html("");
					for(var i=0; i<data.list.length; i++)
					{
						element.append('<option value="'+ data.list[i].No +'">'+ data.list[i].Name +'</option>');
					}
				}
			}
		);
	} else {
		$.getJSON(
			"/?c=user&m=find&f=FirstName&w="+ $("#form_"+ form +" input[name='"+ prefix +"search_user']").val() +'&offset='+ offset,
			function(data) {
				if( check_error(data) ) {
					var element = $("#form_"+ form +" select[name='"+ prefix +"find_user\[\]']");
					element.html("");
					for(var i=0; i<data.list.length; i++)
					{
						element.append('<option value="'+ data.list[i].No +'">'+ data.list[i].FirstName +" "+ data.list[i].MiddleName +" "+ data.list[i].LastName +'</option>');
					}

					var count = parseInt(data.count, 10);
					var $offset = $("#form_"+ form +" select[name='"+ prefix +"search_user_offset']");
					$offset.empty();
					if( count > 1000 ) {
						for(i=0; i<count; i=i+1000) {
							$('<option></option>').val(i).text((i+1) +' ~ '+ (i+1000)).appendTo($offset);
						}
						$offset.val(data.offset);
						$offset.show();
					} else {
						$offset.hide();
					}
				}
			}
		);
	}
}

function push_user(form, finduser, control)
{
    $("#form_"+ form +" select[name='"+ finduser +"\[\]'] option:selected").each(function() {
        $("#form_"+ form +" select[name='"+ control +"\[\]'] option").filter("option[value='"+ $(this).val() +"']").remove();
        $("#form_"+ form +" select[name='"+ control +"\[\]']").append($(this).clone());
    });
}

function pop_user(form, control)
{
    $("#form_"+ form +" select[name='"+ control +"\[\]'] option:selected").remove();
}

function before_submit(form)
{
    var flag;
    //flag = $("#form_"+ form +" input[name='TwoManRule']").attr("checked");
    //$("#form_"+ form +" select[name='TwoMan1UserList\[\]'] option").attr('selected', flag);
    //$("#form_"+ form +" select[name='TwoMan2UserList\[\]'] option").attr('selected', flag);    
    flag = $("#form_"+ form +" input[name='FirstManRule']").attr("checked");
    $("#form_"+ form +" select[name='FirstManUserList\[\]'] option").attr('selected', flag);
    flag = $("#form_"+ form +" input[name='ManagerInRule']").attr("checked");
    $("#form_"+ form +" select[name='ManagerList\[\]'] option").attr('selected', flag);
	flag = $("#form_"+ form +" input[name='PostalLockEnable']").attr("checked");
	//$("#form_"+ form +" select[name='PostalLockSchedule'] option").attr('selected', flag);
}

function change_selecttype_firstman(form)
{
    var selecttype = $("#form_"+ form +" select[name='FirstManSelectType']").val();
    
    $("#form_"+ form +" select[name='f_find_user\[\]'] option").remove();
    $("#form_"+ form +" select[name='FirstManUserList\[\]'] option").remove();
	$("#form_"+ form +" select[name='f_search_user_offset']").empty().hide();

    submit_search_user('edit', 'f_', 0);
}

function change_selecttype_manager(form)
{
	var selecttype = $("#form_"+ form +" select[name='ManagerInSelectType']").val();
    
    $("#form_"+ form +" select[name='m_find_user\[\]'] option").remove();
    $("#form_"+ form +" select[name='ManagerList\[\]'] option").remove();
	$("#form_"+ form +" select[name='m_search_user_offset']").empty().hide();

    submit_search_user('edit', 'm_', 0);
}

/*
function change_selecttype_twoman1(form)
{
    var selecttype = $("#form_"+ form +" select[name='TwoMan1SelectType']").val();
    
    $("#form_"+ form +" select[name='t_find_user\[\]'] option").remove();
    $("#form_"+ form +" select[name='TwoMan1UserList\[\]'] option").remove();
	$("#form_"+ form +" select[name='t_search_user_offset']").empty().hide();
}

function change_selecttype_twoman2(form)
{
    var selecttype = $("#form_"+ form +" select[name='TwoMan2SelectType']").val();
    
    $("#form_"+ form +" select[name='t2_find_user\[\]'] option").remove();
    $("#form_"+ form +" select[name='TwoMan2UserList\[\]'] option").remove();
	$("#form_"+ form +" select[name='t2_search_user_offset']").empty().hide();
}*/

function enable_doorcontact(form)
{
    var flag = $("#form_"+ form +" input[name='DoorContactEnable']").attr("checked");
    if( flag == true )
    {
        $("select[name='CircuitType'] option[value='4']").attr('selected', true);
    }
    else
    {
        $("select[name='CircuitType'] option[value='7']").attr('selected', true);
    }
    
    
    
}

// ???? u???? ??? ?????? ????? ?????? ??.
<?PHP if( $baseController->is_auth(41, 1) != TRUE ) { ?>
function open_new()
{
	alert("<?=$lang->user->error_not_permission?>");
}
<?PHP } ?>

<?PHP if( $baseController->is_auth(41, 2) != TRUE && $baseController->is_auth(41, 3) != TRUE) { ?>
function open_edit()
{
	alert("<?=$lang->user->error_not_permission?>");
}
<?PHP } ?>

<?PHP if( $baseController->is_auth(41, 2) != TRUE) { ?>
function del_alevel()
{
	alert("<?=$lang->user->error_not_permission?>");
}
<?PHP } ?>


function change_lockmode(el)
{
    var $el = $(el);
    var $form = $(el.form);

    if( $el.val() == '4' ) {
        $form.find(".man-trap-option").show();
    } else {
        $form.find(".man-trap-option").hide();
    }
}
</script>
