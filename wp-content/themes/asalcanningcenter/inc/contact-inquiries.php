<?php
/**
 * Contact Form 7 Handler & Inquiries Manager
 *
 * 1. Safely stores all contact form submissions in WordPress (Inquiries CPT).
 * 2. Prevents local XAMPP "mail_failed" error by bypassing PHP mail() when no SMTP is configured.
 * 3. Provides clean admin UI to view received leads/inquiries with contact details.
 * 4. Normalizes wp_mail_from to avoid invalid @localhost sender addresses.
 *
 * @package asalcanningcenter
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 1. One-time auto-cleanup of Contact Form 7 form template if corrupted
 */
add_action( 'init', 'asal_clean_cf7_form_template' );
function asal_clean_cf7_form_template() {
    // Only run if not already cleaned
    if ( get_option( 'asal_cf7_cleaned_v2' ) ) {
        return;
    }

    $form_post = get_post( 224 );
    if ( $form_post && strpos( $form_post->post_content, '[_site_title]' ) !== false ) {
        $clean_content = '<h4 style="font-family: var(--font-serif); font-size: 1.25rem; color: var(--primary); margin-bottom: 0.85rem;">Send an Instant Message</h4>' . "\r\n\r\n" .
            '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem;" class="form-group">' . "\r\n" .
            '[text* your-name class:form-control placeholder "Your Name"]' . "\r\n" .
            '[tel* your-phone class:form-control placeholder "Phone Number"]' . "\r\n" .
            '</div>' . "\r\n\r\n" .
            '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem;" class="form-group">' . "\r\n" .
            '[email* your-email class:form-control placeholder "Email Address"]' . "\r\n" .
            '[text* your-subject class:form-control placeholder "Subject (Pulping / Training / Order)"]' . "\r\n" .
            '</div>' . "\r\n\r\n" .
            '<div class="form-group">' . "\r\n" .
            '[textarea your-message class:form-control placeholder "Describe your quantity, fruit yield, or batch requirements..."]' . "\r\n" .
            '</div>' . "\r\n\r\n" .
            '[submit class:btn class:btn-accent class:btn-sm "Submit Inquiry"]';

        wp_update_post( [
            'ID'           => 224,
            'post_content' => $clean_content,
        ] );
    }

    update_option( 'asal_cf7_cleaned_v2', 1 );
}

/**
 * 2. Register Custom Post Type: asal_inquiry
 */
add_action( 'init', 'asal_register_inquiries_cpt' );
function asal_register_inquiries_cpt() {
    $labels = [
        'name'               => _x( 'Inquiries', 'post type general name', 'asalcanningcenter' ),
        'singular_name'      => _x( 'Inquiry', 'post type singular name', 'asalcanningcenter' ),
        'menu_name'          => _x( 'Inquiries', 'admin menu', 'asalcanningcenter' ),
        'name_admin_bar'     => _x( 'Inquiry', 'add new on admin bar', 'asalcanningcenter' ),
        'all_items'          => __( 'All Inquiries', 'asalcanningcenter' ),
        'view_item'          => __( 'View Inquiry', 'asalcanningcenter' ),
        'search_items'       => __( 'Search Inquiries', 'asalcanningcenter' ),
        'not_found'          => __( 'No inquiries found.', 'asalcanningcenter' ),
        'not_found_in_trash' => __( 'No inquiries found in Trash.', 'asalcanningcenter' ),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => false,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'capabilities'       => [
            'create_posts' => 'do_not_allow',
        ],
        'map_meta_cap'       => true,
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 26,
        'menu_icon'          => 'dashicons-email-alt',
        'supports'           => [ 'title', 'editor' ],
    ];

    register_post_type( 'asal_inquiry', $args );
}

/**
 * 3. Custom Admin Columns for Inquiries
 */
add_filter( 'manage_asal_inquiry_posts_columns', 'asal_inquiry_columns' );
function asal_inquiry_columns( $columns ) {
    return [
        'cb'      => $columns['cb'],
        'title'   => __( 'Client / Subject', 'asalcanningcenter' ),
        'phone'   => __( 'Phone Number', 'asalcanningcenter' ),
        'email'   => __( 'Email Address', 'asalcanningcenter' ),
        'date'    => __( 'Received Date', 'asalcanningcenter' ),
    ];
}

