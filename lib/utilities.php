<?php

/**
 * Replaces non-alphabetic characters with hyphens for use in slugs.
 * @param  string      $string     String to be sanitized.
 * @return string         Sanitized string.
 */
function stb_hyphenate($string){
  return preg_replace("/[^a-zA-Z]/", '-', $string);
}

/**
 * Returns permutations of a name that may be useful in labels.
 * @param  string      $singular Name
 * @param  string|bool $plural   Optional plural name, for terms that have non-standard pluralized forms.
 * @return array                 Array including singular, plural, and lowercase form of name.
 */
function stb_format_name($singular, $plural = null){
  if(!$plural) $plural = $singular.'s';
  return [
    'singular'  => $singular,
    'plural'    => $plural,
    'lc'        => strtolower($singular),
    'lc_plural' => strtolower($plural)
  ];
}

/**
 * Returns a slug from a string.
 * @param  string $string String to be sanitized.
 * @return string         Sanitized string.
 */
function stb_to_slug($string){
  return stb_hyphenate(stb_format_name($string)['lc']);
}

/**
 * Removes prefix content from strings for sanitizing REST response property names..
 * @param  string      $string    String to be sanitized.
 * @return string                 Sanitized string.
 */
function stb_rewrite($string){
  return str_replace(STB_PREFIX, '', $string);
}
