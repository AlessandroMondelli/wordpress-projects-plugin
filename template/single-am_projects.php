<?php

/*
Template Name: Am Projects Template
Template Post Type: am_projects
*/

get_header();

//Inizia loop
while(have_posts()) : the_post();
  //Recupero immagine secondaria
  $secondary_image = get_post_meta($post->ID, 'am-project-secondary-image', true);

  //Recupero sottotitolo
  $subtitle = get_post_meta($post->ID, 'am-project-subtitle', true);

  //Recupero dati da db
  $project_details = [
    'Committente' => get_post_meta($post->ID, 'projects-details-committente', true), 
    'Cliente' => get_post_meta($post->ID, 'projects-details-cliente', true), 
    'Importo lavori' => get_post_meta($post->ID, 'projects-details-importo-lavori', true),
    'Anno' => get_post_meta($post->ID, 'projects-details-anno', true),
    'Luogo' => get_post_meta($post->ID, 'projects-details-luogo', true),
    'Fase' => get_post_meta($post->ID, 'projects-details-fase', true),
    'Discipline' => get_post_meta($post->ID, 'projects-details-discipline', true),
    'Complessità' => get_post_meta($post->ID, 'projects-details-complessita', true),
    'Innovazione' => get_post_meta($post->ID, 'projects-details-innovazione', true)
  ];

  //Recupero immagini galleria
  $gallery_ids = get_post_meta($post->ID, 'projects-gallery-images', true);
  $gallery_ids_array = !empty($gallery_ids) ? explode(',', $gallery_ids) : array();

  //Recupero icone
  $icons_ids = get_post_meta($post->ID, 'projects-icons-tooltip-input', true);
  $icons_ids_array = !empty($icons_ids) ? explode(',', $icons_ids) : array();
  $icons_ids_active = get_post_meta($post->ID, 'projects-icons-active', true);
  $icons_ids_active_array = !empty($icons_ids_active) ? explode(',', $icons_ids_active) : array();

  //Recupero auto scroll
  $auto_scroll = get_post_meta($post->ID, 'projects-auto-scroll', true);
  ?>
    <section class="am-projects-container">
      <div class="am-projects-thumbnail-wrap">
        <?php echo $secondary_image != "" ? wp_get_attachment_image($secondary_image, 'full') : get_the_post_thumbnail(); ?>
      </div>
      <article class="am-projects-content-wrap">
        <div class="am-projects-content-title-wrap">
          <h2 class="am-projects-content-title"><?php the_title() ?></h2>
          <p class="am-projects-content-subtitle"><?php echo $subtitle ?></p>
        </div>
        <div class="am-projects-content">
          <?php the_content(); ?>
        </div>
        <div class="am-projects-info-wrap">
          <div class="am-projects-data">
            <h3>Project Details</h3>
            <div class="am-projects-details">
              <ul class="am-projects-details-list">
              <?php
              //Stampo dettagli progetto
                foreach($project_details as $project_detail_title=>$project_detail_content){
              ?>
                <li class="am-projects-details-list-el">
                  <span class="am-projects-details-list-el-title"><?php echo $project_detail_title ?></span>
                  <?php
                    if($project_detail_title == 'Complessità' || $project_detail_title == 'Innovazione') {
                      ?>
                        <div class="am-projects-details-list-el-progress-bar-wrap">
                          <div class="am-projects-details-list-el-progress-bar-active" data-perc="<?php echo $project_detail_content ?>"></div>
                        </div>
                        <span class="am-projects-details-list-el-content"><?php echo $project_detail_content ?>%</span>
                      <?php
                      continue;
                    }
                  ?>
                  <span class="am-projects-details-list-el-content"><?php echo $project_detail_content ?></span>
                </li>
              <?php
                }
              ?>
              </ul> 
            </div>
            <div class="am-projects-icons-tooltip-wrap">
            <?php
              foreach($icons_ids_array as $icon_id) {
              ?>
              
                <div class="am-projects-icons-tooltip-el <?php echo in_array($icon_id, $icons_ids_active_array) ? 'active' : '' ?>">
                  <?php
                  if(wp_get_attachment_caption($icon_id) !== "") {
                  ?>
                    <p class="am-projects-icons-tooltip-el-caption"><?php echo wp_get_attachment_caption($icon_id); ?></p>
                  <?php
                  }
                    echo wp_get_attachment_image($icon_id);   
                  ?>
                </div>
              <?php
              }
            ?>
            </div>
          </div>
          <div class="am-projects-gallery">
            <div class="am-projects-gallery-swiper-wrapper" data-auto-scroll="<?php echo $auto_scroll ?>">
            <?php
              foreach($gallery_ids_array as $i=>$gallery_el) {
                echo $i == 0 ? "<figure class='am-projects-gallery-el last-duplicate'>" . wp_get_attachment_image($gallery_ids_array[count($gallery_ids_array) - 1], [650, 550], "", ["class" => "am-projects-gallery-el-responsive"]) . "</figure>" : null;
              ?>
                <figure class="am-projects-gallery-el <?php echo $i == 0 ? "first active" : null; echo $i == count($gallery_ids_array) - 1 ? "last" : null  ?>">
                <?php
                  echo wp_get_attachment_image($gallery_el, [650, 550], "", ["class" => "am-projects-gallery-el-responsive"]);
                ?>
                </figure>  
                <?php
                echo $i == count($gallery_ids_array) - 1 ? "<figure class='am-projects-gallery-el first-duplicate'>" . wp_get_attachment_image($gallery_ids_array[0], [650, 550], "", ["class" => "am-projects-gallery-el-responsive"]) . "</figure>" : null;
                }
              ?>
            </div>
            <div class="am-projects-gallery-swiper-icon swipe-back">
              <?php 
                include_once PLUGIN_DIR. "template/media/chevron-left.svg";
              ?>
            </div>
            <div class="am-projects-gallery-swiper-icon swipe-forward">
              <i class="fa-solid fa-chevron-left" aria-hidden="true" ></i>
              <?php 
                include_once PLUGIN_DIR. "template/media/chevron-right.svg";
              ?>
            </div>
          </div>
        </div>
      </article>
    </section>
<?php
endwhile;

get_footer();