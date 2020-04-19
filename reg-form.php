<?php
/*
Plugin Name: Reg Form
Plugin URI:
Description: Friendly Description
Version: 1.0.0
Author: Plain Text Author Name
Author URI:
License: GPLv2 or later
Text Domain: reg-form
 */

add_action( 'register_form', function () {
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $phone_number = $_POST['phone_number'] ?? '';
    ?>
    <p>
        <label for="first_name">
        <?php _e( 'First Name', 'reg-form' );?>
        </label>
        <input type="text" name="first_name" id="first_name" value="<?php echo esc_attr($first_name) ;?>">
    </p>

    <p>
        <label for="last_name">
        <?php _e( 'Last Name', 'reg-form' );?>
        </label>
        <input type="text" name="last_name" id="last_name" value="<?php echo esc_attr($last_name) ;?>">
    </p>
    
    <p>
        <label for="phone_number">
        <?php _e( 'Phone Number', 'reg-form' );?>
        </label>
        <input type="text" name="phone_number" id="phone_number" value="<?php echo esc_attr($phone_number) ;?>">
    </p>
    <?php
} );

add_filter( 'registration_errors', function ( $errors, $sanitized_user_login, $user_email ) {
    if ( empty( $_POST['first_name'] ) ) {
        $errors->add( 'first_name_blank', __( 'First Name Cannot Be Blank', 'reg-form' ) );
    }

    if ( empty( $_POST['last_name'] ) ) {
        $errors->add( 'last_name_blank', __( 'Last Name Cannot Be Blank', 'reg-form' ) );
    }
    
    if ( empty( $_POST['phone_number'] ) ) {
        $errors->add( 'phone_number_blank', __( 'Phone Number Cannot Be Blank', 'reg-form' ) );
    }

    return $errors;
}, 10, 3 );

add_action('user_register',function($user_id){
    if(!empty($_POST['first_name'])){
        update_user_meta($user_id, 'first_name',sanitize_text_field($_POST['first_name']));
    }
    
    if(!empty($_POST['last_name'])){
        update_user_meta($user_id, 'last_name',sanitize_text_field($_POST['last_name']));
    }
    
    if(!empty($_POST['phone_number'])){
        update_user_meta($user_id, 'phone_number',sanitize_text_field($_POST['phone_number']));
    }
});

function rfp_user_profile_phone_number( $user )
{
    ?>
    <!-- <h3>Phone Number</h3> -->
    <table class="form-table">
        <tr>
            <th>
                <label for="phone_number">Phone Number</label>
            </th>
            <td>
                <input type="number"
                       class="regular-text ltr"
                       id="phone_number"
                       name="phone_number"
                       value="<?= esc_attr( get_user_meta( $user->ID, 'phone_number', true ) ) ?>"
                       title="Phone Number"
                       >
                <p class="description">
                    <?php _e("Phone Number","reg-form") ;?>
                </p>
            </td>
        </tr>
    </table>
    <?php
}

add_action(
    'show_user_profile',
    'rfp_user_profile_phone_number'
);
  
add_action(
    'edit_user_profile',
    'rfp_user_profile_phone_number'
);
  

function rfp_update_phone_number( $user_id )
{
    // check that the current user have the capability to edit the $user_id
    if ( ! current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }
  
    // create/update user meta for the $user_id
    return update_user_meta(
        $user_id,
        'phone_number',
        sanitize_text_field($_POST['phone_number'])
    );
}

add_action(
    'personal_options_update',
    'rfp_update_phone_number'
);
  
// Add the save action to user profile editing screen update.
add_action(
    'edit_user_profile_update',
    'rfp_update_phone_number'
);