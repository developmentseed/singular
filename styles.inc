<?php

/**
 * Retrieve an options array of styles.
 */
function singular_get_styles() {
  $options = array();
  $options['book'] = t('Book');
  $options['city'] = t('City');
  $options['clouds'] = t('Clouds');
  $options['rocks'] = t('Rocks');
  $options['sea'] = t('Sea');
  $options['custom'] = t('Custom style');
  return $options;
}

/**
 * Retrieve specific info about the current style.
 */
function singular_get_style_info() {
  $settings = theme_get_settings('singular');
  $style = !empty($settings['style']) ? $settings['style'] : 'sea';
  switch ($style) {
    case 'custom':
      $info = array();
      $info['background_color'] = !empty($settings['background_color']) ? $settings['background_color'] : '';
      $info['background_url'] = !empty($settings['background_path']) ? file_create_url($settings['background_path']) : '';
      $info['background_repeat'] = !empty($settings['background_repeat']) ? $settings['background_repeat'] : 'no-repeat';
      return $info;
    default:
      $path = drupal_get_path('theme', 'singular') .'/images';
      $styles = array(
        'book' => array(
          'background_color' => '#e8cfab',
          'background_repeat' => 'repeat-x',
          'background_url' => url("$path/book.jpg", array('absolute' => TRUE)),
        ),
        'clouds' => array(
          'background_color' => '#f99f7c',
          'background_repeat' => 'repeat-x',
          'background_url' => url("$path/clouds.jpg", array('absolute' => TRUE)),
        ),
        'city' => array(
          'background_color' => '#918d8a',
          'background_repeat' => 'repeat-x',
          'background_url' => url("$path/city.jpg", array('absolute' => TRUE)),
        ),
        'rocks' => array(
          'background_color' => '#36302b',
          'background_repeat' => 'repeat-x',
          'background_url' => url("$path/rocks.jpg", array('absolute' => TRUE)),
        ),
        'sea' => array(
          'background_color' => '#011220',
          'background_repeat' => 'repeat-x',
          'background_url' => url("$path/sea.jpg", array('absolute' => TRUE)),
        ),
      );
      return !empty($styles[$style]) ? $styles[$style] : $styles['sea'];
      break;
  }
}
