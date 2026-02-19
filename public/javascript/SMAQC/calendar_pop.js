$(function() {
    var dates = $( "#from, #to" ).datepicker({
        defaultDate: "+1w",
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        onSelect: function( selectedDate ) {
            var option = this.id == "from" ? "minDate" : "maxDate",
                instance = $( this ).data( "datepicker" ),
                date = $.datepicker.parseDate(
                    instance.settings.dateFormat ||
                    $.datepicker._defaults.dateFormat,
                    selectedDate, instance.settings );
            dates.not( this ).datepicker( "option", option, date );
        },
        onChangeMonthYear: function( year, month, inst) {
            $(this).datepicker('setDate', month + '-1-' + year);
        }
    });
});