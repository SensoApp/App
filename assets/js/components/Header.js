import React from 'react';
import { HashLink } from 'react-router-hash-link';
import { Link } from 'react-router-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

import Button from './Button';
import logo from '../../../public/img/Logo_Senso_3.png';

const Header = () => {
  function collapse() {
    $('.navbar-collapse').collapse('hide');
  }

  return (
    <nav className="navbar sticky-top navbar-expand-lg navbar-light">
      <div className="flex-grow-1">
        <Link to="/frontend/">
          <img src={logo} className="navbar-brand" />
        </Link>
      </div>
      <button
        className="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarNavAltMarkup"
        aria-controls="navbarNavAltMarkup"
        aria-expanded="false"
        aria-label="Toggle navigation">
        <span className="navbar-toggler-icon"></span>
      </button>
      <div
        className="collapse navbar-collapse flex-grow-0"
        id="navbarNavAltMarkup">
        <div className="container">
          <ul className="navbar-nav">
            <li className="nav-item nav-btn" onClick={collapse}>
              <HashLink to="/frontend/#about" className="nav-link" href="#">
                <FontAwesomeIcon icon="info-circle" className="mr-2" />
                About Us
              </HashLink>
            </li>
            <li className="nav-item dropdown">
              <a
                className=" nav-link dropdown-toggle"
                href="#"
                id="navbarDropdownMenuLink"
                role="button"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false">
                <FontAwesomeIcon icon="briefcase" className="mr-2" />
                Our Services
              </a>
              <div
                className="dropdown-menu"
                aria-labelledby="navbarDropdownMenuLink">
                <a className="dropdown-item" href="#" onClick={collapse}>
                  Consulting Services
                </a>
                <a className="dropdown-item" href="#" onClick={collapse}>
                  Payroll Services
                </a>
              </div>
            </li>
            <li className="nav-item nav-btn" onClick={collapse}>
              <Button custom=" nav-link" text="Contact Us">
                <FontAwesomeIcon icon="envelope" className="mr-2" />
              </Button>
            </li>
            <li className="nav-item nav-btn" onClick={collapse}>
              <Button custom=" nav-link" text="Simulation">
                <FontAwesomeIcon icon="money-bill" className="mr-2" />
              </Button>
            </li>
          </ul>
          <Link to="/frontend/dashboard">
            <Button text="My Senso" custom="item btn-primary btn-round">
              <FontAwesomeIcon icon="lock" className="mr-2" />
            </Button>
          </Link>
        </div>
      </div>
    </nav>
  );
};

export default Header;
