<style>

.tooltip {
  position: absolute;
  z-index: 1070;
  display: block;
  visibility: visible;
  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-size: 12px;
  font-weight: normal;
  line-height: 1.4;
  opacity: 0;
  filter: alpha(opacity=0);
}
.tooltip.in {
  opacity: 0.9;
  filter: alpha(opacity=90);
}
.tooltip.top {
  margin-top: -3px;
  padding: 5px 0;
}
.tooltip.right {
  margin-left: 3px;
  padding: 0 5px;
}
.tooltip.bottom {
  margin-top: 3px;
  padding: 5px 0;
}
.tooltip.left {
  margin-left: -3px;
  padding: 0 5px;
}
.tooltip-inner {
  max-width: 200px;
  padding: 3px 8px;
  color: #ffffff;
  text-align: center;
  text-decoration: none;
  background-color: #000000;
  border-radius: 4px;
}
.tooltip-arrow {
  position: absolute;
  width: 0;
  height: 0;
  border-color: transparent;
  border-style: solid;
}
.tooltip.top .tooltip-arrow {
  bottom: 0;
  left: 50%;
  margin-left: -5px;
  border-width: 5px 5px 0;
  border-top-color: #000000;
}
.tooltip.top-left .tooltip-arrow {
  bottom: 0;
  right: 5px;
  margin-bottom: -5px;
  border-width: 5px 5px 0;
  border-top-color: #000000;
}
.tooltip.top-right .tooltip-arrow {
  bottom: 0;
  left: 5px;
  margin-bottom: -5px;
  border-width: 5px 5px 0;
  border-top-color: #000000;
}
.tooltip.right .tooltip-arrow {
  top: 50%;
  left: 0;
  margin-top: -5px;
  border-width: 5px 5px 5px 0;
  border-right-color: #000000;
}
.tooltip.left .tooltip-arrow {
  top: 50%;
  right: 0;
  margin-top: -5px;
  border-width: 5px 0 5px 5px;
  border-left-color: #000000;
}
.tooltip.bottom .tooltip-arrow {
  top: 0;
  left: 50%;
  margin-left: -5px;
  border-width: 0 5px 5px;
  border-bottom-color: #000000;
}
.tooltip.bottom-left .tooltip-arrow {
  top: 0;
  right: 5px;
  margin-top: -5px;
  border-width: 0 5px 5px;
  border-bottom-color: #000000;
}
.tooltip.bottom-right .tooltip-arrow {
  top: 0;
  left: 5px;
  margin-top: -5px;
  border-width: 0 5px 5px;
  border-bottom-color: #000000;
}
/* ========================================================================
 * sliderbar
 * ======================================================================== */

.fc-sliderbar {
}

.fc-sliderbar input {
	display: none;
}

.fc-sliderbar .control {
	display: block;
	position: relative;
	height: 10px;
	margin: 5px 0;
	border-radius: 5px;
	background-color: #e4e6eb;
}

.fc-sliderbar .control .range {
	display: block;
	position: absolute;
	height: 10px;
	border-radius: 5px;
	background-color: #36a9e1;
	width: 100%;
}

.fc-sliderbar.fc-sliderbar-inverse .control .range {
	background-color: #e4e6eb;
}

.fc-sliderbar .control a.handle {
	display: block;
	position: absolute;
	width: 14px;
	height: 14px;
	border: 1px solid #36a9e1;
	border-radius: 7px;
	background-color: #fff;
	margin: -2px -7px;
}

.fc-sliderbar .control.disabled .range {
	background-color: #bdbdbd;
}

.fc-sliderbar .control.disabled .handle {
	border: 1px solid #bdbdbd;
	cursor: not-allowed;
}

.fc-sliderbar.fc-sliderbar-inverse .control {
	background-color: #36a9e1;
}

.fc-sliderbar.fc-sliderbar-inverse .control.disabled {
	background-color: #bdbdbd;
}

.fc-sliderbar.fc-sliderbar-inverse .control.disabled .range {
	background-color: #e4e6eb;
}

.form-horizontal .fc-sliderbar {
	padding-top: 7px;
	padding-bottom: 7px;
}

.fc-sliderbar.fc-sliderbar-vertical {
	display: inline-block;
}

.fc-sliderbar.fc-sliderbar-vertical .control {
	display: inline-block;
	width: 10px;
	height: 150px;
	margin: 0 15px;
}

.fc-sliderbar.fc-sliderbar-vertical .control .range {
	width: 10px;
}

.fc-sliderbar.fc-sliderbar-vertical .control a.handle {
	margin: -7px -2px;
}

</style>

<div id="wrap">

<div id="location">
<?PHP
echo $lang->menu->schedule.'&nbsp;&gt;&nbsp;'.$lang->menu->schedule_sub;
?>
	<button class="btn_help" onclick="openHelp('help', 'schedule')">Help</button>
</div>

