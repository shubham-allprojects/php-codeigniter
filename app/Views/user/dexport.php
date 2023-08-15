<div id="location">
<?
echo $lang->menu->datatransfers.'&nbsp;&gt;&nbsp;'.$lang->menu->dexport;
?>
	<button class="btn_help" onclick="openHelp('<?=$this->class?>', '<?=$lang->_lang?>')">Help</button>
</div>

<div id="edit_section">
    <h2>:: <?=$lang->menu->dexport?></h2>
    <div class="box01">
    <form id="form_edit" method="post" action="/?c=<?=$this->class?>&m=export" target="_self">
        <h3><?=$lang->menu->basic?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->menu->filetype?></th>
            <td width="1">:</td>
            <td>
                <input type="radio" name="sel" value="csv" checked/> <?=$lang->menu->csv?> &nbsp; &nbsp; &nbsp;
            </td>
        </tr>
        </table>

        <div class="button_set">
            <button type="button" onclick="$('#form_edit').submit()"><?=$lang->menu->export?></button>
        </div>
    </form>
    </div>
</div>