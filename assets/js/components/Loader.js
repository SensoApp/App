import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

const Spinner = () => {
  return (
    <div className="spinner">
      <FontAwesomeIcon icon="spinner" size="3x" spin />
    </div>
  );
};

export default Spinner;
