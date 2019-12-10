<?php

namespace O3CliServices\Service;

use O3CliServices\Url_List_Plan_Interface;

/**
 * A generic service that returns paths of posts and menu items based on HTTP
 * request query parameters
 */
class Url_List_Manager {


  /**
   * Build generic test array, to be converted to JSON
   *
   * @param Url_List_Plan_Interface $test_plan
   * @return array
   */
  public function build_paths_array(Url_List_Plan_Interface $test_plan) {
    $node_paths = $this->build_post_paths($test_plan);
    $menu_paths = $this->build_menu_paths($test_plan);
    $merged_paths = array_merge($node_paths, $menu_paths);
    sort($merged_paths);
    return $merged_paths;
  }

  /**
   * Build the node paths from the provided test plan
   *
   * @param Url_List_Plan_Interface $test_plan
   * @return array $paths
   */
  protected function build_post_paths(Url_List_Plan_Interface $test_plan) {
    $properties = $test_plan->get_properties();
    $post_data = $this->get_post_type_data('path', $properties['post_types'], $properties['limit']);
    return $post_data;
  }

  /**
   * Get post types, with count of each
   *
   * @return array
   */
  public function count_posts_by_type() {
    $post_types = get_post_types();
    return $this->get_post_type_data('count', $post_types);
  }

  /**
   * Get post type IDs, depending on count or path
   *
   * @param string $data_type
   * @param array $post_types
   * @param boolean $limit
   * @return void
   */
  protected function get_post_type_data($data_type = 'path', $post_types, $limit = FALSE) {
    $post_ids = array();
    foreach ($post_types as $type) {
      $post_ids[$type] = get_posts(array(
        'fields'          => 'ids', // Only get post IDs
        'posts_per_page'  => $limit,
        'post_type'       => $type,
      ));
    }
    $extracted_data = array();
    if ($data_type === 'path') {
      // Extract paths from all post types, flattening array.
      foreach ($post_ids as $type_data) {
        $extracted_data = array_merge($extracted_data, array_map(function($id) {
          return str_replace(home_url(), '', get_permalink($id));
        },$type_data));
      }
    }
    elseif ($data_type === 'count') {
      // Count ids per post type.
      foreach ($post_ids as $type => $type_data) {
        $extracted_data[$type] = count($type_data);
      }
    }
    return $extracted_data;
  }

  /**
   * Build the node paths from the provided test plan
   *
   * @param Url_List_Plan_Interface $test_plan
   * @return array $paths
   */
  protected function build_menu_paths(Url_List_Plan_Interface $test_plan) {
    // Get the nodes, limit by limit.
    $properties = $test_plan->get_properties();
    return isset($properties['menus']) ? $this->get_menu_data($properties['menus']) : array();
  }

  /**
   * Count nodes in menus
   *
   * @return array
   */
  public function countMenuNodes() {
    $menus = array();
    return $this->get_menu_data($menus, 'count');
  }

  /**
   * Get node either count or path data from menus
   *
   * @param string $menus
   * @param string $data_type
   *   - Either 'path' or 'count'
   * @return array
   */
  protected function get_menu_data($menus, $data_type = 'path') {
    $data = array();
    if (!empty($menus)) {
      // Load each menu & extract all child links, recursively.
      foreach ($menus as $menu) {
        // if ($menu_tree = $this->menuLinkTree->load($menu, new MenuTreeParameters())) {
        //   $this->extract_paths_from_menu($data, $menu, $menu_tree, $data_type);
        //   $data = array_filter(array_unique($data));
        // }
      }
    }
    return $data;
  }

  /**
   * A recursive function that extracts link paths in nested menus
   *s
   * - Only selects links with internal URLs
   *
   * @param array $paths
   * @param string $menu
   * @param array $menu_tree
   * @param string $data_type
   *   - Either 'path' or 'count'
   */
  protected function extract_paths_from_menu(array &$data, string $menu, array $menu_tree, string $data_type) {
    foreach (array_values($menu_tree) as $item) {
      // if ($item->link->isEnabled() && !$item->link->getUrlObject()->isExternal()) {
      //   if ($data_type === 'path') {
      //     //$dataarray() = $item->link->getUrlObject()->toString(TRUE)->getGeneratedUrl();
      //   }
      //   elseif ($data_type === 'count') {
      //     $data[$menu] = isset($data[$menu]) ? $data[$menu] + 1 : 1;
      //   }
      //   if ($item->hasChildren) {
      //     $this->extractPathsFromMenu($data, $menu, $item->subtree, $data_type);
      //   }
      // }
    }
  }

}
