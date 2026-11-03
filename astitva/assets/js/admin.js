/* Theme Options: color pickers and media fields. */
jQuery(function ($) {
	$('.agi-color').wpColorPicker();

	$('.agi-media-pick').on('click', function () {
		var wrap = $(this).closest('.agi-media');
		var frame = wp.media({ multiple: false, library: { type: 'image' } });
		frame.on('select', function () {
			var file = frame.state().get('selection').first().toJSON();
			wrap.find('input').val(file.id);
			wrap.find('img').attr('src', file.url).show();
		});
		frame.open();
	});

	$('.agi-media-clear').on('click', function () {
		var wrap = $(this).closest('.agi-media');
		wrap.find('input').val('');
		wrap.find('img').attr('src', '').hide();
	});
});
