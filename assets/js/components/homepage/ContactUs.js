import React from 'react';

/*************** Import here map component and contact form*************** */

const ContactUs = () => {
  return (
    <section className="contact">
      <h1 className="heading-large">Contact Us</h1>
      <div className="contact-content">
        <address className="mb-4">
          <h2 className="heading-small heading-small--blue">Address</h2>
          <p className="text--basic">
            2-4 Rue Du Nord, L2229 Luxembourg, Luxembourg
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
    </section>
  );
};

export default ContactUs;
