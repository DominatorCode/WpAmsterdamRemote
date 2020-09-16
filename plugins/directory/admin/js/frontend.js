jQuery(document).ready(function($){
	$('.thumbnails img').click(function(){
	var id = $(this).attr("id");
	var thumbSrc = $('#'+id).attr("data-src");
	var largeSrc = $('.largeImage').attr('src');
	$('.loader-image').css("display", "block");
    $('#largeImage').attr('src',thumbSrc);
    $('#largeImage').load(function(){
      $('#description').html($(this).attr('alt'));
      $('.loader-image').css("display", "none");
	});
	var ids= id.split('_').pop();
	$(".thumbnails").removeClass("hide_thumb");
	$("#thumb_"+ids).addClass("hide_thumb");
	});
});