<?php
/*
Plugin Name: Breadcrumbs Anywhere
Plugin URI: https://danielesparza.studio/breadcrumbs-anywhere/
Description: Breadcrumbs Anywhere es un plugin de WordPress que sirve para agregar Breadcrumbs (migas de pan) en cualquier lugar de nuestro sitio web a través de un shortcode.
Version: 1.0
Author: Daniel Esparza
Author URI: https://danielesparza.studio/
License: GPL v3

Breadcrumbs Anywhere
©2020 Daniel Esparza, inspirado por #openliveit #dannydshore | Consultoría en servicios y soluciones de entorno web - https://danielesparza.studio/

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(function_exists('admin_menu_desparza')) { 
    //menu exist
} else {
	add_action('admin_menu', 'admin_menu_desparza');
	function admin_menu_desparza(){
		add_menu_page('DE Plugins', 'DE Plugins', 'manage_options', 'desparza-menu', 'wp_desparza_function', 'dashicons-editor-code', 90 );
		add_submenu_page('desparza-menu', 'Sobre Daniel Esparza', 'Sobre Daniel Esparza', 'manage_options', 'desparza-menu' );
	
    function wp_desparza_function(){  	
	?>
		<div class="wrap">
            <h2>Daniel Esparza</h2>
            <p>Consultoría en servicios y soluciones de entorno web.<br>¿Qué tipo de servicio o solución necesita tu negocio?</p>
            <h4>Contact info:</h4>
            <p>
                Sitio web: <a href="https://danielesparza.studio/" target="_blank">https://danielesparza.studio/</a><br>
                Contacto: <a href="mailto:hi@danielesparza.studio" target="_blank">hi@danielesparza.studio</a><br>
                Messenger: <a href="https://www.messenger.com/t/danielesparza.studio" target="_blank">enviar mensaje</a><br>
                Información acerca del plugin: <a href="https://danielesparza.studio/breadcrumbs-anywhere/" target="_blank">sitio web del plugin</a><br>
                Daniel Esparza | Consultoría en servicios y soluciones de entorno web.<br>
                ©2020 Daniel Esparza, inspirado por #openliveit #dannydshore
            </p>
		</div>
	<?php }
        
    }	
    
    add_action( 'admin_enqueue_scripts', 'wpba_register_adminstyle' );
    function wpba_register_adminstyle() {
        wp_register_style( 'wpba_register_adminstyle_css', plugin_dir_url( __FILE__ ) . 'css/wpba_style_admin.css', array(), '1.0' );
        wp_enqueue_style( 'wpba_register_adminstyle_css' );
    }
    
}


if ( ! function_exists( 'breadcrumbs_anywhere_add' ) ) {

add_action( 'admin_menu', 'breadcrumbs_anywhere_add' );
function breadcrumbs_anywhere_add() {
    add_submenu_page('desparza-menu', 'Breadcrumbs Anywhere', 'Breadcrumbs Anywhere', 'manage_options', 'breadcrumbs-anywhere-settings', 'breadcrumbs_anywhere_how_to_use' );
}

function breadcrumbs_anywhere_how_to_use(){ 
    ?>
    <div class="wrap">
        <h2>Breadcrumbs Anywhere, ¿Como usar el shortcode?</h2>
		<h4>Uso frecuente:</h4>
        <ul>
            <li>[breadcrumbs-anywhere] //Shortcode</li>
            <li>echo do_shortcode( '[breadcrumbs-anywhere]' ); //Shortcode en php</li>
        </ul>
        <h4>Breadcrumbs Anywhere, Otras funciones:</h4>
        <ul>
            <li>[breadcrumbs-anywhere post_type="custom-post"] //Shortcode para Portafolios o Custom post types </li>
            <li>echo do_shortcode( '[breadcrumbs-anywhere post_type="custom-post"]' ); //Shortcode en php para Portafolios o Custom post types</li>
        </ul>
        <p>Clase para editar los estilos: .breadcrumbs-anywhere</p>
   <?php
}

// Add Shortcode
add_shortcode( 'breadcrumbs-anywhere', 'breadcrumbs_anywhere_shortcode' );
function breadcrumbs_anywhere_shortcode($atts) {
	ob_start();
	
	global $post;
	$get_home = home_url();
	$get_home_title = get_the_title( get_option('page_on_front') );
	$root = '<a href="'. $get_home .'" rel="nofollow">'. $get_home_title .'</a>';
	$parent_title = get_the_title($post->post_parent);
	extract( shortcode_atts( array(
      'post_type' => '',
    ), $atts ) );
	?>
		<div class="breadcrumbs-anywhere">
			<?php 
				if (is_single()) {
					if ( $post_type == !NULL){
						echo $root . "&nbsp;&nbsp;&#187;&nbsp;&nbsp;";
        				the_category(' &#187 ');
						$terms = get_the_terms($post->ID, $post_type);
						foreach ($terms as $term) {
							$term_link = get_term_link($term, $post_type);
							echo '<a href="' . $term_link . '">' . $term->name . '</a>  &nbsp;&nbsp;&#187;&nbsp;&nbsp; ';
						}
						echo the_title();
					} else {
						echo $root . '&nbsp;&nbsp;&#187;&nbsp;&nbsp';
						the_category(' &#187 '); 
						echo '<a href="' . $term_link . '">' . $term->name . '</a>  &nbsp;&nbsp;&#187;&nbsp;&nbsp; ' . esc_html(get_the_title());
					}
				} elseif (is_page() && $post->post_parent) {
					echo $root . '&nbsp;&nbsp;&#187;&nbsp;&nbsp;' . '<a href="'. get_permalink($post->post_parent) .'" rel="nofollow">' .  $parent_title . '</a>' . '&nbsp;&nbsp;&#187;&nbsp;&nbsp;' . esc_html(get_the_title());
				} elseif (is_page()) { 
					echo $root . '&nbsp;&nbsp;&#187;&nbsp;&nbsp;' . $parent_title;
				}
			?>
		</div>
	<?php
	
	$output_string = ob_get_contents();
	ob_end_clean();
	return $output_string;
}
}
//[breadcrumbs-anywhere]
//echo do_shortcode( '[breadcrumbs-anywhere]' ); 