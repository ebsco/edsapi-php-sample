function dateRangeSubmit(myForm){
    var min = jQuery('#minDate').val();
    var max = jQuery('#maxDate').val();
    $("#DT1").val('addlimiter(DT1:'+min+'-01/'+max+'-12)');
}