jQuery( document ).ready(function($){
	console.log("FAQ script ready");
	var icons = {
      header: "ui-icon-circle-plus",
      activeHeader: "ui-icon-circle-minus"
    };

    $( "#faqs" ).accordion({
		icons : icons,
		collapsible: true,
	});

	$( "#faqs" ).accordion( "option", "icons", icons );

});
