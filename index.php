<?php 
/*
 Plugin Name: Advance It Company Pro Posttype
 Plugin URI: https://www.themesglance.com/
 Description: Creating new post type for Advance It Company Pro Theme
 Author: Themesglance
 Version: 1.0
 Author URI: https://www.themesglance.com/
*/

define( 'advance_it_company_pro_posttype_version', '1.0' );
add_action( 'init', 'projectscategory');
add_action( 'init', 'advance_it_company_pro_posttype_create_post_type' );

function advance_it_company_pro_posttype_create_post_type() {
  register_post_type( 'services',
    array(
        'labels' => array(
            'name' => __( 'Services','advance-it-company-pro-posttype' ),
            'singular_name' => __( 'Services','advance-it-company-pro-posttype' )
        ),
        'capability_type' =>  'post',
        'menu_icon'  => 'dashicons-tag',
        'public' => true,
        'supports' => array(
        'title',
        'editor',
        'thumbnail',
        'page-attributes',
        'comments'
        )
    )
  );
	register_post_type( 'projects',
    array(
        'labels' => array(
            'name' => __( 'Projects','advance-it-company-pro-posttype' ),
            'singular_name' => __( 'Projects','advance-it-company-pro-posttype' )
        ),
        'capability_type' =>  'post',
        'menu_icon'  => 'dashicons-portfolio',
        'public' => true,
        'supports' => array(
        'title',
        'editor',
        'thumbnail',
        'page-attributes',
        'comments'
        )
    )
	);

  register_post_type( 'Teams',
    array(
        'labels' => array(
            'name' => __( 'Teams','advance-it-company-pro-posttype' ),
            'singular_name' => __( 'Team','advance-it-company-pro-posttype' )
        ),
        'capability_type' =>  'post',
        'menu_icon'  => 'dashicons-welcome-learn-more',
        'public' => true,
        'supports' => array(
        'title',
        'editor',
        'thumbnail',
        'page-attributes',
        'comments'
        )
    )
  );
  register_post_type( 'testimonials',
	array(
		'labels' => array(
			'name' => __( 'Testimonials','advance-it-company-pro-posttype-pro' ),
			'singular_name' => __( 'Testimonials','advance-it-company-pro-posttype-pro' )
			),
		'capability_type' => 'post',
		'menu_icon'  => 'dashicons-businessman',
		'public' => true,
		'supports' => array(
			'title',
			'editor',
			'thumbnail'
			)
		)
	);
  
}
// --------------- Services ------------------
// Serives section
function advance_it_company_pro_posttype_images_metabox_enqueue($hook) {
  if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
    wp_enqueue_script('advance-it-company-pro-posttype-pro-images-metabox', plugin_dir_url( __FILE__ ) . '/js/img-metabox.js', array('jquery', 'jquery-ui-sortable'));

    global $post;
    if ( $post ) {
      wp_enqueue_media( array(
          'post' => $post->ID,
        )
      );
    }

  }
}
add_action('admin_enqueue_scripts', 'advance_it_company_pro_posttype_images_metabox_enqueue');
// Services Meta
function advance_it_company_pro_posttype_bn_custom_meta_services() {

    add_meta_box( 'bn_meta', __( 'Services Meta', 'advance-it-company-pro-posttype-pro' ), 'advance_it_company_pro_posttype_bn_meta_callback_services', 'services', 'normal', 'high' );
}
/* Hook things in for admin*/
if (is_admin()){
  add_action('admin_menu', 'advance_it_company_pro_posttype_bn_custom_meta_services');
}

function advance_it_company_pro_posttype_bn_meta_callback_services( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    ?>
  <div id="property_stuff">
    <table id="list-table">     
      <tbody id="the-list" data-wp-lists="list:meta">
        <tr id="meta-1">
          <p>
            <label for="meta-image"><?php echo esc_html('Icon Image'); ?></label><br>
            <input type="text" name="meta-image" id="meta-image" class="meta-image regular-text" value="<?php echo $bn_stored_meta['meta-image'][0]; ?>">
            <input type="button" class="button image-upload" value="Browse">
          </p>
          <div class="image-preview"><img src="<?php echo $bn_stored_meta['meta-image'][0]; ?>" style="max-width: 250px;"></div>
        </tr>
      </tbody>
    </table>
  </div>
  <?php
}