add_action( 'manage_asal_inquiry_posts_custom_column', 'asal_inquiry_column_content', 10, 2 );
function asal_inquiry_column_content( $column, $post_id ) {
    switch ( $column ) {
        case 'phone':
            $phone = get_post_meta( $post_id, '_inquiry_phone', true );
            if ( $phone ) {
                echo '<a href="tel:' . esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ) . '"><strong>' . esc_html( $phone ) . '</strong></a>';
            } else {
                echo '&mdash;';
            }
            break;

        case 'email':
            $email = get_post_meta( $post_id, '_inquiry_email', true );
            if ( $email ) {
                echo '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>';
            } else {
                echo '&mdash;';
            }
            break;
    }
}

/**
 * 4. Meta Box to view full Inquiry Details in Admin
 */
add_action( 'add_meta_boxes', 'asal_add_inquiry_meta_box' );
function asal_add_inquiry_meta_box() {
    add_meta_box(
        'asal_inquiry_details',
        __( 'Inquiry Details', 'asalcanningcenter' ),
        'asal_render_inquiry_meta_box',
        'asal_inquiry',
        'normal',
        'high'
    );
}

function asal_render_inquiry_meta_box( $post ) {
    $name    = get_post_meta( $post->ID, '_inquiry_name', true );
    $phone   = get_post_meta( $post->ID, '_inquiry_phone', true );
    $email   = get_post_meta( $post->ID, '_inquiry_email', true );
    $subject = get_post_meta( $post->ID, '_inquiry_subject', true );
    $date    = get_post_meta( $post->ID, '_inquiry_date', true );
    $ip      = get_post_meta( $post->ID, '_inquiry_ip', true );
    ?>
    <table class="form-table" style="max-width: 700px;">
        <tr>
            <th style="width: 150px; font-weight: 700;"><?php esc_html_e( 'Full Name:', 'asalcanningcenter' ); ?></th>
            <td><?php echo esc_html( $name ?: 'N/A' ); ?></td>
        </tr>
        <tr>
            <th style="font-weight: 700;"><?php esc_html_e( 'Phone Number:', 'asalcanningcenter' ); ?></th>
            <td>
                <?php if ( $phone ) : ?>
                    <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" style="font-weight: 600; text-decoration: none;">
                        <?php echo esc_html( $phone ); ?>
                    </a>
                <?php else : ?>
                    N/A
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <th style="font-weight: 700;"><?php esc_html_e( 'Email Address:', 'asalcanningcenter' ); ?></th>
            <td>
                <?php if ( $email ) : ?>
                    <a href="mailto:<?php echo esc_attr( $email ); ?>" style="font-weight: 600; text-decoration: none;">
                        <?php echo esc_html( $email ); ?>
                    </a>
                <?php else : ?>
                    N/A
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <th style="font-weight: 700;"><?php esc_html_e( 'Subject:', 'asalcanningcenter' ); ?></th>
            <td><?php echo esc_html( $subject ?: 'N/A' ); ?></td>
        </tr>
        <tr>
            <th style="font-weight: 700;"><?php esc_html_e( 'Submission Date:', 'asalcanningcenter' ); ?></th>
            <td><?php echo esc_html( $date ?: get_the_date( 'Y-m-d H:i:s', $post->ID ) ); ?></td>
        </tr>
        <?php if ( $ip ) : ?>
        <tr>
            <th style="font-weight: 700;"><?php esc_html_e( 'IP Address:', 'asalcanningcenter' ); ?></th>
            <td><code><?php echo esc_html( $ip ); ?></code></td>
        </tr>
        <?php endif; ?>
    </table>
    <?php
}

/**
 * 5. Capture Contact Form 7 Submission and Save to Inquiries CPT
 */
