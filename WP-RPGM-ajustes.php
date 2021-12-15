<?php

/**
 * Plugin Name: RPG Maker.
 * Description: Plugin para crear partidas de rol inline.
 * Version: 0.0.1
 * Author: jubagra <jubagral@gmail.com.com>
 * Plugin URI: https://
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WPRPGMTHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A
 * PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
 * CONTRACT, TORT OR OTHERWPRPGMSE, ARISING FROM, OUT OF OR IN CONNECTION WPRPGMTH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

/** Evitar que se acceda directamente a la clase */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Clase WPRPGM Ajustes
 * Esta es la clase principal del plugin que se encarga de incializar el plugin.
 */
class WPRPGM_Ajustes
{
    
    /**
     * Constructor
     */
    public function __construct()
    {
        // Verisión del plugin
        define( 'WPRPGM_VERSION', '0.0.1' );

        // Definición de rutas (paths) para facilitar el acceso a los archivos del plugin
        define( 'WPRPGM_TEMPLATE_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/templates/' );
        define( 'WPRPGM_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
        define( 'WPRPGM_MAIN_FILE', __FILE__ );
        define( 'WPRPGM_ABSPATH', dirname( __FILE__ ) . '/' );
        
        // "Pegarse" de los Hooks de Wordpress
        add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts_and_styles' ), 999 );
        add_action( 'plugins_loaded', array( $this, 'includes' ) );
        add_action( 'plugins_loaded', array( $this, 'init' ) );
        
        if ( is_admin() ) {
            $this->admin_includes();
        }
    }
    
    /**
     * Cargar los archivos con las funcionalidades que se definan.
     */
    public function includes()
    {
        // Incluir functions.php
        include_once(WPRPGM_ABSPATH . 'functions.php' );
    }
    
    /**
     * Inicializar (cargar los archivos asociados a los templates o 'plantillas').
     */
    public function init()
    {
        // Cargar Templates
        add_filter( 'template_include', array( $this, 'include_template' ), 11 );
        add_filter( 'wc_get_template', array( $this, 'get_template' ), 11, 5 );
    }
    
    /**
     * Admin
     * Cargar los archivos que tienen las funcionalides que deben correr solo en el Admin.
     */
    public function admin_includes()
    {

    }

    /**
     * Cargar los templates.
     * Esto obliga a la plataforma a que busque los templates primero en este plugin
     * Este método funciona para los templates de nivel superior (single.php, page.php, etc).
     * No funciona para "template parts".
     *
     * En caso de problemas, revisar este 'trac ticket': https://core.trac.wordpress.org/ticket/13239
     *
     * @param  string $template template string.
     * @return string $template new template string.
     */
    public function include_template( $template ) {
        if ( file_exists( untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/templates/' . basename( $template ) ) ) {
        $template = untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/templates/' . basename( $template );
        }
        return $template;
    }
        
    /**
     * Forzar WooCommerce para que busque sus templates primero en este plugin.
     * 
     * Por ejemplo, si se quiere modificar el template woocommerce/templates/cart/cart.php,
     * agregar el nuevo template en wprpgm_ajustes/templates/woocommerce/cart/cart.php
     *
     * @param string $located is the currently located template, if any was found so far.
     * @param string $template_name is the name of the template (ex: cart/cart.php).
     * @return string $located is the newly located template if one was found, otherwprpgmse
     *                         it is the previously found template.
     */
    public function get_template( $located, $template_name, $args, $template_path, $default_path ) {
        $plugin_template_path = untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/templates/woocommerce/' . $template_name;
        
        if ( file_exists( $plugin_template_path ) ) {
            $located = $plugin_template_path;
        }
        return $located;
    }

  /**
   * Cargar archivo styles.css con los estilos y scripts.js con los scripts
   */
  public function add_scripts_and_styles()
  {
    // Estilos css 
    wp_register_style( 'WPRPGMAjustesStyles', WPRPGM_PLUGIN_URL .  '/style.css', null, WPRPGM_VERSION );
    wp_enqueue_style( 'wprpgmAjustesStyles' );

    // Scripts de javascript
    wp_enqueue_script('wprpgmAjustesStylesJs', WPRPGM_PLUGIN_URL . '/script.js' , array('jquery'));
  }
}

// Inicializar la clase principal (arrancar el plugin)
$GLOBALS['wprpgm_ajustes'] = new WPRPGM_Ajustes();