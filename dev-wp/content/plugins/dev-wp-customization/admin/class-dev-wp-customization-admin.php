<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/ElRochito/dev-wp
 * @since      1.0.0
 *
 * @package    Dev_Wp_Customization
 * @subpackage Dev_Wp_Customization/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Dev_Wp_Customization
 * @subpackage Dev_Wp_Customization/admin
 * @author     Dave Lopper <dev-wp@dev-wp.fr>
 */
class Dev_Wp_Customization_Admin {

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
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    public function wp_magic_unquotes() {

        $_GET    = stripslashes_deep( $_GET    );
        $_POST   = stripslashes_deep( $_POST   );
        $_COOKIE = stripslashes_deep( $_COOKIE );
        $_SERVER = stripslashes_deep( $_SERVER );
        $_REQUEST = array_merge( $_GET, $_POST );
    }

    /*
     *
     */
    public function remove_actions()
    {
        if( !current_user_can('update_plugins') ) {
            remove_action('admin_notices', 'update_nag', 3);
        }
    }

    /*
     *
     */
    public function admin_color_schemes() {
        wp_admin_css_color( $this->plugin_name, $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'css/dev-wp-customization-admin.css',
            array( '#363b3f', '#40c0f0', '#fff', '#fff' ),
            array( 'base' => '#40c0f0', 'focus' => '#fff', 'current' => '#363b3f' )
        );
    }

    /*
     * Remove color scheme choices
     */
    public function admin_color_remove_choices() {
        global $_wp_admin_css_colors;
        $_wp_admin_css_colors = array($_wp_admin_css_colors[$this->plugin_name]);
    }

    /*
     * Default color scheme
     */
    public function admin_color_user_default()
    {
        return $this->plugin_name;
    }

    /*
     * Default color scheme on register
     */
    public function admin_color_user_register_default( $user_id ) {
        $args = array(
            'ID' => $user_id,
            'admin_color' => 'dev-wp'
        );
        wp_update_user($args);
    }

