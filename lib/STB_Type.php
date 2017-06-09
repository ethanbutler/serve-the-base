<?php
class STB_Type {
  /**
   * Creates a new custom post type.
   * @param String         $singular Name of post type
   * @param String|Boolean $plural   Optional custom plural value for non-standard pluralizations (e.g. persons -> people, radius -> radii)
   * @param Array          $args     Override default values for custom post type registration arguments
   */
  function __construct($singular, $plural = false, $args = []){
    $names = stb_format_name($singular, $plural);

    $labels = [
      'name'                  => _x("{$names['plural']}", 'text_domain'),
      'singular_name'         => _x("{$names['singular']}", 'text_domain'),
      'name_admin_bar'        => __("{$names['singular']}", 'text_domain'),
      'archives'              => __("{$names['singular']} Archives'", 'text_domain'),
      'all_items'             => __("All {$names['plural']}", 'text_domain'),
      'add_new'               => __("Add {$names['singular']}", 'text_domain'),
      'new_item'              => __("New {$names['singular']}", 'text_domain'),
      'edit_item'             => __("Edit {$names['singular']}", 'text_domain'),
      'update_item'           => __("Update {$names['singular']}", 'text_domain'),
      'view_item'             => __("View {$names['singular']}", 'text_domain'),
      'search_items'          => __("Search {$names['singular']}", 'text_domain'),
      'insert_into_item'      => __("Insert into {$names['lc']}", 'text_domain'),
      'uploaded_to_this_item' => __("Uploaded to this {$names['lc']}", 'text_domain'),
      'items_list'            => __("{$names['plural']} list", 'text_domain'),
      'items_list_navigation' => __("{$names['plural']} list navigation", 'text_domain'),
      'not_found'             => __("No {$names['lc_plural']} found", 'text_domain'),
      'filter_items_list'     => __("Filter '.{$names['lc_plural']}.' list", 'text_domain')
    ];

    $default_args = [
      'label'               => $names['singular'],
      'labels'              => $labels,
      'supports'            => [],
      'hierarchical'        => false,
      'public'              => true,
      'has_archive'         => stb_hyphenate($names['lc_plural']),
      'show_in_rest'        => true,
      'rest_base'           => stb_hyphenate($names['lc_plural']),
      'rewrite'             => [ 'slug' => stb_hyphenate($names['lc_plural']) ]
    ];

    $merged_args = is_array($args) ? array_merge($default_args, $args) : $default_args;

    $slug = stb_to_slug($singular);

    register_post_type($slug, $merged_args);

  }
}