function advance_it_company_pro_posttype_bn_meta_save_services( $post_id ) {

  if (!isset($_POST['bn_nonce']) || !wp_verify_nonce($_POST['bn_nonce'], basename(__FILE__))) {
    return;
  }

  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }
  // Save Image
  if( isset( $_POST[ 'meta-image' ] ) ) {
      update_post_meta( $post_id, 'meta-image', esc_url_raw($_POST[ 'meta-image' ]) );
  }
  
}
add_action( 'save_post', 'advance_it_company_pro_posttype_bn_meta_save_services' );

/* Services shortcode */
function advance_it_company_pro_posttype_services_func( $atts ) {
  $services = '';
  $services = '<div class="row">';
  $query = new WP_Query( array( 'post_type' => 'services') );

    if ( $query->have_posts() ) :

  $k=1;
  $new = new WP_Query('post_type=services');

  while ($new->have_posts()) : $new->the_post();
        $custom_url ='';
        $post_id = get_the_ID();
        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'medium' );
          $url = $thumb['0'];
        $excerpt = wp_trim_words(get_the_excerpt(),25);
        $services_image= get_post_meta(get_the_ID(), 'meta-image', true);
        if(get_post_meta($post_id,'meta-services-url',true !='')){$custom_url =get_post_meta($post_id,'meta-services-url',true); } else{ $custom_url = get_permalink(); }
        $services .= '<div class="col-lg-4 col-md-4 col-sm-6">
                        <div class="services-box">
                          <div class="">
                             <div class="services_icon">
                             <img class="" src="'.esc_url($services_image).'">
                          </div>
                        </div>
                      <div class="">
                        <h4><a href="'.esc_url($custom_url).'">'.esc_html(get_the_title()) .'</a></h4>
                        <p>
                          '.$excerpt.'
                        </p>
                    </div>';
                    if (has_post_thumbnail()){
                    $services.= '<div class="services-image">
                    <img src="'.esc_url($url).'">
                    </div>';
                    }
    $services.= ' </div>
                </div>';


    if($k%2 == 0){
      $services.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $services = '<h2 class="center">'.esc_html__('Post Not Found','advance-it-company-pro-posttype').'</h2>';
  endif;
  $services .= '</div>';
  return $services;
}

add_shortcode( 'list-services', 'advance_it_company_pro_posttype_services_func' );


// ----------------- Projects Meta ---------------------
function advance_it_company_pro_posttype_bn_custom_meta_projects() {

    add_meta_box( 'bn_meta', __( 'Project Meta', 'advance-it-company-pro-posttype' ), 'advance_it_company_pro_posttype_bn_meta_callback_projects', 'projects', 'normal', 'high' );
}
/* Hook things in for admin*/
if (is_admin()){
	add_action('admin_menu', 'advance_it_company_pro_posttype_bn_custom_meta_projects');
}

