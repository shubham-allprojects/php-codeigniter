<tr>
    <td id="bottom">
        <div style="position:absolute;"><iframe id="frame_exe" name="frame_exe" src="about:blank" width="100%"
                height="0" marginwidth="0" marginheight="0" frameborder="0" scrolling="no"></iframe></div>
        <div class="bottommenuwrap">

            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="bottommenubg">
                <tr>
                    <? if( $_SESSION['spider_model'] == ConstTable::MODEL_ESSENTIAL || $_SESSION['spider_model'] == ConstTable::MODEL_ELITE || $_SESSION['spider_model'] == ConstTable::MODEL_ENTERPRISE|| $_SESSION['spider_model'] == ConstTable::MODEL_TE_STANDALONE || $_SESSION['spider_model'] == ConstTable::MODEL_TE_SERVER ) { ?>
                    <td width="50%">
                        <table width="142" border="0" cellspacing="0" cellpadding="0" style="width:142px;">
                            <? } else { ?>
                            <td width="50%">
                                <table width="142" border="0" cellspacing="0" cellpadding="0" style="width:74px;">
                                    <? } ?>
                                    <tr>
                                        <? if( $_SESSION['spider_model'] == ConstTable::MODEL_ESSENTIAL || $_SESSION['spider_model'] == ConstTable::MODEL_ELITE || $_SESSION['spider_model'] == ConstTable::MODEL_ENTERPRISE|| $_SESSION['spider_model'] == ConstTable::MODEL_TE_STANDALONE || $_SESSION['spider_model'] == ConstTable::MODEL_TE_SERVER ) { ?>
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
                                        <? } else if( $_SESSION['spider_model'] == ConstTable::MODEL_CLIENT || $_SESSION['spider_model'] == ConstTable::MODEL_ELITE_CLIENT || $_SESSION['spider_model'] == ConstTable::MODEL_ENTERPRISE_CLIENT || $_SESSION['spider_model'] == ConstTable::MODEL_ELITE_ELEVATOR || $_SESSION['spider_model'] == ConstTable::MODEL_ENTERPRISE_ELEVATOR || $_SESSION['spider_model'] == ConstTable::MODEL_TE_CLIENT) { ?>
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

</table>

<?PHP echo view('common/js'); ?>

<script type="text/javascript" charset="utf-8">
                $(function() {
                    $(".topmenu").bind("click", function(event) {
                        event.preventDefault();
                        var submenu = "#" + $(this).data("submenu");
                        if ($(this).hasClass("hover")) {
                            $(submenu).hide();
                            $(this).removeClass("hover");
                        } else {
                            $(".submenubg").hide();
                            $(".topmenu").removeClass("hover");
                            var min = $(".topmenubg > tbody").offset().left;
                            var max = min + $(".topmenubg > tbody").outerWidth();
                            var offset = $(this).offset();
                            var width = $(submenu).outerWidth();
                            var left = offset.left - (width / 2) + 24;
                            if (left < min) left = min;
                            if ((left + width) > max) left = max - width;
                            $(submenu).css({
                                top: 0,
                                left: left,
                                display: 'inline-block'
                            });
                            $(this).addClass("hover");
                        }
                    });
                });
            </script>

            <script type="text/javascript" charset="utf-8">
                function open_alert_logout(message, callback) {
                    $('#alert-dialog-logout').modal({
                        closeHTML: "<a href='#' title='Close' class='modal-close'></a>",
                        position: ["20%", ],
                        overlayId: 'confirm-overlay',
                        containerId: 'confirm-container-logout',
                        onShow: function(dialog) {
                            var modal = this;
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
                    $(".message", $("#alert-dialog-logout")).load('/?c=layout&m=msg_timeout');
                    handle_logout_timer = setTimeout(function() {
                        window.location.href = '/?c=user&m=logout';
                    }, (1000 * 60 * 5));
                    //logout_timer(60*5);
                    //logout_timer(10);
                }
                var handle_logout_timer = null;

                function logout_timer(t) {
                    var min = parseInt(t / 60);
                    var sec = parseInt(t % 60);
                    $(".time", $("#alert-dialog-logout")).text(sprintf("%02d:%02d", min, sec));
                    if (t > 0) {
                        t--;
                        clear_logout_timer();
                        handle_logout_timer = setTimeout(function() {
                            logout_timer(t)
                        }, 1000);
                    } else {
                        window.location.href = '/?c=user&m=logout';
                    }
                }

                function clear_logout_timer() {
                    if (handle_logout_timer != null) {
                        clearTimeout(handle_logout_timer);
                        handle_logout_timer = null;
                    }
                }
            </script>
            

</body>
</html>