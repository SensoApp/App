import React from 'react';

const OurServices = () => {
  return (
    <section lassName="services">
      <div className="services-content">
        <h1 className="heading-large heading-large--white">Our Services</h1>
        <div className="card-deck">
          <div className="card card-body">
            <svg
              width="4.375em"
              height="4.375em"
              viewBox="0 0 16 16"
              className="bi bi-building service-icon"
              fill="#1761a0"
              xmlns="http://www.w3.org/2000/svg">
              <path
                fillRule="evenodd"
                d="M14.763.075A.5.5 0 0 1 15 .5v15a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V14h-1v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V10a.5.5 0 0 1 .342-.474L6 7.64V4.5a.5.5 0 0 1 .276-.447l8-4a.5.5 0 0 1 .487.022zM6 8.694L1 10.36V15h5V8.694zM7 15h2v-1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5V15h2V1.309l-7 3.5V15z"
              />
              <path d="M2 11h1v1H2v-1zm2 0h1v1H4v-1zm-2 2h1v1H2v-1zm2 0h1v1H4v-1zm4-4h1v1H8V9zm2 0h1v1h-1V9zm-2 2h1v1H8v-1zm2 0h1v1h-1v-1zm2-2h1v1h-1V9zm0 2h1v1h-1v-1zM8 7h1v1H8V7zm2 0h1v1h-1V7zm2 0h1v1h-1V7zM8 5h1v1H8V5zm2 0h1v1h-1V5zm2 0h1v1h-1V5zm0-2h1v1h-1V3z" />
            </svg>

            <h5 className="card-title">Consulting Services</h5>
          </div>
          <div className="card card-body">
            <svg
              width="4.375em"
              height="4.375em"
              viewBox="0 0 16 16"
              className="bi bi-cash-stack service-icon"
              fill="#1761a0"
              xmlns="http://www.w3.org/2000/svg">
              <path d="M14 3H1a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1h-1z" />
              <path
                fillRule="evenodd"
                d="M15 5H1v8h14V5zM1 4a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1H1z"
              />
              <path d="M13 5a2 2 0 0 0 2 2V5h-2zM3 5a2 2 0 0 1-2 2V5h2zm10 8a2 2 0 0 1 2-2v2h-2zM3 13a2 2 0 0 0-2-2v2h2zm7-4a2 2 0 1 1-4 0 2 2 0 0 1 4 0z" />
            </svg>

            <h5 className="card-title">Payroll Services</h5>
          </div>
          <div className="card card-body">
            <svg
              width="4.375em"
              height="4.375em"
              viewBox="0 0 16 16"
              className="bi bi-file-code service-icon"
              fill="#1761a0"
              xmlns="http://www.w3.org/2000/svg">
              <path
                fillRule="evenodd"
                d="M4 1h8a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H4z"
              />
              <path
                fillRule="evenodd"
                d="M8.646 5.646a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1 0 .708l-2 2a.5.5 0 0 1-.708-.708L10.293 8 8.646 6.354a.5.5 0 0 1 0-.708zm-1.292 0a.5.5 0 0 0-.708 0l-2 2a.5.5 0 0 0 0 .708l2 2a.5.5 0 0 0 .708-.708L5.707 8l1.647-1.646a.5.5 0 0 0 0-.708z"
              />
            </svg>

            <h5 className="card-title">Development Services</h5>
          </div>
        </div>
      </div>
    </section>
  );
};

export default OurServices;
