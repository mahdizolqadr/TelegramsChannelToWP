<?php
/**
 * Plugin Name: TelegramsChannelToWP
 * Plugin URI: http://www.tele-wall.ir/static/TelegramsChannelToWP.rar
 * Description: Embed Telegram's Channel content, view channel's content on your site from TeleWall Database (http://www.tele-wall.ir).
 * Version: The Plugin's Version Number, e.g.: 0.1
 * Author: Mahdi Zolqadr
 * Author URI: http://www.tele-wall.ir/
 * License: A "Slug" license name e.g. GPL2
 */

if ( ! function_exists( 'add_action' ) ) {
    if ( ! headers_sent() ) {
        if ( function_exists( 'http_response_code' ) ) {
            http_response_code( 403 );
        } else {
            header( 'HTTP/1.1 403 Forbidden', true, 403 );
        }
    }
    exit( 'no directly!' );
}

add_action('admin_menu', 'TelegramsChannelToWP_create_menu');

function TelegramsChannelToWP_create_menu() {

    //create new top-level menu
    add_menu_page('Telegrams Channel To Wordpress', 'Telegram Settings', 'administrator', __FILE__, 'TelegramsChannelToWP_settings_page' , plugins_url('/img/logo.png', __FILE__) );

    //call register settings function
    add_action( 'admin_init', 'register_TelegramsChannelToWP_settings' );
}


function register_TelegramsChannelToWP_settings() {
    //register our settings
    register_setting( 'TelegramsChannelToWP-settings-group', 'API_Key' );
    register_setting( 'TelegramsChannelToWP-settings-group', 'Channels_username' );
}

function TelegramsChannelToWP_settings_page() {
    ?>
    <div class="wrap">
        <h1>Telegram's Channel To WordPress</h1>
        <h4>Embed Telegram's Channel content, view channel's content on your site from TeleWall Database (<a target="_blank" href="http://www.tele-wall.ir">http://www.tele-wall.ir</a>).</h4>
        <h5>Your Api-key: <?php  if ( esc_attr( get_option('API_Key') ) ) {echo esc_attr( get_option('API_Key') ); } ?> </h5>
        <p><b>How can i use it?</b></p>
        <p>First, you must get API-Key from Telewall (<a target="_blank" href="http://www.tele-wall.ir/api/register/">http://tele-wall.ir/api/register/</a>), then fill require fields (your site and your favorite channel).
            Go to plugin's page and set api-key and now last posts of telegrams channel appeare in your site.
        </p>
        <p style="color: red">
            Note: Every Api-Key only use for single channel that you register on TeleWall.
        </p>
        <form method="post" action="options.php">
            <?php settings_fields( 'TelegramsChannelToWP-settings-group' ); ?>
            <?php do_settings_sections( 'TelegramsChannelToWP-settings-group' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">API-KEY</th>
                    <td><input type="text" name="API_Key" value="<?php echo esc_attr( get_option('API_Key') ); ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row">Channel's Username</th>
                    <td><input type="text" name="Channels_username" value="<?php echo esc_attr( get_option('Channels_username') ); ?>" /></td>
                </tr>

            </table>

            <?php submit_button(); ?>

        </form>
    </div>
<?php } ?>



<?php // Creating the widget
class TelegramsChannelToWP_widget extends WP_Widget {

    function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_style' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_script' ) );
        parent::__construct(
// Base ID of your widget
            'TelegramsChannelToWP_widget',

// Widget name will appear in UI
            __('Telegrams Channel Widget', 'TelegramsChannelToWP_widget_domain'),

// Widget description
            array( 'description' => __( 'Embed Telegrams Channel content, view channels content on your site from TeleWall Database (http://www.tele-wall.ir).', 'TelegramsChannelToWP_widget_domain' ), )
        );
    }


    private function fetch_data(){
//echo  __(  $server_output, 'TelegramsChannelToWP_widget_domain' );


    }

    public function enqueue_public_style () {

        wp_enqueue_style(
            'TelegramsChannelToWP',
            plugin_dir_url( __FILE__ ) . 'css/telewall.css',
            array(),
            '0.1',
            'all'
        );

    }

    public function enqueue_public_script () {

        wp_enqueue_script(
            'TelegramsChannelToWP',
            plugin_dir_url( __FILE__ ) . 'js/telewall.js',
            array(),
            '0.1',
            true
        );

    }

// Creating widget front-end
// This is where the action happens
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
// before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if ( ! empty( $title ) )
            echo $args['before_title'] . $title . $args['after_title'];

// This is where you run the code and display the output
        ?>
        <div id="TelegramsChannelToWP_widget_contents" class="twContainer">
            <input type="hidden" id="TWapikey" value="<?php echo __(  esc_attr( get_option('API_Key') )  , 'TelegramsChannelToWP_widget_domain' );  ?>" />
            <input type="hidden" id="TWusername" value="<?php echo __(  esc_attr( get_option('Channels_username') )  , 'TelegramsChannelToWP_widget_domain' );    ?>" />
            <div id="TWContents"><img alt="loading..." src="<?php echo __(  plugin_dir_url( __FILE__ ) . 'img/loading.gif', 'TelegramsChannelToWP_widget_domain' );    ?>" /></div>
        </div>
        <?php

        echo $args['after_widget'];
    }

// Widget Backend
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( 'Title', 'TelegramsChannelToWP_widget_domain' );
        }
// Widget admin form
        ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">
                <?php _e( 'Title:' ); ?>
            </label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php
    }

// Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }
} // Class TelegramsChannelToWP_widget ends here

// Register and youridad the widget
function TelegramsChannelToWP_widget_widget() {
    register_widget( 'TelegramsChannelToWP_widget' );
}
add_action( 'widgets_init', 'TelegramsChannelToWP_widget_widget' );

?>