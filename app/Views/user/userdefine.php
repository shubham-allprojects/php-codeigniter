<div id="location">
<?
echo $lang->menu->userset.'&nbsp;&gt;&nbsp;'.$lang->menu->userdefinefield;
?>
	<button class="btn_help" onclick="openHelp('<?=$this->class?>', '<?=$lang->_lang?>')">Help</button>
</div>

<script type="text/javascript">
function create_list()
{
	open_view(0);
}

$(document).ready(function() {
	load_list();
});

// ���� üũ�ؼ� ��� ������ ȣ���� ������ ��.
<? if( $this->is_auth(55, 1) != TRUE ) { ?>
function open_new()
{
	alert("<?=$this->lang->user->error_not_permission?>");
}
<? } ?>

<? if( $this->is_auth(55, 2) != TRUE && $this->is_auth(55, 3) != TRUE) { ?>
function open_edit()
{
	alert("<?=$this->lang->user->error_not_permission?>");
}
<? } ?>

<? if( $this->is_auth(55, 2) != TRUE) { ?>
function del_alevel()
{
	alert("<?=$this->lang->user->error_not_permission?>");
}
<? } ?>


</script>


<div id="edit_section" class="hide">
	<h2>:: <?=$lang->menu->userdefinefield?></h2>
	<div class="box01">
		<form id="form_edit" method="post" action="/?c=<?=$this->class?>&m=update">
		<?=Form::hidden("No")?>
		<h3>Basic</h3>
		<table class="tbl_view">
		<tr>
			<? if ($this->max_field >= 1) { ?>
			<th width="150"><?=$lang->userdefine->user1?></th>
			<td width="1">:</td>
			<td><?=Form::input('User1', "", array("MAXLENGTH"=>ConstTable::max_name_char))?></td>
			<? } ?>
			<? if ($this->max_field >= 2) { ?>
			<th width="150"><?=$lang->userdefine->user2?></th>
			<td width="1">:</td>
			<td><?=Form::input('User2', "", array("MAXLENGTH"=>ConstTable::max_name_char))?></td>
			<? } ?>
		</tr>
		<tr>
			<? if ($this->max_field >= 3) { ?>
			<th><?=$lang->userdefine->user3?></th>
			<td width="1">:</td>
			<td><?=Form::input('User3', "", array("MAXLENGTH"=>ConstTable::max_name_char))?></td>
			<? } ?>
			<? if ($this->max_field >= 4) { ?>
			<th><?=$lang->userdefine->user4?></th>
			<td width="1">:</td>
			<td><?=Form::input('User4', "", array("MAXLENGTH"=>ConstTable::max_name_char))?></td>
			<? } ?>
		</tr>
		<tr>
			<? if ($this->max_field >= 5) { ?>
			<th><?=$lang->userdefine->user5?></th>
			<td width="1">:</td>
			<td><?=Form::input('User5', "", array("MAXLENGTH"=>ConstTable::max_name_char))?></td>
			<? } ?>
			<? if ($this->max_field >= 6) { ?>
			<th><?=$lang->userdefine->user6?></th>
			<td width="1">:</td>
			<td><?=Form::input('User6', "", array("MAXLENGTH"=>ConstTable::max_name_char))?></td>
			<? } ?>
		</tr>
		<tr>
			<? if ($this->max_field >= 7) { ?>
			<th><?=$lang->userdefine->user7?></th>
			<td width="1">:</td>
			<td><?=Form::input('User7', "", array("MAXLENGTH"=>ConstTable::max_name_char))?></td>
			<? } ?>
			<? if ($this->max_field >= 8) { ?>
			<th><?=$lang->userdefine->user8?></th>
			<td width="1">:</td>
			<td><?=Form::input('User8', "", array("MAXLENGTH"=>ConstTable::max_name_char))?></td>
			<? } ?>
		</tr>
		<tr>
			<? if ($this->max_field >= 9) { ?>
			<th><?=$lang->userdefine->user9?></th>
			<td width="1">:</td>
			<td><?=Form::input('User9', "", array("MAXLENGTH"=>ConstTable::max_name_char))?></td>
			<? } ?>
			<? if ($this->max_field >= 10) { ?>
			<th><?=$lang->userdefine->user10?></th>
			<td width="1">:</td>
			<td><?=Form::input('User10', "", array("MAXLENGTH"=>ConstTable::max_name_char))?></td>
			<? } ?>
		</tr>
		<tr>
			<? if ($this->max_field >= 11) { ?>
			<th><?=$lang->userdefine->user11?></th>
			<td width="1">:</td>
			<td><?=Form::input('User11', "", array("MAXLENGTH"=>ConstTable::max_name_char))?></td>
			<? } ?>
			<? if ($this->max_field >= 12) { ?>
			<th><?=$lang->userdefine->user12?></th>
			<td width="1">:</td>
			<td><?=Form::input('User12', "", array("MAXLENGTH"=>ConstTable::max_name_char))?></td>
			<? } ?>
		</tr>
		<tr>
			<? if ($this->max_field >= 13) { ?>
			<th><?=$lang->userdefine->user13?></th>
			<td width="1">:</td>
			<td><?=Form::input('User13', "", array("MAXLENGTH"=>ConstTable::max_name_char))?></td>
			<? } ?>
			<? if ($this->max_field >= 14) { ?>
			<th><?=$lang->userdefine->user14?></th>
			<td width="1">:</td>
			<td><?=Form::input('User14', "", array("MAXLENGTH"=>ConstTable::max_name_char))?></td>
			<? } ?>
		</tr>
		<tr>
			<? if ($this->max_field >= 15) { ?>
			<th><?=$lang->userdefine->user15?></th>
			<td width="1">:</td>
			<td><?=Form::input('User15', "", array("MAXLENGTH"=>ConstTable::max_name_char))?></td>
			<? } ?>
			<? if ($this->max_field >= 16) { ?>
			<th><?=$lang->userdefine->user16?></th>
			<td width="1">:</td>
			<td><?=Form::input('User16', "", array("MAXLENGTH"=>ConstTable::max_name_char))?></td>
			<? } ?>
		</tr>
		<tr>
			<? if ($this->max_field >= 17) { ?>
			<th><?=$lang->userdefine->user17?></th>
			<td width="1">:</td>
			<td><?=Form::input('User17', "", array("MAXLENGTH"=>ConstTable::max_name_char))?></td>
			<? } ?>
			<? if ($this->max_field >= 18) { ?>
			<th><?=$lang->userdefine->user18?></th>
			<td width="1">:</td>
			<td><?=Form::input('User18', "", array("MAXLENGTH"=>ConstTable::max_name_char))?></td>
			<? } ?>
		</tr>
		<tr>
			<? if ($this->max_field >= 19) { ?>
			<th><?=$lang->userdefine->user19?></th>
			<td width="1">:</td>
			<td><?=Form::input('User19', "", array("MAXLENGTH"=>ConstTable::max_name_char))?></td>
			<? } ?>
			<? if ($this->max_field >= 20) { ?>
			<th><?=$lang->userdefine->user20?></th>
			<td width="1">:</td>
			<td><?=Form::input('User20', "", array("MAXLENGTH"=>ConstTable::max_name_char))?></td>
			<? } ?>
		</tr>
		</tbody>
		</table>

		<div class="button_set">
			<button type="button" onclick="$('#form_edit').submit()"><?=$lang->button->save?></button>&nbsp;&nbsp;
			<button type="button" onclick="close_edit()"><?=$lang->button->cancel?></button>
		</div>
		</form>
	</div>
