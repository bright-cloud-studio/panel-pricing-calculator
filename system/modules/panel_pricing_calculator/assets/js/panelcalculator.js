// when the page loads, check if there is anything in the cart. If there is update the cart number
// A $( document ).ready() block.
$( document ).ready(function() {

    $.ajax({
        url:'/system/modules/panel_pricing_calculator/assets/php/action.get.cart.total.endpoint.php',
        type:'POST',
        success:function(result){
        	$("#cart_total").html(result);
        },
        error:function(result){
			$("#dev_message").html("GET CART TOTAL FAIL");
        }
    });
    
    
     $.ajax({
        url:'/system/modules/panel_pricing_calculator/assets/php/action.show.cart.items.endpoint.php',
        type:'POST',
        success:function(result){
        	$("#cart_contents").html(result);
        },
        error:function(result){
			$("#cart_contents").html("SHOW CART ITEMS FAIL");
        }
    });
    
});

// this is the call to save to cart
function add_to_cart(){

	// store our quote request values
    var panel_id = $("#panel_name_id").html();
    var panel_thickness_id = $("#panel_thickness_id").html();
    var panel_cradle_id = $("#panel_cradle_id").html();
    var panel_width = $("#panel_width").html();
    var panel_height = $("#panel_height").html();
    var order_quantity = $("#quote_quantity").html();

    // trigger this function when our form runs
    $.ajax({
        url:'/system/modules/panel_pricing_calculator/assets/php/action.add.cart.endpoint.php',
        type:'POST',
        data:"panel_id="+panel_id+"&flat_id="+panel_thickness_id+"&cradle_id="+panel_cradle_id+"&width="+panel_width+"&height="+panel_height+"&quantity="+order_quantity+"",
        success:function(result){
        	$("#cart_total").html(result);
        	
        	
        	$.ajax({
        url:'/system/modules/panel_pricing_calculator/assets/php/action.show.cart.items.endpoint.php',
        type:'POST',
        success:function(result){
        	$("#cart_contents").html(result);
        },
        error:function(result){
			$("#cart_contents").html("SHOW CART ITEMS FAIL");
        }
    });
        	
        	

        },
        error:function(result){
			$("#dev_message").html("ADD TO CART FAIL");
        }
    });
	
}

// this is the call to save to cart
function remove_from_cart(id){

    // trigger this function when our form runs
    $.ajax({
        url:'/system/modules/panel_pricing_calculator/assets/php/action.remove.from.cart.endpoint.php',
        type:'POST',
        data:"entry_id="+id+"",
        success:function(result){
        	console.log("REMOVED SUCCESS");
        	
        	var ourItem = '#cart_item_' + id;
        	console.log("SELECTOR: " + ourItem);
        	$(ourItem).remove();
        	$("#cart_total").html(result);

        },
        error:function(result){
			$("#dev_message").html("REMOVE FROM CART FAIL");
        }
    });
	
}


// This is just an ajax call to send in the IDs from the form and get back the total, then push it onto the page
function calculate(){
	
    // store our form values
    var panel_id = $("#panel_id").val();
    var panel_thickness_id = $("#flat_id").val();
    var panel_cradle_id = $("#cradle_id").val();
    var panel_width = $("#width").val();
    var panel_height = $("#height").val();
    var order_quantity = $("#quantity").val();
    
    // trigger this function when our form runs
    $.ajax({
        url:'/system/modules/panel_pricing_calculator/assets/php/action.panel.calculator.endpoint.php',
        type:'POST',
        data:"panel_id="+panel_id+"&flat_id="+panel_thickness_id+"&cradle_id="+panel_cradle_id+"&width="+panel_width+"&height="+panel_height+"&quantity="+order_quantity+"",
        success:function(result){
        	
        	///////////////////////////////////////
        	// quote_render - update values on page
        	///////////////////////////////////////
        	
        	$("#panel_name").html(getPanelNameFromID(panel_id));
        	$("#panel_name_id").html(panel_id);
        	$("#panel_thickness").html(getPanelThicknessFromID(panel_thickness_id));
        	$("#panel_thickness_id").html(panel_thickness_id);
        	$("#panel_cradle_id").html(panel_cradle_id);
        	$("#panel_cradle").html(getPanelCradleFromID(panel_cradle_id));
        	$("#panel_width").html(panel_width);
            $("#panel_height").html(panel_height);
        	$("#quote_quantity").html(order_quantity);
        	
        	// will have to make a function to determine the discount eventually
            //$("#quote_discount").html("0%");
        	
        	// update product details in the quote_rendered area
            $("#quote_price").html(result);
            
            // slide down the quote_render section
            $("#quote_render").slideDown();

        },
        error:function(result){
            $("#quote_price").html(result);
        }
    });

}

// get panel name, as string, from an id
function getPanelNameFromID(id){
	switch(id) {
		case "1":
			return "Claybord";
	break;
		case "2":
			return "Gessobord";
	break;
		case "3":
			return "Aquabord";
	break;
		case "4":
			return "Encausticbord";
	break;
		case "5":
			return "Scratchbord";
	break;
		case "6":
			return "Pastelbord";
	break;
		case "7":
			return "Hardbord";
	break;
		default:
			return "invalid_id";
	}
}

// get panel thickness, as string, from an id
function getPanelThicknessFromID(id){
	switch(id) {
		case "1":
			return "1/8";
	break;
		case "2":
			return "1/4";
	break;
		default:
			return "invalid_id";
	}
}

// get panel cradle, as string, from an id
function getPanelCradleFromID(id){
	switch(id) {
		case "none":
			return "None";
	break;
		case "3":
			return "3/4\" Cradled";
	break;
		case "4":
			return "1\" Cradled";
	break;
		case "5":
			return "1 1/2\" Cradled";
	break;
		case "6":
			return "2\" Cradled";
	break;
		case "7":
			return "2 1/2\" Cradled";
	break;
		case "8":
			return "3\" Cradled";
	break;
		default:
			return "invalid_id";
	}
}
