<style>

#contents { width:771px; float:left; border:0; margin:0 0 16px 0; background:#713800; }

</style>

<script type="text/javascript">
function create_list()
{
    open_view(0);
}

$(document).ready(function() {
    load_list();
});

function edit_start()
{
    $("#view_section").hide();
    $("#edit_section").show();
    
    set_edit();
    
    $("#form_edit input[name='No']").val("1");
    $("#form_edit input[name='No']").val("1");
}

function edit_end()
{
    $("#view_section").show();
    $("#edit_section").hide();
}

// edit data ä���
function set_edit()
{
    set_edit_network('form_edit');
}

function set_edit_network(form)
{
    $.each(_current, function(name, value) {
        var element = $("#"+ form +" input[name='"+ name +"']");
        if( element.attr("type") == "checkbox" ){
            if( value == "1"){
                element.attr("checked", true);
            }else{
                element.attr("checked", false);
            }
        }else if( element.attr("type") == "radio" ){
            $("#"+ form +" input:radio[name='"+ name +"']").filter("input[value='"+ value +"']").attr('checked', true);
        }else{
            element.val(value);
        }

        if( $("#"+ form +" select[name='"+ name +"']").attr("multiple") == true )
            $("#"+ form +" select[name='"+ name +"']").val(eval(value));
        else
            $("#"+ form +" select[name='"+ name +"']").val(value);

    });

    enable_form_edit();
}

function enable_form_edit()
{
    if( $("#form_edit input:radio[name='IPType']:checked").val() == '1' )      
            flag = false;
    else    flag = true;

    $("#form_edit input[name='IPAddress']").attr("disabled", flag);
    $("#form_edit input[name='Subnet']").attr("disabled", flag);
    $("#form_edit input[name='Gateway']").attr("disabled", flag);
}


function confirm_restart()
{
	confirm_dialog("<?=$this->lang->common->reboot?>", '/?c=<?=$this->class?>&m=restart&no=1');
}

</script>

<script type="text/javascript">
function submit_send()
{
	var form		= $('#form_send');
	var type        = form.attr('method');
	var url         = form.attr('action');
	var data        = form.serialize();

	$.modal.close();
	
    $.ajax({
        type        : type,
        url         : url,
        data        : data,
        success     : function(response) { try{ eval(response); } catch(e) { alert("The connection to the server has a problem. Please try again later."); hide_loading(); } },
        beforeSend  : function() { show_loading(); },
        complete    : function() { hide_loading(); }
        //error       : function() { alert("The connection to the server has a problem. Please try again later."); }
    });
}

function confirm_send()
{
	confirm_dialog("<?=$this->lang->common->fdefault?>", '/?c=<?=$this->class?>&m=fdefault');
}

function confirm_dialog(message, action, callback) {
	$('#confirm-dialog').modal({
		closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>",
		position: ["20%",],
		overlayId: 'confirm-overlay',
		containerId: 'confirm-container', 
		containerCss: {
			height: 230
		},
		onShow: function (dialog) {
			var modal = this;

			$('form', dialog.data[0]).attr('action', action);
			$('.message', dialog.data[0]).append(message);
/*
			// if the user clicks "yes"
			$('.yes', dialog.data[0]).click(function () {
				// call the callback
				if ($.isFunction(callback)) {
					callback.apply();
				}
				// close the dialog
				modal.close(); // or $.modal.close();
			});
*/
		}
	});
}


// ���� üũ�ؼ� ��� ������ ȣ���� ������ ��.
<? if( $this->is_auth(71, 11) != TRUE ) { ?>
function confirm_send()
{
	alert("<?=$this->lang->user->error_not_permission?>");
}
<? } ?>
</script>

<!-- modal content -->
<div id='confirm-dialog'>
	<form id="form_send" method="post" action="/?c=<?=$this->class?>&m=fdefault" onsubmit="submit_send(); return false;">
	<div class='header'><span></span></div>
	<div class='message'></div>
	<div style="text-align:center">
		<?=$lang->user->Password?> *:
		<input type="password" name="confirm_pw" value="">
	</div>
	<div class='buttons'>
		<button type="button" onclick="submit_send();" class="yes"><?=$lang->button->ok?></button>
		<button type="button" onclick="$.modal.close()"><?=$lang->button->close?></button>
	</div>
	</form>
