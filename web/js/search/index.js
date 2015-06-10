$( document ).ready(function() {
    $( "#search_keyword" ).autocomplete({
      source: "app_dev.php/keyword.json",
      minLength: 2,
      select: function( event, ui ) {
        log( ui.item ?
          "Selected: " + ui.item.value + " aka " + ui.item.id :
          "Nothing selected, input was " + this.value );
      }
    });
})