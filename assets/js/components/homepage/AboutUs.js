import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

const AboutUs = () => {
  return (
    <section id="about" className="about">
      <div className="about-heading  mb-5">
        <h1 className="heading-large">About Us</h1>
        <p className="text--display">
          Senso is a consulting company specialized in Project Management,
          Business Analysis and Operations created in Luxembourg in 2015.
        </p>
      </div>

      <div className="about-left mb-5 pt-3">
        <div className="d-flex">
          <h2 className="heading-small mr-4">Client Focus</h2>
          <FontAwesomeIcon
            icon={['far', 'user-circle']}
            style={{ color: '#008751' }}
            size="2x"
          />
        </div>
        <p className="text--basic">
          We want to be a reliable partner and a strong support for our clients.
          We have supported several major financial institutions in Luxembourg
          in various migration projects.
        </p>
      </div>

      <div className="about-right mb-5 pt-3">
        <div className="d-flex">
          <h2 className="heading-small mr-4">Staff Focus</h2>
          <FontAwesomeIcon
            icon={['far', 'comments']}
            style={{ color: '#008751' }}
            size="2x"
          />
        </div>
        <p className="text--basic">
          Our people are our main asset and we give a priority to make them
          happy at their work place.
        </p>
        <p className="text--basic">
          Flexibility, work life balance, competitive packages and discussion
          atmosphere allow our people to work in a trustworthy place and
          guarantees their dedication to our company and our clients.
        </p>
      </div>
    </section>
  );
};

export default AboutUs;
