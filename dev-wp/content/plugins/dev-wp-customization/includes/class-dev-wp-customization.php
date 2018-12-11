<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/ElRochito/dev-wp
 * @since      1.0.0
 *
 * @package    Dev_Wp_Customization
 * @subpackage Dev_Wp_Customization/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Dev_Wp_Customization
 * @subpackage Dev_Wp_Customization/includes
 * @author     Dave Lopper <dev-wp@dev-wp.fr>
 */
class Dev_Wp_Customization {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Dev_Wp_Customization_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {

        $this->plugin_name = 'dev-wp-customization';
        $this->version = '1.0.0';

        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();

    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Dev_Wp_Customization_Loader. Orchestrates the hooks of the plugin.
     * - Dev_Wp_Customization_i18n. Defines internationalization functionality.
     * - Dev_Wp_Customization_Admin. Defines all hooks for the admin area.
     * - Dev_Wp_Customization_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-dev-wp-customization-loader.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-dev-wp-customization-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-dev-wp-customization-public.php';

        $this->loader = new Dev_Wp_Customization_Loader();

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {

        $plugin_admin = new Dev_Wp_Customization_Admin( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'admin_menu', $plugin_admin, 'remove_actions' );
        $this->loader->add_action( 'setup_theme', $plugin_admin, 'wp_magic_unquotes' );

        $this->loader->add_action( 'admin_init', $plugin_admin, 'admin_color_schemes' );
        $this->loader->add_action( 'admin_head', $plugin_admin, 'admin_color_remove_choices' );
        $this->loader->add_action( 'user_register', $plugin_admin, 'admin_color_user_register_default' );
        $this->loader->add_filter( 'get_user_option_admin_color', $plugin_admin, 'admin_color_user_default' );

        $this->loader->add_action( 'login_head', $plugin_admin, 'login_custom_logo' );
        $this->loader->add_filter( 'login_headerurl', $plugin_admin, 'login_custom_url' );

        $this->loader->add_action( 'wp_before_admin_bar_render', $plugin_admin, 'admin_bar_no_wp_logo', 0 );

        $this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu_add_separator', 9 );
        $this->loader->add_filter( 'custom_menu_order', $plugin_admin, 'admin_menu_order' );
        $this->loader->add_filter( 'menu_order', $plugin_admin, 'admin_menu_order' );

        $this->loader->add_action( 'admin_init', $plugin_admin, 'disable_default_dashboard_widgets' );
        $this->loader->add_filter( 'hidden_meta_boxes', $plugin_admin, 'hide_meta_boxes', 10, 3 );
        $this->loader->add_filter( 'wp_terms_checklist_args', $plugin_admin, 'keep_hierarchical_categories' );

        $this->loader->add_filter( 'acf/location/rule_types', $plugin_admin, 'acf_location_rules_types' );
        $this->loader->add_filter( 'acf/location/rule_operators', $plugin_admin, 'acf_location_rules_operators' );
        $this->loader->add_filter( 'acf/location/rule_values/taxonomy_depth', $plugin_admin, 'acf_location_rules_values_taxonomy_depth' );
        $this->loader->add_filter( 'acf/location/rule_match/taxonomy_depth', $plugin_admin, 'acf_location_rules_match_taxonomy_depth', 10, 3 );

        $this->loader->add_action( 'tiny_mce_before_init', $plugin_admin, 'tinymce_customization', 50 );

        $this->loader->add_filter( 'upload_mimes', $plugin_admin, 'wpc_mime_types' );

//        $this->loader->add_action( 'admin_init', $plugin_admin, 'redirect_non_admin_users' );
//        $this->loader->add_action( 'after_setup_theme', $plugin_admin, 'hide_bar_to_non_admin_users' );

        $this->loader->add_action( 'admin_init', $plugin_admin, 'set_image_default_link_type' );
//        $this->loader->add_action( 'posts_where', $plugin_admin, 'filter_protected_attachments_where' );
//        $this->loader->add_action( 'posts_join', $plugin_admin, 'filter_protected_attachments_join' );
//        $this->loader->add_filter( 'wp_count_attachments', $plugin_admin, 'fix_media_counts', 10, 2 );

    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {

        $plugin_public = new Dev_Wp_Customization_Public( $this->get_plugin_name(), $this->get_version() );

        $plugin_public->remove_actions();

        $this->loader->add_filter( 'login_errors', $plugin_public, 'login_no_errors' );
        $this->loader->add_filter( 'sanitize_file_name', $plugin_public, 'sanitize_file_name' );
        $this->loader->add_filter( 'the_content', $plugin_public, 'remove_img_ptags' );

        $this->loader->add_filter( 'template_redirect', $plugin_public, 'redirect_attachment_page', 1 );

    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Dev_Wp_Customization_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

}