</div>

<div id="view_section">
	<h2>:: <?=$lang->menu->userdefinefield?></h2>
	<div class="box01">
		<h3>Basic</h3>
		<table class="tbl_view">
		<tr>
			<? if ($this->max_field >= 1) { ?>
			<th width="150"><?=$lang->userdefine->user1?></th>
			<td width="1">:</td>
			<td width="155" id="view_User1"></td>
			<? } ?>
			<? if ($this->max_field >= 2) { ?>
			<th width="150"><?=$lang->userdefine->user2?></th>
			<td width="1">:</td>
			<td id="view_User2"></td>
			<? } ?>
		</tr>
		<tr>
			<? if ($this->max_field >= 3) { ?>
			<th><?=$lang->userdefine->user3?></th>
			<td width="1">:</td>
			<td id="view_User3"></td>
			<? } ?>
			<? if ($this->max_field >= 4) { ?>
			<th><?=$lang->userdefine->user4?></th>
			<td width="1">:</td>
			<td id="view_User4"></td>
			<? } ?>
		</tr>
		<tr>
			<? if ($this->max_field >= 5) { ?>
			<th><?=$lang->userdefine->user5?></th>
			<td width="1">:</td>
			<td id="view_User5"></td>
			<? } ?>
			<? if ($this->max_field >= 6) { ?>
			<th><?=$lang->userdefine->user6?></th>
			<td width="1">:</td>
			<td id="view_User6"></td>
			<? } ?>
		</tr>
		<tr>
			<? if ($this->max_field >= 7) { ?>
			<th><?=$lang->userdefine->user7?></th>
			<td width="1">:</td>
			<td id="view_User7"></td>
			<? } ?>
			<? if ($this->max_field >= 8) { ?>
			<th><?=$lang->userdefine->user8?></th>
			<td width="1">:</td>
			<td id="view_User8"></td>
			<? } ?>
		</tr>
		<tr>
			<? if ($this->max_field >= 9) { ?>
			<th><?=$lang->userdefine->user9?></th>
			<td width="1">:</td>
			<td id="view_User9"></td>
			<? } ?>
			<? if ($this->max_field >= 10) { ?>
			<th><?=$lang->userdefine->user10?></th>
			<td width="1">:</td>
			<td id="view_User10"></td>
			<? } ?>
		</tr>
		<tr>
			<? if ($this->max_field >= 11) { ?>
			<th><?=$lang->userdefine->user11?></th>
			<td width="1">:</td>
			<td id="view_User11"></td>
			<? } ?>
			<? if ($this->max_field >= 12) { ?>
			<th><?=$lang->userdefine->user12?></th>
			<td width="1">:</td>
			<td id="view_User12"></td>
			<? } ?>
		</tr>
		<tr>
			<? if ($this->max_field >= 13) { ?>
			<th><?=$lang->userdefine->user13?></th>
			<td width="1">:</td>
			<td id="view_User13"></td>
			<? } ?>
			<? if ($this->max_field >= 14) { ?>
			<th><?=$lang->userdefine->user14?></th>
			<td width="1">:</td>
			<td id="view_User14"></td>
			<? } ?>
		</tr>
		<tr>
			<? if ($this->max_field >= 15) { ?>
			<th><?=$lang->userdefine->user15?></th>
			<td width="1">:</td>
			<td id="view_User15"></td>
			<? } ?>
			<? if ($this->max_field >= 16) { ?>
			<th><?=$lang->userdefine->user16?></th>
			<td width="1">:</td>
			<td id="view_User16"></td>
			<? } ?>
		</tr>
		<tr>
			<? if ($this->max_field >= 17) { ?>
			<th><?=$lang->userdefine->user17?></th>
			<td width="1">:</td>
			<td id="view_User17"></td>
			<? } ?>
			<? if ($this->max_field >= 18) { ?>
			<th><?=$lang->userdefine->user18?></th>
			<td width="1">:</td>
			<td id="view_User18"></td>
			<? } ?>
		</tr>
		<tr>
			<? if ($this->max_field >= 19) { ?>
			<th><?=$lang->userdefine->user19?></th>
			<td width="1">:</td>
			<td id="view_User19"></td>
			<? } ?>
			<? if ($this->max_field >= 20) { ?>
			<th><?=$lang->userdefine->user20?></th>
			<td width="1">:</td>
			<td id="view_User20"></td>
			<? } ?>
		</tr>
		</tbody>
		</table>

		<div class="button_set">
			<button type="button" onclick="open_edit(_seq)"><?=$lang->button->edit?></button>
		</div>
	</div>
</div>