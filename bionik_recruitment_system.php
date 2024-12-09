<?php
/**
 * Plugin Name: Bionic Recruitment System
 * Description: A simple multi-step form for recruitment with admin and editor management.
 * Version: 1.0
 * Author: Suraiya
 */

// Prevent direct access
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Add menu to WordPress admin
function brs_add_menu() {
    // Admin and Editor access
    if ( current_user_can( 'edit_posts' ) ) {
        add_menu_page(
            'Bionic Recruitment',             // Page title
            'Bionic Recruitment',             // Menu title
            'edit_posts',                     // Capability (Editors and higher)
            'bionic-recruitment',             // Menu slug
            'brs_admin_page',                 // Callback function
            'dashicons-businessperson',       // Icon
            6                                 // Position
        );
    }

    // Subscriber access (view-only)
    if ( current_user_can( 'read' ) ) {
        add_menu_page(
            'Bionic Recruitment',             // Page title
            'Bionic Recruitment',             // Menu title
            'read',                           // Capability (Subscribers)
            'bionic-recruitment-subscriber',  // Separate slug for limited access
            'brs_subscriber_page',            // Callback for subscriber page
            'dashicons-businessperson',       // Icon
            6                                 // Position
        );
    }
}
add_action( 'admin_menu', 'brs_add_menu' );

 
function brs_admin_page() {
    if ( ! current_user_can( 'edit_posts' ) ) {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'bionic_recruitment_entries';

    // Handle form submissions (Add/Edit/Delete)
    if ( $_SERVER['REQUEST_METHOD'] == 'POST' && isset( $_POST['brs_action'] ) ) {
        if ( $_POST['brs_action'] === 'add' ) {
            // Add new entry
            $name = sanitize_text_field( $_POST['name'] );
            $email = sanitize_email( $_POST['email'] );
            $phone = sanitize_text_field( $_POST['phone'] );
            $about = sanitize_textarea_field( $_POST['about'] );
            $nid_number = sanitize_text_field( $_POST['nid_number'] );
            $certificate_number = sanitize_text_field( $_POST['certificate_number'] );
            $cv_file_path = sanitize_text_field( $_POST['cv_file_path'] );

            $wpdb->insert(
                $table_name,
                [
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'about' => $about,
                    'nid_number' => $nid_number,
                    'certificate_number' => $certificate_number,
                    'cv_file_path' => $cv_file_path
                ]
            );
            echo '<div class="notice notice-success"><p>Entry added successfully.</p></div>';
        } elseif ( $_POST['brs_action'] === 'edit' && isset( $_POST['entry_id'] ) ) {
            // Edit existing entry
            $entry_id = intval( $_POST['entry_id'] );
            $name = sanitize_text_field( $_POST['name'] );
            $email = sanitize_email( $_POST['email'] );
            $phone = sanitize_text_field( $_POST['phone'] );
            $about = sanitize_textarea_field( $_POST['about'] );
            $nid_number = sanitize_text_field( $_POST['nid_number'] );
            $certificate_number = sanitize_text_field( $_POST['certificate_number'] );
            $cv_file_path = sanitize_text_field( $_POST['cv_file_path'] );

            $wpdb->update(
                $table_name,
                [
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'about' => $about,
                    'nid_number' => $nid_number,
                    'certificate_number' => $certificate_number,
                    'cv_file_path' => $cv_file_path
                ],
                [ 'ID' => $entry_id ]
            );

            echo '<div class="notice notice-success"><p>Entry updated successfully.</p></div>';
        } elseif ( $_POST['brs_action'] === 'delete' && isset( $_POST['entry_id'] ) ) {
            // Delete an entry
            $entry_id = intval( $_POST['entry_id'] );
            $wpdb->delete( $table_name, [ 'ID' => $entry_id ] );
            echo '<div class="notice notice-success"><p>Entry deleted successfully.</p></div>';
        }
    }

    // Fetch entries for display
    $entries = $wpdb->get_results( "SELECT * FROM $table_name" );

    // Admin page interface
    echo '<div class="wrap">';
    echo '<h1>Recruitment Management</h1>';

    // Add/Edit form
    echo '<form method="POST" style="margin-bottom: 20px;">';
    echo '<h2>' . ( isset( $_POST['edit_entry'] ) ? 'Edit Entry' : 'Add New Entry' ) . '</h2>';
    if ( isset( $_POST['edit_entry'] ) ) {
        $entry_id = intval( $_POST['edit_entry'] );
        $entry = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE ID = %d", $entry_id ) );
    }
    echo '<input type="hidden" name="brs_action" value="' . ( isset( $_POST['edit_entry'] ) ? 'edit' : 'add' ) . '">';
    if ( isset( $_POST['edit_entry'] ) ) {
        echo '<input type="hidden" name="entry_id" value="' . esc_attr( $entry->ID ) . '">';
    }
    echo '<p><label>Name:</label><br><input type="text" name="name" value="' . ( isset( $entry ) ? esc_attr( $entry->name ) : '' ) . '" required></p>';
    echo '<p><label>Email:</label><br><input type="email" name="email" value="' . ( isset( $entry ) ? esc_attr( $entry->email ) : '' ) . '" required></p>';
    echo '<p><label>Phone:</label><br><input type="text" name="phone" value="' . ( isset( $entry ) ? esc_attr( $entry->phone ) : '' ) . '" required></p>';
    echo '<p><label>About:</label><br><textarea name="about" rows="4" required>' . ( isset( $entry ) ? esc_textarea( $entry->about ) : '' ) . '</textarea></p>';
    echo '<p><label>NID Number:</label><br><input type="text" name="nid_number" value="' . ( isset( $entry ) ? esc_attr( $entry->nid_number ) : '' ) . '" required></p>';
    echo '<p><label>Certificate Number:</label><br><input type="text" name="certificate_number" value="' . ( isset( $entry ) ? esc_attr( $entry->certificate_number ) : '' ) . '" required></p>';
    echo '<p><label>CV File Path:</label><br><input type="text" name="cv_file_path" value="' . ( isset( $entry ) ? esc_attr( $entry->cv_file_path ) : '' ) . '" required></p>';
    echo '<p><button type="submit">' . ( isset( $_POST['edit_entry'] ) ? 'Update Entry' : 'Add Entry' ) . '</button></p>';
    echo '</form>';

    // Table display
    echo '<h2>Entries</h2>';
    if ( ! empty( $entries ) ) {
        echo '<table class="widefat fixed striped">';
        echo '<thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>About</th><th>NID Number</th><th>Certificate Number</th><th>CV File Path</th><th>Actions</th></tr></thead>';
        echo '<tbody>';
        foreach ( $entries as $entry ) {
            echo '<tr>';
            echo '<td>' . esc_html( $entry->ID ) . '</td>';
            echo '<td>' . esc_html( $entry->name ) . '</td>';
            echo '<td>' . esc_html( $entry->email ) . '</td>';
            echo '<td>' . esc_html( $entry->phone ) . '</td>';
            echo '<td>' . esc_html( $entry->about ) . '</td>';
            echo '<td>' . esc_html( $entry->nid_number ) . '</td>';
            echo '<td>' . esc_html( $entry->certificate_number ) . '</td>';
            echo '<td><a href="' . esc_url( $entry->cv_file_path ) . '" target="_blank">View CV</a></td>';
            echo '<td>';
            echo '<form method="POST" style="display:inline-block;">';
            echo '<input type="hidden" name="edit_entry" value="' . esc_attr( $entry->ID ) . '">';
            echo '<button type="submit">Edit</button>';
            echo '</form> | ';
            echo '<form method="POST" style="display:inline-block;">';
            echo '<input type="hidden" name="brs_action" value="delete">';
            echo '<input type="hidden" name="entry_id" value="' . esc_attr( $entry->ID ) . '">';
            echo '<button type="submit" onclick="return confirm(\'Are you sure?\')">Delete</button>';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<p>No entries found.</p>';
    }
    echo '</div>';
}
 
function brs_subscriber_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'bionic_recruitment_entries';
    $entries = $wpdb->get_results( "SELECT * FROM $table_name" );

    echo '<div class="wrap">';
    echo '<h1>Recruitment Management</h1>';
    echo '<h2>Entries</h2>';
    if ( ! empty( $entries ) ) {
        echo '<table class="widefat fixed striped">';
        echo '<thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>About</th><th>NID Number</th><th>Certificate Number</th><th>CV File Path</th></tr></thead>';
        echo '<tbody>';
        foreach ( $entries as $entry ) {
            echo '<tr>';
            echo '<td>' . esc_html( $entry->ID ) . '</td>';
            echo '<td>' . esc_html( $entry->name ) . '</td>';
            echo '<td>' . esc_html( $entry->email ) . '</td>';
            echo '<td>' . esc_html( $entry->phone ) . '</td>';
            echo '<td>' . esc_html( $entry->about ) . '</td>';
            echo '<td>' . esc_html( $entry->nid_number ) . '</td>';
            echo '<td>' . esc_html( $entry->certificate_number ) . '</td>';
            echo '<td><a href="' . esc_url( $entry->cv_file_path ) . '" target="_blank">View CV</a></td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<p>No entries found.</p>';
    }
    echo '</div>';
}

 
function brs_create_database_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'bionic_recruitment_entries';

    // SQL to create table
    $sql = "
    CREATE TABLE $table_name (
        ID BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        about TEXT NOT NULL,
        nid_number VARCHAR(50) NOT NULL,
        certificate_number VARCHAR(50) NOT NULL,
        cv_file_path VARCHAR(255) NOT NULL,
        date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (ID)
    );
    ";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}
register_activation_hook( __FILE__, 'brs_create_database_table' );

 
function brs_enqueue_scripts() {
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'brs-script', plugin_dir_url( __FILE__ ) . 'assets/js/script.js', array( 'jquery' ), null, true );

    // Localize script to pass ajax_url and nonce
    wp_localize_script( 'brs-script', 'brs_ajax_obj', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ), // WordPress AJAX URL
        'nonce' => wp_create_nonce( 'brs_nonce' )   // Nonce for security
    ) );

    wp_enqueue_style( 'brs-style', plugin_dir_url( __FILE__ ) . 'assets/css/style.css' );
}
add_action( 'wp_enqueue_scripts', 'brs_enqueue_scripts' );
 
