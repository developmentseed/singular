Drupal.behaviors.singular_settings = function (context) {
  $('select#edit-style').each(function() {
    $(this).change(function() {
      if ($(this).val() == 'custom') {
        $('.singular-custom-settings').show('medium');
      }
      else {
        $('.singular-custom-settings').hide('medium');
      }
    });
  });

  // Add Farbtastic
  var target = $('#edit-background-color', context);
  if (target) {
    var farb = $.farbtastic('div#singular-colorpicker', target);
    target
      .focus(function() {
        $('div#singular-colorpicker').show('medium');
      })
      .blur(function() {
        $('div#singular-colorpicker').hide('medium');
      });
  }
};
