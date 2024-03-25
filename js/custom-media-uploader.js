jQuery(document).ready(function($) {
  $('#am_gallery_button').click(function(e) {
      e.preventDefault();

      const hiddenInputGallery = $('#projects-gallery-images');
      const previewGallery = $('.am-gallery-current-selection-preview');
      
      openCustomUploader('immagine', 'immagini', true, 'image', hiddenInputGallery, previewGallery);
  });

  $('#am_icons_tooltip_button').click(function(e) {
    e.preventDefault();

    const hiddenInputIcons = $('#projects-icons-tooltip-input');
    const previewIcons = $('.am-icons-tooltip-current-selection-preview');

    openCustomUploader('icona', 'icone', true, 'image', hiddenInputIcons, previewIcons);
  });

  //Funzione che permette di avviare media uploader
  function openCustomUploader(multipleMediaName, singularMediaName, isMultiple, mediaType, hiddenInputEl, previewEl) {
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
      const media_ids = [];
      let img_html = '<div class="media-preview">';

      selection.each(function(attachment) {
          media_ids.push(attachment.id);
          img_html += '<img class="media-el" src="' + attachment.attributes.url + '" style="max-width: 50px;" data-id="' + attachment.id + '"/>';
      });

      img_html += '</div>'

      hiddenInputEl.val(media_ids.join(',')); //Join della stringa per trasformare in array
      //Aggiornamento anteprima
      previewEl.empty().append(img_html);
    })
    .open();
  }

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
});