    /*
     * Update logo on login screen
     */
    public function login_custom_logo() { ?>
        <style type="text/css">
            body.login {
                background-image: -ms-linear-gradient(45deg, rgba(17,17,17,1) 0%,rgba(102,102,102,1) 100%);
                background-image: -moz-linear-gradient(45deg, rgba(17,17,17,1) 0%,rgba(102,102,102,1) 100%);
                background-image: -o-linear-gradient(t45deg, rgba(17,17,17,1) 0%,rgba(102,102,102,1) 100%);
                background-image: -webkit-gradient(45deg, rgba(17,17,17,1) 0%,rgba(102,102,102,1) 100%);
                background-image: -webkit-linear-gradient(45deg, rgba(17,17,17,1) 0%,rgba(102,102,102,1) 100%);
                background-image: linear-gradient(45deg, rgba(17,17,17,1) 0%,rgba(102,102,102,1) 100%);
            }
            h1 a {
                background: none !important;
            }
            #login:before {
                content: '';
                background: url(<?php echo plugin_dir_url( __FILE__ ) . 'img/fd-circles.png'; ?>) no-repeat 50% 20% / 135px auto;
                height: 100vw;
                background-size: 100%;
                margin: 0 auto;
                position: absolute;
                left: 0;
                width: 100%;
                top: 0;
                z-index: -1;
            }
            .wp-core-ui .button-primary,
            .wp-core-ui .button-primary:hover,
            .wp-core-ui .button-primary:focus {
                background: none repeat scroll 0 0 #B7BABE;
                -webkit-box-shadow: none;
                box-shadow: none;
                border-color: #A1A2A3;
                text-shadow: none;
            }
            #backtoblog {
                display: none !important;
            }
            .login #nav a {
                color: #fff;
            }
            .login-action-rp #nav,
            .login-action-resetpass #nav {
                display: none !important;
            }
            .login-action-resetpass .reset-pass a {
                display: none !important;
            }
            .password-input-wrapper {
                opacity: 0;
            }
        </style>
        <script type="text/javascript">
            (function ($) {
                setTimeout(function(){
                    $(".password-input-wrapper input").attr("data-pw", "");
                    $(".password-input-wrapper input").val("");
                    $(".password-input-wrapper").css("opacity", 1);
                }, 500);
            })(jQuery);
        </script>
    <?php
    }

    /*
     * Update URL on login screen
     */
    public function login_custom_url() {
        return network_site_url("wp-admin");
    }

    /*
     * No WordPress logo in admin bar
     */
    public function admin_bar_no_wp_logo() {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('wp-logo');
    }

    /*
     * Add seperator in menu
     */
    public function admin_menu_add_separator()
    {
        global $menu;

        $menu[] = array(
            0   => '',
            1   => 'read',
            2   => 'separator-custom1',
            3   => '',
            4   => 'wp-menu-separator custom1'
        );

        $menu[] = array(
            0   => '',
            1   => 'read',
            2   => 'separator-custom2',
            3   => '',
            4   => 'wp-menu-separator custom2'
        );
    }

    /*
     * Reorder menus
     */
    public function admin_menu_order( $menu_ord ) {
        if ( !$menu_ord ) return true;

        return array(
            'index.php', // Dashboard

            'separator1', // First separator

            'edit.php?post_type=page', // Pages
            'edit.php', // Posts
            'edit-comments.php', // Comments
            'upload.php', // Media

            'edit.php?post_type=cas', // Pages
            'edit.php?post_type=reseau', // Pages
            'edit.php?post_type=region', // Pages
            'separator2', // Second separator

            'formidable',
            'themes.php', // Appearance
            'plugins.php', // Plugins
            'users.php', // Users
            'tools.php', // Tools
            'options-general.php', // Settings
            'separator-custom1',
            'separator-custom2',
            'separator-last', // Last separator
        );
    }

    /*
     * Disable default dashboard widgets
     */
    public function disable_default_dashboard_widgets()
    {
        remove_meta_box('dashboard_activity', 'dashboard', 'core');
        remove_meta_box('dashboard_right_now', 'dashboard', 'core');
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'core');
        remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');
        remove_meta_box('dashboard_plugins', 'dashboard', 'core');
        remove_meta_box('dashboard_quick_press', 'dashboard', 'core');
        remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core');
        remove_meta_box('dashboard_primary', 'dashboard', 'core'); // Autres news WordPress
        remove_meta_box('dashboard_secondary', 'dashboard', 'core'); // News WordPress
    }

    /*
     * Hide meta boxes
     */
    public function hide_meta_boxes( $hidden, $screen, $use_defaults ) {
       $hidden[]   = 'revisionsdiv';

       return $hidden;
    }

    /*
     * Keep hierarchical category in meta boxes
     */
    public function keep_hierarchical_categories($args) {
        $args['checked_ontop'] = false;

        return $args;
    }

    public function acf_location_rules_types( $choices )
    {
        $choices['Other']['taxonomy_depth'] = __('Niveau taxonomie', 'dev-wp');
        return $choices;
    }

    public function acf_location_rules_operators( $choices )
    {
        //BY DEFAULT WE HAVE == AND !=
        $choices['<'] = __('inférieur à', 'dev-wp');
        $choices['>'] = __('supérieur à', 'dev-wp');

        return $choices;
    }

    public function acf_location_rules_values_taxonomy_depth( $choices )
    {
        for ($i=0; $i < 6; $i++)
        {
            $choices[$i] = $i;
        }

        return $choices;
    }

    public function acf_location_rules_match_taxonomy_depth( $match, $rule, $options )
    {
        $depth = (int) $rule['value'];
        $term_depth = 0;
        if(isset($_GET['taxonomy']) && isset($_GET['tag_ID']))
        {
            $term_depth = (int) count(get_ancestors($_GET['tag_ID'], $_GET['taxonomy']));
        }

        if($rule['operator'] == "==")
        {
            $match = ($depth == $term_depth);
        }
        elseif($rule['operator'] == "!=")
        {
            $match = ($depth != $term_depth);
        }
        elseif($rule['operator'] == "<")
        {
            $match = ($term_depth < $depth);
        }
        elseif($rule['operator'] == ">")
        {
            $match = ($term_depth > $depth);
        }

        return $match;
    }

    public function wpc_mime_types($mimes)
    {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }

    public function redirect_non_admin_users()
    {
        if( defined('DOING_AJAX') && DOING_AJAX ) {
            return;
        }

        if( !current_user_can('administrator') ) {
            wp_redirect(home_url('/'));
            exit;
        }
    }

    public function hide_bar_to_non_admin_users()
    {
        if( !current_user_can('administrator') && !is_admin() ) {
            show_admin_bar(false);
        }
    }

    public function set_image_default_link_type()
    {
        $image_set = get_option('image_default_link_type');

        if( $image_set !== 'none' ) {
            update_option('image_default_link_type', 'none');
        }
    }

    public function filter_protected_attachments_where( $where )
    {
        global $pagenow;

        if( is_admin() && in_array($pagenow, array('upload.php', 'admin-ajax.php')) && is_main_query() ) {
            $where  .= " ".$this->get_protected_attachments_custom_where();
        }

        return $where;
    }

    public function filter_protected_attachments_join( $join )
    {
        global $pagenow;

        if( is_admin() && in_array($pagenow, array('upload.php', 'admin-ajax.php')) && is_main_query() ) {
            $join  .= ' '.$this->get_protected_attachments_custom_join();
        }

        return $join;
    }

    public function fix_media_counts($counts, $mime_type)
    {
        global $pagenow;

        if( is_admin() && in_array($pagenow, array('upload.php', 'admin-ajax.php')) && is_main_query() ) {
            global $wpdb;

            $and = wp_post_mime_type_where( $mime_type );
            $and .= " ".$this->get_protected_attachments_custom_where();

            $count = $wpdb->get_results( "SELECT post_mime_type, COUNT( * ) AS num_posts"
                    . " FROM $wpdb->posts"
                    . " ".$this->get_protected_attachments_custom_join()
                    . " WHERE post_type = 'attachment'"
                    . " AND post_status != 'trash' $and"
                    . " GROUP BY post_mime_type", ARRAY_A );

            $counts = array();
            foreach( (array) $count as $row ) {
                $counts[ $row['post_mime_type'] ] = $row['num_posts'];
            }

            $counts['trash'] = $wpdb->get_var( "SELECT COUNT( * )"
                    . " FROM $wpdb->posts"
                    . " ".$this->get_protected_attachments_custom_join()
                    . " WHERE post_type = 'attachment'"
                    . " AND post_status = 'trash' $and");
        }

        return $counts;
    }

    private function get_protected_attachments_custom_where()
    {
        global $wpdb, $current_user;

        return 'AND (pm.meta_value NOT LIKE "_mediavault%" OR '.$wpdb->posts.'.post_author = '.intval($current_user->ID).')';
    }

    private function get_protected_attachments_custom_join()
    {
        global $wpdb;

        return 'LEFT JOIN '.$wpdb->postmeta.' AS pm ON pm.post_id = '.$wpdb->posts.'.ID AND pm.meta_key="_wp_attached_file"';
    }

    public function tinymce_customization($in) {
        $in['remove_linebreaks']            = false;
        $in['gecko_spellcheck']             = false;
        $in['keep_styles']                  = true;
        $in['accessibility_focus']          = true;
        $in['tabfocus_elements']            = 'major-publishing-actions';
        $in['media_strict']                 = false;
        $in['paste_remove_styles']          = false;
        $in['paste_remove_spans']           = false;
        $in['paste_strip_class_attributes'] = 'none';
        $in['paste_text_use_dialog']        = true;
        $in['wpeditimage_disable_captions'] = true;
        $in['plugins']                      = 'tabfocus,paste,media,fullscreen,wordpress,wpeditimage,wpgallery,wplink,wpdialogs';
        $in['content_css']                  =  plugin_dir_url( __FILE__ ) . 'css/dev-wp-editor-style.css';
        $in['wpautop']                      = true;
        $in['apply_source_formatting']      = false;
        $in['toolbar1']                     = 'bold,italic,underline,|,bullist,numlist,blockquote,hr,|,link,unlink,|,spellchecker,fullscreen,|,formatselect';
        $in['toolbar2']                     = 'alignjustify,alignleft,aligncenter,alignright,removeformat,charmap,outdent,indent,undo,redo,wp_help ';
        $in['block_formats']                = 'Paragraph=p;Heading 3=h3;Heading 4=h4;Heading 5=h5';

        $menu           = array(
            'file' => array(
                'title' => 'File',
                'items' => 'newdocument',
            ),
            'edit' => array(
                'title' => 'Edit',
                'items' => 'undo redo | cut copy paste pastetext | selectall',
            ),
            'insert' => array(
                'title' => 'Insert',
                'items' => 'link media | template hr',
            ),
            'format' => array(
                'title' => 'Format',
                'items' => 'bold italic underline strikethrough superscript subscript | removeformat',
            ),
            'table' => array(
                'title' => 'Table',
                'items' => 'inserttable tableprops deletetable | cell row column',
            ),
            'tools' => array(
                'title' => 'Tools',
                'items' => 'code',
            ),
        );
        $in['menu']      = json_encode($menu);

        // on crée un tableau contenant nos styles
//        $style_formats = array(
//            array(
//                'title'     => __('Titre important', 'dev-wp') ,
//                'block'     => 'h3',
//                'classes'   => 'main-title',
//            ),
//            array(
//                'title'     => __('References', 'dev-wp') ,
//                'block'     => 'div',
//                'classes'   => 'references',
//            ),
//        );
//
//        $in['style_formats']    = json_encode($style_formats);

        return $in;
    }

}
