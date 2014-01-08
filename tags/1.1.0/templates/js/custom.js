
function hide_infobox() {

	$('div.slidebox').clearQueue();

	$('a.active').removeClass('active');

	$('div.slidebox').animate( { right:'-'+slidebox_width }, slidebox_speed);
}

function show_infobox(element) {

		$('a.btn-show-slidebox').removeClass('active');

		$(element).addClass('active');

		var value = $(element).attr('value');

		$('div.slidebox').animate({right:'0px'},slidebox_speed);
}



$(document).ready(function() {


	$(document).on('click','a.btn-show-slidebox', function(e) {

		e.preventDefault();

		var element = $(this);
		var value 	= $(this).attr('id');

		$('div.active').removeClass('active').hide();
		$('div.'+value).addClass('active').fadeIn();
		show_infobox(element);

	});


	$(document).on('click','a.active', function(e) {

		e.preventDefault();

		$('div.active').removeClass('active').hide();
		hide_infobox();

	});

	$(document).keyup(function(e) {

		if (e.keyCode == 27) {

			e.preventDefault();

			$('div.active').removeClass('active').hide();
			hide_infobox();

		}
	});


	$(document).on('click','button#submit_comment', function(e) {

		$('div.has-error').removeClass('has-error');
		var has_error = false;

		var InputName 	= $('input#InputName').val();
		var InputText 	= $('textarea#InputText').val();

		if(InputName == '') {
			$('input#InputName').closest('div.form-group').addClass('has-error');
			has_error = true;
		}

		if(InputText == '') {
			$('textarea#InputText').closest('div.form-group').addClass('has-error');
			has_error = true;
		}

		if(has_error == true) {
			e.preventDefault();
			return false;
		}

	});

	$(document).on('click','button#submit_approve', function(e) {

		$('div.has-error').removeClass('has-error');
		var has_error = false;

		var InputName 	= $('input#InputNameApprove').val();

		if(InputName == '') {
			$('input#InputNameApprove').closest('div.form-group').addClass('has-error');
			e.preventDefault();
			return false;
		}

	});

});