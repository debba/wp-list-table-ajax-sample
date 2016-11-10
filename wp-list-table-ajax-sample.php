<?php

/**
 * WP List Table Ajax Sample
 *
 * WP List Table Ajax Sample is a WordPress Plugin example of WP_List_Table
 * AJAX implementation. This plugin loads datasets completly in async.
 * It is a fork of Charlie MERLAND's Custom List Table Example plugin.
 *
 * Plugin Name: WP List Table Ajax Sample
 * Plugin URI:  TODO
 * Description: A sample plugin for studying how creating a AJAX List Table completly in async using WP_List_Table
 * Version:     1.0
 * Author:      Andrea Debernardi
 * Author URI:  https://www.dueclic.com
 * License:     GPL-2.0
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * GitHub Plugin URI: TODO
 */

/**
Copyright 2016 Andrea Debernardi (email : andrea.debernardi@dueclic.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


/**
 *
 * WP_List_Table class inclusion
 *
 */

if ( ! class_exists( 'WP_List_Table' ) )
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

/**
 *
 * As suggested from Charlie in its plugin https://github.com/Askelon/Custom-AJAX-List-Table-Example
 * it's better to set error_reporting hiding notices to avoid AJAX errors
 *
 */

error_reporting( ~E_NOTICE );

class My_List_Table extends WP_List_Table {

	/**
	 *
	 * For this sample we'll use a dataset in a static array
	 *
	 */

	private $sample_data = array(

		array (
			"id" => 1,
			"title" => "A hard day's night",
			"artist" => "The Beatles",
			"year" => 1964
		),
		array (
			"id" => 2,
			"title" => "Lucifer Sam",
			"artist" => "Pink Floyd",
			"year" => 1967
		),
		array (
			"id" => 3,
			"title" => "Light My Fire",
			"artist" => "The Doors",
			"year" => 1966
		),
		array (
			"id" => 4,
			"title" => "I heard it through the grapevine",
			"artist" => "Marvin Gaye",
			"year" => 1968
		),
		array (
			"id" => 5,
			"title" => "Like a rolling stone",
			"artist" => "Bob Dylan",
			"year" => 1965
		),
		array (
			"id" => 6,
			"title" => "Suspicious minds",
			"artist" => "Elvis Presley",
			"year" => 1969
		),
		array (
			"id" => 7,
			"title" => "Sympathy for the devil",
			"artist" => "Rolling Stones",
			"year" => 1968
		),
		array (
			"id" => 8,
			"title" => "I’m waiting for the man",
			"artist" => "Velvet Underground",
			"year" => 1967
		),
		array (
			"id" => 9,
			"title" => "Shangri-Las",
			"artist" => "Leader of the pack",
			"year" => 1964
		),
		array (
			"id" => 10,
			"title" => "All along the watchtower",
			"artist" => "Jimi Hendrix Experience",
			"year" => 1968
		),
		array (
			"id" => 11,
			"title" => "Good vibrations",
			"artist" => "Beach Boys",
			"year" => 1966
		),
		array (
			"id" => 12,
			"title" => "Be my baby",
			"artist" => "Ronettes",
			"year" => 1963
		),
		array (
			"id" => 13,
			"title" => "A day in the life",
			"artist" => "The Beatles",
			"year" => 1967
		),
		array (
			"id" => 14,
			"title" => "People are strange",
			"artist" => "The Doors",
			"year" => 1967
		),
		array (
			"id" => 15,
			"title" => "Sunday morning",
			"artist" => "Velvet Underground",
			"year" => 1967
		),
		array (
			"id" => 16,
			"title" => "A hard days night",
			"artist" => "The Beatles",
			"year" => 1964
		),
		array (
			"id" => 17,
			"title" => "Help",
			"artist" => "The Beatles",
			"year" => 1965
		),
		array (
			"id" => 18,
			"title" => "Astronomy Domine",
			"artist" => "Pink Floyd",
			"year" => 1969
		),
		array (
			"id" => 19,
			"title" => "Barbara Ann",
			"artist" => "Beach Boys",
			"year" => 1965
		),
		array (
			"id" => 20,
			"title" => "A Whiter Shade Of Pale",
			"artist" => "Procol Harum",
			"year" => 1967
		)

	);

	function __construct() {

		parent::__construct(
			array(
				'singular'  => '60s hit',
				'plural'    => '60s hits',
				'ajax'      => true
			)
		);

	}

