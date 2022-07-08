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

    //Filtro front-end
    $('.projects-filter .discipline').click(function() { //Al click del filtro
        var catActive = ''; //Inizializzo variabile che controlla altro filtro

        $('.projects-filter .anni').each(function() { //Scorro selezione altro filtro
            if($(this).hasClass('active')) //Se uno è attivo
                catActive = $(this).text(); 
        });

        if($(this).hasClass('active')) { //Se il filtro cliccato era già attivo
            $(this).removeClass('active'); //Rimuovo classe attivo dal filtro

            if(catActive === undefined) { //Se non è attivo un altro filtro
                $('.amproj-inner').fadeIn(); //Mostro tutti i progetti
            } else { //Altrimenti
                $('.amproj-inner').each(function() { //Scorro tutti i progetti
                    var tag = $(this).attr('class'); //Prendo tag
    
                    if((tag.includes(catActive))) { //Se è presente il tag
                        $(this).fadeIn(); //Mostro
                    }
                });
            }
            
        } else {      
            $('.projects-filter .discipline').each(function() {
                if($(this).hasClass('active'))
                    $(this).removeClass('active');
            });

            var value = addClassAndGetValue($(this));

            $('.amproj-inner').each(function() { //Scorro progetti
                var tag = $(this).attr('class'); //Prendo tag

                hideOrShowProjects($(this), value, tag, catActive);    
            });
        }
    });

    $('.projects-filter .anni').click(function() { //Al click del filtro
        var tagActive = ''; //Inizializzo variabile che controlla altro filtro

        $('.projects-filter .discipline').each(function() { //Scorro selezione altro filtro
            if($(this).hasClass('active')) //Se uno è attivo
                tagActive = $(this).text();
        });

        if($(this).hasClass('active')) {
            $(this).removeClass('active');

            if(tagActive === undefined) {
                $('.amproj-inner').fadeIn();
            } else {
                $('.amproj-inner').each(function() {
                    var cat = $(this).attr('class'); //Prendo tag
    
                    if((cat.includes(tagActive))) {
                        $(this).fadeIn();
                    }
                });
            }

        } else {      
            $('.projects-filter .anni').each(function() {
                if($(this).hasClass('active'))
                    $(this).removeClass('active');
            });

            var value = addClassAndGetValue($(this));

            $('.amproj-inner').each(function() { //Scorro progetti
                var cat = $(this).attr('class'); //Prendo tag

                hideOrShowProjects($(this), value, cat, tagActive);     
            });
        }
    });
    
    function addClassAndGetValue(thisValue) {
        thisValue.addClass('active'); //Aggiungo classe per evidenziare opzione scelta
        return thisValue.text(); //Prendo tag scelto
    }

    function hideOrShowProjects(thisValue, choosenFilter, catTag, otherFilter ) {
        thisValue.fadeOut(); //Nascondo

        if(!( (catTag.includes(choosenFilter) && (catTag.includes(otherFilter) || otherFilter === undefined )))) { //Se il tag non è incluso nella classe
            thisValue.fadeOut(); //Nascondo
        } else { //Altrimenti 
            setTimeout(function() {
            thisValue.fadeIn(); //Mostro
            }, 400)
            
        }
    }

    //Spotlight 
    $(".amproj-inner").click(function() {
        var el = $(this);

        if( el.find(".amproj-thumbnail").hasClass("spotlight") && !$(".amproj-wrap-spotlight").length ) {
        var img = el.find(".amproj-thumbnail-img").attr("src");
        console.log(img);
        $("main").append("<div class='amproj-wrap-spotlight active'><img class='amproj-img-spotlight' src='" + img + "'></div>");
        }
    });

    //Rimuovo spotlight al click
    $(document).on("click", ".amproj-wrap-spotlight" ,function() {
        if($(this).hasClass("active")) {
            $(this).remove();
        }
    });

    //Lazyload
    $(document).on("scroll", function() {
        var scrollTop = window.pageYOffset;

        $(".amproj-lazyload").each(function() {
            var imgPos = $(this).offset().top; //Preso posizione immagine

            if( imgPos < window.innerHeight + scrollTop ){ //Confronto posizione immagine con posizione utente
                var imgLink = $(this).attr("data-src");
                $(this).attr("src", imgLink).addClass("visible");
            }
        }); 
    });
});