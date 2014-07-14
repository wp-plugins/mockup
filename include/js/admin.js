jQuery(document).ready(function($) {

	// Set new MockUp image
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

			mockupnonce = $('input#mockupnonce_images').val();
			postid = $('a.set_mockup').attr('postid');
			mockup_id = attachment.id;

			$.ajax({

				type: 'POST',
				url: ajaxurl,
				data: { 
					action: 'mockup_set_image',
					mockupnonce: mockupnonce,
					postid: postid,
					mockup_id: mockup_id
				},
				success: function(data) {

					$('div.mockup_img').html(data);
					$('p.mockup_show').toggle();
					$('p.mockup_hide').toggle();
					$('input#mockup_id').val(attachment.id);
				}
			});
		})

		.open();
	});


	// Delete MockUp Image
	$('a.delete_mockup').click(function(e) {

		e.preventDefault();

		mockupnonce = $('input#mockupnonce_images').val();
		postid = $(this).attr('postid');
		mockup_id = $('input#mockup_id').val();

		$.ajax({

			type: 'POST',
			url: ajaxurl,
			data: { 
				action: 'mockup_delete_image',
				mockupnonce: mockupnonce,
				postid: postid,
				mockup_id: mockup_id
			},
			success: function() {

				$('div.mockup_img').html('');
				$('p.mockup_show').toggle();
				$('p.mockup_hide').toggle();
				$('input#mockup_id').val('');
				
			}
		});
	});


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


	// Activate colorpicker
	$('.mockup-colorpicker').wpColorPicker();
});