	function get_columns() {

		$columns = array(
			'id'      => 'ID',
			'title'   => 'Title',
			'artist'  => 'Artist',
			'year'    => 'Year'
		);
		return $columns;

	}

	function column_default( $item, $column_name ) {
		switch( $column_name ) {
			case 'id':
			case 'title':
			case 'artist':
			case 'year':
				return $item[ $column_name ];
			default:
				return print_r( $item, true );
		}
	}

	private $hidden_columns = array(
		'id'
	);

	function get_sortable_columns() {

		return $sortable_columns = array(
			'title'	 	=> array( 'title', false ),
			'artist'	=> array( 'artist', false ),
			'year'   	=> array( 'year', false )
		);
	}

	function prepare_items() {

		$per_page = 5;
		$columns  = $this->get_columns();
		$hidden   = $this->hidden_columns;
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array($columns, $hidden, $sortable);

		$data = $this->sample_data;

		function usort_reorder( $a, $b ) {

			$orderby = ( ! empty( $_REQUEST['orderby'] ) ) ? $_REQUEST['orderby'] : 'title';
			$order = ( ! empty( $_REQUEST['order'] ) ) ? $_REQUEST['order'] : 'asc';
			$result = strcmp( $a[ $orderby ], $b[ $orderby ] );
			return ( 'asc' === $order ) ? $result : -$result;
		}
		usort( $data, 'usort_reorder' );

		$current_page = $this->get_pagenum();

		$total_items = count($data);

		$data = array_slice($data,(($current_page-1)*$per_page),$per_page);

		$this->items = $data;

		$this->set_pagination_args(
			array(

				'total_items'	=> $total_items,
				'per_page'	    => $per_page,
				'total_pages'	=> ceil( $total_items / $per_page ),
				'orderby'	    => ! empty( $_REQUEST['orderby'] ) && '' != $_REQUEST['orderby'] ? $_REQUEST['orderby'] : 'title',
				'order'		    => ! empty( $_REQUEST['order'] ) && '' != $_REQUEST['order'] ? $_REQUEST['order'] : 'asc'
			)
		);
	}

	function display() {

		wp_nonce_field( 'ajax-custom-list-nonce', '_ajax_custom_list_nonce' );

		echo '<input type="hidden" id="order" name="order" value="' . $this->_pagination_args['order'] . '" />';
		echo '<input type="hidden" id="orderby" name="orderby" value="' . $this->_pagination_args['orderby'] . '" />';

		parent::display();
	}

	function ajax_response() {

		check_ajax_referer( 'ajax-custom-list-nonce', '_ajax_custom_list_nonce' );

		$this->prepare_items();

		extract( $this->_args );
		extract( $this->_pagination_args, EXTR_SKIP );

		ob_start();
		if ( ! empty( $_REQUEST['no_placeholder'] ) )
			$this->display_rows();
		else
			$this->display_rows_or_placeholder();
		$rows = ob_get_clean();

		ob_start();
		$this->print_column_headers();
		$headers = ob_get_clean();

		ob_start();
		$this->pagination('top');
		$pagination_top = ob_get_clean();

		ob_start();
		$this->pagination('bottom');
		$pagination_bottom = ob_get_clean();

		$response = array( 'rows' => $rows );
		$response['pagination']['top'] = $pagination_top;
		$response['pagination']['bottom'] = $pagination_bottom;
		$response['column_headers'] = $headers;

		if ( isset( $total_items ) )
			$response['total_items_i18n'] = sprintf( _n( '1 item', '%s items', $total_items ), number_format_i18n( $total_items ) );

		if ( isset( $total_pages ) ) {
			$response['total_pages'] = $total_pages;
			$response['total_pages_i18n'] = number_format_i18n( $total_pages );
		}

		die( json_encode( $response ) );
	}

}

function my_add_menu_items(){
	add_menu_page('WP List Table Ajax Sample', 'WP List Table Ajax Sample', 'activate_plugins', 'wp_ajax_list_test', 'wp_list_page');
}

add_action('admin_menu', 'my_add_menu_items');

function wp_list_page() {

?>

	<div class="wrap">

		<h2>WP List Table Ajax Sample</h2>

		<form id="email-sent-list" method="get">

			<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
			<input type="hidden" name="order" value="<?php echo $_REQUEST['order']; ?>" />
			<input type="hidden" name="orderby" value="<?php echo $_REQUEST['orderby']; ?>" />

			<div id="ts-history-table" style="">
				<?php
				wp_nonce_field( 'ajax-custom-list-nonce', '_ajax_custom_list_nonce' );
				?>
			</div>

		</form>

	</div>

<?php

}

