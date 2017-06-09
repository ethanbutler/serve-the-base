<?php

/**
 * Wraps a CMB2 field. See CMB2 documentation: https://github.com/CMB2/CMB2/wiki/Field-Types.
 * This class isn't useful outside of being used in STB_Field_Group.
 * @param string    $name    Field name.
 * @param boolean   $type    Field type.
 * @param array     $options Field options array.
 */
class STB_Field {

  const DEFAULT_ARGS = [
    'type'    => 'text_small',
    'options' => []
  ];

  function __construct($name, $type = false, $options = []){
    $names  = stb_format_name($name);
    $id     = stb_hyphenate($names['lc']);
    $prefix = STB_PREFIX;

    $new_args = [
      'id'      => "{$prefix}{$id}",
      'name'    => $name
    ];

    if($options) $new_args['options'] = $options;
    if($type)    $new_args['type'] = $type;

    $this->args = array_merge(self::DEFAULT_ARGS, $new_args);
  }

  function args(){
    return $this->args;
  }

}

/**
 * Registers a field group.
 * @param string       $name       Alias for field group.
 * @param string|array $belongs_to String or array of strings for post types for which the field group belongs.
 * @param array        $fields     Individual field objects.
 */

class STB_Field_Group {

  function __construct($name, $belongs_to, $fields){
    $names  = stb_format_name($name);
    $id     = stb_to_slug($name);
    $prefix = STB_PREFIX;
    $field_group = new_cmb2_box([
      'id'           => "{$prefix}{$id}",
      'title'        => $name,
      'object_types' => $belongs_to,
      'context'      => 'normal',
      'show_in_rest' => WP_REST_Server::ALLMETHODS
    ]);

    // Add individual fields
    foreach($fields as $field){
      $name    = $field['name'];
      $type    = isset($field['type']) ? $field['type'] : false;
      $options = isset($field['options']) ? $field['options'] : [];

      $field = new STB_Field($name, $type, $options);
      $field_group->add_field($field->args());
    }

    // Clean up REST API responses
    // MUST run after loop in functions.php
    foreach($belongs_to as $type){
      add_filter("rest_prepare_$type", function($res) use($id) {
        $fields = $res->data['cmb2'][STB_PREFIX.$id];
        foreach($fields as $field => $value){
          $name = stb_rewrite($field);
          $res->data[$id][$name] = $value;
        }
        return $res;
      }, 15, 1);
    }

  }

}