<div id="new_section" class="hide">
<form id="form_new" method="post" action="<?=base_url()?>schedule-add">
    <h2>:: <?=$lang->menu->schedule_sub?></h2>
    <div class="box01">

        <h3><?=$lang->menu->basic?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->schedule->Name?> *</th>
            <td width="1">:</td>
            <td><?=Form::input('Name', "", array("MAXLENGTH"=>max_name_char, "SIZE"=>"30"))?></td>
        </tr>
        <tr>
            <th><?=$lang->schedule->Mean?></th>
            <td width="1">:</td>
            <td><?=Form::input('Mean', "", array("MAXLENGTH"=>max_description_char, "SIZE"=>"30"))?></td>
        </tr>
        </table>
        
        <div class="space" style="height:10px"></div>

        <h3><?=$lang->menu->Schedule?></h3>
        <table class="tbl_view">
        <tr class="header">
            <th class="header" width="120" style="text-align:center"><?=$lang->schedule->Day?></th>
            <th class="header" width="50" style="text-align:center"><?=$lang->schedule->Reverse?></th>
            <th class="header" width="70" style="text-align:center"><?=$lang->schedule->Start?></th>
            <th class="header" style="text-align:center"><?=$lang->schedule->Time?></th>
            <th class="header" width="70" style="text-align:center"><?=$lang->schedule->End?></th>
        </tr>
        <tr style="text-align:center">
            <th style="text-align:center"><?=$lang->schedule->sun_long?></th>
            <td><input type="checkbox" name="sun_reverse" value="1" class="reverse" onclick="reverseSlider(this)" data-slider="sun_time" /></td>
            <td>
                <?=Form::inputnum('sun_st_h', '', array('class'=>'time_h', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>:
                <?=Form::inputnum('sun_st_m', '', array('class'=>'time_m', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>
			</td>
			<td>
                <div class="fc-sliderbar">
                    <input type="text" name="sun_time" class="slider_input"  class="slider_input" data-min="0" data-max="1439" data-step="1">
                    <i class="control">
                        <span class="range"></span>
                        <a href="#" class="handle"></a>
                        <a href="#" class="handle"></a>
                    </i>
                </div>
            </td>
            <td>
                <?=Form::inputnum('sun_ed_h', '', array('class'=>'time_h', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>:
                <?=Form::inputnum('sun_ed_m', '', array('class'=>'time_m', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>
            </td>
        </tr>

        <tr style="text-align:center">
            <th style="text-align:center"><?=$lang->schedule->mon_long?></th>
			<td><input type="checkbox" name="mon_reverse" value="1" class="reverse" onclick="reverseSlider(this)" data-slider="mon_time" /></td>
            <td>
				<?=Form::inputnum('mon_st_h', '', array('class'=>'time_h', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>:
                <?=Form::inputnum('mon_st_m', '', array('class'=>'time_m', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>
			</td>
			<td>
                <div class="fc-sliderbar">
                    <input type="text" name="mon_time" class="slider_input"   class="slider_input" data-min="0" data-max="1439" data-step="1">
                    <i class="control">
                        <span class="range"></span>
                        <a href="#" class="handle"></a>
                        <a href="#" class="handle"></a>
                    </i>
                </div>
            </td>
            <td>
                <?=Form::inputnum('mon_ed_h', '', array('class'=>'time_h', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>:
                <?=Form::inputnum('mon_ed_m', '', array('class'=>'time_m', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>
            </td>
        </tr>
        <tr style="text-align:center">
            <th style="text-align:center"><?=$lang->schedule->tue_long?></th>
			<td><input type="checkbox" name="tue_reverse" value="1" class="reverse" onclick="reverseSlider(this)" data-slider="tue_time" /></td>
            <td>
				<?=Form::inputnum('tue_st_h', '', array('class'=>'time_h', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>:
                <?=Form::inputnum('tue_st_m', '', array('class'=>'time_m', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>
			</td>
			<td>
                <div class="fc-sliderbar">
                    <input type="text" name="tue_time" class="slider_input" data-min="0" data-max="1439" data-step="1">
                    <i class="control">
                        <span class="range"></span>
                        <a href="#" class="handle"></a>
                        <a href="#" class="handle"></a>
                    </i>
                </div>
            </td>
            <td>
                <?=Form::inputnum('tue_ed_h', '', array('class'=>'time_h', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>:
                <?=Form::inputnum('tue_ed_m', '', array('class'=>'time_m', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>
            </td>
        </tr>
        <tr style="text-align:center">
            <th style="text-align:center"><?=$lang->schedule->wed_long?></th>
			<td><input type="checkbox" name="wed_reverse" value="1" class="reverse" onclick="reverseSlider(this)" data-slider="wed_time" /></td>
            <td>
				<?=Form::inputnum('wed_st_h', '', array('class'=>'time_h', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>:
                <?=Form::inputnum('wed_st_m', '', array('class'=>'time_m', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>
			</td>
			<td>
                <div class="fc-sliderbar">
                    <input type="text" name="wed_time"  class="slider_input" data-min="0" data-max="1439" data-step="1">
                    <i class="control">
                        <span class="range"></span>
                        <a href="#" class="handle"></a>
                        <a href="#" class="handle"></a>
                    </i>
                </div>
            </td>
            <td>
                <?=Form::inputnum('wed_ed_h', '', array('class'=>'time_h', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>:
                <?=Form::inputnum('wed_ed_m', '', array('class'=>'time_m', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>
            </td>
        </tr>
        <tr style="text-align:center">
            <th style="text-align:center"><?=$lang->schedule->thu_long?></th>
			<td><input type="checkbox" name="thu_reverse" value="1" class="reverse" onclick="reverseSlider(this)" data-slider="thu_time" /></td>
            <td>
				<?=Form::inputnum('thu_st_h', '', array('class'=>'time_h', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>:
                <?=Form::inputnum('thu_st_m', '', array('class'=>'time_m', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>
			</td>
			<td>
                <div class="fc-sliderbar">
                    <input type="text" name="thu_time"  class="slider_input" data-min="0" data-max="1439" data-step="1">
                    <i class="control">
                        <span class="range"></span>
                        <a href="#" class="handle"></a>
                        <a href="#" class="handle"></a>
                    </i>
                </div>
            </td>
            <td>
                <?=Form::inputnum('thu_ed_h', '', array('class'=>'time_h', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>:
                <?=Form::inputnum('thu_ed_m', '', array('class'=>'time_m', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>
            </td>
        </tr>
        <tr style="text-align:center">
            <th style="text-align:center"><?=$lang->schedule->fri_long?></th>
			<td><input type="checkbox" name="fri_reverse" value="1" class="reverse" onclick="reverseSlider(this)" data-slider="fri_time" /></td>
            <td>
				<?=Form::inputnum('fri_st_h', '', array('class'=>'time_h', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>:
                <?=Form::inputnum('fri_st_m', '', array('class'=>'time_m', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>
			</td>
			<td>
                <div class="fc-sliderbar">
                    <input type="text" name="fri_time"  class="slider_input" data-min="0" data-max="1439" data-step="1">
                    <i class="control">
                        <span class="range"></span>
                        <a href="#" class="handle"></a>
                        <a href="#" class="handle"></a>
                    </i>
                </div>
            </td>
            <td>
                <?=Form::inputnum('fri_ed_h', '', array('class'=>'time_h', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>:
                <?=Form::inputnum('fri_ed_m', '', array('class'=>'time_m', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>
            </td>
        </tr>
        <tr style="text-align:center">
            <th style="text-align:center"><?=$lang->schedule->sat_long?></th>
			<td><input type="checkbox" name="sat_reverse" value="1" class="reverse" onclick="reverseSlider(this)" data-slider="sat_time" /></td>
            <td>
				<?=Form::inputnum('sat_st_h', '', array('class'=>'time_h', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>:
                <?=Form::inputnum('sat_st_m', '', array('class'=>'time_m', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>
			</td>
			<td>
                <div class="fc-sliderbar">
                    <input type="text" name="sat_time"  class="slider_input" data-min="0" data-max="1439" data-step="1">
                    <i class="control">
                        <span class="range"></span>
                        <a href="#" class="handle"></a>
                        <a href="#" class="handle"></a>
                    </i>
                </div>
            </td>
            <td>
                <?=Form::inputnum('sat_ed_h', '', array('class'=>'time_h', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>:
                <?=Form::inputnum('sat_ed_m', '', array('class'=>'time_m', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>
            </td>
        </tr>
        <tr style="text-align:center">
            <th style="text-align:center; color: red;"><?=$lang->schedule->hol_long?></th>
			<td><input type="checkbox" name="hol_reverse" value="1" class="reverse" onclick="reverseSlider(this)" data-slider="hol_time" /></td>
            <td>
				<?=Form::inputnum('hol_st_h', '', array('class'=>'time_h', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>:
                <?=Form::inputnum('hol_st_m', '', array('class'=>'time_m', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>
			</td>
			<td>
                <div class="fc-sliderbar">
                    <input type="text" name="hol_time"  class="slider_input" data-min="0" data-max="1439" data-step="1">
                    <i class="control">
                        <span class="range"></span>
                        <a href="#" class="handle"></a>
                        <a href="#" class="handle"></a>
                    </i>
                </div>
            </td>
            <td>
                <?=Form::inputnum('hol_ed_h', '', array('class'=>'time_h', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>:
                <?=Form::inputnum('hol_ed_m', '', array('class'=>'time_m', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>
            </td>
        </tr>
        <tr style="text-align:center">
            <th style="text-align:center"><?=$lang->schedule->HolidayNo?></th>
            <td colspan="5">
                <div style="width:120px; float:left;"><?=Form::checkbox('Holiday1', $lang->schedule->Holiday1, FALSE, '1')?></div>
                <div style="width:120px; float:left;"><?=Form::checkbox('Holiday2', $lang->schedule->Holiday2, FALSE, '1')?></div>
                <div style="width:120px; float:left;"><?=Form::checkbox('Holiday3', $lang->schedule->Holiday3, FALSE, '1')?></div>
                <div style="width:120px; float:left;"><?=Form::checkbox('Holiday4', $lang->schedule->Holiday4, FALSE, '1')?></div>
            </td>
            
        </tr>
        </table>

        <div class="button_set">
            <button type="button" onclick="$('#form_new').submit()"><?=$lang->button->add?></button>&nbsp;&nbsp;
            <button type="button" onclick="open_new(); init_new();"><?=$lang->button->reset?></button>&nbsp;&nbsp;
            <button type="button" onclick="close_new()"><?=$lang->button->cancel?></button>
        </div>
    </div>
</form>
</div>

<div id="edit_section" class="hide">
<form id="form_edit" method="post" action="<?=base_url()?>schedule-update">
<?=Form::hidden("No")?>
    <h2>:: <?=$lang->menu->schedule_sub?></h2>
    <div class="box01">

        <h3><?=$lang->menu->basic?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->schedule->Name?> *</th>
            <td width="1">:</td>
            <td><?=Form::input('Name', "", array("MAXLENGTH"=>max_name_char, "SIZE"=>"30"))?></td>
        </tr>
        <tr>
            <th><?=$lang->schedule->Mean?></th>
            <td width="1">:</td>
            <td><?=Form::input('Mean', "", array("MAXLENGTH"=>max_description_char, "SIZE"=>"30"))?></td>
        </tr>
        </table>

        <div class="space" style="height:10px"></div>
        
        <h3><?=$lang->menu->Schedule?></h3>
        <table class="tbl_view">
        <tr class="header">
            <th class="header" width="120" style="text-align:center"><?=$lang->schedule->Day?></th>
            <th class="header" width="50" style="text-align:center"><?=$lang->schedule->Reverse?></th>
            <th class="header" width="70" style="text-align:center"><?=$lang->schedule->Start?></th>
            <th class="header" style="text-align:center"><?=$lang->schedule->Time?></th>
            <th class="header" width="70" style="text-align:center"><?=$lang->schedule->End?></th>
        </tr>
        <tr style="text-align:center">
            <th style="text-align:center"><?=$lang->schedule->sun_long?></th>
			<td><input type="checkbox" name="sun_reverse" value="1" class="reverse" onclick="reverseSlider(this)" data-slider="sun_time" /></td>
            <td>
				<?=Form::inputnum('sun_st_h', '', array('class'=>'time_h', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>:
                <?=Form::inputnum('sun_st_m', '', array('class'=>'time_m', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>
			</td>
			<td>
                <div class="fc-sliderbar">
                    <input type="text" name="sun_time" class="slider_input"  data-min="0" data-max="1439" data-step="1">
                    
                    <i class="control">
                        <span class="range"></span>
                        <a href="#" class="handle"></a>
                        <a href="#" class="handle"></a>
                    </i>
                </div>
            </td>
            <td>
                <?=Form::inputnum('sun_ed_h', '', array('class'=>'time_h', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>:
                <?=Form::inputnum('sun_ed_m', '', array('class'=>'time_m', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>
            </td>
        </tr>
        <tr style="text-align:center">
            <th style="text-align:center"><?=$lang->schedule->mon_long?></th>
			<td><input type="checkbox" name="mon_reverse" value="1" class="reverse" onclick="reverseSlider(this)" data-slider="mon_time" /></td>
            <td>
				<?=Form::inputnum('mon_st_h', '', array('class'=>'time_h', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>:
                <?=Form::inputnum('mon_st_m', '', array('class'=>'time_m', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>
			</td>
			<td>
                <div class="fc-sliderbar">
                    <input type="text" name="mon_time" class="slider_input"  data-min="0" data-max="1439" data-step="1">
                    <i class="control">
                        <span class="range"></span>
                        <a href="#" class="handle"></a>
                        <a href="#" class="handle"></a>
                    </i>
                </div>
            </td>
            <td>
                <?=Form::inputnum('mon_ed_h', '', array('class'=>'time_h', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>:
                <?=Form::inputnum('mon_ed_m', '', array('class'=>'time_m', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>
            </td>
        </tr>
        <tr style="text-align:center">
            <th style="text-align:center"><?=$lang->schedule->tue_long?></th>
			<td><input type="checkbox" name="tue_reverse" value="1" class="reverse" onclick="reverseSlider(this)" data-slider="tue_time" /></td>
            <td>
				<?=Form::inputnum('tue_st_h', '', array('class'=>'time_h', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>:
                <?=Form::inputnum('tue_st_m', '', array('class'=>'time_m', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>
			</td>
			<td>
                <div class="fc-sliderbar">
                    <input type="text" name="tue_time" class="slider_input"  data-min="0" data-max="1439" data-step="1">
                    <i class="control">
                        <span class="range"></span>
                        <a href="#" class="handle"></a>
                        <a href="#" class="handle"></a>
                    </i>
                </div>
            </td>
            <td>
                <?=Form::inputnum('tue_ed_h', '', array('class'=>'time_h', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>:
                <?=Form::inputnum('tue_ed_m', '', array('class'=>'time_m', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>
            </td>
        </tr>
        <tr style="text-align:center">
            <th style="text-align:center"><?=$lang->schedule->wed_long?></th>
			<td><input type="checkbox" name="wed_reverse" value="1" class="reverse" onclick="reverseSlider(this)" data-slider="wed_time" /></td>
            <td>
				<?=Form::inputnum('wed_st_h', '', array('class'=>'time_h', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>:
                <?=Form::inputnum('wed_st_m', '', array('class'=>'time_m', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>
			</td>
			<td>
                <div class="fc-sliderbar">
                    <input type="text" name="wed_time" class="slider_input"  data-min="0" data-max="1439" data-step="1">
                    <i class="control">
                        <span class="range"></span>
                        <a href="#" class="handle"></a>
                        <a href="#" class="handle"></a>
                    </i>
                </div>
            </td>
            <td>
                <?=Form::inputnum('wed_ed_h', '', array('class'=>'time_h', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>:
                <?=Form::inputnum('wed_ed_m', '', array('class'=>'time_m', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>
            </td>
        </tr>
        <tr style="text-align:center">
            <th style="text-align:center"><?=$lang->schedule->thu_long?></th>
			<td><input type="checkbox" name="thu_reverse" value="1" class="reverse" onclick="reverseSlider(this)" data-slider="thu_time" /></td>
            <td>
				<?=Form::inputnum('thu_st_h', '', array('class'=>'time_h', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>:
                <?=Form::inputnum('thu_st_m', '', array('class'=>'time_m', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>
			</td>
			<td>
                <div class="fc-sliderbar">
                    <input type="text" name="thu_time" class="slider_input"  data-min="0" data-max="1439" data-step="1">
                    <i class="control">
                        <span class="range"></span>
                        <a href="#" class="handle"></a>
                        <a href="#" class="handle"></a>
                    </i>
                </div>
            </td>
            <td>
                <?=Form::inputnum('thu_ed_h', '', array('class'=>'time_h', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>:
                <?=Form::inputnum('thu_ed_m', '', array('class'=>'time_m', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>
            </td>
        </tr>
        <tr style="text-align:center">
            <th style="text-align:center"><?=$lang->schedule->fri_long?></th>
			<td><input type="checkbox" name="fri_reverse" value="1" class="reverse" onclick="reverseSlider(this)" data-slider="fri_time" /></td>
            <td>
				<?=Form::inputnum('fri_st_h', '', array('class'=>'time_h', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>:
                <?=Form::inputnum('fri_st_m', '', array('class'=>'time_m', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>
			</td>
			<td>
                <div class="fc-sliderbar">
                    <input type="text" name="fri_time" class="slider_input"  data-min="0" data-max="1439" data-step="1">
                    <i class="control">
                        <span class="range"></span>
                        <a href="#" class="handle"></a>
                        <a href="#" class="handle"></a>
                    </i>
                </div>
            </td>
            <td>
                <?=Form::inputnum('fri_ed_h', '', array('class'=>'time_h', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>:
                <?=Form::inputnum('fri_ed_m', '', array('class'=>'time_m', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>
            </td>
        </tr>
        <tr style="text-align:center">
            <th style="text-align:center"><?=$lang->schedule->sat_long?></th>
			<td><input type="checkbox" name="sat_reverse" value="1" class="reverse" onclick="reverseSlider(this)" data-slider="sat_time" /></td>
            <td>
				<?=Form::inputnum('sat_st_h', '', array('class'=>'time_h', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>:
                <?=Form::inputnum('sat_st_m', '', array('class'=>'time_m', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>
			</td>
			<td>
                <div class="fc-sliderbar">
                    <input type="text" name="sat_time" class="slider_input"  data-min="0" data-max="1439" data-step="1">
                    <i class="control">
                        <span class="range"></span>
                        <a href="#" class="handle"></a>
                        <a href="#" class="handle"></a>
                    </i>
                </div>
            </td>
            <td>
                <?=Form::inputnum('sat_ed_h', '', array('class'=>'time_h', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>:
                <?=Form::inputnum('sat_ed_m', '', array('class'=>'time_m', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>
            </td>
        </tr>
        <tr style="text-align:center">
            <th style="text-align:center; color: red;"><?=$lang->schedule->hol_long?></th>
			<td><input type="checkbox" name="hol_reverse" value="1" class="reverse" onclick="reverseSlider(this)" data-slider="hol_time" /></td>
            <td>
				<?=Form::inputnum('hol_st_h', '', array('class'=>'time_h', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>:
                <?=Form::inputnum('hol_st_m', '', array('class'=>'time_m', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>
			</td>
			<td>
                <div class="fc-sliderbar">
                    <input type="text" name="hol_time" class="slider_input"  data-min="0" data-max="1439" data-step="1">
                    <i class="control">
                        <span class="range"></span>
                        <a href="#" class="handle"></a>
                        <a href="#" class="handle"></a>
                    </i>
                </div>
            </td>
            <td>
                <?=Form::inputnum('hol_ed_h', '', array('class'=>'time_h', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>:
                <?=Form::inputnum('hol_ed_m', '', array('class'=>'time_m', 'MAXLENGTH'=>max_schedule_day_char, 'onblur'=>'changeTextTime(this)'))?>
            </td>
        </tr>
        <tr style="text-align:center">
            <th style="text-align:center"><?=$lang->schedule->HolidayNo?></th>
            <td colspan="5">
                <div style="width:120px; float:left;"><?=Form::checkbox('Holiday1', $lang->schedule->Holiday1, FALSE, '1')?></div>
                <div style="width:120px; float:left;"><?=Form::checkbox('Holiday2', $lang->schedule->Holiday2, FALSE, '1')?></div>
                <div style="width:120px; float:left;"><?=Form::checkbox('Holiday3', $lang->schedule->Holiday3, FALSE, '1')?></div>
                <div style="width:120px; float:left;"><?=Form::checkbox('Holiday4', $lang->schedule->Holiday4, FALSE, '1')?></div>
            </td>
          
        </tr>
        </table>

        <div class="button_set">
            <button type="button" onclick="$('#form_edit').submit();"><?=$lang->button->save?></button>&nbsp;&nbsp;
            <button type="button" onclick="open_edit(_seq); init_edit();"><?=$lang->button->reset?></button>&nbsp;&nbsp;
            <button type="button" onclick="close_edit()"><?=$lang->button->cancel?></button>
        </div>
    </div>
</form>
</div>


<div id="view_section" class="hide">
    <h2>:: <?=$lang->menu->schedule_sub?></h2>
    <div class="box01">

        <h3><?=$lang->menu->basic?></h3>
        <table class="tbl_view">
        <tr style="display:none">
            <td></td>
            <td id="view_No"></td>
        </tr>
        <tr>
            <th width="150"><?=$lang->schedule->Name?> *</th>
            <td width="1">:</td>
            <td id="view_Name"></td>
        </tr>
        <tr>
            <th><?=$lang->schedule->Mean?></th>
            <td width="1">:</td>
            <td id="view_Mean"></td>
        </tr>
        </table>
        
        <div class="space" style="height:10px"></div>
        
        <h3><?=$lang->menu->Schedule?></h3>
        <table class="tbl_view">
        <tr class="header">
            <th class="header" width="150" style="text-align:center"><?=$lang->schedule->Day?></th>
            <th class="header" width="180" style="text-align:center"><?=$lang->schedule->Reverse?></th>
            <th class="header" style="text-align:center"><?=$lang->schedule->Start?></th>
            <th class="header" style="text-align:center"><?=$lang->schedule->End?></th>
            <th class="header"></th>
        </tr>
        <tr style="text-align:center">
            <th style="text-align:center"><?=$lang->schedule->sun_long?></th>
            <td id="view_sun_reverse_str"></td>
            <td id="view_sun_st_str"></td>
            <td id="view_sun_ed_str"></td>
            <td></td>
        </tr>
        <tr style="text-align:center">
            <th style="text-align:center"><?=$lang->schedule->mon_long?></th>
            <td id="view_mon_reverse_str"></td>
            <td id="view_mon_st_str"></td>
            <td id="view_mon_ed_str"></td>
            <td></td>
        </tr>
        <tr style="text-align:center">
            <th style="text-align:center"><?=$lang->schedule->tue_long?></th>
            <td id="view_tue_reverse_str"></td>
            <td id="view_tue_st_str"></td>
            <td id="view_tue_ed_str"></td>
            <td></td>
        </tr>
        <tr style="text-align:center">
            <th style="text-align:center"><?=$lang->schedule->wed_long?></th>
            <td id="view_wed_reverse_str"></td>
            <td id="view_wed_st_str"></td>
            <td id="view_wed_ed_str"></td>
            <td></td>
        </tr>
        <tr style="text-align:center">
            <th style="text-align:center"><?=$lang->schedule->thu_long?></th>
            <td id="view_thu_reverse_str"></td>
            <td id="view_thu_st_str"></td>
            <td id="view_thu_ed_str"></td>
            <td></td>
        </tr>
        <tr style="text-align:center">
            <th style="text-align:center"><?=$lang->schedule->fri_long?></th>
            <td id="view_fri_reverse_str"></td>
            <td id="view_fri_st_str"></td>
            <td id="view_fri_ed_str"></td>
            <td></td>
        </tr>
        <tr style="text-align:center">
            <th style="text-align:center"><?=$lang->schedule->sat_long?></th>
            <td id="view_sat_reverse_str"></td>
            <td id="view_sat_st_str"></td>
            <td id="view_sat_ed_str"></td>
            <td></td>
        </tr>
        <tr style="text-align:center">
            <th style="text-align:center; color: red;"><?=$lang->schedule->hol_long?></th>
            <td id="view_hol_reverse_str"></td>
            <td id="view_hol_st_str"></td>
            <td id="view_hol_ed_str"></td>
            <td></td>
        </tr>
        <tr>
            <th style="text-align:center"><?=$lang->schedule->HolidayNo?></th>
            <td style="text-align:center" colspan="2" id="view_HolidayName"></td>
            <td></td>
            <td></td>
        </tr>
        </table>

        <div class="button_set">
            <button type="button" onclick="open_edit(_seq); init_edit();"><?=$lang->button->edit?></button>&nbsp;&nbsp;
            <button type="button" onclick="delete_form()"><?=$lang->button->delete?></button>&nbsp;&nbsp;
            <button type="button" onclick="close_view()"><?=$lang->button->cancel?></button>
        </div>
    </div>
</div>


<div id="list_section">
    <h2>:: <?=$lang->menu->list?></h2>
    <div class="box01">
        <table class="tbl_list">
        <tr>
            <th width="20"><?=$lang->schedule->No?></th>
            <th width="80"><?=$lang->schedule->Name?></th>
            <th width="80"><?=$lang->schedule->sun?></th>
            <th width="80"><?=$lang->schedule->mon?></th>
            <th width="80"><?=$lang->schedule->tue?></th>
            <th width="80"><?=$lang->schedule->wed?></th>
            <th width="80"><?=$lang->schedule->thu?></th>
            <th width="80"><?=$lang->schedule->fri?></th>
            <th width="80"><?=$lang->schedule->sat?></th>
        </tr>
        <tbody id="list_body">
        </tbody>
        </table>

        <table class="list_button_set">
        <tr>
            <td width="100"><button type="button" onclick="before_open_new();"><?=$lang->button->new?></button></td>
            <td align="center">
            <form id="form_search" method="post" action="<?=base_url()?>schedule-get" onsubmit="load_list_search(<?=base_url()?>schedule-get); return false;" target="_self">
            <?=Form::select('field', '', array('Name'=>$lang->menu->name, 'mean'=>$lang->menu->description))?>
            <?=Form::input('word', '')?>
            <button type="button" onclick="load_list_search(<?=base_url()?>schedule-get)"><?=$lang->button->search?></button>
            </form>
            </td>
            <td width="100" align="right"><button type="button" onclick="load_list(<?=base_url()?>schedule-get)"><?=$lang->button->list?></button></td>
        </tr>
        </table>

        <div id="pagination" class="pagination">[ 1 ]</div>
    </div>
</div>
</div>

<?PHP echo view('common/js.php'); ?>



<script type="text/javascript">

function reverseSlider(self)
{
    var $baseController   = $(self);
    var $slider = $(self.form).find("input[name='" + $baseController.data("slider") + "']").parent(".fc-sliderbar");

    if($baseController.is(":checked"))    $slider.addClass("fc-sliderbar-inverse");
    else                        $slider.removeClass("fc-sliderbar-inverse");
}


function changeTextTime(self)
{
    var $hours = $(self).parent().parent().find(".time_h");
    var $mins = $(self).parent().parent().find(".time_m");
    var $input = $(self).parent().parent().find(".slider_input");
    
    var startTime = (parseInt($($hours[0]).val()) * 60) + parseInt($($mins[0]).val());
    var endTime = (parseInt($($hours[1]).val()) * 60) + parseInt($($mins[1]).val());
    
    if( startTime > 1439 ) startTime = 1439;
    if( endTime > 1439 ) endTime = 1439;
    
    if( startTime > endTime )
    {
        endTime = startTime;
    }
    
    $($input).val(startTime + "~" + endTime).trigger("change");
}


function create_list()
{
    $("#list_body").html("");
    for(var i=0; i<_data.list.length; i++)
    {
        $("#list_body").append(
            '<tr id="list_'+ i +'" onclick="open_view('+ i +')" class="ov">' +
            '   <td>'+ _data.list[i].No +'</td>' +
            '   <td class="td_small">'+ _data.list[i].Name +'</td>' +
            '   <td class="td_small">'+ _data.list[i].sun_str +'</td>' +
            '   <td class="td_small">'+ _data.list[i].mon_str +'</td>' +
            '   <td class="td_small">'+ _data.list[i].tue_str +'</td>' +
            '   <td class="td_small">'+ _data.list[i].wed_str +'</td>' +
            '   <td class="td_small">'+ _data.list[i].thu_str +'</td>' +
            '   <td class="td_small">'+ _data.list[i].fri_str +'</td>' +
            '   <td class="td_small">'+ _data.list[i].sat_str +'</td>' +
            '</tr>'
        );
    }

    create_pagination();
}

$(document).ready(function() {
    load_list('<?=base_url()?>schedule-get');
});

function delete_form()
{
	var no = $("#view_No").html();
	if (no == 1)
    {
    	alert("<?=$lang->addmsg->cannot_delete_always?>");
    }
	else
	{
		if (confirm("<?=$lang->addmsg->confirm_delete?>")) 
		{
			$.getScript("<?=base_url()?>schedule-check-dependency/?no="+no);
		}
	}
}

function del_data_prepass() {
	var no = $("#view_No").html();
	$.getScript('<?=base_url()?>schedule-delete/?no='+no,function(){
		load_list('<?=base_url()?>schedule-get');
	});
}

function confirm_dependency()
{
	if( confirm("<?=$lang->addmsg->confirm_data_delete?>") )
	{
		del_data_prepass();
	}
}

<?PHP if( $baseController->is_auth(7, 1) != TRUE ) { ?>
function open_new()
{
	alert("<?=$lang->user->error_not_permission?>");
}
<?PHP } ?>

<?PHP if( $baseController->is_auth(7, 2) != TRUE && $baseController->is_auth(7, 3) != TRUE) { ?>
function open_edit()
{
	alert("<?=$lang->user->error_not_permission?>");
}
<?PHP } ?>

function init_new()
{
    $("#form_new .fc-sliderbar input").each(function() {
        $(this).val("0~1439").trigger("change");
    });
   
    
    $("#form_new input.reverse").each(function() {
        $(this).attr("checked", false);
        reverseSlider(this);
    });
}

function init_edit()
{
    $("#form_edit .fc-sliderbar input").each(function() {
        $(this).trigger("change");
    });

    $("#form_edit input.reverse").each(function() {
        reverseSlider(this);
    });
}

function before_open_new()
{
	$.ajax({
		cache: false,
		dataType: 'script',
		url: '<?=base_url()?>schedule-check-max-count'
	});
}


<?PHP if( $baseController->is_auth(7, 2) != TRUE) { ?>
function delete_form()
{
	alert("<?=$lang->user->error_not_permission?>");
}
<?PHP } ?>
</script>



<script language="JavaScript">

/* ========================================================================
 * sliderbar
 * ======================================================================== */

(function($) {
	function moveHandle($handle, event)
	{
		var $container	= $handle.parents(".fc-sliderbar"),
			$control	= $container.find(".control"),
			$range		= $container.find(".range"),
			$handles	= $container.find(".handle"),
			vertical	= $container.hasClass("fc-sliderbar-vertical");
           
            
		if($control.hasClass("disabled"))	return;

		if(vertical) {
			var top		= event.pageY,
				min		= $control.offset().top,
				max		= min + $control.outerHeight();

			if($handles.length == 2) {
				if($handles[0] == $handle[0]) {
					max = $($handles[1]).offset().top + $handle.outerHeight() / 2;
				} else if($handles[1] == $handle[0]) {
					min = $($handles[0]).offset().top + $handle.outerHeight() / 2;
				}

				top = Math.max(top, min);
				top = Math.min(top, max);
			} else {
				top = Math.max(top, min);
				top = Math.min(top, max);
			}

			$handle.offset({ top: top - $handle.outerHeight() / 2 });
		} else {
			var left	= event.pageX,
				min		= $control.offset().left,
				max		= min + $control.outerWidth();

			if($handles.length == 2) {
				if($handles[0] == $handle[0]) {
					max = $($handles[1]).offset().left + $handle.outerWidth() / 2;
				} else if($handles[1] == $handle[0]) {
					min = $($handles[0]).offset().left + $handle.outerWidth() / 2;
				}

				left = Math.max(left, min);
				left = Math.min(left, max);
			} else {
				left = Math.max(left, min);
				left = Math.min(left, max);
			}

			$handle.offset({ left: left - $handle.outerWidth() / 2 });
		}

		positionToValue($container);
	}

	function positionToValue($container)
	{
		var $control	= $container.find(".control"),
			$handles	= $container.find(".handle"),
			$input		= $container.find("input"),
			vertical	= $container.hasClass("fc-sliderbar-vertical"),
			max			= $input.data("max") || 100,
			min			= $input.data("min") || 0,
			step		= $input.data("step") || 1,
			separator	= $input.data("separator") || "~",
			values		= [];

		$handles.each(function() {
			var position = value = 0;

			if(vertical) {
				position	= Math.floor($(this).offset().top - $control.offset().top + $(this).outerHeight() / 2);
				value		= position / $control.height() * (max - min);
				value 		= Math.max(value, min);
				value 		= Math.min(value, max);
			} else {
				position	= Math.floor($(this).offset().left - $control.offset().left + $(this).outerWidth() / 2);
				value		= Math.round(position / $control.width() * (max - min));
				value 		= Math.max(value, min);
				value 		= Math.min(value, max);
			}
			
			if(value % step < step / 2) {
				values.push(value - (value % step));
			} else {
				values.push(value + step - (value % step));
			}
		});

		$input.val(values.join(separator));

		valueToPosition($container);
	}

	function valueToPosition($container)
	{
		var $control	= $container.find(".control"),
			$range		= $container.find(".range"),
			$handles	= $container.find(".handle"),
			$input		= $container.find("input"),
			vertical	= $container.hasClass("fc-sliderbar-vertical"),
			max			= $input.data("max") || 100,
			min			= $input.data("min") || 0,
			separator	= $input.data("separator") || "~",
			values		= $input.val().split(separator),
			valueLimit	= min;

		$handles.each(function(index, element) {
			var value = parseInt(values[index], 10) || valueLimit;
			valueLimit = value;

			if(vertical) {
				var position = Math.round((value / (max - min) * $control.height()) + $control.offset().top);

				$(this).offset({ top: position - $(this).outerHeight() / 2 });

				if($handles.length == 2) {
					$range.offset({ top: ($($handles[0]).offset().top || $control.offset().top) + $($handles[0]).outerHeight() / 2 });
					$range.height($($handles[1]).offset().top - ($($handles[0]).offset().top || $control.offset().top));
				} else {
					$range.height(position - $control.offset().top);
				}
			} else {
				var position = Math.round((value / (max - min) * $control.width()) + $control.offset().left);

				$(this).offset({ left: position - $(this).outerWidth() / 2 });

				if($handles.length == 2) {
					$range.offset({ left: ($($handles[0]).offset().left || $control.offset().left) + $($handles[0]).outerWidth() / 2 });
					$range.width($($handles[1]).offset().left - ($($handles[0]).offset().left || $control.offset().left));
				} else {
					$range.width(position - $control.offset().left);
				}
			}
		});

		refreshTooltip($container);
	}

	function createTooltip($container)
	{
        if($container.find(".tooltip").length == 0) {
            var placement;
            var topposition;
            $container.find(".handle").each(function(index) {
                if( index == 0 )
                {
                    placement = "top";
                    topposition = ($(this).height() + $container.find(".handle").height() / 2) * -1 - 2;
                }
                else{
                    placement = "bottom";
                    topposition = $container.find(".handle").height() + 1;
                }
                
                $(this).html('<div class="tooltip fade '+ placement +' in" style="top: '+topposition+';"><div class="tooltip-arrow" style=""></div><div class="tooltip-inner">&nbsp;</div></div>');
                
			});
			$container.find(".tooltip").hide();
		}
	}

	function showTooltip($container)
	{
		var tooltip	= $container.find("input").data("tooltip") || "true";

		if(tooltip == "true") {
			createTooltip($container);
			$container.find(".tooltip").show();
		}
	}

	function hideTooltip($container)
	{
		var tooltip	= $container.find("input").data("tooltip") || "true";

		if(tooltip == "true" && !$container.hasClass("hover") && $container.find(".draggable").length == 0) {
			$container.find(".tooltip").hide();
		}
	}

	function refreshTooltip($container)
	{
        var tooltip		= $container.find("input").data("tooltip") || "true",
			vertical	= $container.hasClass("fc-sliderbar-vertical");

        var $hours  = $container.parent().parent().find(".time_h");
        var $mins = $container.parent().parent().find(".time_m");
        
		if(tooltip == "true") {
			createTooltip($container);

			var separator	= $container.find("input").data("separator") || "~",
				values		= $container.find("input").val().split(separator);

			$container.find(".handle").each(function(index, element) {
                var hour = parseInt(parseInt(values[index], 10) / 60),
                    min  = parseInt(values[index], 10) % 60;
				$(this).find(".tooltip-inner").text(sprintf("%02d:%02d", hour, min));
                
                $($hours[index]).val(sprintf("%02d", hour));
                $($mins[index]).val(sprintf("%02d", min));
			});
            
			$container.find(".tooltip").each(function(index) {
				if(vertical) {
					$(this).css({
                        top: (($(this).outerHeight() - $(this).parents(".handle").height()) / 2) * -1,
                        left: ($(this).width() + $container.find(".handle").outerWidth() / 2) * -1 - 2,
					});
				} else {
                    if(index == 0)
                    {
                        $(this).css({
                            top:  ($(this).height() + $container.find(".handle").height() / 2) * -1 - 2,
                            left: (($(this).outerWidth() - $(this).parents(".handle").width()) / 2) * -1
                        });
                    }
                    else
                    {
                        $(this).css({
                            top:  $container.find(".handle").height() + 1,
                            left: (($(this).outerWidth() - $(this).parents(".handle").width()) / 2) * -1
                        });
                    }
				}
			});
		}
	}

	$(".fc-sliderbar input")
		.bind("change", function() {
			valueToPosition($(this).parents(".fc-sliderbar"));
			refreshTooltip($(this).parents(".fc-sliderbar"));
		}).change();

	$(".fc-sliderbar .control")
		.bind("click", function(event) {
			var $container	= $(this).parents(".fc-sliderbar"),
				$handles	= $container.find(".handle"),
				vertical	= $container.hasClass("fc-sliderbar-vertical");

			if($handles.length == 1) {
				moveHandle($handles, event);
			} else if($handles.length == 2) {
				if(vertical) {
					if(event.pageY < $($handles[0]).offset().top) {
						moveHandle($($handles[0]), event);
					} else if(event.pageY > $($handles[1]).offset().top) {
						moveHandle($($handles[1]), event);
					} else if(Math.abs(event.pageY - $($handles[0]).offset().top) > Math.abs(event.pageY - $($handles[1]).offset().top)) {
						moveHandle($($handles[1]), event);
					} else {
						moveHandle($($handles[0]), event);
					}
				} else {
					if(event.pageX < $($handles[0]).offset().left) {
						moveHandle($($handles[0]), event);
					} else if(event.pageX > $($handles[1]).offset().left) {
						moveHandle($($handles[1]), event);
					} else if(Math.abs(event.pageX - $($handles[0]).offset().left) > Math.abs(event.pageX - $($handles[1]).offset().left)) {
						moveHandle($($handles[1]), event);
					} else {
						moveHandle($($handles[0]), event);
					}
				}
			}
		})
		.bind("mouseover", function() {
			var $handle		= $(".draggable");
			if($handle.length == 0) {
				showTooltip($(this).parents(".fc-sliderbar").addClass("hover"));
			}
		})
		.bind("mouseout", function() {
			hideTooltip($(this).parents(".fc-sliderbar").removeClass("hover"));
		});

	$(".fc-sliderbar .control a.handle")
		.bind("mousedown", function(event) {
			var $baseController = $(this).addClass("draggable");
            event.preventDefault(); // disable selection
		})
		.bind("click", function(event) {
			event.preventDefault();
		});

	$(document)
		.bind("mousemove", function(event) {
			var $handle = $(this).find(".draggable");

			if($handle.length > 0) {
				moveHandle($handle, event);
				$handle.parents(".fc-sliderbar").find("input").trigger("slide.fc.sliderbar");
			}
		});

	$(document)
		.bind("mouseup", function(event) {
			var $handle		= $(this).find(".draggable");

			if($handle.length > 0) {
				$(".draggable").removeClass("draggable");
				positionToValue($handle.parents(".fc-sliderbar"));
				$handle.parents(".fc-sliderbar").find("input").trigger("slidechange.fc.sliderbar");
			}

			hideTooltip($handle.parents(".fc-sliderbar"));
		});
        
})(jQuery);

</script>