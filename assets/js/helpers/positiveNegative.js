import React from 'react';

export default function positiveNegative(amount) {
  const parsedAmount = parseFloat(amount);
  if (parsedAmount > 0) {
    return (
      <span className="positive">
        {amount}
      </span>
    );
  } else if (parsedAmount < 0) {
    return (
      <span className="negative">
        {amount}
      </span>
    );
  }
  return <span>{amount}</span>;
}
