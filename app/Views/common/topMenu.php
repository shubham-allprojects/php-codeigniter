<tr>
    <td id="gnb">
    <table width="994" border="0" cellspacing="0" cellpadding="0" class="topmenubg">
            <tr>
                <td width="816">
                    <table width="816" border="0" cellpadding="0" cellspacing="0">
                        <tr>

                            <?php if (session()->get('spider_model') == MODEL_ESSENTIAL || session()->get('spider_model') == MODEL_ELITE || session()->get('spider_model') == MODEL_ENTERPRISE || session()->get('spider_model') == MODEL_TE_STANDALONE || session()->get('spider_model') == MODEL_TE_SERVER): ?>
                            <td width="5"></td>
                            <td width="3"><span class="topmenuline"></span></td>
                            <td width="5"></td>
                            <td width="47"><a href="<?=base_url()?>cardholder" class="topmenu menu03"
                                    data-submenu="submenu03"></a></td>
                            <td width="45"><a href="<?=base_url()?>camview" class="topmenu menu02" data-submenu="submenu02"></a>
                            </td>

                            <td width="45"><a href="<?=base_url()?>schedule" class="topmenu menu04" data-submenu="submenu04"></a>
                            </td>
                            <td width="45"><a href="<?=base_url()?>event" class="topmenu menu05" data-submenu="submenu05"></a>
                            </td>
                            <?php if (session()->get('spider_model') == MODEL_ELITE ||
    session()->get('spider_model') == MODEL_ENTERPRISE ||
    session()->get('spider_model') == MODEL_TE_STANDALONE ||
    session()->get('spider_model') == MODEL_TE_SERVER): ?>
                            <td width="49"><a href="<?=base_url()?>alevel" class="topmenu menu06" data-submenu="submenu06"></a>
                            </td>
                            <?php endif;?>
                            <td width="5"></td>
                            <td width="3"><span class="topmenuline"></span></td>
                            <td width="5"></td>
                            <td width="47"><a href="<?=base_url()?>door" class="topmenu menu07" data-submenu="submenu07"></a>
                            </td>
                            <td width="47"><a href="<?=base_url()?>userdefine" class="topmenu menu08" data-submenu="submenu08"></a>
                            </td>
                            <td width="45"><a href="<?=base_url()?>update" class="topmenu menu09" data-submenu="submenu09"></a>
                            </td>
                            <td width="45"><a href="<?=base_url()?>ipset" class="topmenu menu10" data-submenu="submenu10"></a>
                            </td>
                            <?php if (TARGET_BOARD != "EVB"): ?>
                            <td width="48"><a href="<?=base_url()?>cfloor" class="topmenu menu11" data-submenu="submenu11"></a>
                            </td>
                            <?php endif;?>
                            <td width="5"></td>
                            <td width="3"><span class="topmenuline"></span></td>
                            <td width="5"></td>
                            <td width="47"><a href="<?=base_url()?>dexport" class="topmenu menu12" data-submenu="submenu12"></a>
                            </td>
                            <td width="45"><a href="<?=base_url()?>logreport" class="topmenu menu13" data-submenu="submenu13"></a>
                            </td>
                            <td width="48"><a href="<?=base_url()?>report" class="topmenu menu14" data-submenu="submenu14"></a>
                            </td>
                            <td width="5"></td>
                            <td width="3"><span class="topmenuline"></span></td>
                            <td width="5"></td>
                            <td width="47"><a href="<?=base_url()?>groupuser" class="topmenu menu15" data-submenu="submenu15"></a>
                            </td>
                            <?php if (session()->get('spider_model') == MODEL_ELITE ||
    session()->get('spider_model') == MODEL_ENTERPRISE ||
    session()->get('spider_model') == MODEL_TE_STANDALONE ||
    session()->get('spider_model') == MODEL_TE_SERVER): ?>
                                <td width="48"><a href="<?=base_url()?>server-client" class="topmenu menu16" data-submenu="submenu16"></a></td>
                            <?php else: ?>
                                <?php if (TARGET_BOARD != "EVB"): ?>
                                <td width="48"><a href="<?=base_url()?>site" class="topmenu menu16" data-submenu="submenu16"></a>
                                </td>
                                <?php endif;?>
                            <?php endif;?>
                            <td width="5"></td>
                            <td width="3"><span class="topmenuline"></span></td>
                            <td width="5"></td>
                            <td width="50"><a href="<?=base_url()?>logout" class="topmenu menu17"
                                    data-submenu="submenu17"></a></td>
                             <?php else: ?>
                            <td width="812"></td>
                            <td width="50"><a href="<?=base_url()?>logout" class="topmenu menu17"
                                    data-submenu="submenu17"></a></td>
                        <?php endif;?>
                        </tr>
                    </table>
                </td>
                <td align="right"><span class="topmenulogo"></span></td>
                <td width="20" align="right"></td>
            </tr>
        </table>
        <div class="submenuwrap">
            <div id="submenu01" class="submenubg" data-submenu="submenu01">
                <a href="<?=base_url()?>alarm-map" class="submenu menu01"></a>
                <a href="/?c=alarm_set" class="submenu menu02"></a>
            </div>

            <div id="submenu03" class="submenubg" data-submenu="submenu03">
                <a href="<?=base_url()?>cardholder" class="submenu menu05"></a>
                <a href="<?=base_url()?>cardformat" class="submenu menu06"></a>
                <a href="<?=base_url()?>alevel" class="submenu menu07"></a>
            </div>

            <div id="submenu02" class="submenubg" data-submenu="submenu02">
                <? if( session()->get('spider_model') == MODEL_ELITE ||
		       session()->get('spider_model') == MODEL_ENTERPRISE
			   )  { ?>
                <a href="<?=base_url()?>camview" class="submenu menu03"></a>
                <a href="<?=base_url()?>camsetup" class="submenu menu04"></a>
                <? } ?>

                <? if(is_option(OPTION_VMS_DW)) { ?>
                <a href="<?=base_url()?>dvr-view" class="submenu menu52"></a>
                <a href="<?=base_url()?>dvr-setup" class="submenu menu53"></a>
                <? } ?>
                <a href="<?=base_url()?>nvr-viewer" class="submenu menu73"></a>
                <a href="<?=base_url()?>nvr-setup" class="submenu menu74"></a>

            </div>

            <div id="submenu04" class="submenubg" data-submenu="submenu04">
                <a href="<?=base_url()?>schedule" class="submenu menu08"></a>
                <a href="<?=base_url()?>holiday" class="submenu menu09"></a>
                <a href="<?=base_url()?>unlockschedule" class="submenu menu10"></a>
                <? if( $_SESSION['spider_model'] == MODEL_ELITE ||
		       $_SESSION['spider_model'] == MODEL_ENTERPRISE||
			   $_SESSION['spider_model'] == MODEL_TE_STANDALONE ||
			   $_SESSION['spider_model'] == MODEL_TE_SERVER) { ?>
                <a href="<?=base_url()?>onetime-unlock-schedule" class="submenu menu104"></a>
                <? } ?>
            </div>

            <div id="submenu05" class="submenubg" data-submenu="submenu05">
                <a href="<?=base_url()?>event" class="submenu menu62"></a>
                <a href="<?=base_url()?>event-code" class="submenu menu70"></a>
            </div>

            <div id="submenu06" class="submenubg" data-submenu="submenu06">
                <a href="<?=base_url()?>atlevel" class="submenu menu12"></a>
                <a href="<?=base_url()?>tlevel" class="submenu menu13"></a>
            </div>

            <div id="submenu07" class="submenubg" data-submenu="submenu07">
                <a href="<?=base_url()?>door" class="submenu menu14"></a>
                <? if( $_SESSION['spider_model'] == MODEL_ELITE ||
		       $_SESSION['spider_model'] == MODEL_ENTERPRISE||
			   $_SESSION['spider_model'] == MODEL_TE_STANDALONE ||
			   $_SESSION['spider_model'] == MODEL_TE_SERVER) { ?>
                <? if( TARGET_BOARD != "EVB") { ?>
                <a href="<?=base_url()?>elevator" class="submenu menu15"></a>
                <? } ?>
                <? } ?>
                <a href="<?=base_url()?>ainput" class="submenu menu16"></a>
                <a href="<?=base_url()?>aoutput" class="submenu menu17"></a>
                <? if( $_SESSION['spider_model'] == MODEL_ELITE ||
		       $_SESSION['spider_model'] == MODEL_ENTERPRISE||
			   $_SESSION['spider_model'] == MODEL_TE_STANDALONE ||
			   $_SESSION['spider_model'] == MODEL_TE_SERVER) { ?>
                <? if( TARGET_BOARD != "EVB") { ?>
                <a href="<?=base_url()?>elevator-action" class="submenu menu55"></a>
                <? } ?>
                <? } ?>
                <a href="<?=base_url()?>ctrl" class="submenu menu18"></a>
                <? if($_SESSION['spider_model'] == MODEL_ENTERPRISE||
		      $_SESSION['spider_model'] == MODEL_TE_STANDALONE ||
			  $_SESSION['spider_model'] == MODEL_TE_SERVER) { ?>
                <? if( TARGET_BOARD != "EVB") { ?>
                <a href="<?=base_url()?>region" class="submenu menu103"></a>
                <? } ?>
                <? } ?>
            </div>

            <div id="submenu08" class="submenubg" data-submenu="submenu08">
                <a href="<?=base_url()?>user-define" class="submenu menu20"></a>
                <a href="<?=base_url()?>user-role" class="submenu menu21"></a>
                <a href="<?=base_url()?>webuser" class="submenu menu22"></a>
            </div>

            <div id="submenu09" class="submenubg" data-submenu="submenu09">
                <a href="<?=base_url()?>update" class="submenu menu23"></a>
                <a href="<?=base_url()?>backup" class="submenu menu24"></a>
                <a href="<?=base_url()?>restore" class="submenu menu25"></a>
                <a href="<?=base_url()?>reset" class="submenu menu26"></a>
                <a href="<?=base_url()?>fdefault" class="submenu menu27"></a>
            </div>

            <div id="submenu10" class="submenubg" data-submenu="submenu10">
                <a href="<?=base_url()?>ipset" class="submenu menu28"></a>
                <a href="<?=base_url()?>ftp" class="submenu menu29"></a>

                <a href="<?=base_url()?>smtp" class="submenu menu32"></a>
                <a href="<?=base_url()?>timesvr" class="submenu menu33"></a>
                <? if(is_option(OPTION_RMR)) { ?>
                <a href="<?=base_url()?>rmc" class="submenu menu100"></a>
                <? } ?>
                <a href="<?=base_url()?>api" class="submenu menu107"></a>
            </div>

            <div id="submenu11" class="submenubg" data-submenu="submenu11">
                <? if( $_SESSION['spider_model'] == MODEL_ELITE ||
		       $_SESSION['spider_model'] == MODEL_ENTERPRISE||
			   $_SESSION['spider_model'] == MODEL_TE_STANDALONE ||
			   $_SESSION['spider_model'] == MODEL_TE_SERVER) { ?>
                <? if( TARGET_BOARD != "EVB") { ?>
                <a href="/?c=cfloor" class="submenu menu34"></a>
                <? } ?>
                <? } else { ?>
                <? if( TARGET_BOARD != "EVB") { ?>
                <a href="/?c=cfloor" class="submenu menu68"></a>
                <? } ?>
                <? } ?>
            </div>

            <div id="submenu12" class="submenubg" data-submenu="submenu12">
                <a href="/?c=dexport" class="submenu menu36"></a>
                <a href="/?c=dimport" class="submenu menu35"></a>
            </div>

            <div id="submenu13" class="submenubg" data-submenu="submenu13">
                <a href="/?c=log_report" class="submenu menu37"></a>
                <a href="/?c=log_viewer" class="submenu menu67"></a>
                <a href="/?c=logmanagement" class="submenu menu38"></a>
            </div>

            <div id="submenu14" class="submenubg" data-submenu="submenu14">
                <? if( $_SESSION['spider_model'] == MODEL_ENTERPRISE||
		       $_SESSION['spider_model'] == MODEL_TE_STANDALONE ||
			   $_SESSION['spider_model'] == MODEL_TE_SERVER ) { ?>
                <a href="/?c=report" class="submenu menu39"></a>
                <a href="/?c=access_report" class="submenu menu101"></a>
                <a href="/?c=systemreport" class="submenu menu71"></a>
                <a href="/?c=smartreport" class="submenu menu40"></a>
                <a href="/?c=smartreport_set" class="submenu menu54"></a>
                <? } else if( $_SESSION['spider_model'] == MODEL_ELITE ) { ?>
                <a href="/?c=report" class="submenu menu63"></a>
                <a href="/?c=access_report" class="submenu menu101"></a>
                <a href="/?c=systemreport" class="submenu menu71"></a>
                <? } else { ?>
                <a href="/?c=report" class="submenu menu69"></a>
                <a href="/?c=access_report" class="submenu menu101"></a>
                <a href="/?c=systemreport" class="submenu menu71"></a>
                <? } ?>
            </div>

            <div id="submenu15" class="submenubg" data-submenu="submenu15">
                <a href="/?c=groupuser" class="submenu menu41"></a>
                <a href="/?c=groupdoor" class="submenu menu42"></a>
                <? if( $_SESSION['spider_model'] == MODEL_ELITE ||
		       $_SESSION['spider_model'] == MODEL_ENTERPRISE||
			   $_SESSION['spider_model'] == MODEL_TE_STANDALONE ||
			   $_SESSION['spider_model'] == MODEL_TE_SERVER) { ?>
                <? if( TARGET_BOARD != "EVB") { ?>
                <a href="/?c=groupcamera" class="submenu menu102"></a>
                <? } ?>
                <? } ?>
                <a href="/?c=groupaccess" class="submenu menu45"></a>
            </div>

            <? if( $_SESSION['spider_model'] == MODEL_ENTERPRISE||
	       $_SESSION['spider_model'] == MODEL_TE_STANDALONE ||
		   $_SESSION['spider_model'] == MODEL_TE_SERVER ) { ?>
            <div id="submenu16" class="submenubg" data-submenu="submenu16">
                <a href="/?c=server_client" class="submenu menu48"></a>
                <a href="/?c=clientreplace" class="submenu menu56"></a>
                <? if( TARGET_BOARD != "EVB") { ?>
                <a href="/?c=site" class="submenu menu49"></a>
                <a href="/?c=sitedevice" class="submenu menu50"></a>
                <? } ?>
            </div>
            <? } else if ( $_SESSION['spider_model'] == MODEL_ELITE ) { ?>
            <div id="submenu16" class="submenubg" data-submenu="submenu16">
                <a href="/?c=server_client" class="submenu menu60"></a>
                <a href="/?c=clientreplace" class="submenu menu61"></a>
                <? if( TARGET_BOARD != "EVB") { ?>
                <a href="/?c=site" class="submenu menu49"></a>
                <? } ?>
            </div>
            <? } else if ( $_SESSION['spider_model'] == MODEL_ESSENTIAL ) { ?>
            <div id="submenu16" class="submenubg" data-submenu="submenu16">
                <? if( TARGET_BOARD != "EVB") { ?>
                <a href="/?c=site" class="submenu menu65"></a>
                <? } ?>
            </div>
            <? } ?>
            <div id="submenu17" class="submenubg" data-submenu="submenu17">
                <a href="<?=base_url()?>logout" class="submenu menu66"></a>
            </div>

        </div>

        <div id='alert-dialog-logout' class="hide">
            <div class='header'><span></span></div>
            <div class='message'></div>
            <div class='time' style="text-align:center"></div>
            <div class='buttons' style="width:350px; text-align:right;">
                <button type="button" onclick="clear_logout_timer(); $.modal.close();"
                    class="btn_large4 yes"><?=$lang->button->connect_stay?></button>
                <button type="button" onclick="$.modal.close(); window.location.href='<?=base_url()?>logout';"
                    class="btn_large4"><?=$lang->button->disconnect_now?></button>
            </div>
        </div>
    </td>
</tr>