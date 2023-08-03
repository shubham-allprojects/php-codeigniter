<div id="location">
<?
echo $lang->menu->admin.'&nbsp;&gt;&nbsp;'.$lang->menu->alevel;
?>
	<button class="btn_help" onclick="openHelp('<?=$this->class?>', '<?=$lang->_lang?>')">Help</button>
</div>


<script type="text/javascript">
function create_list()
{
    $("#list_body").html("");
    for(var i=0; i<_data.list.length; i++)
    {
        $("#list_body").append(
            '<tr id="list_'+ i +'" onclick="open_view('+ i +')" class="ov">' +
            //'   <td>'+ _data.list[i].No +'</td>' +
            '   <td>'+ _data.list[i].Name +'</td>' +
            '   <td>'+ _data.list[i].Mean +'</td>' +
            '   <td>'+ _data.list[i].DoorList2Str +'</td>' +
            '   <td>'+ _data.list[i].ScheduleName +'</td>' +
            '</tr>'
        );
    }
    create_pagination();
}

$(document).ready(function() {
    load_list();
});

function init_edit()
{
    set_access_list('edit');
}

function set_access_list(form)
{
    $("#form_"+ form +" select[name='DoorList2\[\]'] option").remove();
    $.each(_current.DoorList2, function(name, value) {
        $("#form_"+ form +" select[name='DoorList2\[\]']").append('<option value="'+ value.No +'">'+ value.Name +'</option>');
    });
}

function del_alevel()
{
    if (confirm("<?=$this->lang->addmsg->confirm_delete?>")) 
    {
        var no = $("#view_No").html();
    	$.getScript("/?c=<?=$this->class?>&m=check_dependency&no="+no);
    }
}

function del_data_prepass() {
	var no = $("#view_No").html();
	$.getScript("/?c=<?=$this->class?>&m=delete&no="+no,function(){
		load_list();
	});
}

function confirm_dependency()
{
	if( confirm("<?=$this->lang->addmsg->confirm_data_delete?>") )
	{
		del_data_prepass();
	}
}

function change_selecttype(form)
{
    var selecttype = $("#form_"+ form +" select[name='SelectType']").val();
    
    $("#form_"+ form +" select[name='DoorList1\[\]'] option").remove();
    $("#form_"+ form +" select[name='DoorList2\[\]'] option").remove();

    submit_search_door(form);
}

function push_door(form)
{
    $("#form_"+ form +" select[name='DoorList1\[\]'] option:selected").each(function() {
        $("#form_"+ form +" select[name='DoorList2\[\]'] option").filter("option[value='"+ $(this).val() +"']").remove();
        $("#form_"+ form +" select[name='DoorList2\[\]']").append($(this).clone());
    });
}

function pop_door(form)
{
    $("#form_"+ form +" select[name='DoorList2\[\]'] option:selected").remove();
}

function submit_search_door(form)
{
    if ($("#form_"+ form +" select[name='SelectType']").val() != "2")
    {
        $.getJSON(
            "/?c=door&m=find&f=Name&w="+ $("#form_"+ form +" input[name='search_door']").val(),
            function(data) {
				if( check_error(data) ) {
					var element = $("#form_"+ form +" select[name='DoorList1\[\]']");
					element.html("");
					for(var i=0; i<data.list.length; i++)
					{
						element.append('<option value="'+ data.list[i].No +'">'+ data.list[i].Name +'</option>');
					}
				}
            }
        );
    }
    else
    {
        $.getJSON(
            "/?c=groupdoor&m=find&f=Name&w="+ $("#form_"+ form +" input[name='search_door']").val(),
            function(data) {
				if( check_error(data) ) {
					var element = $("#form_"+ form +" select[name='DoorList1\[\]']");
					element.html("");
					for(var i=0; i<data.list.length; i++)
					{
						element.append('<option value="'+ data.list[i].No +'">'+ data.list[i].Name +'</option>');
					}
				}
            }
        );
    }
}

function before_submit(form)
{
    $("#form_"+ form +" select[name='DoorList2\[\]'] option").attr('selected', true);
} 

function init_new()
{
	$("#form_new select[name='ScheduleNo'] option:first").attr('selected', true);
	$("#form_new select[name='SelectType'] option:first").attr('selected', true);
}

function before_open_new()
{
	$.ajax({
		cache: false,
		dataType: 'script',
		url: '/?c=alevel&m=check_max_count',
        success: function() {
            init_new();
            change_selecttype('new');
        }
	});
}

