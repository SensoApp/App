import React from 'react';

const AboutUs = () => {
  return (
    <section id="about" className="about">
      <div className="about-heading  mb-5">
        <h1 className="heading-large">About Us</h1>
        <p className="text-display">
          Senso is a consulting company specialized in Project Management,
          Business Analysis and Operations created in Luxembourg in 2015.
        </p>
      </div>

      <div className="about-left mb-5 pt-3">
        <div className="d-flex">
          <h2 className="heading-small mr-4">Client Focus</h2>
          <svg
            width="2em"
            height="2em"
            viewBox="0 0 16 16"
            className="bi bi-chat"
            fill="#008751"
            xmlns="http://www.w3.org/2000/svg">
            <path
              fillRule="evenodd"
              d="M2.678 11.894a1 1 0 0 1 .287.801 10.97 10.97 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8.06 8.06 0 0 0 8 14c3.996 0 7-2.807 7-6 0-3.192-3.004-6-7-6S1 4.808 1 8c0 1.468.617 2.83 1.678 3.894zm-.493 3.905a21.682 21.682 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a9.68 9.68 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9.06 9.06 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105z"
            />
          </svg>
        </div>
        <p className="text-basic">
          We want to be a reliable partner and a strong support for our clients.
          We have supported several major financial institutions in Luxembourg
          in various migration projects.
        </p>
      </div>

      <div className="about-right mb-5 pt-3">
        <div className="d-flex">
          <h2 className="heading-small mr-4">Staff Focus</h2>
          <svg
            width="2em"
            height="2em"
            viewBox="0 0 16 16"
            className="bi bi-people"
            fill="#008751"
            xmlns="http://www.w3.org/2000/svg">
            <path
              fillRule="evenodd"
              d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.995-.944v-.002.002zM7.022 13h7.956a.274.274 0 0 0 .014-.002l.008-.002c-.002-.264-.167-1.03-.76-1.72C13.688 10.629 12.718 10 11 10c-1.717 0-2.687.63-3.24 1.276-.593.69-.759 1.457-.76 1.72a1.05 1.05 0 0 0 .022.004zm7.973.056v-.002.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10c-1.668.02-2.615.64-3.16 1.276C1.163 11.97 1 12.739 1 13h3c0-1.045.323-2.086.92-3zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"
            />
          </svg>
        </div>
        <p className="text-basic">
          Our people are our main asset and we give a priority to make them
          happy at their work place.
        </p>
        <p className="text-basic">
          Flexibility, work life balance, competitive packages and discussion
          atmosphere allow our people to work in a trustworthy place and
          guarantees their dedication to our company and our clients.
        </p>
      </div>
    </section>
  );
};

export default AboutUs;
