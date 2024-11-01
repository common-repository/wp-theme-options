<?php
	/*
	Plugin Name: WP Theme Options
	Plugin URI: http://zeaks.org/wp-theme-options-plugin/
	Description: Adds simple options to your theme
	Version: 1.0
	Author: Scott Dixon
	Author URI: http://zeaks.org
	License: GPL2
	*/

	// Initialize the above class after theme setup
	add_action( 'after_setup_theme', create_function( '', 'global $wpoptions; $wpoptions = new WP_Options();' ) );
		
	class WP_Options {
		
		var $options = array();
		var $defaults = array();
	
		// constructor 
			
		function WP_Options()
		{
			$this->defaults = array(
				"color-scheme"	=> "default",		
				"title-font"	=> "",
				"content-font"	=> "",
				"custom-css"	=> ""
			);

			
			$this->options = (array) get_option( 'wpoptions-options' );
			$this->options = array_merge( $this->defaults, $this->options );
			
			
			add_action( 'admin_menu', array( &$this, 'add_admin_options' ) );
//			add_action( 'wp_enqueue_scripts', array( &$this, 'color_scheme_scripts' ) );
			add_action( 'wp_print_styles', array( &$this, 'color_scheme_scripts' ) );
			add_action( 'wp_print_styles', array( &$this, 'custom_css' ) );
			
			// calling ajax 
			add_action( 'wp_ajax_wp-options-update', array( &$this, 'wp_options_update' ) );
		}
//  For child themes you must change the next 4 directory paths using plugin_dir_url( __FILE__ ) . 'includes/colors to use  get_stylesheet_directory_uri() . '/includes/colors	
//	Note the / before the colors directory
	
		function color_scheme_scripts() {
			if ( isset( $this->options['color-scheme'] ) ) { 
				if ( $this->options['color-scheme'] == 'default' ) {
					wp_enqueue_style( 'wpoptions-default', plugin_dir_url( __FILE__ ) . 'includes/colors/default/default.css', array(), null );
				} elseif ( $this->options['color-scheme'] == 'dark' ) {
					wp_enqueue_style( 'wpoptions-dark', plugin_dir_url( __FILE__ ) . 'includes/colors/dark/dark.css', array(), null );
				} elseif ( $this->options['color-scheme'] == 'light' ) {
					wp_enqueue_style( 'wpoptions-light', plugin_dir_url( __FILE__ ) . 'includes/colors/light/light.css', array(), null );
				}
				
				do_action( 'wpoptions_enqueue_color_scheme', $this->options['color-scheme'] );
				
			} 
			else {
				wp_enqueue_style( 'wpoptions-default', plugin_dir_url( __FILE__ ) . 'includes/colors/default/default.css', array(), null );
			}
		}
		
		function custom_css() {
			$fonts = $this->get_my_available_fonts ();
			
			if ( isset( $this->options['custom-css'] ) && strlen( $this->options['custom-css'] ) )
				echo "<style>\n" . $this->options['custom-css'] . "\n</style>\n";
			
			echo "<style type=\"text/css\">\n";
			
			if ( 
				isset( $this->options['title-font'] ) && strlen( $this->options['title-font'] )  &&  
				isset( $this->options['content-font'] ) && strlen( $this->options['content-font'] )
			)
			{
				if ($this->options['title-font'] != $this->options['content-font'])	
				{
					echo $fonts[$this->options["title-font"]]["import"]."\n";
					echo $fonts[$this->options["content-font"]]["import"]."\n";		
				}
				else
					echo $fonts[$this->options["title-font"]]["import"]."\n";
						
			}
			elseif (isset( $this->options['title-font'] ) && strlen( $this->options['title-font'] )){
				echo $fonts[$this->options["title-font"]]["import"]."\n";
			}
			elseif (isset( $this->options['content-font'] ) && strlen( $this->options['content-font'] )){
				echo $fonts[$this->options["content-font"]]["import"]."\n";
			}
			
			
			if ( isset( $this->options['title-font'] ) && strlen( $this->options['title-font'] ) )
			{
				//echo $fonts[$this->options["title-font"]]["import"]."\n
				
// Below you can select what classes and ID's you want to be affected by the primary font selectors in options  EX: h1, h2, h3 etc etc*/					
				echo	"h1, h2, h3, h4, h5, h6, .widget .heading, #site-title a, #site-title, .entry-title {						
						".$fonts[$this->options["title-font"]]["css"]."	
					}";			
			}
				
			if ( isset( $this->options['content-font'] ) && strlen( $this->options['content-font'] ) )
			{		
				//echo $fonts[$this->options["content-font"]]["import"]."\n				
				echo "div.entry-content{
						".$fonts[$this->options["content-font"]]["css"]."	
					}
					";
					
			}
							
			echo "\n</style>\n";			
				
		}
		
		function add_admin_options() {
			// menu
			add_theme_page( __( 'WP Theme Options', 'wpoptions' ), __('WP Theme Options', 'wpoptions' ), 'edit_theme_options', 'wpoptions-settings', array( &$this, 'theme_options' ) );
			// insert js
			
// For child theme change line below to wp_register_script('wp-options.js', get_stylesheet_directory_uri() . '/includes/wp-options.js', array('jquery'));

			wp_register_script('wp-options.js', plugin_dir_url( __FILE__ ) . 'includes/wp-options.js', array('jquery'));

			wp_enqueue_script('jquery');
			wp_enqueue_script('wp-options.js');
		}	
