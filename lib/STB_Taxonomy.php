<?php

/**
 * Creates a new taxonomy.
 * @param string         $singular   Name of taxonomy
 * @param string|bool    $plural     Optional custom plural value for non-standard pluralizations (e.g. persons -> people, radius -> radii)
 * @param string|array   $describes  Override default values for custom taxonomy registration arguments
 */
class STB_Taxonomy {

  function __construct($singular, $plural = false, $describes = false, $args = []){
    $names = stb_format_name($singular, $plural);

    $labels = [
      'name'                       => _x( "{$names['plural']}", 'Taxonomy General Name', 'text_domain' ),
      'singular_name'              => _x( "{$names['singular']}", 'Taxonomy Singular Name', 'text_domain' ),
      'menu_name'                  => __( "{$names['singular']}", 'text_domain' ),
      'all_items'                  => __( "All Items", 'text_domain' ),
      'parent_item'                => __( "Parent {$names['singular']}", 'text_domain' ),
      'parent_item_colon'          => __( "Parent {$names['singular']}:", 'text_domain' ),
      'new_item_name'              => __( "New {$names['singular']} Name", 'text_domain' ),
      'add_new_item'               => __( "Add New {$names['singular']}", 'text_domain' ),
      'edit_item'                  => __( "Edit {$names['singular']}", 'text_domain' ),
      'update_item'                => __( "Update {$names['singular']}", 'text_domain' ),
      'view_item'                  => __( "View {$names['singular']}", 'text_domain' ),
      'separate_items_with_commas' => __( "Separate {$names['lc_plural']} with commas", 'text_domain' ),
      'add_or_remove_items'        => __( "Add or remove {$names['lc_plural']}", 'text_domain' ),
      'popular_items'              => __( "Popular {$names['plural']}", 'text_domain' ),
      'search_items'               => __( "Search {$names['plural']}", 'text_domain' ),
      'no_terms'                   => __( "No {$names['lc_plural']}", 'text_domain' ),
      'not_found'                  => __( "No {$names['lc_plural']} found", 'text_domain'),
      'items_list'                 => __( "{$names['plural']} list", 'text_domain' ),
      'items_list_navigation'      => __( "{$names['plural']} list navigation", 'text_domain' ),
    ];

    $default_args = [
      'label'               => $names['singular'],
      'labels'              => $labels,
      'hierarchical'        => false,
      'show_in_rest'        => true
    ];

    $merged_args = is_array($args) ? array_merge($default_args, $args) : $default_args;

    if(!$describes){
      $describes = ['post'];
    } elseif(is_array($describes)) {
      foreach($describes as &$describe){
        $describe = stb_to_slug($describe);
      }
    } else {
      $describes = stb_to_slug($describes);
    }

    register_taxonomy(stb_to_slug($singular), $describes, $merged_args);

  }
}
