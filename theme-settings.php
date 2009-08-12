<?php
// $Id$

include_once('styles.inc');

/**
 * Implementation of hook_settings() for themes.
 */
function singular_settings($settings) {
  // Add js & css
  drupal_add_css('misc/farbtastic/farbtastic.css', 'module', 'all', FALSE);
  drupal_add_js('misc/farbtastic/farbtastic.js');
  drupal_add_js(drupal_get_path('theme', 'singular') .'/js/settings.js');
  drupal_add_css(drupal_get_path('theme', 'singular') .'/css/settings.css');

  file_check_directory(file_directory_path(), FILE_CREATE_DIRECTORY, 'file_directory_path');

  // Check for a new uploaded logo, and use that instead.
  if ($file = file_save_upload('background_file', array('file_validate_is_image' => array()))) {
    $parts = pathinfo($file->filename);
    $filename = 'singular_background.'. $parts['extension'];
    if (file_copy($file, $filename, FILE_EXISTS_REPLACE)) {
      $settings['background_path'] = $file->filepath;
    }
  }

  $form = array();

  $form['layout'] = array(
    '#title' => t('Layout'),
    '#type' => 'select',
    '#options' => array('fixed' => t('Fixed width'), 'fluid' => t('Fluid width')),
    '#default_value' => !empty($settings['layout']) ? $settings['layout'] : 'fixed',
  );

  $form['messages'] = array(
    '#type' => 'fieldset',
    '#tree' => FALSE,
    '#title' => t('Autoclose messages'),
    '#descriptions' => t('Select the message types to close automatically after a few seconds.'),
  );
  $form['messages']['autoclose'] = array(
    '#type' => 'checkboxes',
    '#options' => array('status' => t('Status'), 'warning' => t('Warning'), 'error' => t('Error')),
    '#default_value' => !empty($settings['autoclose']) ? $settings['autoclose'] : array('status'),
  );

  $form['style'] = array(
    '#title' => t('Styles'),
    '#type' => 'select',
    '#options' => singular_get_styles(),
    '#default_value' => !empty($settings['style']) ? $settings['style'] : 'sea',
  );
  $form['custom'] = array(
    '#tree' => FALSE,
    '#type' => 'fieldset',
    '#attributes' => array('class' => ($form['style']['#default_value'] == 'custom') ? 'singular-custom-settings' : 'singular-custom-settings hidden'),
  );
  $form['custom']['background_file'] = array(
    '#type' => 'file',
    '#title' => t('Background image'),
    '#maxlength' => 40,
  );
  if (!empty($settings['background_path'])) {
    $form['custom']['background_preview'] = array(
      '#type' => 'markup',
      '#value' => !empty($settings['background_path']) ? theme('image', $settings['background_path'], NULL, NULL, array('width' => '100'), FALSE) : '',
    );
  }
  $form['custom']['background_path'] = array(
    '#type' => 'value',
    '#value' => !empty($settings['background_path']) ? $settings['background_path'] : '',
  );
  $form['custom']['background_color'] = array(
    '#title' => t('Background color'),
    '#type' => 'textfield',
    '#size' => '7',
    '#maxlength' => '7',
    '#default_value' => !empty($settings['background_color']) ? $settings['background_color'] : '#888888',
    '#suffix' => '<div id="singular-colorpicker"></div>',
  );
  $form['custom']['background_repeat'] = array(
    '#title' => t('Tile'),
    '#type' => 'select',
    '#options' => array(
      'no-repeat' => t('Don\'t tile'),
      'repeat-x' => t('Horizontal'),
      'repeat-y' => t('Vertical'),
      'repeat' => t('Both'),
    ),
    '#default_value' => !empty($settings['background_repeat']) ? $settings['background_repeat'] : 'no-repeat',
  );
  return $form;
}
