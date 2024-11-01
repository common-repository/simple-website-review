jQuery(document).ready(function($){
	$(".positive").click(function(){
		$(".review-page").slideUp();
	 	$(".question").fadeOut();
	  	$(".review-page").slideDown();
	  	$(".links").delay(500).fadeIn();
	});
	$(".back").click(function(){
		$(".review-page").slideUp();
	 	$(".links").fadeOut();
        $(".form").fadeOut();
	  	$(".review-page").slideDown();
	  	$(".question").delay(500).fadeIn();
	});
    $(".negative").click(function(){
        $(".review-page").slideUp();
        $(".question").fadeOut();
        $(".review-page").slideDown();
        $(".form").delay(500).fadeIn();
    }); 
});

jQuery( document ).ready(function() {
    jQuery( ".swr-ajax").hide();
    jQuery( "#scuf" ).submit(function(event) {

        //Validation
        var name = document.forms["scuf"]["swrn"].value;
        if (name == null || name == "") {
            alert("Name must be filled out");
            return false;
        }
        var subject = document.forms["scuf"]["swrsj"].value;
        if (subject == null || subject == "") {
            alert("Subject must be filled out");
            return false;
        }
        var message = document.forms["scuf"]["swrm"].value;
        if (message == null || message == "") {
            alert("Message must be filled out");
            return false;
        }
        var email = document.forms["scuf"]["swre"].value;
        var atpos = email.indexOf("@");
        var dotpos = email.lastIndexOf(".");
        if (atpos< 1 || dotpos<atpos+2 || dotpos+2>=email.length) {
            alert("Not a valid e-mail address");
            return false;
        }

        jQuery( ".swr-ajax").show();
        var posting = jQuery.post( SWR.ajaxurl, jQuery("#scuf :input").serialize() )
        .done(function() {
            jQuery( ".swr-ajax").hide();
            jQuery(".formmessage p").html('<span class="">Thanks, Your Message Was Sent Successfully.');
        })
        .fail(function() {
            jQuery( ".swr-ajax").hide();
            jQuery(".formmessage p").html('<span class="">Oops, something went wrong...');
        });
        event.preventDefault();
    });
});
