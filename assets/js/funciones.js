function formatCurrency(number){
  return new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(number)
}

function formatNumber(number){
  return new Intl.NumberFormat('es-AR', {useGrouping: true}).format(number)
}

function formatNumber2Decimal(number){
  return new Intl.NumberFormat('es-AR', {minimumFractionDigits: 2, maximumFractionDigits: 2, useGrouping: true}).format(number)
}

// Función para deshabilitar un botón y mostrar el spinner
function mostrarSpinner($boton) {
  $boton.prop('disabled', true).append('<span class="spinner-border spinner-border-sm ml-2" role="status" aria-hidden="true"></span>');
}

// Función para restaurar el botón y eliminar el spinner
function restaurarBoton($boton) {
  $boton.prop('disabled', false).find('.spinner-border').remove();
}

$(document).on('show.bs.modal','.modal', function () {
  //$('[data-toggle="tooltip"]').tooltip('hide');
  $('.tooltip').tooltip('hide');
});

//Función para agregar una nueva opción a un select y actualizar el select2.
function agregarOpcionSelect(selectId, administrar, accion, noResultText, tabla, campo) {
  let ajaxUrl = `models/administrar_${administrar}.php`;

  $('#' + selectId).select2({
      language: {
          noResults: function() {
              return noResultText;
          }
      },
      matcher: function(params, data) {
          if ($.trim(params.term) === '') {
              return data;
          }
          if (data.text.toLowerCase() === params.term.toLowerCase()) {
              return data;
          }
          return null;
      }
  });

  $('#' + selectId).on('select2:open', function() {
      let searchField = $('.select2-search__field');
      let noResultsShown = false;

      searchField.off('keydown').on('keydown', function(e) {
          if ($('.select2-results__option').text() == noResultText) {
              noResultsShown = true;
          }

          if (e.key === 'Enter' && noResultsShown) {
              let searchTerm = $(this).val();
              $.ajax({
                  url: ajaxUrl,
                  type: "POST",
                  datatype: "json",
                  data: { accion: accion, nombre: searchTerm },
                  success: function(response) {
                      let data = JSON.parse(response);
                      let newOption = new Option(searchTerm, data.id_value, false, true);
                      $('#' + selectId).append(newOption);

                      console.log("SelectId: " + selectId + " , dataID: " + data.id_value);

                      $('#' + selectId).val(data.id_value).trigger('change').select2('close');
                  }
              });
          }
      });
  });
}