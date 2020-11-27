var booking = {
  init: function () {

  }, 
  redirect: function ( e, page_id ) {
    e.preventDefault();
    
    jQuery.ajax({
        type: 'POST',
        url: my_ajax_object.ajax_url,
        data: {
          action: 'BookingSession',
          page_id: page_id,
        },
        success: function(data, textStatus, XMLHttpRequest){
          window.location.href = "/bookings";
        },
        error: function(MLHttpRequest, textStatus, errorThrown){
          alert(errorThrown);
        }
    });
  },
  modelChange: function (e) {

    var url = e.selectedOptions[0].getAttribute('img');

    $('.background-block').css('background-image', 'url("'+url+'")');

  },
  bookClick: function (e) {
    e.preventDefault();
    
    if (
        $('#name').val() =='' || 
        $('#email').val() =='' ||
        $('#tel').val() =='' ||
        $('#location').val() =='' ||
        $('.madel-dropdown :selected').val() == '0' ||
        $('.day-dropdown :selected').text() == 'Day' 
    ) {
        if ($('.booking-alert').is(":hidden"))
            $('.booking-alert').toggle('slide');
        return false;
    }
    
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
          booking_date: $('.month-dropdown :selected').val(),
          booking_day: $('.day-dropdown :selected').text(),
          booking_duration: $('.duration-dropdown :selected').val(),
          booking_type: $('#booking-type input:radio:checked').val(),
          booking_image: $('.madel-dropdown :selected').attr('img')
        },
        success: function(data, textStatus, XMLHttpRequest){
          location.reload();
        },
        error: function(MLHttpRequest, textStatus, errorThrown){
          alert(errorThrown);
        }
    });

  }

}