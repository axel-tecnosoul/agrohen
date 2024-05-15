function formatCurrency(number){
  return new Intl.NumberFormat('es-AR', {currency: 'ARS', style: 'currency'}).format(number)
}

function formatNumber(number){
  return new Intl.NumberFormat('es-AR', {useGrouping: true}).format(number)
}

function formatNumber2Decimal(number){
  return new Intl.NumberFormat('es-AR', {minimumFractionDigits: 2, maximumFractionDigits: 2, useGrouping: true}).format(number)
}