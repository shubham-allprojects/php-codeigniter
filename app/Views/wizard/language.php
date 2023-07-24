
<style>

#wrap { background:url(); }

</style>

<form id="form_edit" method="post" action="<?=base_url()?>save-language" target="frame_exe">
<h3><?=$lang->menu->language?></h3>
<table class="tbl_view">
<tr>
	<td>
		<?PHP if ($language=='en') { ?>
			<?=Form::radio('language', 'en', array('en'=>$lang->common->English))?>
		<?PHP } else if ($language=='sp') { ?>
			<?=Form::radio('language', 'en', array('en'=>$lang->common->English))?>
		<?PHP } else { ?>
			<?=Form::radio('language', '', array('en'=>'English'))?>
		<?PHP } ?>
	</td>
</tr>
<tr class="hide">
	<td>
		<?PHP if ($language=='fr') { ?>
			<?=Form::radio('language', 'fr', array('fr'=>'French'))?>
		<?PHP } else { ?>
			<?=Form::radio('language', '', array('fr'=>'French'))?>
		<?PHP } ?>
	</td>
</tr>
<tr>
	<td>
		<?PHP if ($language=='sp') { ?>
			<?=Form::radio('language', 'sp', array('sp'=>$lang->common->Spanish))?>
		<?PHP } else { ?>
			<?=Form::radio('language', '', array('sp'=>'Spanish'))?>
		<?PHP } ?>
	</td>
</tr>
<tr class="hide">
	<td>
		<?PHP if ($language=='pt') { ?>
			<?=Form::radio('language', 'pt', array('pt'=>'Portuguese'))?>
		<?PHP } else { ?>
			<?=Form::radio('language', '', array('pt'=>'Portuguese'))?>
		<?PHP } ?>
	</td>
</tr>
</table>
<br>

<h3><?=$lang->menu->country?></h3>
<table class="tbl_view">
<tr>
	<td>
		<?PHP if ($country=='1') { ?>
			<?=Form::radio('country', '1', array('1'=>$lang->common->united_states))?>
		<?PHP } else { ?>
			<?=Form::radio('country', '', array('1'=>$lang->common->united_states))?>
		<?PHP } ?>
	</td>
</tr>
<tr>
	<td>
		<?PHP if ($country=='2') { ?>
			<?=Form::radio('country', '2', array('2'=>$lang->common->canada))?>
		<?PHP } else { ?>
			<?=Form::radio('country', '', array('2'=>$lang->common->canada))?>
		<?PHP } ?>
	</td>
</tr>
<tr>
	<td>
		<?PHP if ($country=='3') { ?>
			<?=Form::radio('country', '3', array('3'=>$lang->common->brazil))?>
		<?PHP } else { ?>
			<?=Form::radio('country', '', array('3'=>$lang->common->brazil))?>
		<?PHP } ?>
	</td>
</tr>
<tr>
	<td>
		<?PHP if ($country=='4') { ?>
			<?=Form::radio('country', '4', array('4'=>$lang->common->mexico))?>
		<?PHP } else { ?>
			<?=Form::radio('country', '', array('4'=>$lang->common->mexico))?>
		<?PHP } ?>
	</td>
</tr>
</table>
<br>

<div class="button_set">
		<button type="button" onclick="$('#form_edit').submit()"><?=$lang->button->save?></button>
</div>

</form>

<?PHP echo view('common/js.php'); ?>
<script type="text/javascript">
function restore_holiday(country)
{
	if( confirm("<?=$lang->addmsg->confirm_restore_holiday?>") ) {
		$.getScript("<?=base_url()?>restore-holiday/?country="+country, function(data, textStatus, jqXHR) {
		});
	}
}
</script>