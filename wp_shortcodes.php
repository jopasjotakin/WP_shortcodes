/* WORDPRESS SHORTCODES CREATED BY JOANNA */

/* 1. Post listing */
/* Displays a list of custom type posts (in this case, lists all staff members). */

<?php
function staff_member_list() {

    /* Array for arguments */

    $args = array(
        'post_type'       => 'henkilosto',
        'post_status' => 'publish',
        'posts_per_page' => 24, 
        'orderby' => 'menu_order',
        'order' => 'ASC'
        );

    /* Query for arguments */
    $p_query = new WP_Query( $args );
    /* Checks whether or not the query has posts */
    if ( $p_query->have_posts() ) {
        
        /* Creates a variable for a HTML element to show the posts */
        $buffer = '<div class="person-cards">';

        /* Gets info from all the posts */
        while ( $p_query->have_posts() ) : $p_query->the_post();

            $size = 'medium_large'; // (thumbnail, medium, large, full or custom size)
            $person_name = get_field('nimi');
            $vastuu = get_field('vastuualue');
            $titteli = get_field('titteli');
            $email = get_field('sahkoposti');
            $phone = get_field('puhelin');
            $phonelink = str_replace(' ', '', $phone);
            $emaillink = str_replace('(at)', '@', $email);

            /* Changes the variable to show all the info in the element */
            $buffer = $buffer . '<div class="person-card">
            <div class="person-img">' . get_the_post_thumbnail($post, $size) . '</div>
            <div class="person-details">
            <h6 class="person-title">' . $titteli . '</h6>
            <h2 class="person-name">' . $person_name . '</h2>
            <h6 class="person-resp">' . $vastuu . '</h6>
            <div class="person-phone"><a href="tel:' . $phonelink . '">' . $phone . '</a></div>
            <div class="person-email"><a href="mailto:' . $emaillink . '">' . $email . '</a></div>
            </div>
            </div>';

        endwhile;
        $buffer = $buffer .  '</div>';
    wp_reset_postdata();
        return $buffer;
    }
}
?>

/* 2. Link listing */
/* Displays links from posts added with ACF fields. */

<?php
function show_links() {

    /* Checks if ACF is used */
    if (class_exists('ACF')) {

        /* Checks if post is custom post type 1, custom post type 2 or regular post. */
        if(is_singular( 'custom_posttype_1' ) || is_singular( 'custom_posttype_2' ) || is_singular( 'post' )) {

            /* Checks if post has fields called 'content_links */
            if( have_rows('content_links') ) { 
                ob_start(); ?>

                <!-- Creates HTML element to display links -->
                <div class="aihelinkit">
                <h3>Linkit</h3><br>
                <ul><?php

                /* Gets all of the links and adds them to the element  */
                while( have_rows('content_links') ) : the_row();

                $linkurl = get_sub_field('link_url');
                $linktext = get_sub_field('link_text'); 

                ?><li><a href="<?php echo $linkurl ?>" target="_blank"><?php echo $linktext ?></a></li><?php

                endwhile; ?>

                </ul>
                </div><?php

                return ob_get_clean();
            }
        } 
    }
    return '';
}
?>

/* 3 & 4. A certain landing page */
/* These are shortcodes that I created to display content for one of our customers' landing page.
   The theme used for that site was made by some local developer years ago and if we wanted to display any new content,
   we basically had to use CPT UI, ACF fields and custom shortcodes for that. This landing page had the content split in two functions, one 
   for the header (top) and one for the rest. */

   <?php

