<?php
/*
 * Plugin Name: Posts Navigation Links for Sections and Headings - Free by WP Masters
 * Plugin URI: https://wp-masters.com/products/wpm-post-navigation-links
 * Description: Add links to all H1-H6 titles under post content for SEO
 * Author: WP-Masters
 * Text Domain: wpm-post-navigation-linkss
 * Author URI: https://wp-masters.com/
 * Version: 1.0.1
 *
 * @author      WP-Masters
 * @version     v.1.0.1 (12/08/22)
 * @copyright   Copyright (c) 2022
*/

require_once 'templates/libs/simple_html_dom/simple_html_dom.php';

define('WPM_POST_NAV_LINKS', plugins_url('', __FILE__));

class WPM_PostNavigationLinks
{
	private $settings;

    /**
     * Initialize functions
     */
    public function __construct()
    {
        // Include Styles and Scripts
	    add_action('admin_enqueue_scripts', [$this, 'admin_scripts_and_styles']);
        add_action('wp_enqueue_scripts', [$this, 'include_scripts_and_styles'], 99);

        // Filters
	    add_filter( 'the_content', [$this, 'add_navigation_links_from_title'], 99);

	    // Admin menu
	    add_action('admin_menu', [$this, 'register_menu']);

	    // Init Functions
	    add_action('init', [$this, 'save_settings']);
	    add_action('init', [$this, 'load_settings']);
    }

	/**
	 * Check Titles
	 */
    public function add_navigation_links_from_title($content)
    {
    	global $post;

	    // Prepare HTML DOM of Content
    	if(is_single() && isset($this->settings['import_types']) && in_array($post->post_type, $this->settings['import_types'])) {
		    $document = new simple_html_dom();
		    $document->load( $content );

		    // Search Titles
		    $founded_titles = [];
		    foreach($document->find('h1,h2,h3,h4,h5,h6') as $element) {
		    	$slug = str_replace(' ', '_', strtolower($element->innertext));
			    $element->setAttribute('id', $slug);
			    $founded_titles[$slug] = $element->innertext;
		    }

		    // Prepare List Links
		    ob_start();
		    include ('templates/frontend/list_links.php');
		    $links = ob_get_clean();

		    $content = $links.$document->save();
	    }

    	return $content;
    }

	/**
	 * Include Scripts And Styles on Admin Pages
	 */
	public function admin_scripts_and_styles()
	{
		// Register styles
		wp_enqueue_style('wpm-posts-navigation-links-font-awesome', plugins_url('templates/libs/font-awesome/scripts/all.min.css', __FILE__));
		wp_enqueue_style('wpm-posts-navigation-links-tips', plugins_url('templates/libs/tips/tips.css', __FILE__));
		wp_enqueue_style('wpm-posts-navigation-links-color-spectrum', plugins_url('templates/libs/color-spectrum/spectrum.css', __FILE__));
		wp_enqueue_style('wpm-posts-navigation-links-admin', plugins_url('templates/assets/css/admin.css', __FILE__));

		// Register Scripts
		wp_enqueue_script('wpm-posts-navigation-links-font-awesome', plugins_url('templates/libs/font-awesome/scripts/all.min.js', __FILE__));
		wp_enqueue_script('wpm-posts-navigation-links-tips', plugins_url('templates/libs/tips/tips.js', __FILE__));
		wp_enqueue_script('wpm-posts-navigation-links-color-spectrum', plugins_url('templates/libs/color-spectrum/spectrum.js', __FILE__));
		wp_enqueue_script('wpm-posts-navigation-links-admin', plugins_url('templates/assets/js/admin.js', __FILE__));
	}

    /**
     * Include Scripts And Styles on FrontEnd
     */
    public function include_scripts_and_styles()
    {
        // Register styles
        wp_enqueue_style('wpm-posts-navigation-links', plugins_url('templates/assets/css/frontend.css', __FILE__) , false, '1.0.6', 'all');

        // Register scripts
	    wp_enqueue_script( 'wpm-posts-navigation-links', plugins_url( 'templates/assets/js/frontend.js', __FILE__ ), array( 'jquery' ), '1.0.6', 'all' );
    }

	/**
	 * Save Core Settings to Option
	 */
	public function save_settings()
	{
		if(isset($_POST['wpm_navigation_links']) && is_array($_POST['wpm_navigation_links'])) {
			$data = $this->sanitize_array($_POST['wpm_navigation_links']);
			update_option('wpm_navigation_links', serialize($data));
		}
	}

	/**
	 * Sanitize Array Data
	 */
	public function sanitize_array($data)
	{
		$filtered = [];
		foreach($data as $key => $value) {
			if(is_array($value)) {
				foreach($value as $sub_key => $sub_value) {
					$filtered[$key][$sub_key] = sanitize_text_field($sub_value);
				}
			} else {
				$filtered[$key] = sanitize_text_field($value);
			}
		}

		return $filtered;
	}

	/**
	 * Load Saved Settings
	 */
	public function load_settings()
	{
		$this->settings = unserialize(get_option('wpm_navigation_links'));
	}

	/**
	 * Add Settings to Admin Menu
	 */
	public function register_menu()
	{
		add_menu_page('Navigations Links', 'Navigations Links', 'edit_others_posts', 'wpm_navigation_links_settings');
		add_submenu_page('wpm_navigation_links_settings', 'Navigations Links', 'Navigations Links', 'manage_options', 'wpm_navigation_links_settings', function ()
		{
			global $wp_version, $wpdb;

			// Get Saved Settings
			$post_types = get_post_types();
			$settings = $this->settings;

			include 'templates/admin/settings.php';
		});
	}
}

new WPM_PostNavigationLinks();