// ���� üũ�ؼ� ��� ������ ȣ���� ������ ��.
<? if( $this->is_auth(6, 1) != TRUE ) { ?>
function open_new()
{
	alert("<?=$this->lang->user->error_not_permission?>");
}
<? } ?>

<? if( $this->is_auth(6, 2) != TRUE && $this->is_auth(6, 3) != TRUE) { ?>
function open_edit()
{
	alert("<?=$this->lang->user->error_not_permission?>");
}
<? } ?>

<? if( $this->is_auth(6, 2) != TRUE) { ?>
function del_alevel()
{
	alert("<?=$this->lang->user->error_not_permission?>");
}
<? } ?>

</script>


<div id="new_section" class="hide">
<form id="form_new" method="post" action="/?c=<?=$this->class?>&m=insert">
    <h2>:: <?=$lang->menu->alevel?></h2>
    <div class="box01">

        <h3><?=$lang->menu->basic?></h3>
        <table class="tbl_view">
        <tr>
            <th><?=$lang->alevel->Name?> *</th>
            <td width="1">:</td>
            <td><?=Form::input('Name', "", array("MAXLENGTH"=>ConstTable::max_access_level_name_char))?></td>
        </tr>
        <tr>
            <th><?=$lang->alevel->Mean?></th>
            <td width="1">:</td>
            <td><?=Form::input('Mean', "", array("MAXLENGTH"=>ConstTable::max_description_char))?></td>
        </tr>
        <tr>
            <th><?=$lang->alevel->ScheduleNo?></th>
            <td width="1">:</td>
            <td><?=Form::select('ScheduleNo', '', $arr_schedule)?></td>
        </tr>
        <tr>
            <th><?=$lang->alevel->SelectType?></th>
            <td width="1">:</td>
            <td><?=Form::select('SelectType', '', EnumTable::$attrGroup, array('onchange'=>"change_selecttype('new')"))?></td>
        </tr>
        <tbody id="toggle_1_edit">
        <tr>
            <th><?=$lang->alevel->DoorList?></th>
            <td width="1">:</td>
            <td>
                <?=Form::input('search_door')?>&nbsp;&nbsp;
                <button name="btnSearch" class="btn_find" type="button" onclick="submit_search_door('new')"></button><br />
                <div class="left"><?=Form::select('DoorList1[]', '', array(), array('multiple'=>'multiple', 'style'=>'width:200px'))?></div>
                <div class="left text_left">
                    &nbsp;<button name="btn_push" class="btn_push" type="button" onclick="push_door('new')"></button>&nbsp;<br /><br />
                    &nbsp;<button name="btn_pop"  class="btn_pop" type="button" onclick="pop_door('new')"></button>&nbsp;
                </div>
                <div class="left"><?=Form::select('DoorList2[]', '', array(), array('multiple'=>'multiple', 'style'=>'width:200px'))?></div>
            </td>
        </tr>
        </tbody>
        </table>

        <div class="button_set">
            <button type="button" onclick="before_submit('new'); $('#form_new').submit()"><?=$lang->button->add?></button>&nbsp;&nbsp;
            <button type="button" onclick="before_open_new();"><?=$lang->button->reset?></button>&nbsp;&nbsp;
            <button type="button" onclick="close_new()"><?=$lang->button->cancel?></button>
        </div>
    </div>
</form>
</div>