/* Function for the top part of the landing page */
function landingpage_top() {
/* Creates a variable for WP Query that gets content of landing page posts */
$loop = new WP_Query(
    array(
        'post_type' => 'lander',
        'posts_per_page' => 50,
		'order' => 'ASC',
		'category__and' => array( 71, 69 ),
		'post_status' => array( 'publish', 'private')
    )
);

/* Checks if there are any posts in the query */
if($loop->have_posts()) {
    /* While there are posts, get the posts */
	while($loop->have_posts()): $loop->the_post(); 
      
      /* Variables for different info gathered from ACF fields */
	  $id = $post->ID;
      $conpl = get_field('content_placement', $id);
      $posttitle = get_field('otsikko', $id);
      $buttonlink = get_field('button_link', $id);
      $buttontext = get_field('button_text', $id);
      $imgurl = get_field('tagline_bg_url', $id); ?>

      <!-- HTML element to display everything -->
      <div class="_landingpage_top_container" style="<?php if($conpl == 'oikea') { ?> flex-direction: row-reverse; <?php } ?>">
        <div class="_landingpage_top_text_container">
          <h1 class="do-display"> <?php echo $posttitle; ?> </h1>
	      <span><?php the_content($id); ?></span>
	      <a href="<?php echo $buttonlink; ?>" class="_btn_yellow"><?php echo $buttontext; ?></a>
        </div>
	    <div class="_landingpage_top_img_container">
          <img src="<?php echo $imgurl ?>" />
		</div>
      </div>

<?php
endwhile;
}
}

/* Function for the content part (it's almost the same, it just has more fields) */

function landingpage_content() {

$loop = new WP_Query(
    array(
        'post_type' => 'lander',
        'posts_per_page' => 50,
		'order' => 'ASC',
		'category__and' => array( 71, 70 ),
		'post_status' => array( 'publish', 'private')
    )
);

if($loop->have_posts()) {
	while($loop->have_posts()): $loop->the_post(); 
      $id = $post->ID; 
      $conpl = get_field('content_placement', $id); 
	  $posttitle = get_field('otsikko', $id);
	  $tagline = get_field('tagline', $id);
      $tagleft = get_field('tagline_left', $id); 
      $tagtop = get_field('tagline_top', $id);  
      $tagbg = get_field('tagline_bg_url', $id); 
      $showbtn = get_field('show_btn', $id); 
 $buttontext = get_field('button_text', $id);
      $imgurl = get_field('tagline_bg_url', $id);?>

     
      <div class="lp_content_container">
		  <div class="lp_content_wrapper" style="<?php if($conpl == 'oikea') { ?> flex-direction: row-reverse; <?php } ?>">
	      <div class="lp_content_content" style="<?php if($conpl == 'vasen') { ?> text-align: right; <?php } ?>">
	        <h3>
			  <?php echo $posttitle; ?>
			</h3>
			<span><?php the_content($id); ?></span>
		  </div>
		  <div class="lp_content_tagline" style="background: url('<?php echo $tagbg ?>') no-repeat center; left: <?php echo $tagleft;?>em; top: <?php echo $tagtop ?>em;">
		    <span><?php echo $tagline; ?></span>
	      </div>
			  </div>
		  <?php if($showbtn == true) { ?>
		  <div class="lp_content_btn">
			  <a href="<?php echo $buttonlink; ?>" class="_btn_yellow"><?php echo $buttontext; ?></a>
		  </div>
	 <?php } ?>
		</div>
		
		<?php
endwhile;
}
}
	?>

/* 5. Add button */
/* This function adds a button to event type posts */

<?php
function back_to_all_events() {
    if(is_singular( 'event' ) {
        /* Sets variable for current post */
	    $post = get_post();
        /* Sets variable for current time */ 
		$today = strtotime(date("Ymd"));
        /* Gets the end date of the event from ACF field */
		$event_ends = get_post_meta($post->ID, 'evt_end_date', true);
        /* Sets variable for the end date and converts it to time value */
        $end_date = strtotime($nayttely_ends);
		
        /* Checks if event has ended */
		if($today > $end_date) { ?>
            <!-- If event has ended, creates a link that takes you back to the page that displays past events -->
			<a href="/event-archive" class="go-back-button">« BACK TO ALL EVENTS</a>
<?php
		}
			else { ?>
            <!-- If event is still going, creates a link that takes you back to the page that displays current events -->
            <a href="/nayttelyt" class="go-back-button">« TAKAISIN NÄYTTELYIHIN</a>
<?php
				 }
return ob_get_clean();

		}
    }