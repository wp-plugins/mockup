//@prepros-prepend tooltip.js

function loading() {

	$('div#load').html('<div class="loading"><i class="fa fa-refresh fa-spin"></i></div>').fadeIn(200);	
}

function slide_menu(action) {

	$('div.slidebox').clearQueue();

	if(action == 'close') {
		
		$('div.navbar a').removeClass('active');

		$('div.slidebox').animate({
			'margin-left': '-220px'
		}, 1200);	
	}

	if(action == 'open') {
		$('div.slidebox').animate({
			'margin-left': '0'
		}, 1200);	
	}
}

function toggle_menu(element) {

	$('div.slidebox').clearQueue();

	loading();

	id = $(element).attr('id');
	action = 'mockup_single_'+id;

	$.ajax({

		type: 'POST',
		url: ajax_url,
		data: { 
			action: action,
			post_id: post_id
		},
		success: function(data) { 
			$('div#load').hide().html(data).fadeIn(200);
		}
	});

	$('a.show-title').tooltip('hide');
	$('a').removeClass('active');
	$(element).addClass('active');
	slide_menu('open');
}

jQuery(document).ready(function($) {

	// Tooltip - Twitter Bootstrap
	$('a.show-title').tooltip({
		'placement': 'right'
	});

	// Slidebox
	$(document).on('click','a#close', function(e) {

		e.preventDefault();
		slide_menu('close');
	});

	$(document).keyup(function(e) {

		if(e.keyCode == 27 || e.keyCode == 37) {
			slide_menu('close');
		}
	});


	$(document).on('click','a.toggle', function(e) {

		e.preventDefault();
		toggle_menu(this);
	});


	// Validation
	$(document).on('click','button.submit', function(e) {

		$('.error').removeClass('error');
		has_error = false;

		// Check if empty
		$(this).closest('form').find('.not-empty').each(function() {
			var value = $(this).val();

			if(value == '') {
				$(this).addClass('error');
				has_error = true;
			}
		});

		if(has_error == true) {
			e.preventDefault();
			return false;
		} 
	});


	// Process forms
	$(document).on('click','button#approve_submit', function(e) {

		e.preventDefault();

		if(has_error == true) {
			return false;
		}

		mockupnonce = $('input#mockupnonce').val();
		name = $('input#approve_name').val();

		loading();

		$.ajax({

			type: 'POST',
			url: ajax_url,
			data: { 
				action: 'mockup_process_approve',
				post_id: post_id,
				mockupnonce: mockupnonce,
				name: name
			},
			success: function(data) {
				$('div#load').hide().html(data).fadeIn(200);
			}
		});
	});


	$(document).on('click','button#comment_submit', function(e) {

		e.preventDefault();

		if(has_error == true) {
			return false;
		}

		mockupnonce = $('input#mockupnonce').val();
		name = $('input#comment_name').val();
		text = $('textarea#comment_text').val();

		loading();

		$.ajax({

			type: 'POST',
			url: ajax_url,
			data: { 
				action: 'mockup_process_comment',
				post_id: post_id,
				mockupnonce: mockupnonce,
				name: name,
				text: text
			},
			success: function(data) {
				$('div#load').hide().html(data).fadeIn(200);
			}
		});
	});
});