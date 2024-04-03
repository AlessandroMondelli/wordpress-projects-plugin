jQuery(document).ready(function($) {
  //Ordinamento Drag&Drop
  let currentOverEl;
  let elPosEnd;

  $('#am_project_secondary_image').click(function(e) {
    e.preventDefault();

    const hiddenInputSecondaryImage = $('#am-project-secondary-image');
    const imageWrapper = $('.am-secondary-image-preview');

    openCustomUploader('immagine', 'immagini', false, 'image', hiddenInputSecondaryImage, imageWrapper, false);
  });

  $('#am_gallery_button').click(function(e) {
      e.preventDefault();

      const hiddenInputGallery = $('#projects-gallery-images');
      const imagesWrapper = $('.am-gallery-preview');
      
      openCustomUploader('immagine', 'immagini', true, 'image', hiddenInputGallery, imagesWrapper, false);
  });

  $('#am_icons_tooltip_button').click(function(e) {
    e.preventDefault();

    const hiddenInputIcons = $('#projects-icons-tooltip-input');
    const iconsWrapper = $('.am-icons-tooltip-preview');

    openCustomUploader('icona', 'icone', true, 'image', hiddenInputIcons, iconsWrapper, true);
  });

  //Rimozione elementi
  $(document).on('click', '.remove-el', function(e) {
    //Recupero id da eliminare
    const idToDelete = Number($(this).parent().attr('data-id'));
    //Elemento input
    const hiddenInput = $(this).closest('.am-images-container').siblings('.projects-images-hidden-input');

    //Modifico valori attivi
    let activeImagesArr = hiddenInput.val().split(',').map(Number);
    activeImagesArr = activeImagesArr.filter(el => el !== idToDelete);
    hiddenInput.val(activeImagesArr.join(','));

    //Rimuovo icona immagine
    $(".img-preview-el[data-id=" + idToDelete + "]").remove();
  });

  //Attivazione/disattivazione icona
  $(document).on('click', '.am-icons-tooltip-preview-el', function(e) {
    //Prendo id selezionato
    const selectedId = $(this).attr('data-id');

    //Prendo icone già attive
    let activeValuesString = $('#projects-icons-active').val();
    const activeValueStringArr = activeValuesString !== "" ? activeValuesString.split(',') : [];

    //Se l'id non era incluso
    if(!activeValueStringArr.includes(selectedId)) {
      $(this).find("img").css({"filter":"contrast(1)"});
      activeValueStringArr.push(selectedId);
    } else {
      $(this).find("img").css({"filter":"contrast(0)"});
      activeValueStringArr.splice(activeValueStringArr.indexOf(selectedId), 1);
    }

    //Invio dati ad elemento
    activeValuesString = activeValueStringArr.join(',');
    $('#projects-icons-active').val(activeValuesString);
  });

  setDragDropHandlers();

  //Funzione che permette di avviare media uploader
  function openCustomUploader(multipleMediaName, singularMediaName, isMultiple, mediaType, hiddenInputEl, mediaContainer, tooltipSupport) {
    const custom_uploader = wp.media({
      title: 'Seleziona ' + multipleMediaName,
      button: {
          text: 'Aggiungi ' + singularMediaName
      },
      multiple: isMultiple,
      library: {
        type: mediaType
      }
    })
    .on('select', function() {
      const selection = custom_uploader.state().get('selection');
      const media_ids = isMultiple && hiddenInputEl.val().length !== 0 ? hiddenInputEl.val().split(',').map(Number) : [];

      selection.each(function(attachment) {
        if(!isMultiple) mediaContainer.empty();
        if(!media_ids.includes(attachment.id)) {
          //Creo wrapper html in base a supporto tooltip
          let wrapperHTML = tooltipSupport ? '<div class="am-icons-tooltip-preview-el img-preview-el draggable" data-id="' + attachment.id + '" style="position:relative"> ' : '<div class="gallery-el img-preview-el draggable" data-id="' + attachment.id + '" style="position:relative"> ';

          wrapperHTML +=  '<p class="remove-el" style="position:absolute; top: -1.5rem; right: -1rem; border: 1px solid black; padding: 1px 5px; border-radius: 50%; background-color: white; z-index: 99; cursor: pointer">X</p>';

          wrapperHTML += '<img src="' + attachment.attributes.url  + '" style="max-width: 250px;" />' + '</div>'

          mediaContainer.append(wrapperHTML);

          media_ids.push(attachment.id);
        }
      });

      hiddenInputEl.val(media_ids.join(',')); //Join della stringa per trasformare in array
      //Aggiornamento anteprima

      //Inizializzo drag&drop
      setDragDropHandlers();
    })
    .open();
  }

  function setDragDropHandlers() {
    $('.draggable').each(function() {
      $(this).on('dragstart', function(){ initDragStart($(this)) });
      $(this).on('dragend', function(){ initDragEnd($(this)) });
    });
  
    $('.img-preview-el').each(function() {
      $(this).on('dragover', function(e) { 
        e.preventDefault();
        e.stopPropagation();
        initDragOver(e, $(this)) 
      });
  
      $(this).on('drop', function(e) { initDrop($(this)) });
    });
  }

  function initDragStart(el) {
    el.addClass('dragging');  
    //Aggiungo classe per conoscere zona di drop
    el.parent().addClass('drop-zone');
  }

  function initDragEnd(el) {
    el.removeClass('dragging');
    el.parent().removeClass('drop-zone');
  }

  function initDragOver(event, el) {
    //Calcolo posizione elemento
    currentOverEl = el;
    const draggingPos = event.pageX;
    const elPos = el.offset().left;
    const elCenterWidth = el.width() / 2;

    //Se l'elemento draggato viene portato dopo la metà dell'elemento sottostante
    if(draggingPos > (elPos + elCenterWidth)) {
      elPosEnd = 'after';
    } else {
      elPosEnd = 'before';
    }
  }

  function initDrop(el) {
    //Prendo elementi
    const wrapEl = el.parent();

    //Se non è zona di drop giusta termino operazione
    if(!wrapEl.hasClass('drop-zone')) return;

    //Prendo elemento input
    const hiddenInput = wrapEl.parent().parent().find('.projects-images-hidden-input');
    //Recupero elemento dragging 
    const dragEl = $('.dragging');

    //inizializzo array
    const newOrderArr = [];

    //Posiziono elemento in base a posizione
    if(elPosEnd === 'after') {
      currentOverEl.after(dragEl);
    } else {
      currentOverEl.before(dragEl);
    }

    //Salvo in array valori con nuovo ordine
    wrapEl.children().each(function() {
      newOrderArr.push($(this).attr('data-id'));
    });
    
    //Inserisco valori in input
    hiddenInput.val(newOrderArr.join(','));
  }
}); 