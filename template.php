<?php

/**
 * Implementation of preprocess_page().
 */
function singular_preprocess_page(&$vars) {
  include_once(drupal_get_path('theme', 'singular') .'/styles.inc');
  $vars['styles'] .= singular_style_css(singular_get_style_info());

  $settings = theme_get_settings('singular');
  if (!empty($settings['layout'])) {
    $vars['attr']['class'] .= ' ' . $settings['layout'];
  }
}

/**
 * Implementation of preprocess_node().
 */
function singular_preprocess_node(&$vars) {
  $node = menu_get_object();
  if ($node === $vars['node'] && !isset($_GET['print'])) {
    unset($vars['title']);
  }
}

/**
 * Generate inline CSS for a given style.
 */
function singular_style_css($info) {
  $output = "<style type='text/css'>body.tao { ";
  $output .= "background-color: {$info['background_color']}; ";
  if (!empty($info['background_url'])) {
    $output .= "background-image: url('{$info['background_url']}'); ";
    $output .= "background-position: 0% 0%; ";
    if (!empty($info['background_repeat'])) {
      $output .= "background-repeat: {$info['background_repeat']}; ";
    }
    else {
      $output .= "background-repeat: no-repeat; ";
    }
    $output .= "background-fixed: fixed; ";
  }
  $output .= "}</style>";
  return $output;
}

/**
 * Override of theme_status_message().
 */
function singular_status_messages($display = NULL) {
  $output = '';
  $first = TRUE;

  // Theme specific settings
  $settings = theme_get_settings('singular');
  $autoclose = isset($settings['autoclose']) ? $settings['autoclose'] : array('status' => 1, 'warning' => 0, 'error' => 0);

  foreach (drupal_get_messages($display) as $type => $messages) {
    $class = $first ? 'first' : '';
    $class .= !empty($autoclose[$type]) || !isset($autoclose[$type]) ? ' autoclose' : '';
    $first = FALSE;

    $output .= "<div class='messages clear-block $type $class'>";
    $output .= "<span class='close'>". t('Hide') ."</span>";
    $output .= "<div class='message-content'>";
    if (count($messages) > 1) {
      $output .= "<ul>";
      foreach ($messages as $k => $message) {
        if ($k == 0) {
          $output .= "<li class='message-item first'>$message</li>";
        }
        else if ($k == count($messages) - 1) {
          $output .= "<li class='message-item last'>$message</li>";
        }
        else {
          $output .= "<li class='message-item'>$message</li>";
        }
      }
      $output .= "</ul>";
    }
    else {
      $output .= $messages[0];
    }
    $output .= "</div>";
    $output .= "</div>";
  }
  return $output;
}