function brs_display_form() {
    ob_start();
    ?>
    <form id="bionic-recruitment-form" enctype="multipart/form-data">
        <div class="step" id="step-1">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <textarea name="about" placeholder="About You" required></textarea>
            <button type="button" onclick="nextStep()">Next</button>
        </div>
        <div class="step" id="step-2" style="display: none;">
            <input type="text" name="nid_number" placeholder="NID Number" required>
            <input type="text" name="certificate_number" placeholder="Certificate Number" required>
            <input type="file" name="cv_file" accept=".pdf,.jpg,.doc,.png" required>
            <button type="submit">Submit</button>
        </div>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/js/script.js'; ?>"></script>
    <?php
    return ob_get_clean();
}
// Handle AJAX request
function brs_handle_form_submission() {
    // Verify nonce for security
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'brs_nonce' ) ) {
        wp_send_json_error( 'Security check failed' );
    }

    // Check if the form is being submitted twice
    if ( isset( $_SESSION['form_submitted'] ) && $_SESSION['form_submitted'] == true ) {
        wp_send_json_error( 'Form has already been submitted' );
    }

    // Handle the form data (e.g., file upload, text fields)
    if ( isset( $_FILES['cv_file'] ) ) {
        $file = $_FILES['cv_file'];
        // Handle file upload here, save it, and retrieve the path
        $uploaded_file = wp_handle_upload( $file, array( 'test_form' => false ) );

        if ( isset( $uploaded_file['file'] ) ) {
            // Insert form data into the database
            global $wpdb;
            $table_name = $wpdb->prefix . 'bionic_recruitment_entries';
            $wpdb->insert(
                $table_name,
                array(
                    'name' => sanitize_text_field( $_POST['name'] ),
                    'email' => sanitize_email( $_POST['email'] ),
                    'phone' => sanitize_text_field( $_POST['phone'] ),
                    'about' => sanitize_textarea_field( $_POST['about'] ),
                    'nid_number' => sanitize_text_field( $_POST['nid_number'] ),
                    'certificate_number' => sanitize_text_field( $_POST['certificate_number'] ),
                    'cv_file_path' => $uploaded_file['url'] // Save the file URL
                )
            );
            $_SESSION['form_submitted'] = true; // Mark the form as submitted

            wp_send_json_success( 'Form submitted successfully' );
        } else {
            wp_send_json_error( 'File upload failed' );
        }
    } else {
        wp_send_json_error( 'Form data missing' );
    }

    wp_die(); // End the AJAX request
}

add_action( 'wp_ajax_brs_submit_form', 'brs_handle_form_submission' ); // For logged-in users
// add_action( 'wp_ajax_nopriv_brs_submit_form', 'brs_handle_form_submission' ); // For non-logged-in users

add_shortcode( 'bionic_recruitment_form', 'brs_display_form' );
