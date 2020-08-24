import React from 'react';
import positiveNegative from './positiveNegative';

export default function amountFormatting (amount) {
    const formattedAmount = new Intl.NumberFormat('de-DE', {
                    style: 'currency',
                    currency: 'EUR',
                  }).format(amount);
    formattedAmount.toLocaleString('de-DE', { maximumFractionDigits: 2, minimumFractionDigits: 2 }) 
    return positiveNegative(formattedAmount);
  }