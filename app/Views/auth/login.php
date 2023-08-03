<!DOCTYPE html>
<html lang='en'>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="Expires" content="0"/> 
    <title>Linear eMerge</title>
    <link rel="shortcut icon" href="<?PHP echo base_url(); ?>assets/img/emerge.ico">
    <link rel="stylesheet" href="<?PHP echo base_url(); ?>assets/css/common.css" type="text/css" />
    <link rel="stylesheet" href="<?PHP echo base_url(); ?>assets/css/language/en.css" type="text/css" />
    <link href="<?PHP echo base_url(); ?>assets/css/custom.css" rel="stylesheet" />
</head>

<body class="login">
	<table class="login" id="login_box" style="width:274px; table-layout:fixed">
		<div>
		    <td>
		        <img src="<?PHP echo base_url(); ?>assets/img/logo.png" width="179px" height="72px" align="center" hspace="47">
		    </td>
		</div>
		<div>
		    <tr>
		    	<td width="274px" height="31px" style="background:url('<?PHP echo base_url(); ?>assets/img/login_window_top.png');">
		    		<img src="<?PHP echo base_url(); ?>assets/img/login_window_login.png" alt="" width="48px" height="26px" style="float:left;">
		    	</td>
		    </tr>
		</div>
		<div>
	    	<td style="background:url('<?PHP echo base_url(); ?>assets/img/login_window_middle.png');">
	        	<form 
                    id="loginForm" 
                    method="post" 
                    action="<?PHP echo base_url(); ?>api/login" 
                    onsubmit="return postRequestViaAjax(event, handleApiResponse);"
                >
	        		<table class="login_table">
	        			<div height="15">
		            		<tr height="15"></tr>		            		
						</div>
						<?PHP if(($spider_model == MODEL_ENTERPRISE || session()->get('spider_model') == MODEL_TE_STANDALONE || session()->get('spider_model') == MODEL_TE_SERVER) && count($arr_site) > 1) { ?>
	        			<div height="25">
		            		<tr height="25">
		            			<td width="25">&nbsp;</td>   
		                   		<td class="Title">Company</td>
		            		    <td width="5">&nbsp;</td>
		                        <td class="Data"><?=Form::select('login_site', '', $arr_site, TRUE) ?></td>
		                    </tr>
		                </div>
		            	<div height="2">
		            		<tr height="2"></tr>
						</div>
						<?PHP } ?>
		                <div height="25">
		            		<tr height="25">
		            			<td width="25">&nbsp;</td>
		            			<td class="Title">User ID</td>
		            			<td width="5">&nbsp;</td>
		            			<td class="Data"><?=Form::input('login_id', "", array("width"=>"20")); ?></td>
		            		</tr>
		            	</div>
		            	<div height="2">
		            		<tr height="2"></tr>
						</div>
	                    <div height="25">
		            		<tr height="25">
		            			<td width="25">&nbsp;</td>
		            			<td class="Title">Password</td>
		            		    <td width="5">&nbsp;</td>
		            			<td class="Data"><?=Form::password('login_pw', "", array("SIZE"=>"20")); ?></td>
		            		</tr>
		            	</div>
	                	<div height="10">
		            		<tr height="10"></tr>
						</div>
					</table>
					<table>						
	            		<div height="33">
		            		<tr>
		                        <td align="center">
		                        	<button class="btn_login" type="submit">
		                        </td>
		                    </tr>
		                </div>
	                	<div height="10">
		            		<tr height="10"></tr>		            		
						</div>

	        		</table>

                    <a href="javascript:forgot_password();">Forgot your password?</a>
	        	</form>
	        </td>
	    </div>
	    <div>
		    <tr height="7">
		    	<td style="background:url('<?PHP echo base_url(); ?>assets/img/login_window_bottom.png');"></td>
		    </tr>
		</div>
	</table>


<script type="text/javascript" src="<?PHP echo base_url(); ?>assets/js/jquery-3.7.0.min.js" charset="utf-8"></script>
<!-- <script type="text/javascript" src="<?PHP echo base_url(); ?>assets/js/jquery.browser.min.js" charset="utf-8"></script>

<link rel="stylesheet" href="<?PHP echo base_url(); ?>assets/js/plugin/jquery.datepicker/css/datepicker.css" type="text/css" />
<script type="text/javascript" src="<?PHP echo base_url(); ?>assets/js/plugin/jquery.datepicker/js/datepicker.js" charset="utf-8"></script>

<script type="text/javascript" src="<?PHP echo base_url(); ?>assets/js/plugin/jquery.simplemodal/jquery.simplemodal-1.3.5.min.js" charset="utf-8"></script>

<script type="text/javascript" src="<?PHP echo base_url(); ?>assets/js/plugin/jquery.flash/jquery.flash.min_.js" charset="utf-8"></script>
<script type="text/javascript" src="<?PHP echo base_url(); ?>assets/js/plugin/jquery.jw/jwUploader.js" charset="utf-8"></script>
<script type="text/javascript" src="<?PHP echo base_url(); ?>assets/js/plugin/sprintf-0.6.js" charset="utf-8"></script> -->
<script type="text/javascript" src="<?PHP echo base_url(); ?>assets/js/plugin/jquery.simplemodal/jquery.simplemodal-1.4.4.min.js" charset="utf-8"></script>
<script src="<?PHP echo base_url(); ?>assets/js/custom.js"></script>
<script src="<?PHP echo base_url(); ?>assets/js/ajax/public.js"></script>

<script type="text/javascript">
    function handleApiResponse(response){
		if(response.status === 200){
			window.location.href = "<?PHP echo base_url(); ?>";
		}
    }

	function forgot_password()
	{
		var login_id = $("#loginForm input[name=login_id]").val();
		
		if( login_id == '' || login_id == undefined ) {
			alert("Enter User ID");
		} else {
			$.ajax({
				method: "post",
				dataType: "script",
				url: "<?=base_url()?>forgot-password",
				data: "login_id=" + login_id,
				success: function(data) {
				}
			});
		}
	}
</script>

</body>
</html>