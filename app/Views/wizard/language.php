<?PHP echo view('common/css.php'); ?>
<style>

#wrap { background:url(); }

</style>

<form id="form_edit" method="post" action="<?PHP echo base_url(); ?>save-language" target="frame_exe">
<h3><?=lang('Message.Menu.language')?></h3>
<table class="tbl_view">
<tr>
	<td>
		<? if ($language=='en') { ?>
			<?=Form::radio('language', 'en', array('en'=>lang('Message.Common.english')))?>
		<? } else if ($language=='sp') { ?>
			<?=Form::radio('language', 'en', array('en'=>lang('Message.Common.Spanish')))?>
		<? } else { ?>
			<?=Form::radio('language', '', array('en'=>'english'))?>
		<? } ?>
	</td>
</tr>
<tr class="hide">
	<td>
		<? if ($language=='fr') { ?>
			<?=Form::radio('language', 'fr', array('fr'=>'French'))?>
		<? } else { ?>
			<?=Form::radio('language', '', array('fr'=>'French'))?>
		<? } ?>
	</td>
</tr>
<tr>
	<td>
		<? if ($language=='sp') { ?>
			<?=Form::radio('language', 'sp', array('sp'=>lang('Message.Common.Spanish')))?>
		<? } else { ?>
			<?=Form::radio('language', '', array('sp'=>'Spanish'))?>
		<? } ?>
	</td>
</tr>
<tr class="hide">
	<td>
		<? if ($language=='pt') { ?>
			<?=Form::radio('language', 'pt', array('pt'=>'Portuguese'))?>
		<? } else { ?>
			<?=Form::radio('language', '', array('pt'=>'Portuguese'))?>
		<? } ?>
	</td>
</tr>
</table>
<br>

<h3><?=lang('Message.Menu.country')?></h3>
<table class="tbl_view">
<tr>
	<td>
		<? if ($country=='1') { ?>
			<?=Form::radio('country', '1', array('1'=>lang('Message.Common.united_states')))?>
		<? } else { ?>
			<?=Form::radio('country', '', array('1'=>lang('Message.Common.united_states')))?>
		<? } ?>
	</td>
</tr>
<tr>
	<td>
		<? if ($country=='2') { ?>
			<?=Form::radio('country', '2', array('2'=>lang('Message.Common.canada')))?>
		<? } else { ?>
			<?=Form::radio('country', '', array('2'=>lang('Message.Common.canada')))?>
		<? } ?>
	</td>
</tr>
<tr>
	<td>
		<? if ($country=='3') { ?>
			<?=Form::radio('country', '3', array('3'=>lang('Message.Common.brazil')))?>
		<? } else { ?>
			<?=Form::radio('country', '', array('3'=>lang('Message.Common.brazil')))?>
		<? } ?>
	</td>
</tr>
<tr>
	<td>
		<? if ($country=='4') { ?>
			<?=Form::radio('country', '4', array('4'=>lang('Message.Common.mexico')))?>
		<? } else { ?>
			<?=Form::radio('country', '', array('4'=>lang('Message.Common.mexico')))?>
		<? } ?>
	</td>
</tr>
</table>
<br>

<div class="button_set">
		<button type="button" onclick="$('#form_edit').submit()"><?=lang('Message.Button.save')?></button>
</div>

</form>
<?PHP echo view('common/js.php'); ?>
<script type="text/javascript">
function restore_holiday(country)
{
	if( confirm("<?=lang('Message.addmsg.confirm_restore_holiday')?>") ) {
		$.getScript("/?c=wizard&m=restore_holiday&country="+country, function(data, textStatus, jqXHR) {
		});
	}
}
</script>
