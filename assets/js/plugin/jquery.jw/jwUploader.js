
(function ($) {
	var jwUploader = function() {
		return {
			init : function(options) {
				options = $.extend({
					id				: jQuery(this).attr("id"),
					uploader		: "jwUploader_" + jQuery(this).attr("id"),
					uploaderMovie	: "FileUploader.swf",
					script			: "upload.php",
					width			: jQuery(this).innerWidth(),
					height			: jQuery(this).innerHeight(),
					fileDataName	: "Filedata",
					queueSizeLimit	: 999,
					multi			: false,
					auto			: true,
					fileDesc		: '',
					fileExt			: '',
					sizeLimit		: 2 * 1024 * 1024,
					buttonText		: '',
					onInit			: function() {},
					onSelect		: function() {},
					onQueueFull		: function() {},
					onSizeLimit		: function() {},
					onCancel		: function() {},
					onError			: function() {},
					onProgress		: function() {},
					onComplete		: function() {},
					onAllComplete	: function() {}
				}, options||{});

				jQuery(this).before('<div id="'+ options.uploader +'"></div>');

				jQuery("#" + options.uploader).flash({
					'src'				: options.uploaderMovie,
					'width'				: options.width,
					'height'			: options.height,
					'allowfullscreen'	: 'false',
					'allowscriptaccess'	: 'always',
					'wmode'				: 'transparent',
					'flashvars'			: {
						'id'				: options.id,
						'script'			: options.script,
						'width'				: options.width,
						'height'			: options.height,
						'fileDataName'		: options.fileDataName,
						'queueSizeLimit'	: options.queueSizeLimit,
						'multi'				: options.multi,
						'auto'				: options.auto,
						'fileDesc'			: options.fileDesc,
						'fileExt'			: options.fileExt,
						'sizeLimit'			: options.sizeLimit,
						'buttonText'		: options.buttonText
					}
				});

				jQuery("#" + options.uploader).css({
					'position'	: 'absolute'
				});

				jQuery(this).bind("reposition", function() {
					var position = jQuery(this).offset();
					jQuery("#" + options.uploader).css({
						'left'	: position.left,
						'top'	: position.top
					});
				});

				jQuery(window).bind('resize', {'target':jQuery(this)}, function(event) {
					event.data.target.trigger('reposition');
				});

				jQuery(this).trigger('reposition');

				jQuery(this).bind("onInit",			function(event) { options.onInit(event); });
				jQuery(this).bind("onSelect",		function(event, index, file) { options.onSelect(event, index, file); });
				jQuery(this).bind("onQueueFull",	function(event) { if( options.onQueueFull(event) != false ) alert("멀티업로드는 "+ options.queueSizeLimit +"개까지 가능합니다."); });
				jQuery(this).bind("onSizeLimit",	function(event, index, file) { if( options.onSizeLimit(event, index, file) != false ) alert("파일용량은 "+ options.sizeLimit +"bytes까지 가능합니다."); });
				jQuery(this).bind("onCancel",		function(event, index, file) { options.onCancel(event, index, file); });
				jQuery(this).bind("onError",		function(event) { options.onError(event); });
				jQuery(this).bind("onProgress",		function(event, index, file, data) { options.onProgress(event, index, file, data); });
				jQuery(this).bind("onComplete",		function(event, index, file, response) { options.onComplete(event, index, file, response); });
				jQuery(this).bind("onAllComplete",	function(event, data) { options.onAllComplete(event, data); });

				jQuery(this).bind("upload",	function() {
					if( navigator.userAgent.indexOf("Microsoft") != -1 )		uploader = window[options.uploader];
					else if( navigator.userAgent.indexOf("Chrome") != -1 )		uploader = document.getElementById( options.uploader );
					else if( navigator.userAgent.indexOf("Safari") != -1 )		uploader = document.getElementById( options.uploader );
					else														uploader = document[options.uploader];
					uploader.uploadFile();
				});
				jQuery(this).bind("cancel",	function() {
					if( navigator.userAgent.indexOf("Microsoft") != -1 )		uploader = window[options.uploader];
					else if( navigator.userAgent.indexOf("Chrome") != -1 )		uploader = document.getElementById( options.uploader );
					else if( navigator.userAgent.indexOf("Safari") != -1 )		uploader = document.getElementById( options.uploader );
					else														uploader = document[options.uploader];
					uploader.cancelUpload();
				});
				jQuery(this).bind("clear",	function() {
					if( navigator.userAgent.indexOf("Microsoft") != -1 )		uploader = window[options.uploader];
					else if( navigator.userAgent.indexOf("Chrome") != -1 )		uploader = document.getElementById( options.uploader );
					else if( navigator.userAgent.indexOf("Safari") != -1 )		uploader = document.getElementById( options.uploader );
					else														uploader = document[options.uploader];
					uploader.clearQueue();
				});
			}
		}
	}();
	$.fn.extend({
		jwUploader: jwUploader.init
	});
})(jQuery);
