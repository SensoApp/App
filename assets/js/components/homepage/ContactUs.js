import React from 'react';

import Form from './Form';

/*************** Import here map component and contact form*************** */

const ContactUs = () => {
  return (
    <section className="contact">
      <h1 className="heading-large">Contact Us</h1>
      <div className="contact-content">
        <address className="mb-4">
          <h2 className="heading-small heading-small--blue">Address</h2>
          <p className="text--basic">
            2-4 Rue Du Nord, L2229 <br /> Luxembourg, Luxembourg
          </p>
        </address>
        <address className="mb-4">
          <h2 className="heading-small heading-small--blue">Phone</h2>
          <p className="text--basic">
            +352 661 661 196
            <br />
            +352 691 198 027
          </p>
        </address>
        <address className="">
          <h2 className="heading-small heading-small--blue">E-Mail</h2>
          <p className="text--basic">info@senso.lu</p>
        </address>
      </div>
      <div className="map">
        <iframe
          width="300"
          height="400"
          frameBorder="0"
          style={{ border: 0 }}
          src="https://www.google.com/maps/embed/v1/place?key=AIzaSyCAQyTHyg3QJ-AcSxPA7BCT215SootfSpI
    &q=2+Rue+du+Nord%2C+2229+Luxembourg"
          allowFullScreen></iframe>
      </div>
      <Form />
    </section>
  );
};

export default ContactUs;
