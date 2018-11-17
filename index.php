<?php
/**
 * Plugin Name: DMI Delete Revisions
 * Description: A simple custom plugin to delete post revisions before a specific date.
 * Author: Aboelabbas Shahwan.
 * Version: 1.0
 * Author URI: https://fb.com/aboelabbass
 * 
 */
namespace DMI;

class DeleteRevisions {

    /**
     * The date before which we want to delete revisions.
     * 
     * @var string
     * 
     */
    private $before = 'January 1st, 2018';

    /**
     * The number of revisions to delete per request.
     * 
     * @var int
     * 
     */
    private $number = 100;

    public function __construct() {

        add_action( 'admin_menu', [ $this, 'menu'] );
        add_action( 'wp_ajax_delete_revisions', [ $this, 'delete' ] );

    }

    /**
     * Set the date before which we should delete
     * revisions.
     * 
     * @param string $date;
     * 
     */
    public function before( $date ) {

        if ( $date ) {

            $this->before = $date;

        }

    }

    /**
     * Set the number of revisions to delete every time.
     * 
     * @param int $number.
     * 
     */
    public function number( $number ) {

        $number = (int) $number;

        if ( $number ) {

            $this->number = $number;

        }
    }

    /**
     * Add a Dashboard menu for our plugin.
     * 
     */
    public function menu() {

        add_menu_page( 
            __( 'Delete Revisions', 'dmi' ),
            'Delete Revisions',
            'manage_options',
            'dmi-delete-revisions',
            [ $this, 'page' ],
            '',
            140
        ); 
    }

    /**
     * Delete the required number of revisions.
     * used as AJAX Handler.
     * 
     */
    public function delete() {

        check_ajax_referer( 'dmi-delete-revisions', 'security' );

        if ( ! current_user_can( 'manage_options' ) ) return;

        header( 'Content-Type: application/json;charset=UTF-8');
        $response = [
            'msg'     => 'Process completed, No revisions before ' .  $this->before,
            'success' => true,
            'action'  => 'stop',
            'data'    => [],
        ];
        
        $revisions = $this->getRevisions();

        if ( ! $revisions ) {

            echo json_encode( $response );
            wp_die();
            return;
        }

        $response['msg']    = 'The revision delete request was done successfully.';
        $response['action'] = 'next';

        foreach ( $revisions as $rev ) {

            $deleted = wp_delete_post_revision( $rev );

            if ( $deleted && ! is_wp_error( $deleted ) ) {

                $response['data'][] = [
                    'title'   => $rev->post_title,
                    'date'    => $rev->post_date,
                    'id'      => $rev->ID,
                    'deleted' => true
                ];

            } else {

                $response['data'][] = [
                    'title'   => $rev->post_title,
                    'date'    => $rev->post_date,
                    'id'      => $rev->ID,
                    'deleted' => false,

                ];
            }


        }

        echo json_encode( $response );
        wp_die();
    }

    /**
     * Display our plugin's page in the Dashboard.
     */
    public function page() {

        if ( ! current_user_can( 'manage_options' ) ) return;

        $ajax_nonce = wp_create_nonce( "dmi-delete-revisions" );
        $start      = isset( $_REQUEST['start_del'] ) ? $_REQUEST['start_del'] : '';

        if ( 'true' === $start ) {
            
            include plugin_dir_path( __FILE__ ) . 'templates/started.php';

        } else {

            include plugin_dir_path( __FILE__ ) . 'templates/intro.php';

        }

    }

    /**
     * Get an array of revisions to prepare for deletion.
     * 
     */
    private function getRevisions() {

        $args = [
            'post_type'   => 'revision',
            'post_status' => 'inherit',
            'posts_per_page' => $this->number,
            'no_found_rows'  => true,
            'order' => 'ASC',
            'orderby' => 'post_date',
            'date_query' => array(
                array(
                    'before'     => $this->before,
                    'inclusive'  => true,
                ),
            ),
        ];

        $revisions = get_posts( $args );
        return $revisions;

    }

}

//Initialize Our plugin
$deleteRevision = new \DMI\DeleteRevisions();
