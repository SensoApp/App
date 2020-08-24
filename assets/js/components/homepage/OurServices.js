import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

import Button from '../Button';

const OurServices = () => {
  return (
    <section className="services">
      <div className="services-content">
        <h1 className="heading-large heading-large--white">Our Services</h1>
        <div className="card-deck">
          <div className="card card-body flex-fill">
            <FontAwesomeIcon
              icon={['far', 'building']}
              style={{ color: '#1761a0' }}
              size="4x"
            />
            <h5 className="card-title mt-4">Consulting Services</h5>
            <Button text="Learn More" custom="btn-square btn-secondary" />
          </div>
          <div className="card card-body flex-fill">
            <FontAwesomeIcon
              icon={['far', 'money-bill-alt']}
              style={{ color: '#1761a0' }}
              size="4x"
            />
            <h5 className="card-title mt-4">Payroll Services</h5>
            <Button text="Learn More" custom="btn-square btn-secondary" />
          </div>
          <div className="card card-body flex-fill">
            <FontAwesomeIcon
              icon="laptop-code"
              style={{ color: '#1761a0' }}
              size="4x"
            />
            <h5 className="card-title mt-4">Development Services</h5>
            <Button text="Learn More" custom="btn-square btn-secondary" />
          </div>
        </div>
      </div>
    </section>
  );
};

export default OurServices;
