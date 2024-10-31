<?php
/*
* Plugin Name: NGO-production
* Plugin URI: https://ngo-portal.org
* Description: Plugin to create events of the type "Theater production". Creates a custom post type production with actors taxonomy etc. It only needs to be active on the sites that needs it.
* Version: 1.2.2
* Author: George Bredberg
* Author URI: https://datagaraget.se
* Text Domain: ngo-production
* Domain Path: /languages
* License   GPL-2.0 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// Prevent direct access to this file.
if ( ! defined( 'ABSPATH' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	echo 'This file should not be accessed directly!';
	exit; // Exit if accessed directly
}

// Load translation
add_action( 'plugins_loaded', 'ngop_load_plugin_textdomain' );
function ngop_load_plugin_textdomain() {
	load_plugin_textdomain( 'ngo-production', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}

// Check if mother-plugin is installed and activated
// If not, show a warning message.
add_action( 'admin_init', 'ngop_plugin_has_parent_plugin' );
function ngop_plugin_has_parent_plugin() {
	$req_plugin = 'wp-custom-taxonomy-image/wp-custom-taxonomy-image.php';
	if ( is_admin() && current_user_can( 'activate_plugins' ) &&  !is_plugin_active( $req_plugin ) ) {
		add_action( 'admin_notices', 'ngop_plugin_notice' );
	}
}

function ngop_plugin_notice(){
	?><div class="error notice is-dismissable"><p><?php _e( 'The plugin theatre production need the plugin "WP Custom Taxonomy Image" to view pictures of the actors and scenes.', 'ngo-production'); ?></p></div><?php
}
// Done complaining about missing plugin...

// + ACTIONS AND FILTERS
// ===========================================================
register_activation_hook( __FILE__, 'ngop_pluginprefix_install' );			// Flush rewrite rules when posttype is instanceated (plugin activated)
register_deactivation_hook( __FILE__, 'ngop_pluginprefix_deactivation' );	// Flush rewrite rules when posttype is unregistered (plugin deactivated)
add_action( 'init', 'ngop_custom_post' );							// Creates custom post type productions
add_action( 'contextual_help', 'ngop_contextual_help', 10, 3 );	// Adds contextual help to custom post type productions
add_action( 'init', 'ngop_taxonomies', 0 );					// Adds custom "category style" taxonomies to productions
add_action( 'add_meta_boxes', 'ngop_meta_box' );			// Adds a box in post type productions to add meta info
add_action( 'save_post', 'ngop_meta_box_save' );			// Well, we need to save this...
add_action( 'add_meta_boxes', 'ngop_remove_metaboxes' );	// Removes comments field in edit production.

add_filter( 'post_updated_messages', 'ngop_updated_messages' );	// Adds custom messages to post type productions

// Refresh WordPress permalinks when the plugin registers custom post type. This gets rid of these nasty 404 errors.
function ngop_pluginprefix_install() {
	// Trigger our function that registers the custom post type
	ngop_custom_post();

	// Clear the permalinks after the post type has been registered
	flush_rewrite_rules();
}

// Refresh WordPress permalinks when the plugin unregisters custom post type. To get rid of custom post type rewrite rules.
function ngop_pluginprefix_deactivation() {
	// Our post type will be automatically removed, so no need to unregister it

	// Clear the permalinks to remove our post type's rules
	flush_rewrite_rules();
}

// Creates the custom post type production, meant to be used for events regarding theater productions.
// menu_position - Defines the position of the custom post type menu in the back end. Setting it to “5” places it below the “posts” menu; the higher you set it, the lower the menu will be placed.
function ngop_custom_post() {
	$labels = array(
		'name'               => _x( 'Productions', 'post type general name', 'ngo-production' ),
		'singular_name'      => _x( 'Production', 'post type singular name', 'ngo-production' ),
		'add_new'            => _x( 'Add New', 'book', 'ngo-production' ),
		'add_new_item'       => __( 'Add New Production', 'ngo-production' ),
		'edit_item'          => __( 'Change production', 'ngo-production' ),
		'new_item'           => __( 'New production', 'ngo-production' ),
		'all_items'          => __( 'All Productions', 'ngo-production' ),
		'view_item'          => __( 'Show production', 'ngo-production' ),
		'search_items'       => __( 'Search production', 'ngo-production' ),
		'not_found'          => __( 'No productions found', 'ngo-production' ),
		'not_found_in_trash' => __( 'No productions found in the trash', 'ngo-production' ),
		'parent_item_colon'  => '',
//	'featured_image'	=> __( 'Poster', 'ngo-production' ), //could be used if we don't want to move the image form in backoffice
		'menu_name'          => __( 'Productions', 'ngo-production' )
	);
	$args = array(
		'labels'        => $labels,
		'description'   => __( 'Creates post type for productions and production specific data' ),
		'public'        => true,
		'menu_position' => 5,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt' ), // Add 'comments' if it's going to be used.
		'menu_icon'     => 'dashicons-video-alt',
		'has_archive'   => true,
	);
	register_post_type( 'production', $args );
}

// Change name for "Thumbnail" to "Affish" and move it out from the side bar.
add_action('do_meta_boxes', 'ngop_change_image_box');
function ngop_change_image_box() {
	remove_meta_box( 'postimagediv', 'production', 'side' );
	add_meta_box('postimagediv', __('Poster'), 'post_thumbnail_meta_box', 'production', 'normal', 'high');
}

// Adds custom messages to post type productions
function ngop_updated_messages( $messages ) {
	global $post, $post_ID;
	$messages['production'] = array(
		0 => '',
		1 => sprintf( __('Production updated. <a href="%s">View post</a>', 'ngo-production'), esc_url( get_permalink($post_ID) ) ),
		2 => __('Custom field updated.', 'ngo-production'),
		3 => __('Custom field deleted.', 'ngo-production'),
		4 => __('Production updated.', 'ngo-production'),
		5 => isset($_GET['revision']) ? sprintf( __('Production is restored to audit from %s', 'ngo-production'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Production is published. <a href="%s">Show production</a>', 'ngo-production'), esc_url( get_permalink($post_ID) ) ),
		7 => __('The production is saved.', 'ngo-production'),
		8 => sprintf( __('Production is added. <a target="_blank" href="%s">Preview production</a>', 'ngo-production'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __('The production is scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview production</a>', 'ngo-production'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __('Production draft is updated. <a target="_blank" href="%s">Preview production</a>', 'ngo-production'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);
	return $messages;
}

// Remove Comments metaboxes from custom post type production
//FIX: Add a check, if Comments are allowed? (See ngo-disable-comments)
function ngop_remove_metaboxes(){
	// Remove comments fields
	remove_meta_box( 'commentstatusdiv' , 'production' , 'normal' ); //removes comments status
	remove_meta_box( 'commentsdiv' , 'production' , 'normal' ); //removes comments
}

// Adds contextual help to custom post type productions
function ngop_contextual_help( $contextual_help, $screen_id, $screen ) {
	if ( 'edit-production' == $screen->id ) {

		$contextual_help = '<h2>' . __('Productions', 'ngo-production') . '</h2>
		<p>' . __('Productions provides information about theater productions that will be shown at your Web page. You can see a list of them on this page, in reverse order - the most recently added appears first.', 'ngo-production') . '</p>
		<p>' . __('You can view/modify the information about a production by clicking on its name, or by doing a bulk change by selecting several productions, and then use the drop list "Select action ...".', 'ngo-production') . '</p>';

	} elseif ( 'production' == $screen->id ) {

		$contextual_help = '<h2>' . __('Change production', 'ngo-production') . '</h2>
		<p>' . __('On this page you can view and change information about theater productions. Please ensure that you fill in the forms with the appropriate information (production picture, actors, other participants, venue) and to <strong>not</strong> add this to the main page of the production. (If  you do then this information will appear twice on the page.)', 'ngo-production') . '</p>
		<p>' . __('In the field "Additional information", you can add information about the Director, Scenografer, Props, etc.', 'ngo-production') . '<br />' .
		__('It will look best if you type "Header: Information" and then add a new line. E.g. Scriptwriter: Important Person', 'ngo-production') . '</p>
		<p>' . __('Don\'t forget to add a picture in the "Selected picture" field. It will appear as a thumbnail. It could be a copy of the production\'s poster, so it\'s easily recognized.', 'ngo-production') . '</p>';

	}
	return $contextual_help;
}

// Adds custom "category style" taxonomies to productions called "Genre"
function ngop_taxonomies() {
	$labels = array(
		'name'              => _x( 'Productions Genre', 'taxonomy general name', 'ngo-production' ),
		'singular_name'     => _x( 'Production Genre', 'taxonomy singular name', 'ngo-production' ),
		'search_items'      => __( 'Search genre for productions', 'ngo-production' ),
		'all_items'         => __( 'All Production Genres', 'ngo-production' ),
		'parent_item'       => __( 'Parent production genre', 'ngo-production' ),
		'parent_item_colon' => __( 'Parent production genre:', 'ngo-production' ),
		'edit_item'         => __( 'Change production genre', 'ngo-production' ),
		'update_item'       => __( 'Update production genre', 'ngo-production' ),
		'add_new_item'      => __( 'Add production genre', 'ngo-production' ),
		'new_item_name'     => __( 'New production genre', 'ngo-production' ),
		'menu_name'         => __( 'Production genre', 'ngo-production' ),
	);
	$args = array(
		'labels' => $labels,
		'hierarchical' => true,
	);
	register_taxonomy( 'production_category', 'production', $args );

	// Adds custom "tags style" taxonomies to productions called "production_scenes"
	$labels = array(
		'name'                       => _x( 'Venues', 'taxonomy general name', 'ngo-production' ),
		'singular_name'              => _x( 'Venue', 'taxonomy singular name', 'ngo-production' ),
		'search_items'               => __( 'Venues', 'ngo-production' ),
		'all_items'                  => __( 'All Venues', 'ngo-production' ),
		'parent_item'                => __( 'Parent Venue', 'ngo-production' ),
		'parent_item_colon'          => __( 'Parent Venue:', 'ngo-production' ),
		'edit_item'                  => __( 'Change Venue', 'ngo-production' ),
		'update_item'                => __( 'Update Venue', 'ngo-production' ),
		'add_new_item'               => __( 'Add Venue', 'ngo-production' ),
		'new_item_name'              => __( 'New name for Venue', 'ngo-production' ),
		'menu_name'                  => __( 'Venues', 'ngo-production' ),
	);

	$args = array(
	'labels'                => $labels,
	'hierarchical'          => true,
	'show_ui'               => true,
	'show_admin_column'     => true,
	);

	register_taxonomy( 'production_scenes', 'production', $args );

// Adds custom "tags style" taxonomies to produktions called "production_actors"
	$labels = array(
		'name'                       => _x( 'Actors', 'taxonomy general name', 'ngo-production' ),
		'singular_name'              => _x( 'Actor', 'taxonomy singular name', 'ngo-production' ),
		'search_items'               => __( 'Actors', 'ngo-production') ,
		'popular_items'              => __( 'Popular Actors', 'ngo-production' ),
		'all_items'                  => __( 'All Actors', 'ngo-production' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Change Actor', 'ngo-production' ),
		'update_item'                => __( 'Update Actor', 'ngo-production' ),
		'add_new_item'               => __( 'Add Actor', 'ngo-production' ),
		'new_item_name'              => __( 'New name for the Actor', 'ngo-production' ),
		'separate_items_with_commas' => __( 'Separate the Actors with a comma', 'ngo-production' ),
		'add_or_remove_items'        => __( 'Add or remove Actor', 'ngo-production' ),
		'choose_from_most_used'      => __( 'Select from mostly used Actors', 'ngo-production' ),
		'not_found'                  => __( 'Did not find any Actor. (Maybe you should create some?)', 'ngo-production' ),
		'menu_name'                  => __( 'Actors', 'ngo-production' ),
	);

	$args = array(
		'hierarchical'          => false,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'rewrite'               => array( 'slug' => 'skadespelare' ),
	);

	register_taxonomy( 'production_actors', 'production', $args );
}

// Adds a box in post type productions to add meta info.
function ngop_meta_box() {
	add_meta_box(
		'ngop_meta_box',
		__( 'Additional info', 'ngo-production' ),
		'ngop_meta_content',
		'production',
		'side',
		'high'
	);
}

// Load jQuery datepicker
function ngop_load_datepicker_scripts() {
	// Enqueue Datepicker + jQuery UI CSS
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_style( 'jquery-ui-style', plugins_url( '/css/jquery-ui.css', __FILE__ ), true);
}

function ngop_meta_content( $post ) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ngop_meta_content_nonce' );

	// Get the data if its already been entered
	global $post;
	$pMetaInfo = get_post_meta($post->ID, 'ngop_meta_info', true);
	$pFirstPerf = get_post_meta( $post->ID, 'ngop_first_performance', true  );
	$pLastPerf = get_post_meta( $post->ID, 'ngop_last_performance', true  );
	$pNoPerf = get_post_meta($post->ID, 'ngop_performances', true);
	$pTicket = get_post_meta($post->ID, 'ngop_ticket_url', true);

	// Create the custom info box
	echo '<label for="ngop_meta_info"></label>';

	// Co workers
	?><p><strong><?php _e( 'Coworkers, other then actors:', 'ngo-production' ); ?></strong><br/>
	<textarea name="ngop_meta_info" id="ngop_meta_info" cols="32" rows="6" placeholder="<?php _e( 'Enter the metainfo, like set designer, script writer &#10; make-up artist &#10; for example; &#10; Director: Any Person &#10; Script: Someone Else', 'ngo-production'); ?>"><?php echo $pMetaInfo; ?></textarea></p><?php

	// First performance
	?><p><strong><?php _e( 'Date of first performance:', 'ngo-production' ); ?></strong><br/><?php
	// Enqueue Datepicker + jQuery UI CSS
	//FIX: Translate this, see links down on next invocation
	ngop_load_datepicker_scripts(); ?>
	<script>
	jQuery(document).ready(function(){
		jQuery('#ngop_first_performance').datepicker({
		autoSize: true,
		dateFormat : 'yy-mm-dd',
		appendText: " (åååå-mm-dd)",
		firstDay : 1,
		dayNamesMin: [ "Sö", "Må", "Ti", "On", "To", "Fr", "Lö" ],
		monthNames: [ "Januari", "Februari", "Mars", "April", "Maj", "Juni", "Juli", "Augusti", "September", "Oktober", "November", "December" ],
		constrainInput: true,
		showOn: "button",
		changeMonth: true,
		changeYear: true,
		yearRange: "-1:+3",
		showWeek: true,
		weekHeader: "v."
		});
	});
	</script>
	<input type="text" name="ngop_form_first_performance" id="ngop_first_performance" value="<?php echo $pFirstPerf; ?>" /></p>
	<?php

	// Last performance
	?><p><strong><?php _e( 'Date of last performance:', 'ngo-production' ); ?></strong><br/>(<?php _e('leave blank if only one gig', 'ngo-production');?>)<br/><?php
	// Enqueue Datepicker + jQuery UI CSS
	// To localize see: http://www.tutorialspark.com/jqueryUI/jQuery_UI_DatePicker_Events.php
	// To set time see: http://www.c-sharpcorner.com/UploadFile/manas1/more-on-jquery-date-picker/
	//FIX: Translate this
	ngop_load_datepicker_scripts(); ?>
	<script>
	jQuery(document).ready(function(){
		jQuery('#ngop_last_performance').datepicker({
		autoSize: true,
		dateFormat : 'yy-mm-dd',
		appendText: " (åååå-mm-dd)",
		firstDay : 1,
		dayNamesMin: [ "Sö", "Må", "Ti", "On", "To", "Fr", "Lö" ],
		monthNames: [ "Januari", "Februari", "Mars", "April", "Maj", "Juni", "Juli", "Augusti", "September", "Oktober", "November", "December" ],
		constrainInput: true,
		showOn: "button",
		changeMonth: true,
		changeYear: true,
		yearRange: "-1:+3",
		showWeek: true,
		weekHeader: "v."
		});
	});
	</script>
	<input type="text" name="ngop_form_last_performance" id="ngop_last_performance" value="<?php echo $pLastPerf; ?>" /></p>
	<?php

	// Total no of performances
	?><p><strong><?php _e( 'Number of performances:', 'ngo-production' ); ?></strong><br/><?php
	echo '<input type="text" name="ngop_performances" value="' . $pNoPerf  . '" class="widefat" /></p>';

	// url to tickets
	?><p><strong><?php _e( 'Buy tickets here url:', 'ngo-production' ); ?></strong><br/><?php
	echo '<input type="text" name="ngop_ticket_url" value="' . $pTicket  . '" class="widefat" /></p>';
}

// We need to save this...
// Sec: Check for submission, then nonce, then user privileges
function ngop_meta_box_save( $post_id ) {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
	return;

	if ( ( !empty(  $_POST['ngop_meta_content_nonce'] ) ) && ( !wp_verify_nonce( $_POST['ngop_meta_content_nonce'], plugin_basename( __FILE__ ) ) ) )
	return;

	if ( ( !empty($_POST['post_type']) ) && ( 'page' == $_POST['post_type'] ) ) {
		if ( !current_user_can( 'edit_page', $post_id ) )
		return;
	} else {
		if ( !current_user_can( 'edit_post', $post_id ) )
		return;
	}
	// FIX: It would be nicer with an array...
	if ( !empty($_POST['ngop_meta_info']) ) {
		//Sanitize, but keep linebreaks
		$ngop_meta_info = implode( "\n", array_map( 'sanitize_text_field', explode( "\n", $_POST['ngop_meta_info'] ) ) );
		update_post_meta( $post_id, 'ngop_meta_info', $ngop_meta_info );
	}
	if ( !empty($_POST['ngop_form_first_performance']) ) {
		$pFirstPerf = sanitize_text_field($_POST['ngop_form_first_performance']);
		$split = array();
		if (preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $pFirstPerf, $split))
		{
			if(wp_checkdate($split[2],$split[3],$split[1],$pFirstPerf))
			{
				update_post_meta( $post_id, 'ngop_first_performance', $pFirstPerf );
			}
		}
	} else {
		update_post_meta( $post_id, 'ngop_first_performance', '' );
	}
	if ( !empty($_POST['ngop_form_last_performance']) ) {
		$pLastPerf = sanitize_text_field($_POST['ngop_form_last_performance']);
		$split = array();
		if (preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $pLastPerf, $split))
		{
			if(wp_checkdate($split[2],$split[3],$split[1],$pLastPerf))
			{
				update_post_meta( $post_id, 'ngop_last_performance', $pLastPerf );
			}
		}
	} else {
		update_post_meta( $post_id, 'ngop_last_performance', '' );
	}
	if ( !empty($_POST['ngop_performances']) ) {
		$pNoPerf = absint($_POST['ngop_performances']);
		update_post_meta( $post_id, 'ngop_performances', $pNoPerf );
	}
	if ( !empty($_POST['ngop_ticket_url']) ) {
		$pTicket = esc_url($_POST['ngop_ticket_url']);
		update_post_meta( $post_id, 'ngop_ticket_url', $pTicket );
	}
}
?>
