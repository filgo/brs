$( document ).ready(function() {
    $( "#search_keyword" ).autocomplete({
	  source: function(request, response) {
        $.ajax({
            url: "keyword.json",
            dataType: "json",
            data: {
                name : request.term,
            },
            success: function(data) {
                response(data);
            }
        });
    },
      minLength: 2,
    });
    
    $( "#search_city_cp" ).autocomplete({
	  source: function(request, response) {
        $.ajax({
            url: "cities.json",
            dataType: "json",
            data: {
                name : request.term,
            },
            success: function(data) {
                response(data);
            }
        });
    },
    change: function()
    {
    	var sVal = $('#search_city_cp').val();
    	aRes = sVal.match(/\((.*)\)/);
    	$('#search_postal_code').val(aRes[1]);
    	aRes = sVal.match(/(.*) \(/);
    	$('#search_city').val(aRes[1].toLowerCase());
    },
    minLength: 2,
    });
})