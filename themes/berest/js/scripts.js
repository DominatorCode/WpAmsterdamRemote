
$(document).ready(function(){
	
	$('.navbar-toggle').click(function(){
		$(this).toggleClass('open');
	});
	
	
  AOS.init({
	easing: 'ease-in-out-sine',
	  duration: 700,
	  once: true, 
  });
	
});