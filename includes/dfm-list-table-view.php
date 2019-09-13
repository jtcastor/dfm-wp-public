<?php
/**
 * Register the DFM_List_Table_Class wich extends WP_List_Table.
 * This is a custom view for displaying the posts in a default WP_LIST_TABLE.
 * Side Note: I would like to expand on this view to have more functionlity like, clickable titles, sort abillity, search box, bulk actions, and filters.
 *
 * @since 1.0.0
 */
if( ! class_exists( 'WP_List_Table' ) ) {
  /**
  *  Ideally this would be a duplicate of the core file or I would add extra test parameters to make sure future updates to case problems.
  */
  require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
if ( !class_exists( 'DFM_List_Table_Class' ) ) {
  class DFM_List_Table_Class extends WP_List_Table {
    /**
    *  Create a prepare_args method for passing the currentTermId, CatPostLimit, and postCatName
    */
    private $currentTermID;
    private $catPostLimit;
    private $postCatName;
    public function prepare_args(int $currentTermID, string $postCatName, int $catPostLimit) {
      $this->currentTermID = $currentTermID;
      $this->catPostLimit = $catPostLimit;
      $this->postCatName = $postCatName;
    }
    // extends prepare_items
    public function prepare_items() {
      $datas = $this->dfm_wp_list_table_data();
      $per_page = 25;
      $current_page = $this->get_pagenum();
      $total_items = count($datas);
      $this->set_pagination_args(array(
        'total_items' => $total_items,
        'per_page' => $per_page
      ));
      $this->items = array_slice($datas, (($current_page -1) * $per_page), $per_page);
      $columns = $this->get_columns();
      $this->_column_headers = array($columns);
    }
    // extends wp_list_table_data
    public function dfm_wp_list_table_data() {
      $postTermID = $this->currentTermID;
      $postCatLimit = $this->catPostLimit;
      /**
      *  ALERT: I would like to circle back to this and pull the get_posts into a seperate/reusuable object.
      */
      $all_posts = get_posts(array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'category' => $postTermID,
        'numberposts' => $postCatLimit,
      ));
      $post_array = array();
      if ( count($all_posts) > 0 ) {
        foreach ($all_posts as $index => $post) {
          $post_array[] = array(
            'title' => $post->post_title,
            'date' => $post->post_modified
          );
        }
      }
      return $post_array;
    }
    // extends no_items
    public function no_items() {
      $postCatName = $this->postCatName;
      _e('There are no ' . $postCatName . ' posts. Check back later');
    }
    // extends get_columns
    public function get_columns() {
      $columns = array(
        'title' => 'Title',
        'date' => 'Date'
      );
      return $columns;
    }
    // extends column_default
    public function column_default($item, $column_name) {
      switch($column_name) {
        case 'title':
        case 'date':
          return $item[$column_name];
        default:
          return 'no value';
      }
    }
  }
}
