<?php

/*************************************************************************************
REMEMBER you have more WORDPRESS LOGIN SECURITY measures in JavaScript on /js folder and login.php Template
*************************************************************************************/


//************************* Protect WP-ADMIN and WP-LOGIN **************************************

// Change login URL
function custom_login_page($login_url, $redirect) {
    return home_url('/login'); // login page (change if necessary)
}
add_filter('login_url', 'custom_login_page', 10, 2);


// Redirect unauthorized access to wp-login.php or wp-admin to the custom login page
function protect_wp_login() {
    $custom_login_url = custom_login_page('', ''); 
    $requested_url = sanitize_text_field($_SERVER['REQUEST_URI']);
    
    if (!is_user_logged_in() && (
        strpos($requested_url, '/wp-login.php') !== false || 
        strpos($requested_url, '/wp-admin.php') !== false
    )) {
        wp_redirect($custom_login_url);
        exit;
    }
}
add_action('init', 'protect_wp_login');


// Redirect to custom login page after logout
function custom_logout_redirect() {
    wp_redirect(home_url('/login'));
    exit;
}
add_action('wp_logout', 'custom_logout_redirect');


//******************** Lock DASHBOARD to certain roles **************************
function dashboard_redirect()
{
  if (is_admin() && !defined('DOING_AJAX') && (current_user_can('subscriber') || current_user_can('contributor'))) {
    wp_redirect(home_url());
    exit;
  }
}
add_action('init', 'dashboard_redirect');
  

//******************** DISABLE Plugin And Theme Modifications on DASHBOARD from non admin users **************************

  function restrict_non_admin_plugin_theme_modifications($caps, $cap, $user_id, $args) {
    if (in_array($cap, array('activate_plugins', 'deactivate_plugins', 'edit_plugins', 'update_plugins', 'edit_themes', 'update_themes', 'install_plugins', 'delete_plugins'))) {
        $user = get_userdata($user_id);
        if (!$user || !in_array('administrator', $user->roles)) { // You can ADD USER ROLES
            $caps[] = 'do_not_allow';
        }
    }
    return $caps;
}

add_filter('map_meta_cap', 'restrict_non_admin_plugin_theme_modifications', 10, 4);


//******************** DISABLE Unfiltered HTML **************************
//define('DISALLOW_UNFILTERED_HTML', true);


//************************* REMOVE x-pingback-by header  **************************************
add_filter('pings_open', function() {
    return false;
});

//************************* REMOVE recent comments **************************************
function remove_recent_comments_style()
{
  global $wp_widget_factory;
  remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
}
add_action('widgets_init', 'remove_recent_comments_style');


//******************** DISABLE Directory Browsing (wp-content/uploads/) **************************
// If you're still seeing the directory listing in your browser after adding the code to functions.php,
//it's possible that the server configuration is not allowing the PHP code to take effect. In such cases,
//you should consider adding the following code to your site's root .htaccess file instead,
//as it's a more reliable way to disable directory browsing:

//# Disable directory browsing
//Options -Indexes

function disable_directory_browsing() {
    $uploads_dir = wp_upload_dir();
    $uploads_path = $uploads_dir['basedir'];

    if (is_dir($uploads_path)) {
        $index_file = trailingslashit($uploads_path) . 'index.php';

        if (!file_exists($index_file)) {
            $index_content = "<?php // Silence is golden.";
            file_put_contents($index_file, $index_content);
        }
    }
}

add_action('admin_init', 'disable_directory_browsing');
add_action('template_redirect', 'disable_directory_browsing');


//******************** REMOVES UNECESSARY INFORMATION FROM head **************************
add_action('init', function() {
    // Remove post and comment feed link
    remove_action( 'wp_head', 'feed_links', 2 );

    // Remove post category links
	remove_action('wp_head', 'feed_links_extra', 3);

    // Remove link to the Really Simple Discovery service endpoint
	remove_action('wp_head', 'rsd_link');

    // Remove the link to the Windows Live Writer manifest file
	remove_action('wp_head', 'wlwmanifest_link');

    // Remove the XHTML generator that is generated on the wp_head hook, WP version
	remove_action('wp_head', 'wp_generator');

    // Remove start link
	remove_action('wp_head', 'start_post_rel_link');

    // Remove index link
	remove_action('wp_head', 'index_rel_link');

    // Remove previous link
	remove_action('wp_head', 'parent_post_rel_link', 10, 0);

    // Remove relational links for the posts adjacent to the current post
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

    // Remove relational links for the posts adjacent to the current post
    remove_action('wp_head', 'wp_oembed_add_discovery_links');

    // Remove REST API links
    remove_action('wp_head', 'rest_output_link_wp_head');

    // Remove Link header for REST API
    remove_action('template_redirect', 'rest_output_link_header', 11, 0 );

    // Remove Link header for shortlink
    remove_action('template_redirect', 'wp_shortlink_header', 11, 0 );

});


//******************** REMOVES wp-embed.js from loading **************************
/* If you need to call content from other websites comment this code */
//add_action( 'wp_footer', function() {
//    wp_deregister_script('wp-embed');
//});


//******************** DISABLE xmlrpc **************************
/* Disable only if your site does not require use of xmlrpc*/
add_filter('xmlrpc_enabled', function() {
    return false;
});


//******************** SET MAXIMUM revisions number **************************
if (!defined('WP_POST_REVISIONS')) define('WP_POST_REVISIONS', 3);


//******************** SET COMMENTS & PINGBACKS off by default when CREATE A POST **************************
// Disable comments and pingbacks on existing posts
function disable_comments_and_pingbacks_on_existing_posts() {
    global $wpdb;
    $wpdb->query("UPDATE $wpdb->posts SET comment_status = 'closed', ping_status = 'closed'");
}

// Disable comments and pingbacks on new posts
function disable_comments_and_pingbacks_on_new_posts($data) {
    $data['comment_status'] = 'closed';
    $data['ping_status'] = 'closed';
    return $data;
}

// Apply the functions
add_action('init', 'disable_comments_and_pingbacks_on_existing_posts');
add_filter('wp_insert_post_data', 'disable_comments_and_pingbacks_on_new_posts', 10, 2);