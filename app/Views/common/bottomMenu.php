<tr>
    <td id="bottom">
        <div style="position:absolute;"><iframe id="frame_exe" name="frame_exe" src="about:blank" width="100%"
                height="0" marginwidth="0" marginheight="0" frameborder="0" scrolling="no"></iframe></div>
        <div class="bottommenuwrap">

            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="bottommenubg">
                <tr>
                    <? if( $_SESSION['spider_model'] == MODEL_ESSENTIAL || $_SESSION['spider_model'] == MODEL_ELITE || $_SESSION['spider_model'] == MODEL_ENTERPRISE|| $_SESSION['spider_model'] == MODEL_TE_STANDALONE || $_SESSION['spider_model'] == MODEL_TE_SERVER ) { ?>
                    <td width="50%">
                        <table width="142" border="0" cellspacing="0" cellpadding="0" style="width:142px;">
                            <? } else { ?>
                            <td width="50%">
                                <table width="142" border="0" cellspacing="0" cellpadding="0" style="width:74px;">
                                    <? } ?>
                                    <tr>
                                        <? if( $_SESSION['spider_model'] == MODEL_ESSENTIAL || $_SESSION['spider_model'] == MODEL_ELITE || $_SESSION['spider_model'] == MODEL_ENTERPRISE|| $_SESSION['spider_model'] == MODEL_TE_STANDALONE || $_SESSION['spider_model'] == MODEL_TE_SERVER ) { ?>
                                        <td width="8" height="42"> </td>
                                        <td width="32"><a href="/?c=wizard"
                                                class="bottommenu <?=(service('router')->controllerName() == 'wizard' ? 'menu01-select' : 'menu01')?>"></a>
                                        </td>
                                        <td width="2"> </td>
                                        <td width="32"><a href="/?c=lostcard"
                                                class="bottommenu <?=(service('router')->controllerName() == 'lostcard' ? 'menu02-select' : 'menu02')?>"></a>
                                        </td>
                                        <td width="2"> </td>
                                        <td width="32"><a href="/?c=sitemap"
                                                class="bottommenu <?=(service('router')->controllerName() == 'sitemap' ? 'menu03-select' : 'menu03')?>"></a>
                                        </td>
                                        <td width="2"> </td>
                                        <td width="32"><a href="/?c=license"
                                                class="bottommenu <?=(service('router')->controllerName() == 'license' ? 'menu04-select' : 'menu04')?>"></a>
                                        </td>
                                        <? } else if( $_SESSION['spider_model'] == MODEL_CLIENT || $_SESSION['spider_model'] == MODEL_ELITE_CLIENT || $_SESSION['spider_model'] == MODEL_ENTERPRISE_CLIENT || $_SESSION['spider_model'] == MODEL_ELITE_ELEVATOR || $_SESSION['spider_model'] == MODEL_ENTERPRISE_ELEVATOR || $_SESSION['spider_model'] == MODEL_TE_CLIENT) { ?>
                                        <td width="8" height="42"> </td>
                                        <td width="32"><a href="/?c=license"
                                                class="bottommenu <?=(service('router')->controllerName() == 'license' ? 'menu04-select' : 'menu04')?>"></a>
                                        </td>
                                        <td width="2"> </td>
                                        <td width="32"><a href="/?c=client"
                                                class="bottommenu <?=(service('router')->controllerName() == 'client' ? 'menu05-select' : 'menu05')?>"></a>
                                        </td>
                                        <? } else { ?>
                                        <td width="8" height="42"> </td>
                                        <td width="32"><a href="/?c=license"
                                                class="bottommenu <?=(service('router')->controllerName() == 'license' ? 'menu04-select' : 'menu04')?>"></a>
                                        </td>
                                        <? } ?>
                                    </tr>
                                </table>
                            </td>
                            <td width="50%" align="right"><span class="bottommenulogo"></span></td>
                </tr>
            </table>
        </div>
    </td>
</tr>