// HTML5 placeholder plugin version 0.3
// Enables cross-browser* html5 placeholder for inputs, by first testing
// for a native implementation before building one.
//
// USAGE: 
//$('input[placeholder]').placeholder();

(function($){
  
  $.fn.placeholder = function(options) {
    return this.each(function() {
      if ( !("placeholder"  in document.createElement(this.tagName.toLowerCase()))) {
        var $this = $(this);
        var placeholder = $this.attr('placeholder');
        $this.val(placeholder);
        $this
          .focus(function(){ if ($.trim($this.val())==placeholder){ $this.val(''); }; })
          .blur(function(){ if (!$.trim($this.val())){ $this.val(placeholder); }; });
      }
    });
  };
})(jQuery);

// perform JavaScript after the document is scriptable.
$(document).ready(function() {
    try {
      $('.paginate').dataTable();
    } catch(e) {
    }

    $('.tabbed-pane .nav a').click(function (e) {
      e.preventDefault()
      $(this).tab('show')
    });

    $('.pricing-table article').hover(function() {
        $('.pricing-table article').removeClass('selected');
    });

    $('input[placeholder]').placeholder();
    
    $('.nav-toggle').click(function(){
      $(this).toggleClass('active');
    });
    
    $('input[placeholder]').placeholder();

    $(".panel.collapsible .panel-heading").prepend('<span class="panel-collapse"><i class="fa fa-chevron-up"></i></span>')
        .find('.panel-collapse')
        .click(function(){
            if ($(this).hasClass('panel-collapse')) {
                $(this).parents('.panel').find('.panel-body').slideUp('fast', function(){$(this).parents('.panel').addClass('collapsed');});
                $(this).removeClass('panel-collapse').addClass('panel-expand').find('.fa').removeClass('fa-chevron-up').addClass('fa-chevron-down');
            } else {
                $(this).parents('.panel').removeClass('collapsed').find('.panel-body').slideDown();
                $(this).removeClass('panel-expand').addClass('panel-collapse').find('.fa').removeClass('fa-chevron-down').addClass('fa-chevron-up');
            }
        });

    try {
      $(".sortable").sortable({
          connectWith: '.sortable',
          handle: '.panel-heading',
          cursor: 'move',
          revert: 500,
          opacity: 0.7,
          appendTo: 'body',
          placeholder: 'panel-placeholder col-md-6',
          forcePlaceholderSize: true,
          start: function(event, ui) {
            setTimeout(function(){
              console.log($('.ui-sortable-helper').height());
            $('.panel-placeholder').append('<div style="height: ' + $('.ui-sortable-helper .panel').height() + 'px !important' + '"></div>');
            }, 100)
          },
          stop: function(event, ui) {
          },
          update: function(event, ui) {
              // This will trigger after a sort is completed
              var ordering = "";
              var $columns = $(".sortable");
              $columns.each(function() {
                  ordering += this.id + "=" + $columns.index(this) + ";";
              });
              //$.cookie("ordering", ordering);
          }
      });
    } catch(e) {
    }
});
