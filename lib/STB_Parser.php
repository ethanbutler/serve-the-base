<?php

/**
 * Parses schema.json file
 * @param string   $child_dir    Directory path in which schema.json can be found.
 */
class STB_Parser {

  function __construct($child_dir){
    $this->is_initialized = false;
    $this->is_field_initialized = false;

    $schema = file_get_contents("$child_dir/schema.json");

    if(!$schema){
      $message = __('Please provide a schema file.');
      $notice  = new STB_Notices($message);
      $notice->display('error');
      return;
    }

    $this->data = json_decode($schema, true);

    if(!$this->data){
      $message = __('You have a syntax error in your schema file.');
      $notice  = new STB_Notices($message);
      $notice->display('error');
      return;
    }
  }

  public function init(){
    if($this->is_initialized){
      $message = __('Do not call the init method on STB_Parse multiple times.');
      $notice  = new STB_Notices($message);
      $notice->display('error');
      return;
    }

    $this->is_initialized = true;
    $this->register_types();
    $this->register_taxonomies();
  }

  public function field_init(){
    if($this->is_field_initialized){
      $message = __('Do not call the field_init method on STB_Parse multiple times.');
      $notice  = new STB_Notices($message);
      $notice->display('error');
      return;
    }

    $this->is_field_initialized = true;
    $this->register_field_groups();
  }

  private function register_types(){
    $error = null;
    $types = $this->data['types'];
    if(!$types) return;

    foreach($types as $type){
      if(!isset($type['name'])){
        $error = __('Types must have a specified name');
      }

      if($error){
        $notice = new STB_Notices($error);
        $notice->display('error');
        return;
      }

      $singular = $type['name'];
      $plural   = isset($type['plural']) ? $type['plural'] : false;
      $args     = isset($type['args']) ? $type['args'] : [];
      $args['supports'] = isset($type['supports']) ? $type['supports'] : [];

      $registered_type = new STB_Type($singular, $plural, $args);
    }
  }

  private function register_taxonomies(){
    $taxonomies = $this->data['taxonomies'];
    if(!$taxonomies) return;

    foreach($taxonomies as $taxonomy){
      $error = null;

      if(!isset($taxonomy['name'])){
        $error = __('Taxonomies must have a specified name');
      }

      if($error){
        $notice = new STB_Notices($error);
        $notice->display('error');
        return;
      }

      $singular  = $taxonomy['name'];
      $plural    = isset($taxonomy['plural']) ? $taxonomy['plural'] : false;
      $describes = isset($taxonomy['describes']) ? $taxonomy['describes'] : false;
      $args      = isset($taxonomy['args']) ? $taxonomy['args'] : null;

      $registered_taxonomy = new STB_Taxonomy($singular, $plural, $describes, $args);
    }
  }

  private function register_field_groups(){
    $error = null;
    $field_groups = $this->data['field_groups'];

    if(!$field_groups) return;

    foreach($field_groups as $field_group){
      if(!isset($field_group['name'])){
        $error = __('Field groups must have a specified name.');
      } elseif(!isset($field_group['fields'])) {
        $error = __("Field group {$field_group['name']} does not have any fields.");
      }

      if($error){
        $notice = new STB_Notices($error);
        $notice->display('error');
        return;
      }

      // Figure out what content types the field group belongs to
      if(isset($field_group['belongs_to'])){
        if($field_group['belongs_to'] === 'all'){
          $belongs_to = get_post_types(['public' => true]);
        } elseif(is_array($field_group['belongs_to'])) {
          $belongs_to = $field_group['belongs_to'];
          foreach($belongs_to as &$type){
            $type = stb_to_slug($type);
          }
        } else {
          $belongs_to = [stb_to_slug($field_group['belongs_to'])];
        }
      } else {
        $belongs_to = ['post'];
      }

      $name       = $field_group['name'];
      $fields     = $field_group['fields'];

      $field_group = new STB_Field_Group($name, $belongs_to, $fields);
    }
  }

}