<div id="edit_section" class="hide">
<form id="form_edit" method="post" action="/?c=<?=$this->class?>&m=update">
<?=Form::hidden("No")?>
<?=Form::hidden("PrevName")?>
    <h2>:: <?=$lang->menu->alevel?></h2>
    <div class="box01">

        <h3><?=$lang->menu->basic?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->alevel->Name?> *</th>
            <td width="1">:</td>
            <td><?=Form::input('Name', "", array("MAXLENGTH"=>ConstTable::max_access_level_name_char))?></td>
        </tr>
        <tr>
            <th><?=$lang->alevel->Mean?></th>
            <td width="1">:</td>
            <td><?=Form::input('Mean', "", array("MAXLENGTH"=>ConstTable::max_description_char))?></td>
        </tr>
        <tr>
            <th><?=$lang->alevel->ScheduleNo?></th>
            <td width="1">:</td>
            <td><?=Form::select('ScheduleNo', '', $arr_schedule)?></td>
        </tr>
        <tr>
            <th><?=$lang->alevel->SelectType?></th>
            <td width="1">:</td>
            <td><?=Form::select('SelectType', '', EnumTable::$attrGroup, array('onchange'=>"change_selecttype('edit')"))?></td>
        </tr>
        <tbody id="toggle_1_edit">
        <tr>
            <th><?=$lang->alevel->DoorList?></th>
            <td width="1">:</td>
            <td>
                <?=Form::input('search_door')?>&nbsp;&nbsp;
                <button name="btnSearch" class="btn_find" type="button" onclick="submit_search_door('edit')"></button><br />
                <div class="left"><?=Form::select('DoorList1[]', '', array(), array('multiple'=>'multiple', 'style'=>'width:200px'))?></div>
                <div class="left text_left">
                    &nbsp;<button name="btn_push" class="btn_push" type="button" onclick="push_door('edit')"></button>&nbsp;<br /><br />
                    &nbsp;<button name="btn_pop"  class="btn_pop" type="button" onclick="pop_door('edit')"></button>&nbsp;
                </div>
                <div class="left"><?=Form::select('DoorList2[]', '', array(), array('multiple'=>'multiple', 'style'=>'width:200px'))?></div>
            </td>
        </tr>
        </tbody>
        </table>

        <div class="button_set">
            <button type="button" onclick="before_submit('edit'); $('#form_edit').submit()"><?=$lang->button->save?></button>&nbsp;&nbsp;
            <button type="button" onclick="open_edit(_seq); change_selecttype('edit'); init_edit(); "><?=$lang->button->reset?></button>&nbsp;&nbsp;
            <button type="button" onclick="close_edit()"><?=$lang->button->cancel?></button>
        </div>
    </div>
</form>
</div>


<div id="view_section" class="hide">
    <h2>:: <?=$lang->menu->alevel?></h2>
    <div class="box01">

        <h3><?=$lang->menu->basic?></h3>
        <table class="tbl_view">
        <tr style="display:none;">
            <td id="view_No"></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <th width="150"><?=$lang->alevel->Name?> *</th>
            <td width="1">:</td>
            <td id="view_Name"></td>
        </tr>
        <tr>
            <th><?=$lang->alevel->Mean?></th>
            <td width="1">:</td>
            <td id="view_Mean"></td>
        </tr>
        <tr>
            <th><?=$lang->alevel->ScheduleNo?></th>
            <td width="1">:</td>
            <td id="view_ScheduleName"></td>
        </tr>
        <tr>
            <th><?=$lang->alevel->SelectType?></th>
            <td width="1">:</td>
            <td id="view_SelectTypeName"></td>
        </tr>
        <tr>
            <th><?=$lang->alevel->SelectReader?></th>
            <td width="1">:</td>
            <td id="view_DoorList2Str"></td>
        </tr>
        </table>

        <div class="button_set">
            <button type="button" onclick="open_edit(_seq); change_selecttype('edit'); init_edit(); "><?=$lang->button->edit?></button>&nbsp;&nbsp;
            <button type="button" onclick="del_alevel();"><?=$lang->button->Delete?></button>&nbsp;&nbsp;
            <button type="button" onclick="close_view()"><?=$lang->button->cancel?></button>
        </div>
    </div>
</div>

<div id="list_section">
    <h2>:: <?=$lang->menu->list?></h2>
    <div class="box01">
        <table class="tbl_list">
        <tr>
            <!-- <th><?=$lang->alevel->No?></th> -->
            <th><?=$lang->alevel->Name?></th>
            <th><?=$lang->alevel->Mean?></th>
            <th><?=$lang->alevel->Doors?></th>
            <th><?=$lang->alevel->ScheduleName?></th>
        </tr>
        <tbody id="list_body">
        </tbody>
        </table>

        <table class="list_button_set">
        <tr>
            <td width="100"><button type="button" onclick="before_open_new();"><?=$lang->button->new?></button></td>
            <td align="center">
            <form id="form_search" method="post" action="/?c=<?=$this->class?>&m=select" onsubmit="load_list_search(); return false;" target="_self">
            <?=Form::select('field', '', array('Name'=>$lang->alevel->Name, 'Mean'=>$lang->alevel->Mean))?>
            <?=Form::input('word', '')?>&nbsp;
            <button type="button" onclick="load_list_search()"><?=$lang->button->search?></button>
            </form>
            </td>
            <td width="100" align="right"><button type="button" onclick="load_list()"><?=$lang->button->list?></button></td>
        </tr>
        </table>

        <div id="pagination" class="pagination">[ 1 ]</div>
    </div>
</div>
