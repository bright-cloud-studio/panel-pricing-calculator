//This is the code that updates the page as the user uses it
(function($) {
$(document).ready(function() {

	//the page is fully loaded, show our search boxes
	$("div.mod_locations_list").fadeIn();

	//hide all divs by default
	$(".location_full").each(function(){
		$(this).hide();
		//$(this).fadeOut();
	});

	// show our inputs
	$("input.locations_zip_input").show();
	$("p.enter_zip").show();
	
	//when changing zip
	$("input.locations_zip_input").keyup(function(){
	
		//first hide everything
		//hide all divs by default
		$(".location_full").each(function(){
			//$(this).hide();
			$(this).fadeOut();
		});
		
		//if the zip is the right characters in length or longer
		if($(this).val().length >=5 )
		{
			//get the category selector's value
			var zipVal = $("input.locations_zip_input").val();
			
			var zip_found = 0;
			var counter = 0;
			var wasFound = 0;
		
			//loop through each listing, and their children divs
			$('.location_full').find('div').each(function(){
				counter = counter + 1;
				var innerDivId = $(this).attr('class');	
				var innerDivText = $(this).html();
				if(innerDivId == "hidden_zip")
				{
					if(innerDivText.includes(zipVal))
					{
						zip_found = 1;
						//alert("found_2");
					}
					if(zip_found == 1)
					{
						//make parent div visible
						//$(this).parent().show();
						wasFound = 1;
						$(this).parent().fadeIn();
						//alert($(this).parent().html());
					}
					counter = 0;
					zip_found = 0;
				}
			});
				if(wasFound == 0)
					$("div.state_not_found").fadeIn();
				else
					$("div.state_not_found").hide();
		}
	});
});
})(jQuery);
