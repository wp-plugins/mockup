jQuery(document).ready(function($) {

	$('a.set_mockup').click(function(e) {

		e.preventDefault();

		var custom_uploader = wp.media({
			multiple: false,
			title: mockupL10n.popup_title,
			button: {
				text: mockupL10n.popup_button
			}
		})

		.on('select', function() {		
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			$('.mockup_show').attr('src', attachment.url).removeClass('mockup_none');
			$('textarea.mockup_description').removeClass('mockup_none');
			$('input#mockup_id').val(attachment.id);
		})

		.open();
	});


	// Activate colorpicker
	$('.mockup-colorpicker').wpColorPicker();


	// Delete comment
	$('a.delete_comment').click(function(e) {

		e.preventDefault();

		mockupnonce = $('input#mockupnonce').val();
		commentid = $(this).attr('id');
		postid = $(this).attr('postid');
		hide = this;

		if(confirm(mockupL10n.confirm)) { 

			$.ajax({

				type: 'POST',
				url: ajaxurl,
				data: { 
					action: 'mockup_delete_comment',
					mockupnonce: mockupnonce,
					commentid: commentid,
					postid: postid
				},
				success: function() {
					$(hide).closest('tr').fadeOut();
				}
			});
		}
	})
});