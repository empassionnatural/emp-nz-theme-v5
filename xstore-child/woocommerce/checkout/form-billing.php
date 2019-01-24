<?php
/**
 * Checkout billing information form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.9
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div id="acc" class="accordion" onclick="openAccordion(event, 'acc','acctDet')" >

    <h3 class="accordion-title step-title"><span><?php esc_html_e( 'Account Details', 'xstore' ); ?></span><span value="-" class="minus"><i class="et-icon et-minus"></i></span><span value="+" class="plus"><i class="et-icon et-plus"></i></span></h3>

</div>

<div id="acctDet" class="woocommerce-billing-fields"  style="display: none;">

<!--    <button class="accordion">--><?php //esc_html_e( 'Account Details', 'xstore' ); ?><!--</button>-->

    <?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

	<div class="woocommerce-billing-fields__field-wrapper">
		
		<?php $fields = $checkout->get_checkout_fields( 'billing' ); ?>
		<?php foreach ( $fields as $key => $field ) : ?>

			<?php
				if ( isset( $field['country_field'], $fields[ $field['country_field'] ] ) ) {
					$field['country'] = $checkout->get_value( $field['country_field'] );
				}
			 ?>

			<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

		<?php endforeach; ?>

	</div>

	<?php do_action('woocommerce_after_checkout_billing_form', $checkout ); ?>


	<?php if ( ! is_user_logged_in() && $checkout->is_registration_enabled() ) : ?>

		<div class="woocommerce-account-fields">
			<?php if ( ! $checkout->is_registration_required() ) : ?>

				<p class="form-row form-row-wide create-account">

					<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">

						<input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ) ?> type="checkbox" name="createaccount" value="1" /> <span><?php esc_html_e( 'Create an account?', 'xstore' ); ?></span>

                    </label>

				</p>

			<?php endif; ?>

			<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

			<?php if ( $checkout->get_checkout_fields( 'account' ) ) : ?>

				<div class="create-account">
					<?php foreach ( $checkout->get_checkout_fields( 'account' )  as $key => $field ) : ?>
						<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
					<?php endforeach; ?>
					<div class="clear"></div>
				</div>

			<?php endif; ?>

			<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>
		</div>

	<?php endif; ?>

</div>