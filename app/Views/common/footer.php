</div>
</td>
</tr>
</tbody>
</table>
<?=view('common/bottomMenu')?>

</tbody>
</table>

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