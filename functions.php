<?php

define('STB_PREFIX', '_STB_');

$parent_dir = get_template_directory();
$child_dir  = get_stylesheet_directory();

$requires = array_merge(
  glob("$parent_dir/lib/*.php"),
  glob("$child_dir/lib/*.php"),
  [
    "$parent_dir/lib/vendor/cmb2/init.php"
  ]
);
foreach($requires as $file) require_once($file);

$parser = new STB_Parser($child_dir);

add_action('init', function() use($child_dir, $parser) {
  $parser->init();

  // Clean up rest API responses
  // MUST run after loop in STB_Field_Group class
  foreach(get_post_types(['public' => true]) as $type){
    add_filter("rest_prepare_$type", function($res){
      unset($res->data['cmb2']);
      return $res;
    }, 16, 1);
  }
});

add_action('cmb2_init', function() use($child_dir, $parser){
  $parser->field_init();
});

add_action('admin_notices', function() use($parent_dir, $child_dir){
  if($parent_dir === $child_dir){
    $message = __('Please use a child theme when working with Serve The Base.');
    $notice  = new STB_Notices($message);
    $notice->display('error');
  }
});