</div>

<div style="height:35px; padding-left:690px;>
	<a href="<?=$_SERVER['HTTP_REFERER']?>" class="client-close"></a>
	<a href="/?c=user&m=logout" class="submenu menu66"></a>
</div>

<table>
<tr>
	<td width="6" height="3" background="/img/menu/quick_window_top_left.png"></td>
	<td bgcolor="#ffffff"></td>
	<td width="6" height="3" background="/img/menu/quick_window_top_right.png"></td>
</tr>
<tr>
	<td bgcolor="#ffffff"></td>
	<td height="500" bgcolor="#ffffff" style="padding-left:30px;">
		<div class="client-location">
			<img src="/img/menu/icon_info.png" valign="absmiddle">
			<span id="client-location-text"></span>
		</div>
		
        <div id="view_section">
            <h2>:: <?=$lang->client->Client?></h2>
            <div class="box01">
	            <form id="form_view">
	                <h3><?=$lang->client->Network?></h3>
	                <table class="tbl_view">
			        <tr>
			            <th width="150"><?=$lang->network->IPType?> *</th>
			            <td width="1">:</td>
			            <td id="view_IPTypeStr"></td>
			        </tr>
			        <tr>
			            <th><?=$lang->network->IPAddress?> *</th>
			            <td width="1">:</td>
			            <td id="view_IPAddress"></td>
			        </tr>
			        <tr>
			            <th><?=$lang->network->Subnet?> *</th>
			            <td width="1">:</td>
			            <td id="view_Subnet"></td>
			        </tr>
			        <tr>
			            <th><?=$lang->network->Gateway?> *</th>
			            <td width="1">:</td>
			            <td id="view_Gateway"></td>
			        </tr>
			        <tr>
			            <th><?=$lang->network->DNS1?></th>
			            <td width="1">:</td>
			            <td id="view_DNS1"></td>
			        </tr>
			        <tr>
			            <th><?=$lang->network->DNS2?></th>
			            <td width="1">:</td>
			            <td id="view_DNS2"></td>
			        </tr>
			        <tr>
			            <th><?=$lang->network->HTTPPort?></th>
			            <td width="1">:</td>
			            <td id="view_HTTPPort"></td>
			        </tr>
			        </table>
			        
			        <div class="space" style="height:5px"></div>
			        
			        <h3><?=$lang->client->Server?></h3>
	                <table class="tbl_view">
	                <tr>
	                    <th width="150"><?=$lang->client->ServerIP?> *</th>
	                    <td width="1">:</td>
	                    <td id="view_ServerIP"></td>
	                </tr>
	                <tr>
	                    <th width="150"><?=$lang->client->ServerFTP?> *</th>
	                    <td width="1">:</td>
	                    <td id="view_ServerFTP"></td>
	                </tr>
	                </table>
	                
	                <div class="space" style="height:5px"></div>
	                
	                <h3><?=$lang->client->ID?></h3>
	                <table class="tbl_view">
	                <tr>
	                    <th width="150"><?=$lang->client->ID?> *</th>
	                    <td width="1">:</td>
	                    <td id="view_ID"></td>
	                </tr>
	                </table>
	                
	                <div class="button_set">
	                    <button type="button" onclick="edit_start();"><?=$lang->button->edit?></button>
	                    <button type="button" onclick="confirm_restart()"><?=$lang->menu->reboot?></button>
