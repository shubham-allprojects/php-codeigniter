<?php 

//global $log;

$log_img    = $this->conn->prepare("SELECT LogoFile FROM Site WHERE No=?");
$log_img->execute(array($_SESSION['spider_site']));
$log_img    = $log_img->fetchColumn();
?>
<script>
var Event = <? include LANGUAGE_DIR.'/en/eventcode.json' ?>;
</script>

<script type="text/javascript">
function create_list()
{
    $("#list_body").html("");

    for(var i=0; i<_data.list.length; i++)
    {
        if(_data.list[i]['No'] % 2){
            dom = "<tr id='log_"+_data.list[i]['No']+"' style='background:#ffffff'>";
        }else{
            dom = "<tr id='log_"+_data.list[i]['No']+"' style='background:#f4f4f4'>";
        }

/*		
        dom = dom + '   <td>'+ nullToBlank(_data.list[i].dd) +'</td>' +
            '   <td class="<?=($is_localtime_display ? '' : 'hide')?>">'+ nullToBlank(_data.list[i].LocalTime) +'</td>' +
            '   <td>'+ nullToBlank(_data.list[i].DeviceName) +'</td>' +
            '   <td>'+ nullToBlank(_data.list[i].UserName) +'</td>' +
            '   <td>'+ nullToBlank(_data.list[i].EventCode) +'</td>' +
            '   <td>'+ (_data.list[i].EventCode != "15606" ? nullToBlank(_data.list[i].EventDescription) : nullToBlank(_data.list[i].Message)) +'</td>' +
            '</tr>';
*/
        dom = dom + '   <td>'+ nullToBlank(_data.list[i].dd) +'</td>' +
            '   <td class="<?=($is_localtime_display ? '' : 'hide')?>">'+ nullToBlank(_data.list[i].LocalTime) +'</td>' +
            '   <td>'+ nullToBlank(_data.list[i].DeviceName) +'</td>' +
            '   <td>'+ nullToBlank(_data.list[i].UserName) +'</td>' +
            '   <td>'+ (_data.list[i].EventCode != "15606" ? nullToBlank(_data.list[i].EventDescription) : nullToBlank(_data.list[i].Message)) +'</td>' +
            '</tr>';

        $("#list_body").append(dom);
    }
    create_pagination();
}

$(document).ready(function() {  
    load_list();
});


// 권한 체크해서 등록 페이지 호출을 재정의 함.
<? if( $this->is_auth(74, 1) != TRUE ) { ?>
function open_new()
{
	alert("<?=$this->lang->user->error_not_permission?>");
}
<? } ?>

<? if( $this->is_auth(74, 2) != TRUE && $this->is_auth(74, 3) != TRUE) { ?>
function open_edit()
{
	alert("<?=$this->lang->user->error_not_permission?>");
}
<? } ?>

<? if( $this->is_auth(74, 2) != TRUE) { ?>	
function del_alevel()
{
	alert("<?=$this->lang->user->error_not_permission?>");
}
<? } ?>


</script>

<div id="location">
<?
echo $lang->menu->clog.'&nbsp;&gt;&nbsp;'.$lang->menu->logreport;
?>
	<button class="btn_help" onclick="openHelp('<?=$this->class?>', '<?=$lang->_lang?>')">Help</button>
</div>

<h2>:: <?=$lang->menu->clog?></h2>
<div id="list_section">
    <div class="box01">
    <table class="tbl_list">
        <tr>
            <th><?=$lang->menu->time?></th>
            <th class="<?=($is_localtime_display ? '' : 'hide')?>"><?=$lang->menu->localtime?></th>
            <th><?=$lang->menu->devicename?></th>
            <th><?=$lang->menu->user?></th>
			<!--
            <th><?=$lang->menu->eventcode?></th>
			-->
            <th><?=$lang->menu->eventdescription?></th>
        </tr>       
        <tbody id="list_body">
        </tbody>
    </table>
    </div>
</div>

<table class="list_button_set">
<tr>
	<td align="center">
		<button type="button" onclick="window.print()"><?=$lang->addmsg->print?></button>
	</td>
</tr>
</table>

<div id="pagination" class="pagination">[ 1 ]</div>

<div class="space" style="height:10px"></div>

