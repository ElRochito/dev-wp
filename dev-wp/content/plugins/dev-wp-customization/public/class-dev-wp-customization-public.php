<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/ElRochito/dev-wp
 * @since      1.0.0
 *
 * @package    Dev_Wp_Customization
 * @subpackage Dev_Wp_Customization/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Dev_Wp_Customization
 * @subpackage Dev_Wp_Customization/public
 * @author     Dave Lopper <dev-wp@dev-wp.fr>
 */
class Dev_Wp_Customization_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Dev_Wp_Customization_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Dev_Wp_Customization_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dev-wp-customization-public.css', array(), $this->version, 'all' );

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Dev_Wp_Customization_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Dev_Wp_Customization_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dev-wp-customization-public.js', array( 'jquery' ), $this->version, false );

    }

    /*
     *
     */
    public function remove_actions() {
        // Remove emoji
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );

        // On supprimer l'affichage de la version de Wordpress dans le code source
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0 );
        remove_action('wp_head', 'wp_dlmp_l10n_style' );
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
    }

    /*
     * Obscure login screen error messages, don't let people know which is incorrect: the username or password.
     */
    public function login_no_errors() {
        return __("<strong>Erreur</strong> : le nom d'utilisateur et/ou le mot de passe sont invalides", 'dev-wp');
    }

    /*
     * Remove accents for filename uploading
     */
    public function sanitize_file_name($filename) {
        setlocale(LC_ALL, "fr_FR.utf8");
        return iconv("utf-8", "ascii//TRANSLIT", $filename);
    }

    /*
     * Stop images getting wrapped up in p tags when they get dumped out with the_content() for easier theme styling
     */
    public function remove_img_ptags($content){
        return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
    }

    public function redirect_attachment_page()
    {
        if( is_attachment() ) {
            wp_redirect(home_url());
            exit;
        }
    }

}
