jQuery( function( $ ) {
    $( ".amproj-inner" ).mouseenter(function() { //All'entrata del mouse su progetto
        var wrap = $(this).find(".amproj-content-wrap");
        var img = $(this).find(".amproj-thumbnail-img"); //Prendo html immagine
        var title = $(this).find(".amproj-title"); //Prendo titolo progetto
        
      if(!(img.hasClass("active"))) { //Se l'immagine non è attiva
          wrap.addClass("active");
          img.addClass("active"); //Mostro immagine
      
          if((title.hasClass("leave"))) { //Se il titolo è stato nascosto
            title.removeClass("leave"); //Tolgo classe per animazione leave
          }
      
          title.addClass("enter"); //Aggiungo classe per animazione enter       
        }
      });
      
        $( ".amproj-inner" ).mouseleave(function() { //All'uscito del mouse da progetto
        var wrap = $(this).find(".amproj-content-wrap");
        var img = $(this).find(".amproj-thumbnail-img"); //Prendo html immagine
        var title = $(this).find(".amproj-title");
        
      if(img.hasClass("active")) { //Se ha la classe attiva
          wrap.removeClass("active");
          img.removeClass("active"); //Rimuovo classe
          
          if((title.hasClass("enter"))) { //Se il titolo è stato mostrato
            title.removeClass("enter"); //Tolgo classe per animazione enter
          }
        
          title.addClass("leave"); //Aggiungo classe per animazione leave
        }
      });
});