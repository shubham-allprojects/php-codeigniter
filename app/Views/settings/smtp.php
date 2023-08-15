<div id="location">
<?
echo $lang->menu->networks.'&nbsp;&gt;&nbsp;'.$lang->menu->smtp;
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

// edit data 채우기
function set_edit()
{
    set_edit_network('form_edit4');
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
    if( $("#form_edit4 input[name='SMTPEnable']").attr("checked") )   
            flag = false;
    else    flag = true;

    $("#form_edit4 input[name='SMTPServer']").attr("disabled", flag);
    $("#form_edit4 input[name='SMTPID']").attr("disabled", flag);
    $("#form_edit4 input[name='SMTPPassword']").attr("disabled", flag);
    $("#form_edit4 input[name='SMTPSend']").attr("disabled", flag);
	$("#form_edit4 input[name='SMTPNumber']").attr("disabled", flag);
	$("#form_edit4 input[name='SMTPTTL']").attr("disabled", flag);
	$("#form_edit4 input[name='SMTPSendTo']").attr("disabled", flag);
}

</script>


<div id="edit_section" class="hide">
    <h2>:: <?=$lang->menu->smtp?></h2>
    <div class="box01">

        <form id="form_edit4" method="post" action="/?c=<?=$this->class?>&m=update4">
        <?=Form::hidden("No")?>
        <?=Form::hidden("mode")?>
        <h3><?=$lang->menu->basic?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->network->SMTPEnable?> </th>
            <td width="1">:</td>
            <td><?=Form::checkbox('SMTPEnable', '', FALSE, '1', array('onclick'=>'enable_form_edit4()'))?></td>
        </tr>
        <tr>
            <th><?=$lang->network->SMTPServer?> </th>
            <td width="1">:</td>
            <td><?=Form::input('SMTPServer','',array("style"=>"width:300px"))?></td>
        </tr>
        <tr>
            <th><?=$lang->network->SMTPNumber?> </th>
            <td width="1">:</td>
            <td><?=Form::inputnum('SMTPNumber','', array("MAXLENGTH"=>"5"))?> (<?=$lang->menu->default587?>)</td>
        </tr>
        <tr>
            <th><?=$lang->network->SMTPTTL?> </th>
            <td width="1">:</td>
            <td><?=Form::checkbox('SMTPTTL', '', FALSE, '1')?> Used</td>
        </tr>
        <tr>
            <th><?=$lang->network->SMTPID?> </th>
            <td width="1">:</td>
            <td><?=Form::input('SMTPID')?></td>
        </tr>
        <tr>
            <th><?=$lang->network->SMTPPassword?> </th>
            <td width="1">:</td>
            <td><?=Form::password('SMTPPassword')?></td>
        </tr>
        <tr>
            <th><?=$lang->network->SMTPSendTo?> </th>
            <td width="1">:</td>
            <td><?=Form::input('SMTPSendTo','',array("style"=>"width:300px"))?> <span id="card_btn"><input size="30" type="button" onclick="$('#form_edit4 input[name=mode]').val('test'); $('#form_edit4').submit();" value='Test' /></span></td>
        </tr>
        </table>

        <div class="button_set">
            <button type="button" onclick="$('#form_edit4 input[name=mode]').val('save'); $('#form_edit4').submit();"><?=$lang->button->save?></button>&nbsp;&nbsp;
            <button type="button" onclick="open_edit(_seq)"><?=$lang->button->reset?></button>&nbsp;&nbsp;
            <button type="button" onclick="close_edit()"><?=$lang->button->cancel?></button>
        </div>
        </form>
    </div>
</div>


<div id="view_section" class="hide">
    <h2>:: <?=$lang->menu->smtp?></h2>
    <div class="box01">
        <h3><?=$lang->menu->basic?></h3>
        <table class="tbl_view">
        <tr>
            <th width="150"><?=$lang->network->SMTPEnable?> </th>
            <td width="1">:</td>
            <td id="view_SMTPEnableStr"></td>
        </tr>
        <tr>
            <th><?=$lang->network->SMTPServer?> </th>
            <td width="1">:</td>
            <td id="view_SMTPServer"></td>
        </tr>
        <tr>
            <th><?=$lang->network->SMTPNumber?> </th>
            <td width="1">:</td>
            <td id="view_SMTPNumber"></td>
        </tr>
        <tr>
            <th><?=$lang->network->SMTPTTL?> </th>
            <td width="1">:</td>
            <td id="view_SMTPTTLStr"></td>
        </tr>
        <tr>
            <th><?=$lang->network->SMTPID?> </th>
            <td width="1">:</td>
            <td id="view_SMTPID"></td>
        </tr>
        <tr>
            <th><?=$lang->network->SMTPSendTo?> </th>
            <td width="1">:</td>
            <td id="view_SMTPSendTo"></td>
        </tr>
        </table>

        <div class="button_set">
<? if( $this->is_auth(98, 3) != TRUE) { ?>
            <button type="button" onclick="alert('<?=$this->lang->user->error_not_permission?>');"><?=$lang->button->edit?></button>
<? } else { ?>
            <button type="button" onclick="open_edit(_seq);"><?=$lang->button->edit?></button>
<? } ?>        
        </div>
    </div>
</div>


