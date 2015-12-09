<?php
/*
Plugin Name: Simple shipping method
Description: Simple shipping method plugin
Version: 1.0.0
*/

/**
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	function simple_shipping_method_init() {
		if ( ! class_exists( 'WC_Simple_Shipping_Method' ) ) {
			class WC_Simple_Shipping_Method extends WC_Shipping_Method {
				/**
				 * Constructor for your shipping class
				 *
				 * @access public
				 * @return void
				 */
				public function __construct() {
					$this->id                 = 'simple_shipping_method';
					$this->method_title       = __( 'Simple shipping Method' );
					$this->method_description = __( 'Description of your shipping method' );
					$this->enabled            = 'yes';
					$this->title              = 'Simple shipping Method';
					$this->cost				  = '0';

					$this->init();
				}

				/**
				 * Init your settings
				 *
				 * @access public
				 * @return void
				 */
				function init() {
					$this->init_form_fields();
					$this->init_settings();

					add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
				}

				/**
				 * init_form_fields function.
				 * 
				 * @access public
				 * @return void
				 */
				public function init_form_fields() {
					$this->form_fields = array(
						'enabled' => array(
							'title' => __( 'Enable/Disable', 'woocommerce' ),
							'type' => 'checkbox',
							'label' => __( 'Enable', 'woocommerce' ),
							'default' => 'yes'
						),
						'title' => array (
							'title' => __( 'Method Title', 'woocommerce' ),
							'type' => 'text',
							'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
							'default'	=> __( 'Simple Shipping Method', 'woocommerce' ),
						),
						'cost' => array (
							'title' => __( 'Cost', 'woocommerce' ),
							'type' => 'text',
							'default'	=> __( '0', 'woocommerce' ),
						)
					);
				}


				/**
				 * calculate_shipping function.
				 *
				 * @access public
				 * @param mixed $package
				 * @return void
				 */
				public function calculate_shipping( $package ) {
					$rate = array(
						'id' => $this->id,
						'label' => $this->settings['title'],
						'cost' => $this->settings['cost'],
						'calc_tax' => 'per_item'
					);

					// Register the rate
					$this->add_rate( $rate );
				}
			}
		}
	}

	add_action( 'woocommerce_shipping_init', 'simple_shipping_method_init' );

	function add_simple_shipping_method( $methods ) {
		$methods[] = 'WC_Simple_Shipping_Method';
		return $methods;
	}

	add_filter( 'woocommerce_shipping_methods', 'add_simple_shipping_method' );
}