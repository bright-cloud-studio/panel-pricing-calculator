// This is just an ajax call to send in the IDs from the form and get back the total, then push it onto the page
function calculate(){
    $.ajax({
        url:'/system/modules/panel_pricing_calculator/assets/php/action.panel.calculator.endpoint.php',
        type:'POST',
        data:"panel_id="+$("#panel_id").val()+"&flat_id="+$("#flat_id").val()+"&cradle_id="+$("#cradle_id").val()+"&width="+$("#width").val()+"&height="+$("#height").val()+"&quantity="+$("#quantity").val()+"",
        success:function(result){
            $("#total").html(result);
        },
        error:function(result){
            $("#total").html(result);
        }
    });

}
