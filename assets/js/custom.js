function layout_center(layerId)
{
	$("#"+layerId).css({
		'position'	: 'absolute',
		'top'		: (($(document).height() - $("#"+layerId).height()) / 2) + "px",
		'left'		: (($(document).width() - $("#"+layerId).width()) / 2) + "px"
	});
}

$(document).ready(function() 
{
	$(window).resize(function() 
	{
		layout_center("login_box")
	});
	layout_center("login_box");
});


function showLoader()
{
	try
	{
		$.modal('<div><img src="assets/img/loading.gif" width="200" /></div>', {
			overlayCss		: {backgroundColor:"#000"},
			overlayClose	: false
		});
	}
	catch (e)
	{
	}
}

// 로딩창 닫기
function hideLoader()
{
	try
	{
		$.modal.close();
	}
	catch (e)
	{
	}
}
