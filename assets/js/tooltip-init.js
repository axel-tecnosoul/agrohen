(function($) {
	"use strict";
	var tooltip_init = {
		init: function() {
			$("button").tooltip();
			$("a").tooltip();
			$("input").tooltip();
			$("img").tooltip();

      /*$('[data-toggle="tooltip"]').tooltip({
        //placement: 'top',
        placement: 'auto',
        delay: { "show": 500, "hide": 100 },
        boundary: 'viewport' // Asegura que el tooltip se mantenga dentro de la ventana gráfica
      });

      $('[data-toggle="tooltip"]').on('show.bs.tooltip', function () {
        $('[data-toggle="tooltip"]').not(this).tooltip('hide');
      });*/
		}
	};
  tooltip_init.init()

  $("li").tooltip('dispose');
})(jQuery);