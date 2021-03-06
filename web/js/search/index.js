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
            url: "cidades.json",
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

    $('#submit_search').click(function () {
    	var url = Routing.generate('company_list', {keyword : $("#search_keyword").val(), city: $('#search_city').val(), postal_code: $('#search_postal_code').val()})+'.html';

    	window.location.href = url;
    });
})