function _ajax_fetch_sts_history_callback() {

	$wp_list_table = new My_List_Table();
	$wp_list_table->ajax_response();

}

add_action( 'wp_ajax__ajax_fetch_sts_history', '_ajax_fetch_sts_history_callback' );

function _ajax_sts_display_callback() {

	check_ajax_referer( 'ajax-custom-list-nonce', '_ajax_custom_list_nonce', true );

	$wp_list_table = new My_List_Table();
	$wp_list_table->prepare_items();

	ob_start();
	$wp_list_table->display();
	$display = ob_get_clean();

	die(

	json_encode(array(

		"display" => $display

	))

	);

}

add_action('wp_ajax__ajax_sts_display', '_ajax_sts_display_callback');

function fetch_ts_script() {
	$screen = get_current_screen();

	/**
	 * For testing purpose, finding Screen ID
	 */

	?>

	<script type="text/javascript">console.log("<?php echo $screen->id; ?>")</script>

	<?php

	if ( $screen->id != "toplevel_page_wp_ajax_list_test" )
		return;

	?>

	<script type="text/javascript">

		(function ($) {

			list = {

				/** added method display
				 * for getting first sets of data
				 **/

				display: function() {

					$.ajax({

						url: ajaxurl,
						dataType: 'json',
						data: {
							_ajax_custom_list_nonce: $('#_ajax_custom_list_nonce').val(),
							action: '_ajax_sts_display'
						},
						success: function (response) {

							$("#ts-history-table").html(response.display);

							$("tbody").on("click", ".toggle-row", function(e) {
								e.preventDefault();
								$(this).closest("tr").toggleClass("is-expanded")
							});

							list.init();
						}
					});

				},

				init: function () {

					var timer;
					var delay = 500;

					$('.tablenav-pages a, .manage-column.sortable a, .manage-column.sorted a').on('click', function (e) {
						e.preventDefault();
						var query = this.search.substring(1);

						var data = {
							paged: list.__query( query, 'paged' ) || '1',
							order: list.__query( query, 'order' ) || 'asc',
							orderby: list.__query( query, 'orderby' ) || 'title'
						};
						list.update(data);
					});

					$('input[name=paged]').on('keyup', function (e) {

						if (13 == e.which)
							e.preventDefault();

						var data = {
							paged: parseInt($('input[name=paged]').val()) || '1',
							order: $('input[name=order]').val() || 'asc',
							orderby: $('input[name=orderby]').val() || 'title'
						};

						window.clearTimeout(timer);
						timer = window.setTimeout(function () {
							list.update(data);
						}, delay);
					});

					$('#email-sent-list').on('submit', function(e){

						e.preventDefault();

					});

				},

				/** AJAX call
				 *
				 * Send the call and replace table parts with updated version!
				 *
				 * @param    object    data The data to pass through AJAX
				 */
				update: function (data) {

					$.ajax({

						url: ajaxurl,
						data: $.extend(
							{
								_ajax_custom_list_nonce: $('#_ajax_custom_list_nonce').val(),
								action: '_ajax_fetch_sts_history',
							},
							data
						),
						success: function (response) {

							var response = $.parseJSON(response);

							if (response.rows.length)
								$('#the-list').html(response.rows);
							if (response.column_headers.length)
								$('thead tr, tfoot tr').html(response.column_headers);
							if (response.pagination.bottom.length)
								$('.tablenav.top .tablenav-pages').html($(response.pagination.top).html());
							if (response.pagination.top.length)
								$('.tablenav.bottom .tablenav-pages').html($(response.pagination.bottom).html());

							list.init();
						}
					});
				},

				/**
				 * Filter the URL Query to extract variables
				 *
				 * @see http://css-tricks.com/snippets/javascript/get-url-variables/
				 *
				 * @param    string    query The URL query part containing the variables
				 * @param    string    variable Name of the variable we want to get
				 *
				 * @return   string|boolean The variable value if available, false else.
				 */
				__query: function (query, variable) {

					var vars = query.split("&");
					for (var i = 0; i < vars.length; i++) {
						var pair = vars[i].split("=");
						if (pair[0] == variable)
							return pair[1];
					}
					return false;
				},
			}

			list.display();

		})(jQuery);

	</script>
	<?php
}

add_action('admin_footer', 'fetch_ts_script');
