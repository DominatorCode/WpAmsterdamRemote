var booking = {
	init: function () {

	},
	redirect: function (e, page_id) {
		e.preventDefault();

		jQuery.ajax({
			type: 'POST',
			url: my_ajax_object.ajax_url,
			data: {
				action: 'BookingSession',
				page_id: page_id,
			},
			success: function (data, textStatus, XMLHttpRequest) {
				window.location.href = "/bookings";
			},
			error: function (MLHttpRequest, textStatus, errorThrown) {
				alert(errorThrown);
			}
		});
	},
	modelChange: function (e) {

		const url = e.selectedOptions[0].getAttribute('img');

		if (e.selectedIndex != 0) {
			$('.model-name').text(e.selectedOptions[0].label);
			$('.background-block').css('background-image', 'url("' + url + '")');
		} else {
			$('.model-name').text("");
			$('.background-block').css('background-image', '');

		}
	},
	bookClick: function (e) {
		e.preventDefault();

		jQuery.ajax({
			type: 'POST',
			url: my_ajax_object.ajax_url,
			data: {
				action: 'BookNow',
				client_name: $('#name').val(),
				client_email: $('#email').val(),
				client_phone: $('#tel').val(),
				client_location: $('#location').val(),
				client_message: $('#message').val(),
				booking_name: $('.madel-dropdown :selected').text(),
				booking_date: $('.month-dropdown :selected').text(),
				booking_day: $('.day-dropdown :selected').text(),
				booking_duration: $('.duration-dropdown :selected').val(),
				booking_type: $('#booking-type input:radio:checked').val(),
				booking_image: $('.madel-dropdown :selected').attr('img')
			},
			success: function (data, textStatus, XMLHttpRequest) {
				window.location.href = "/";
			},
			error: function (MLHttpRequest, textStatus, errorThrown) {
				alert(errorThrown);
			}
		});

	}

}