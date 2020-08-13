import React from 'react';

export default function positiveNegative(amount, includeCurrency = false) {
  console.log(amount);
  if (amount > 0) {
    return (
      <span className="positive">
        {amount}
        {includeCurrency ? ' €' : ''}
      </span>
    );
  } else if (amount < 0) {
    return (
      <span className="negative">
        {amount}
        {includeCurrency ? ' €' : ''}
      </span>
    );
  }
  return <span>{amount}</span>;
}
