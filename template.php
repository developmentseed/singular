<?php

/**
 * Implementation of preprocess_page().
 */
function singular_preprocess_page(&$vars) {
  include_once(drupal_get_path('theme', 'singular') .'/styles.inc');
  $vars['styles'] .= singular_style_css(singular_get_style_info());
  $vars['styles_ie6'] = singular_style_ie6();

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
  if ($node === $vars['node']) {
    $vars['attr']['class'] .= ' node-page';

    if (!isset($_GET['print'])) {
      unset($vars['title']);
    }
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
 * PNG transparency compatibility for IE6.
 */
function singular_style_ie6() {
  $mask_path = base_path() . path_to_theme() . '/images/mask.png';
  $property = "background:transparent; filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=scale, src='{$mask_path}');";
  $linkfix = "position:relative;";
  return "#sidebar ul.menu a { height:1%; } #main,#utility,#sidebar,#branding div.primary ul.links a,#sidebar ul.menu a.active { $property } #main a,#utility a,#sidebar a,#branding div.primary ul.links a,#sidebar ul.menu a.active { $linkfix }";
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
