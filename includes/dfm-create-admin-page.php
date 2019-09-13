<?php
/**
 * Register the DFM_Create_Admin_Page_Class
 *
 * @since 1.0.0
 */

if ( !class_exists( 'DFM_Create_Admin_Page_Class' ) ) {
  class DFM_Create_Admin_Page_Class {
    function __construct($adminPageName, $adminPageSlug, $adminPagePos, $adminPageTermId, $adminPageTermName, $adminPageMax) {
      $this->adminPageName = $adminPageName;
      $this->adminPageSlug = $adminPageSlug;
      $this->adminPagePos = $adminPagePos;
      $this->adminPageTermId = $adminPageTermId;
      $this->adminPageTermName = $adminPageTermName;
      $this->adminPageMax = $adminPageMax;
      add_action('admin_menu', array( $this, 'dfm_add_admin_menu' ));
    }
    function dfm_add_admin_menu() {
      add_menu_page($this->adminPageName, $this->adminPageName, 'manage_options', $this->adminPageSlug, array( $this, 'wpl_owt_list_table_fn'), '', $this->adminPagePos);
    }
    /**
    *  Load WP_LIST_TABLE page template
    */
    function wpl_owt_list_table_fn() {
      include_once 'dfm-list-table-view.php';
      $postTermId = $this->adminPageTermId;
      $postTermName = $this->adminPageTermName;
      $postMax = $this->adminPageMax;
      $dfm_table = new DFM_List_Table_Class();
      $dfm_table->prepare_args($postTermId, $postTermName, $postMax);
      $dfm_table->prepare_items();
      echo '<div class="wrap">';
      echo '<h1 class="wp-heading-inline">' . $this->adminPageName . '</h1>';
      $dfm_table->display();
      echo '</div>';
    }
  }
  /**
  *  Create 5 new admin pages using the DFM_Create_Admin_Page_Class
  */
  function dfm_create_admin_page() {
    if (class_exists('DFM_Create_Admin_Page_Class')) {
      /**
      *  Check if Category Exists and return the term_id
      *  ALERT: Would like to restructure this method to be more reusuable.
      */
      $sportsCatId = term_exists( 'sports-category', 'category' );
      $animalsCatId = term_exists( 'animals-category', 'category' );
      $businessCatId = term_exists( 'business-category', 'category' );
      $entertainmentCatId = term_exists( 'entertainment-category', 'category' );
      $worldAndNewsCatId = term_exists( 'world-and-news-category', 'category' );
      /**
      *  Create new DFM_Create_Admin_Page_Class() and pass Page Title, Page Slug, Menu Position, term_id, Category Name, and Max Posts to Display.
      */
      $dfm_table1 = new DFM_Create_Admin_Page_Class('Sports Content', 'sports-content', 54, $sportsCatId['term_id'], 'Sports', 25);
      $dfm_table2 = new DFM_Create_Admin_Page_Class('Animals Content', 'animals-content', 55, $animalsCatId['term_id'], 'Animals', 10);
      $dfm_table3 = new DFM_Create_Admin_Page_Class('Business Content', 'business-content', 56, $businessCatId['term_id'], 'Business', 12);
      $dfm_table4 = new DFM_Create_Admin_Page_Class('Entertainment Content', 'entertainment-content', 57, $entertainmentCatId['term_id'], 'Entertainment', 50);
      $dfm_table5 = new DFM_Create_Admin_Page_Class('World and News Content', 'world-and-news-content', 58, $worldAndNewsCatId['term_id'], 'World and News', 100);
    }
  }
  dfm_create_admin_page();
}
/**
*  Create Class for adding required categories
*  ALERT: With a little more time here I would like to optimize this further and make a reusuable object for the wp_insert_category.
*/
if ( !class_exists( 'DFM_Add_Categories' ) ) {
  class DFM_Add_Categories {
    public function __construct() {
      add_action('admin_init', array( $this, 'wp_create_category' ));
    }
    public function wp_create_category( ) {
      //Define the category
      $wpdocs_cat1 = array('cat_name' => 'Sports', 'category_description' => 'Sports Content', 'category_nicename' => 'sports-category', 'category_parent' => '');
      $wpdocs_cat2 = array('cat_name' => 'Animals', 'category_description' => 'Animals Content', 'category_nicename' => 'animals-category', 'category_parent' => '');
      $wpdocs_cat3 = array('cat_name' => 'Business', 'category_description' => 'Business Content', 'category_nicename' => 'business-category', 'category_parent' => '');
      $wpdocs_cat4 = array('cat_name' => 'Entertainment', 'category_description' => 'Entertainment Content', 'category_nicename' => 'entertainment-category', 'category_parent' => '');
      $wpdocs_cat5 = array('cat_name' => 'World and News', 'category_description' => 'World and News Content', 'category_nicename' => 'world-and-news-category', 'category_parent' => '');
      // Create the category
      $wpdocs_cat_id1 = wp_insert_category($wpdocs_cat1);
      $wpdocs_cat_id2 = wp_insert_category($wpdocs_cat2);
      $wpdocs_cat_id3 = wp_insert_category($wpdocs_cat3);
      $wpdocs_cat_id4 = wp_insert_category($wpdocs_cat4);
      $wpdocs_cat_id5 = wp_insert_category($wpdocs_cat5);
    }
  }
  if (class_exists('DFM_Add_Categories')) {
    $book = new DFM_Add_Categories();
  }
}