<? if( $this->is_auth(71, 11) != TRUE) { ?>
						<button type="button" onclick="alert('<?=$this->lang->user->error_not_permission?>');" class="btn_large4"><?=$lang->menu->fdefault?></button>
<? } else { ?>
						<button type="button" onclick="confirm_send()" class="btn_large4"><?=$lang->menu->fdefault?></button>
<? } ?>		
	                </div>
	            </form>
            </div>
        </div>
        
        <div id="edit_section" class="hide">
            <h2>:: <?=$lang->menu->client?></h2>
            <div class="box01">
                <form id="form_edit" method="post" action="/?c=<?=$this->class?>&m=update">
                    <?=Form::hidden("No")?>
                    <?=Form::hidden("PrevID")?>
                    <?=Form::hidden("Password")?>
                    <h3><?=$lang->client->Network?></h3>
               		<table class="tbl_view">
		            <tr>
		                <th width="150"><?=$lang->network->IPType?> *</th>
		                <td width="1">:</td>
		                <td><?=Form::radio('IPType', '', EnumTable::$attrIPType, '&nbsp;&nbsp;', array('onclick'=>'enable_form_edit()'))?></td>
		            </tr>
		            <tr>
		                <th><?=$lang->network->IPAddress?> *</th>
		                <td width="1">:</td>
		                <td><?=Form::input('IPAddress')?></td>
		            </tr>
		            <tr>
		                <th><?=$lang->network->Subnet?> *</th>
		                <td width="1">:</td>
		                <td><?=Form::input('Subnet')?></td>
		            </tr>
		            <tr>
		                <th><?=$lang->network->Gateway?> *</th>
		                <td width="1">:</td>
		                <td><?=Form::input('Gateway')?></td>
		            </tr>
		            <tr>
		                <th><?=$lang->network->DNS1?></th>
		                <td width="1">:</td>
		                <td><?=Form::input('DNS1')?> (<?=$lang->menu->optional?>)</td>
		            </tr>
		            <tr>
		                <th><?=$lang->network->DNS2?></th>
		                <td width="1">:</td>
		                <td><?=Form::input('DNS2')?> (<?=$lang->menu->optional?>)</td>
		            </tr>
		            <tr>
		                <th><?=$lang->network->HTTPPort?></th>
		                <td width="1">:</td>
		                <td><?=Form::inputnum('HTTPPort')?> (<?=$lang->menu->default80?>)</td>
		            </tr>
		            </table>
			        <div class="space" style="height:5px"></div>
                    <h3><?=$lang->client->Server?></h3>
                    <table class="tbl_view">
                    <tr>
                        <th width="150"><?=$lang->client->ServerIP?> *</th>
                        <td width="1">:</td>
                        <td><?=Form::input('ServerIP')?></td>
                    </tr>
                    <tr>
                        <th width="150"><?=$lang->client->ServerFTP?> *</th>
                        <td width="1">:</td>
                        <td><?=Form::inputnum('ServerFTP')?> (<?=$lang->menu->defaultPort?>)</td>
                    </tr>
                    </table>
                    
                    <div class="space" style="height:5px"></div>
                    
	                <h3><?=$lang->client->ID?>, <?=$lang->client->Password?></h3>
	                <table class="tbl_view">
	                <tr>
	                    <th width="150"><?=$lang->client->ID?> *</th>
	                    <td width="1">:</td>
	                    <td><?=Form::input('ID')?></td>
	                </tr>
	                <tr>
	                    <th width="150"><?=$lang->client->Password?> *</th>
	                    <td width="1">:</td>
	                    <td><?=Form::password('PrevPassword', "", array("MAXLENGTH"=>"12"))?></td>
	                </tr>
	                </table>
            
                    <div class="button_set">
                        <button type="button" onclick="$('#form_edit').submit()"><?=$lang->button->save?></button>&nbsp;&nbsp;
                        <button type="button" onclick="edit_start()"><?=$lang->button->reset?></button>&nbsp;&nbsp;
                        <button type="button" onclick="edit_end()"><?=$lang->button->cancel?></button>
                    </div>
                </form>
            </div>
        </div>
	</td>
	<td bgcolor="#ffffff"></td>
</tr>
<tr>
	<td width="6" height="3" background="/img/menu/quick_window_bottom_left.png"></td>
	<td bgcolor="#ffffff"></td>
	<td width="6" height="3" background="/img/menu/quick_window_bottom_right.png"></td>
</tr>
</table>
