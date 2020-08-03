import React from 'react';

import Button from '../Button';

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
          width="400"
          height="400"
          frameBorder="0"
          style={{ border: 0 }}
          src="https://www.google.com/maps/embed/v1/place?key=AIzaSyCAQyTHyg3QJ-AcSxPA7BCT215SootfSpI
    &q=2+Rue+du+Nord%2C+2229+Luxembourg"
          allowFullScreen></iframe>
      </div>
      <form>
        <div className="form-group">
          <label htmlFor="name">Your name</label>
          <input
            type="text"
            className="form-control"
            id="name"
            aria-describedby="name"
          />
          {/* <small id="emailHelp" className="form-text text-muted">
            We'll never share your email with anyone else.
          </small> */}
        </div>
        <div className="form-group">
          <label htmlFor="phone">Phone</label>
          <input type="password" className="form-control" id="phone" />
        </div>
        <div className="form-group form-check">
          <label htmlFor="mail">Email address</label>
          <input
            type="email"
            className="form-control"
            id="mail"
            aria-describedby="emailHelp"
          />
        </div>
        <div className="form-group">
          <label htmlFor="message">Message</label>
          <textarea className="form-control" id="message" rows="3"></textarea>
        </div>
        <Button type="submit" text="Submit" />
      </form>
    </section>
  );
};

export default ContactUs;
