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
    
    $( "#search_city" ).autocomplete({
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
      minLength: 2,
    });
})