function advance_it_company_pro_posttype_bn_meta_callback_projects( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    ?>
	<div id="property_stuff">
		<table id="list-table">			
			<tbody id="the-list" data-wp-lists="list:meta">
			
        <tr id="meta-2">
          <td class="left">
            <?php esc_html_e( 'Project Duration', 'advance-it-company-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="text" name="meta-project-duration" id="meta-project-duration" value="<?php echo esc_html($bn_stored_meta['meta-project-duration'][0]); ?>" />
          </td>
        </tr>
        <tr id="meta-3">
          <td class="left">
            <?php esc_html_e( 'Client Name', 'advance-it-company-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="text" name="meta-project-institution" id="meta-project-institution" value="<?php echo esc_html($bn_stored_meta['meta-project-institution'][0]); ?>" />
          </td>
        </tr>
        
      </tbody>
		</table>
	</div>
	<?php
}

function projectscategory() {
  // Add new taxonomy, make it hierarchical (like categories)
  $labels = array(
    'name'              => __( 'Categories', 'advance-it-company-pro-posttype' ),
    'singular_name'     => __( 'Categories', 'advance-it-company-pro-posttype' ),
    'search_items'      => __( 'Search cats', 'advance-it-company-pro-posttype' ),
    'all_items'         => __( 'All Categories', 'advance-it-company-pro-posttype' ),
    'parent_item'       => __( 'Parent Categories', 'advance-it-company-pro-posttype' ),
    'parent_item_colon' => __( 'Parent Categories:', 'advance-it-company-pro-posttype' ),
    'edit_item'         => __( 'Edit Categories', 'advance-it-company-pro-posttype' ),
    'update_item'       => __( 'Update Categories', 'advance-it-company-pro-posttype' ),
    'add_new_item'      => __( 'Add New Categories', 'advance-it-company-pro-posttype' ),
    'new_item_name'     => __( 'New Categories Name', 'advance-it-company-pro-posttype' ),
    'menu_name'         => __( 'Categories', 'advance-it-company-pro-posttype' ),
  );
  $args = array(
    'hierarchical'      => true,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array( 'slug' => 'projectscategory' ),
  );
  register_taxonomy( 'projectscategory', array( 'projects' ), $args );
}


function advance_it_company_pro_posttype_bn_meta_save_projects( $post_id ) {

	if (!isset($_POST['bn_nonce']) || !wp_verify_nonce($_POST['bn_nonce'], basename(__FILE__))) {
		return;
	}

	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}
	
  
  // Save Course Duration
  if( isset( $_POST[ 'meta-project-duration' ] ) ) {
    update_post_meta( $post_id, 'meta-project-duration', sanitize_text_field($_POST[ 'meta-project-duration' ]) );
  }
  // Save Course Start
  if( isset( $_POST[ 'meta-project-institution' ] ) ) {
    update_post_meta( $post_id, 'meta-project-institution', sanitize_text_field($_POST[ 'meta-project-institution' ]) );
  }


}
add_action( 'save_post', 'advance_it_company_pro_posttype_bn_meta_save_projects' );


/* --------------------- projects shortcode  ------------------- */
function advance_it_company_pro_posttype_projects_func( $atts ) {
  $projects = '';
  $projects = '<div class="row">';
  $query = new WP_Query( array( 'post_type' => 'projects') );

    if ( $query->have_posts() ) :

  $k=1;
  $new = new WP_Query('post_type=projects');

  while ($new->have_posts()) : $new->the_post();
        $custom_url ='';
        $post_id = get_the_ID();
        $projectduration= get_post_meta($post_id,'meta-project-duration',true);
        $clientname= get_post_meta($post_id,'meta-project-institution',true);
        $project_image= get_post_meta(get_the_ID(), 'meta-image', true);
        $excerpt = wp_trim_words(get_the_excerpt(),25);
        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
        if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
        
        if(get_post_meta($post_id,'meta-projects-url',true !='')){$custom_url =get_post_meta($post_id,'meta-projects-url',true); } else{ $custom_url = get_permalink(); }
        $projects .= '<div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="row projects-box">
                          <div class="col-lg-12">
                            <div class="project_icon row">
                            <div class="col-lg-3"><img class="" src="'.esc_url($project_image).'"></div>
                            <div class="col-lg-9"><h4><a href="'.esc_url($custom_url).'">'.esc_html(get_the_title()) .'</a></h4></div>
                            </div>
                            <div class="projects_icon">
                              <img src="'.esc_url($thumb_url).'">
                              <p class="project-duration">
                                Project Duration :'.$projectduration.'
                              </p>
                              <p class="project-client">
                                Client Name :'.$clientname.'
                              </p>
                            </div>
                            <p class="course-text">
                              '.$excerpt.'
                            </p>
                          </div>
                        </div>
                      </div>';


    if($k%2 == 0){
      $projects.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $projects = '<h2 class="center">'.esc_html__('Post Not Found','advance-it-company-pro-posttype-pro').'</h2>';
  endif;
  $projects .= '</div>';
  return $projects;
}

add_shortcode( 'list-projects', 'advance_it_company_pro_posttype_projects_func' );

/* ----------------- Team ---------------- */
function advance_it_company_pro_posttype_bn_designation_meta() {
    add_meta_box( 'advance_it_company_pro_posttype_bn_meta', __( 'Enter Designation','advance-it-company-pro-posttype' ), 'advance_it_company_pro_posttype_bn_meta_callback', 'Teams', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'advance_it_company_pro_posttype_bn_designation_meta');
}
/* Adds a meta box for custom post */
function advance_it_company_pro_posttype_bn_meta_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'advance_it_company_pro_posttype_bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    ?>
    <div id="Teams_custom_stuff">
        <table id="list-table">         
          <tbody id="the-list" data-wp-lists="list:meta">
              <tr id="meta-9">
                <td class="left">
                  <?php esc_html_e( 'Designation', 'advance-it-company-pro-posttype' )?>
                </td>
                <td class="left" >
                  <input type="text" name="meta-designation" id="meta-designation" value="<?php echo esc_html($bn_stored_meta['meta-designation'][0]); ?>" />
                </td>
              </tr>
              <tr id="meta-9">
                <td class="left">
                  <?php esc_html_e( 'Email', 'advance-it-company-pro-posttype' )?>
                </td>
                <td class="left" >
                  <input type="text" name="meta-Team-email" id="meta-Team-email" value="<?php echo esc_html($bn_stored_meta['meta-Team-email'][0]); ?>" />
                </td>
              </tr>
               <tr id="meta-9">
                <td class="left">
                  <?php esc_html_e( 'Phone', 'advance-it-company-pro-posttype' )?>
                </td>
                <td class="left" >
                  <input type="text" name="meta-Team-call" id="meta-Team-call" value="<?php echo esc_html($bn_stored_meta['meta-Team-call'][0]); ?>" />
                </td>
              </tr>
              <tr id="meta-3">
                <td class="left">
                  <?php esc_html_e( 'Facebook Url', 'advance-it-company-pro-posttype' )?>
                </td>
                <td class="left" >
                  <input type="url" name="meta-facebookurl" id="meta-facebookurl" value="<?php echo esc_url($bn_stored_meta['meta-facebookurl'][0]); ?>" />
                </td>
              </tr>
              <tr id="meta-4">
                <td class="left">
                  <?php esc_html_e( 'linkedin Url', 'advance-it-company-pro-posttype' )?>
                </td>
                <td class="left" >
                  <input type="url" name="meta-linkdenurl" id="meta-linkdenurl" value="<?php echo esc_url($bn_stored_meta['meta-linkdenurl'][0]); ?>" />
                </td>
              </tr>
              <tr id="meta-5">
                <td class="left">
                  <?php esc_html_e( 'Twitter Url', 'advance-it-company-pro-posttype' )?>
                </td>
                <td class="left" >
                  <input type="url" name="meta-twitterurl" id="meta-twitterurl" value="<?php echo esc_url( $bn_stored_meta['meta-twitterurl'][0]); ?>" />
                </td>
              </tr>
              <tr id="meta-6">
                <td class="left">
                  <?php esc_html_e( 'GooglePlus URL', 'advance-it-company-pro-posttype' )?>
                </td>
                <td class="left" >
                  <input type="url" name="meta-googleplusurl" id="meta-googleplusurl" value="<?php echo esc_url($bn_stored_meta['meta-googleplusurl'][0]); ?>" />
                </td>
              </tr>
              <tr id="meta-7">
                <td class="left">
                  <?php esc_html_e( 'Pinterest URL', 'advance-it-company-pro-posttype' )?>
                </td>
                <td class="left" >
                  <input type="url" name="meta-pinteresturl" id="meta-pinteresturl" value="<?php echo esc_url($bn_stored_meta['meta-pinteresturl'][0]); ?>" />
                </td>
              </tr>
               <tr id="meta-8">
                <td class="left">
                  <?php esc_html_e( 'Instagram URL', 'advance-it-company-pro-posttype' )?>
                </td>
                <td class="left" >
                  <input type="url" name="meta-instagramurl" id="meta-instagramurl" value="<?php echo esc_url($bn_stored_meta['meta-instagramurl'][0]); ?>" />
                </td>
              </tr>
              
          </tbody>
        </table>
    </div>
    <?php
}
/* Saves the custom fields meta input */
function advance_it_company_pro_posttype_bn_metadesig_Teams_save( $post_id ) {
    if( isset( $_POST[ 'meta-desig' ] ) ) {
        update_post_meta( $post_id, 'meta-desig', sanitize_text_field($_POST[ 'meta-desig' ]) );
    }
    if( isset( $_POST[ 'meta-call' ] ) ) {
        update_post_meta( $post_id, 'meta-call', sanitize_text_field($_POST[ 'meta-call' ]) );
    }
    // Save facebookurl
    if( isset( $_POST[ 'meta-facebookurl' ] ) ) {
        update_post_meta( $post_id, 'meta-facebookurl', esc_url_raw($_POST[ 'meta-facebookurl' ]) );
    }
    // Save linkdenurl
    if( isset( $_POST[ 'meta-linkdenurl' ] ) ) {
        update_post_meta( $post_id, 'meta-linkdenurl', esc_url_raw($_POST[ 'meta-linkdenurl' ]) );
    }
    if( isset( $_POST[ 'meta-twitterurl' ] ) ) {
        update_post_meta( $post_id, 'meta-twitterurl', esc_url_raw($_POST[ 'meta-twitterurl' ]) );
    }
    // Save googleplusurl
    if( isset( $_POST[ 'meta-googleplusurl' ] ) ) {
        update_post_meta( $post_id, 'meta-googleplusurl', esc_url_raw($_POST[ 'meta-googleplusurl' ]) );
    }

    // Save Pinterest
    if( isset( $_POST[ 'meta-pinteresturl' ] ) ) {
        update_post_meta( $post_id, 'meta-pinteresturl', esc_url_raw($_POST[ 'meta-pinteresturl' ]) );
    }

     // Save Instagram
    if( isset( $_POST[ 'meta-instagramurl' ] ) ) {
        update_post_meta( $post_id, 'meta-instagramurl', esc_url_raw($_POST[ 'meta-instagramurl' ]) );
    }
    // Save designation
    if( isset( $_POST[ 'meta-designation' ] ) ) {
        update_post_meta( $post_id, 'meta-designation', sanitize_text_field($_POST[ 'meta-designation' ]) );
    }

    // Save Email
    if( isset( $_POST[ 'meta-Team-email' ] ) ) {
        update_post_meta( $post_id, 'meta-Team-email', sanitize_text_field($_POST[ 'meta-Team-email' ]) );
    }
    // Save Call
    if( isset( $_POST[ 'meta-Team-call' ] ) ) {
        update_post_meta( $post_id, 'meta-Team-call', sanitize_text_field($_POST[ 'meta-Team-call' ]) );
    }
}
add_action( 'save_post', 'advance_it_company_pro_posttype_bn_metadesig_Teams_save' );

/* Teams shorthcode */
function advance_it_company_pro_posttype_Teams_func( $atts ) {
    $Teams = ''; 
    $custom_url ='';
    $Teams = '<div class="row">';
    $query = new WP_Query( array( 'post_type' => 'Teams' ) );
    if ( $query->have_posts() ) :
    $k=1;
    $new = new WP_Query('post_type=Teams'); 
    while ($new->have_posts()) : $new->the_post();
    	$post_id = get_the_ID();
    	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
      if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
		  $url = $thumb['0'];
      $excerpt = wp_trim_words(get_the_excerpt(),25);
      $designation= get_post_meta($post_id,'meta-designation',true);
      $call= get_post_meta($post_id,'meta-call',true);
      $facebookurl= get_post_meta($post_id,'meta-facebookurl',true);
      $linkedin=get_post_meta($post_id,'meta-linkdenurl',true);
      $twitter=get_post_meta($post_id,'meta-twitterurl',true);
      $googleplus=get_post_meta($post_id,'meta-googleplusurl',true);
      $pinterest=get_post_meta($post_id,'meta-pinteresturl',true);
      $instagram=get_post_meta($post_id,'meta-instagramurl',true);
      $Teams .= '<div class="Teams_box col-lg-4 col-md-6 col-sm-6">
                    <div class="image-box ">
                      <img class="client-img" src="'.esc_url($thumb_url).'" alt="Teams-thumbnail" />
                      <div class="Teams-box w-100 float-left">
                        <h4 class="Teams_name"><a href="'.get_permalink().'">'.get_the_title().'</a></h4>
                        <p class="designation">'.esc_html($designation).'</p>
                      </div>
                    </div>
                  <div class="content_box w-100 float-left">
                    <div class="short_text">'.$excerpt.'</div>
                    <div class="about-socialbox">
                      <p>'.$call.'</p>
                      <div class="inst_socialbox">';
                        if($facebookurl != ''){
                          $Teams .= '<a class="" href="'.esc_url($facebookurl).'" target="_blank"><i class="fab fa-facebook-f"></i></a>';
                        } if($twitter != ''){
                          $Teams .= '<a class="" href="'.esc_url($twitter).'" target="_blank"><i class="fab fa-twitter"></i></a>';
                        } if($googleplus != ''){
                          $Teams .= '<a class="" href="'.esc_url($googleplus).'" target="_blank"><i class="fab fa-google-plus-g"></i></a>';
                        } if($linkedin != ''){
                          $Teams .= '<a class="" href="'.esc_url($linkedin).'" target="_blank"><i class="fab fa-linkedin-in"></i></a>';
                        }if($pinterest != ''){
                          $Teams .= '<a class="" href="'.esc_url($pinterest).'" target="_blank"><i class="fab fa-pinterest-p"></i></a>';
                        }if($instagram != ''){
                          $Teams .= '<a class="" href="'.esc_url($instagram).'" target="_blank"><i class="fab fa-instagram"></i></a>';
                        }
                      $Teams .= '</div>
                    </div>
                  </div>
                </div>';

      if($k%2 == 0){
          $Teams.= '<div class="clearfix"></div>'; 
      } 
      $k++;         
  endwhile; 
  wp_reset_postdata();
  $Teams.= '</div>';
  else :
    $Teams = '<h2 class="center">'.esc_html_e('Not Found','advance-it-company-pro-posttype').'</h2>';
  endif;
  return $Teams;
}
add_shortcode( 'Teams', 'advance_it_company_pro_posttype_Teams_func' );

/* Testimonial section */
/* Adds a meta box to the Testimonial editing screen */
function advance_it_company_pro_posttype_bn_testimonial_meta_box() {
	add_meta_box( 'advance-it-company-pro-posttype-pro-testimonial-meta', __( 'Enter Designation', 'advance-it-company-pro-posttype-pro' ), 'advance_it_company_pro_posttype_bn_testimonial_meta_callback', 'testimonials', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'advance_it_company_pro_posttype_bn_testimonial_meta_box');
}

/* Adds a meta box for custom post */
function advance_it_company_pro_posttype_bn_testimonial_meta_callback( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'advance_it_company_pro_posttype_posttype_testimonial_meta_nonce' );
  $bn_stored_meta = get_post_meta( $post->ID );
	$desigstory = get_post_meta( $post->ID, 'advance_it_company_pro_posttype_posttype_testimonial_desigstory', true );
	?>
	<div id="testimonials_custom_stuff">
		<table id="list">
			<tbody id="the-list" data-wp-lists="list:meta">
        <tr id="meta-9">
          <td class="left">
            <?php esc_html_e( 'Designation', 'advance-it-company-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="text" name="student-course" id="student-course" value="<?php echo esc_html($bn_stored_meta['student-course'][0]); ?>" />
          </td>
        </tr>
        <tr id="meta-3">
          <td class="left">
            <?php esc_html_e( 'Facebook Url', 'advance-it-company-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="url" name="meta-tes-facebookurl" id="meta-tes-facebookurl" value="<?php echo esc_url($bn_stored_meta['meta-tes-facebookurl'][0]); ?>" />
          </td>
        </tr>
        <tr id="meta-5">
          <td class="left">
            <?php esc_html_e( 'Twitter Url', 'advance-it-company-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="url" name="meta-tes-twitterurl" id="meta-tes-twitterurl" value="<?php echo esc_url( $bn_stored_meta['meta-tes-twitterurl'][0]); ?>" />
          </td>
        </tr>
        <tr id="meta-6">
          <td class="left">
            <?php esc_html_e( 'GooglePlus URL', 'advance-it-company-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="url" name="meta-tes-googleplusurl" id="meta-tes-googleplusurl" value="<?php echo esc_url($bn_stored_meta['meta-tes-googleplusurl'][0]); ?>" />
          </td>
        </tr>
        <tr id="meta-7">
          <td class="left">
            <?php esc_html_e( 'Pinterest URL', 'advance-it-company-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="url" name="meta-tes-pinteresturl" id="meta-tes-pinteresturl" value="<?php echo esc_url($bn_stored_meta['meta-tes-pinteresturl'][0]); ?>" />
          </td>
        </tr>
        <tr id="meta-8">
          <td class="left">
            <?php esc_html_e( 'Instagram URL', 'advance-it-company-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="url" name="meta-tes-instagramurl" id="meta-tes-instagramurl" value="<?php echo esc_url($bn_stored_meta['meta-tes-instagramurl'][0]); ?>" />
          </td>
        </tr>
      </tbody>
		</table>
	</div>
	<?php
}

/* Saves the custom meta input */
function advance_it_company_pro_posttype_bn_metadesig_save( $post_id ) {
	if (!isset($_POST['advance_it_company_pro_posttype_posttype_testimonial_meta_nonce']) || !wp_verify_nonce($_POST['advance_it_company_pro_posttype_posttype_testimonial_meta_nonce'], basename(__FILE__))) {
		return;
	}

	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	// Save desig.
	if( isset( $_POST[ 'advance_it_company_pro_posttype_posttype_testimonial_desigstory' ] ) ) {
		update_post_meta( $post_id, 'advance_it_company_pro_posttype_posttype_testimonial_desigstory', sanitize_text_field($_POST[ 'advance_it_company_pro_posttype_posttype_testimonial_desigstory']) );
	}
  
  // Course Name
  if( isset( $_POST[ 'student-course' ] ) ) {
        update_post_meta( $post_id, 'student-course', sanitize_text_field($_POST[ 'student-course' ]) );
  } 

  // Save facebookurl
    if( isset( $_POST[ 'meta-tes-facebookurl' ] ) ) {
        update_post_meta( $post_id, 'meta-tes-facebookurl', esc_url_raw($_POST[ 'meta-tes-facebookurl' ]) );
    }
    
    if( isset( $_POST[ 'meta-tes-twitterurl' ] ) ) {
        update_post_meta( $post_id, 'meta-tes-twitterurl', esc_url_raw($_POST[ 'meta-tes-twitterurl' ]) );
    }
    // Save googleplusurl
    if( isset( $_POST[ 'meta-tes-googleplusurl' ] ) ) {
        update_post_meta( $post_id, 'meta-tes-googleplusurl', esc_url_raw($_POST[ 'meta-tes-googleplusurl' ]) );
    }

    // Save Pinterest
    if( isset( $_POST[ 'meta-tes-pinteresturl' ] ) ) {
        update_post_meta( $post_id, 'meta-tes-pinteresturl', esc_url_raw($_POST[ 'meta-tes-pinteresturl' ]) );
    }

     // Save Instagram
    if( isset( $_POST[ 'meta-tes-instagramurl' ] ) ) {
        update_post_meta( $post_id, 'meta-tes-instagramurl', esc_url_raw($_POST[ 'meta-tes-instagramurl' ]) );
    }

}

add_action( 'save_post', 'advance_it_company_pro_posttype_bn_metadesig_save' );

/* Testimonials shortcode */
function advance_it_company_pro_posttype_testimonial_func( $atts ) {
	$testimonial = '';
	$testimonial = '<div class="row">';
	$query = new WP_Query( array( 'post_type' => 'testimonials') );

    if ( $query->have_posts() ) :

	$k=1;
	$new = new WP_Query('post_type=testimonials');

	while ($new->have_posts()) : $new->the_post();
        $custom_url = '';
      	$post_id = get_the_ID();
      	$excerpt = wp_trim_words(get_the_excerpt(),25);
        $course= get_post_meta($post_id,'student-course',true);
        $testimonail_image= get_post_meta(get_the_ID(), 'meta-image', true);
                             
      	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
		    if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
        $testimonial .= '
          <div class="col-lg-4 col-md-6 col-sm-6">
            <div class="testimonial_box mb-3">
              <img class="" src="'.esc_url($testimonail_image).'">
              <div class="short_text pt-1"><p>'.$excerpt.'</p></div>
              <div class="image-box">
                <img class="testi-img" src="'.esc_url($thumb_url).'" />
                <div class="content_box">
                 <h4 class="testimonial_name"><a href="'.get_permalink().'">'.esc_html(get_the_title()) .'</a></h4>
                </div>
                <div class="testimonial-box">    
                    <p class="desig-name"> - '.esc_html($course).'</p>
                </div>
              </div>
            </div>
          </div>';
		if($k%3 == 0){
			$testimonial.= '<div class="clearfix"></div>';
		}
      $k++;
  endwhile;
  else :
  	$testimonial = '<h2 class="center">'.esc_html__('Post Not Found','advance-it-company-pro-posttype-pro').'</h2>';
  endif;
  $testimonial .= '</div>';
  return $testimonial;
}
add_shortcode( 'testimonials', 'advance_it_company_pro_posttype_testimonial_func' );