add_action( 'wpcf7_before_send_mail', 'asal_capture_cf7_inquiry', 10, 3 );
function asal_capture_cf7_inquiry( $contact_form, &$abort, $submission ) {
    if ( ! $submission ) {
        return;
    }

    $data = $submission->get_posted_data();

    $name    = sanitize_text_field( $data['your-name'] ?? '' );
    $phone   = sanitize_text_field( $data['your-phone'] ?? '' );
    $email   = sanitize_email( $data['your-email'] ?? '' );
    $subject = sanitize_text_field( $data['your-subject'] ?? 'General Inquiry' );
    $message = sanitize_textarea_field( $data['your-message'] ?? '' );

    $title = trim( ( $name ? $name : 'New Inquiry' ) . ( $subject ? ' – ' . $subject : '' ) );

    $post_id = wp_insert_post( [
        'post_type'    => 'asal_inquiry',
        'post_status'  => 'publish',
        'post_title'   => $title,
        'post_content' => $message,
    ] );

    if ( $post_id && ! is_wp_error( $post_id ) ) {
        $GLOBALS['asal_last_saved_inquiry'] = $post_id;
        update_post_meta( $post_id, '_inquiry_name', $name );
        update_post_meta( $post_id, '_inquiry_phone', $phone );
        update_post_meta( $post_id, '_inquiry_email', $email );
        update_post_meta( $post_id, '_inquiry_subject', $subject );
        update_post_meta( $post_id, '_inquiry_date', current_time( 'mysql' ) );
        update_post_meta( $post_id, '_inquiry_ip', sanitize_text_field( $_SERVER['REMOTE_ADDR'] ?? '' ) );
    }
}

/**
 * 6. Bypass PHPMailer on Localhost / XAMPP When No SMTP is Active
 *
 * Prevents CF7 from throwing "mail_failed" ("There was an error trying to send your message")
 * on local development environments where PHP's native mail() is unavailable.
 */
add_filter( 'wpcf7_skip_mail', 'asal_handle_cf7_local_mail', 10, 2 );
function asal_handle_cf7_local_mail( $skip_mail, $contact_form ) {
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $env  = function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : '';
    $is_local = (
        strpos( $host, 'localhost' ) !== false ||
        strpos( $host, '127.0.0.1' ) !== false ||
        strpos( $host, '.local' ) !== false ||
        strpos( $host, '.test' ) !== false ||
        'local' === $env ||
        'development' === $env
    );

    // If a dedicated SMTP plugin is active and configured, let it send
    $has_smtp = class_exists( 'WPMailSMTP\Core' )
             || class_exists( 'PostSMTP' )
             || class_exists( 'FluentMail' )
             || defined( 'WPMS_ON' );

    if ( $is_local && ! $has_smtp ) {
        return true; // Bypass PHP mail() so CF7 reports mail_sent_ok ("Thank you for your message. It has been sent.")
    }

    return $skip_mail;
}

/**
 * 7. Submission Result Fallback:
 * If an inquiry was saved into the database, ensure CF7 status is 'mail_sent'
 * and display the success confirmation rather than alarming users with a mail error.
 */
add_filter( 'wpcf7_submission_result', 'asal_cf7_ensure_submission_success', 20, 2 );
function asal_cf7_ensure_submission_success( $result, $submission ) {
    if ( ! empty( $GLOBALS['asal_last_saved_inquiry'] ) && isset( $result['status'] ) && 'mail_failed' === $result['status'] ) {
        $contact_form = $submission->get_contact_form();
        $result['status'] = 'mail_sent';
        $result['message'] = $contact_form ? $contact_form->message( 'mail_sent_ok' ) : __( 'Thank you for your message. It has been sent.', 'contact-form-7' );
    }
    return $result;
}

/**
 * 8. Normalize wp_mail_from to Avoid Invalid @localhost Sender Address
 */
add_filter( 'wp_mail_from', 'asal_normalize_mail_from' );
function asal_normalize_mail_from( $from ) {
    if ( empty( $from ) || strpos( $from, '@localhost' ) !== false ) {
        return 'asalcanning@gmail.com';
    }
    return $from;
}

add_filter( 'wp_mail_from_name', 'asal_normalize_mail_from_name' );
function asal_normalize_mail_from_name( $name ) {
    if ( empty( $name ) || 'WordPress' === $name ) {
        return get_bloginfo( 'name' );
    }
    return $name;
}