// For child theme change $data['plugin_dir'] = plugin_dir_url( __FILE__ );  to  $data['plugin_dir'] = get_stylesheet_directory_uri() . '/'; in the line below
	
		function theme_options() {
			$data['plugin_dir'] = plugin_dir_url( __FILE__ );
			$data['fonts'] = $this->get_my_available_fonts();
			$data["options"]	= $this->options;
		
			$this->fetch_template ("option_html", $data)	;
		}
		
		function fetch_template ($template="", $data = array())
		{
			if (count($data))
				foreach ($data as $key => $val)
					$$key = $val;

			require_once "includes/view/{$template}.php";			
		}
// You can add or remove fnts here, take a look at each font array to see how they can be added. Visit http://www.google.com/webfonts for the proper codes	
		
		function get_my_available_fonts() {
			$fonts = array(
				'open-sans' => array(
					'name' => 'Open Sans',
					'import' => '@import url(http://fonts.googleapis.com/css?family=Open+Sans);',
					'css' => "font-family: 'Open Sans', sans-serif;"
				),
				'lato' => array(
					'name' => 'Lato',
					'import' => '@import url(http://fonts.googleapis.com/css?family=Lato);',
					'css' => "font-family: 'Lato', sans-serif;"
				),
				'Arial' => array(
					'name' => 'Arial',
					'import' => '',
					'css' => "font-family: Arial, sans-serif;"
				),
				'Allan' => array(
					'name' => 'Allan',
					'import' => '@import url(http://fonts.googleapis.com/css?family=Allan:700);',
					'css' => "font-family: 'Allan', cursive;"
				),
				'Allerta' => array(
					'name' => 'Allerta',
					'import' => '@import url(http://fonts.googleapis.com/css?family=Allerta);',
					'css' => "font-family: 'Allerta', sans-serif;"
				),
				'Arimo' => array(
					'name' => 'Arimo',
					'import' => '@import url(http://fonts.googleapis.com/css?family=Arimo);',
					'css' => "font-family: 'Arimo', sans-serif;"
				),
				'IM Fell Double Pica SC' => array(
					'name' => 'IM Fell Double Pica SC',
					'import' => '@import url(http://fonts.googleapis.com/css?family=IM+Fell+Double+Pica+SC);',
					'css' => "font-family: 'IM Fell Double Pica SC', serif;"
				),	
				'Josefin Sans' => array(
					'name' => 'Josefin Sans',
					'import' => '@import url(http://fonts.googleapis.com/css?family=Josefin+Sans);',
					'css' => "font-family: 'Josefin Sans', sans-serif;"
				),
				'Lobster' => array(
					'name' => 'Lobster',
					'import' => '@import url(http://fonts.googleapis.com/css?family=Lobster);',
					'css' => "font-family: 'Lobster', cursive;"
				),
				'Maiden Orange' => array(
				'name' => 'Maiden Orange',
				'import' => '@import url(http://fonts.googleapis.com/css?family=Maiden+Orange);',
				'css' => "font-family: 'Maiden Orange', serif;"
				),
				'Molengo' => array(
					'name' => 'Molengo',
					'import' => '@import url(http://fonts.googleapis.com/css?family=Molengo);',
					'css' => "font-family: 'Molengo', sans-serif;"
				),
				'PT Sans Narrow' => array(
				'name' => 'PT Sans Narrow',
				'import' => '@import url(http://fonts.googleapis.com/css?family=PT+Sans+Narrow);',
				'css' => "font-family: 'PT Sans Narrow', sans-serif;"
				),
				'Raleway' => array(
				'name' => 'Raleway',
				'import' => '@import url(http://fonts.googleapis.com/css?family=Raleway:100);',
				'css' => "font-family: 'Raleway', sans-serif;"
				),
				'Smythe' => array(
				'name' => 'Smythe',
				'import' => '@import url(http://fonts.googleapis.com/css?family=Smythe);',
				'css' => "font-family: 'Smythe', cursive;"
				),
				'Vollkorn' => array(
				'name' => 'Vollkorn',
				'import' => '@import url(http://fonts.googleapis.com/css?family=Vollkorn);',
				'css' => "font-family: 'Vollkorn', sans-serif;"
				),
				'Walter Turncoat' => array(
				'name' => 'Walter Turncoat',
				'import' => '@import url(http://fonts.googleapis.com/css?family=Walter+Turncoat);',
				'css' => "font-family: 'Walter Turncoat', cursive;"
				),
				'Yanone Kaffeesatz' => array(
				'name' => 'Yanone Kaffeesatz',
				'import' => '@import url(http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz);',
				'css' => "font-family:'Yanone Kaffeesatz', sans-serif;"
				),
			);
		
			return apply_filters( 'my_available_fonts', $fonts );
		}
		
		// ajax functions here 
		function wp_options_update ()
		{
			echo "<pre>";
			print_r ($_POST);
			echo "</pre>";
			
			echo "<pre>";
			print_r ($this->options);
			echo "</pre>";
			
			
				
			$color_scheme = "";
				
			if ($_POST['color_default'] == "checked")
				$color_scheme = "default";
			elseif ($_POST['color_light'] == "checked")
				$color_scheme = "light";
			elseif ($_POST['color_dark'] == "checked")
				$color_scheme = "dark";
				
			
			$this->options["color-scheme"]	= $color_scheme;
			$this->options["title-font"]	= $_POST["title_font"];
			$this->options["content-font"]	= $_POST["content_font"];
			$this->options["custom-css"]	= $_POST["custom_css"];
			
			
			update_option( 'wpoptions-options', $this->options );
			
			die ();
		}
	
	}
	

?>