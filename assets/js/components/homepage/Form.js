import React from "react";

import Button from "../Button";

const Form = () => {
  return (
    <form>
      <h2 className="heading-medium">Don't hesitate to send us a message</h2>
      <div className="form-group">
        <label htmlFor="name">Your name</label>
        <input
          type="text"
          className="form-control"
          id="name"
          aria-describedby="name"
        />
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
        <Button type="submit" text="Submit" />{" "}
      </div>
    </form>
  );
};

export default Form;
