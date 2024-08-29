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