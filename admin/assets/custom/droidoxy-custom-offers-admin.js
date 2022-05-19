function readURL(input) {
	
	if (input.files && input.files[0]) {
		
		var reader = new FileReader();
		
		reader.onload = function (e) {
			$('#offerImage')
				.attr('src', e.target.result)
				.width(150)
				.height(150);
			};
		reader.readAsDataURL(input.files[0]);
		document.getElementById("choose-btn").remove();
		//$('#offer_image_file').val(input.files[0].name);
		//$('#offer_image_file').prop('disabled', false);
	}
}

$(function() {
    
    if(offerTime == "none"){
        
        offerTime = moment().format('DD/MM/YYYY h:mm A'); // present date
        
        //offerTime = moment.unix(1584021600).utc().format('DD/MM/YYYY h:mm A') // timstamp to date
    }
    
    $('input[name="datetimes"]').daterangepicker({
        timePicker: true,
        singleDatePicker: true,
        timePickerIncrement: 1,
        //startDate: "06/03/2020 12:15 PM",
        startDate: offerTime,
        locale: {
            format: 'DD/MM/YYYY h:mm A'
        }

    }, function(start, end, label) {
        
        //console.log('New date range selected: ' + start.unix());
        $('#date_time').val(start.unix());
        
    });
});

    //let chip = [{tag: 'All Countries'}];
    
    //var of_countries = [];
    //of_countries.push('All Countries');
    addjson(of_countries);

$(document).ready(() => {
    
    $('#chips-autocomplete-countries').materialChip({
      placeholder: 'Enter a Country',
      secondaryPlaceholder: 'Country Targeting',
      dataChip: country_codes,
      data: chip
    });
    
})

//console.log($('.chips-initial').materialChip('data'););

$('.chips').on('chip.add', function(e, chip){
    
    // you have the added chip here
    of_countries.push(chip.tag);
    //console.log(of_countries);
    addjson(of_countries);
    
});

$('.chips').on('chip.delete', function(e, chip){
    
    // you have the deleted chip here
    of_countries = of_countries.filter((value)=>value!=chip.tag)
    //console.log(of_countries);
    addjson(of_countries);
    
});

$('.chips').on('chip.select', function(e, chip){
    
    // you have the selected chip here
    
});

function addjson(of_countries){
    
    var json_arr = JSON.stringify(of_countries);
    $('#countries_data').val(json_arr);
}