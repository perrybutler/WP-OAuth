<?php 

class WP_OauthLinkedAccountsTpl {
  protected $query_result, $wpoa;

  function __construct( $wpoa, $query_result ) {
    $this->query_result = $query_result;
    $this->wpoa = $wpoa;
  }

  public function display() {
    ?>
    <div id='wpoa-linked-accounts'>
      <h3><?php _e( "Linked Accounts" ) ?></h3>
      <p><?php _e( "Manage the linked accounts which you have previously authorized to be used for logging into this website." ); ?></p>
      <table class='form-table'>
        <tr valign='top'>
          <th scope='row'><?php _e( "Your Linked Providers" ); ?></th>
          <td>
            <?php $this->no_linked_account_notify(); ?>
            <ul class='wpoa-linked-accounts'>
              <?php $this->get_linked_providers(); ?>
            </ul>
          </td>
        </tr>
        <tr>
          <th scope="row"><?php _e( "Link Another Provider" ); ?></th>
          <td><?php $this->list_other_providers(); ?></td>
        </tr>
      </table>
    </div>
    <?php
  }


  public function get_linked_providers() {
    foreach ($this->query_result as $wpoa_row) {
      $wpoa_identity_parts = explode('|', $wpoa_row->meta_value);
      $oauth_provider = $wpoa_identity_parts[0];
      $oauth_id = $wpoa_identity_parts[1]; // keep this private, don't send to client
      $time_linked = $wpoa_identity_parts[2];
      $local_time = strtotime("-" . $_COOKIE['gmtoffset'] . ' hours', $time_linked);

      $button = sprintf(
        '<a href="#" data-wpoa-identity-row="%s" class="wpoa-unlink-account">%s</a>',
        $wpoa_row->umeta_id,
        __( "Unlink" )
      );

      printf( '<li>%s %s %s %s</li>',
        $oauth_provider,
        __( "on" ),
        date('F d, Y h:i A', $local_time),
        $button
      );
    }
  }


  public function no_linked_account_notify() {
    if ( count( $this->query_result ) == 0 ) {
      printf( "<p>%s</p>", __( "You currently don't have any accounts linked" ) );
    }
  }


  public function list_other_providers() {
    $design = get_option('wpoa_login_form_show_profile_page');
    if ($design != "None") {
      // TODO: we need to use $settings defaults here, not hard-coded defaults...
      echo $this->wpoa->wpoa_login_form_content($design, 'none', 'buttons-row', 'Link', 'left', 'always', 'never', 'Select a provider:', 'Select a provider:', 'Authenticating...', '');
    }
  }
}