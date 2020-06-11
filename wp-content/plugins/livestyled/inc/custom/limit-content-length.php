<?php 
/* ========================================================================================================================
    Limit the length of the excerpt/content
======================================================================================================================== */

function excerpt($limit) {
    return wp_trim_words(get_the_excerpt(), $limit);
}

function content($limit) {
  $content = explode(' ', get_the_content(), $limit);
  if (count($content)>=$limit) {
    array_pop($content);
    $content = implode(" ",$content).'...';
  } else {
    $content = implode(" ",$content);
  }  
  return $content;
}