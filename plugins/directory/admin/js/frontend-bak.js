jQuery(document).ready(function($){
	$('.thumbnails img').click(function(){

	 // Grab img.thumb class src attribute
	 // NOTE: ALL img tags must have use this class, 
	 // otherwise you can't click back to the first image.
	var id = $(this).attr("id");
	var thumbSrc = $('#'+id).attr("data-src");
	// Grab img#largeImage src attribute
	var largeSrc = $('.largeImage').attr('src');
	// Use variables to replace src instead of relying on file names.
	//$('#largeImage').attr('src',$(this).attr('src').replace(thumbSrc,largeSrc));
	$('#largeImage').attr('src',thumbSrc);
	$('#description').html($(this).attr('alt'));
	var ids= id.split('_').pop();
	$(".thumbnails").removeClass("hide_thumb");
	$("#thumb_"+ids).addClass("hide_thumb");
	});
});