jQuery(document).ready(function () {

    function galleryFeed( $page = 1 )
    {
      // body...

        var age = $('.age-filter').val();
        var hair = $('.hair-filter').val();
        var height = $('.height-filter').val();
        var bust = $('.bust-filter').val();
        var body = $('.body-filter').val();
        var service = $('.service-filter').val();
        var min_price =  $('.price-filter').children("option:selected").attr('min-value');
        var max_price =  $('.price-filter').children("option:selected").attr('max-value');

        jQuery.ajax({
            type: 'POST',
            url: my_ajax_object.ajax_url,
            data: {
                action: 'GalleryFeed',
                page: $page,
                age: age,
                hair: hair,
                height: height,
                bust: bust,
                body: body,
                service: service,
                min_price: min_price,
                max_price: max_price,
            },
            success: function (data, textStatus, XMLHttpRequest) {

              //debugger;
                var json_response = JSON.parse(data);

                jQuery(".hot-modal-section .content-holder").html('');
                jQuery(".hot-modal-section .content-holder").append(json_response.content);

                jQuery(".gallery-right-dropdown").html(json_response.navigation).append('<span class="input-group-btn">\n' +
                    '    <button class="btn btn-default btn-reset" type="button" tabindex="-1">Reset</button>\n' +
                    '  </span>');


                jQuery(".pagination-div .pagination").html('');
                jQuery(".pagination-div .pagination").append(json_response.pages);

                $('.pagination-div .pagination .active').off('click').click(function () {
                    galleryFeed($(this).attr('p'));
                });

                $('.gallery-right-dropdown select.form-control').off('change').change(function () {
                    galleryFeed();
                });

            },
            error: function (MLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });

    }

    galleryFeed();

});