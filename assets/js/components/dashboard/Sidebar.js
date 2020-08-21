import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { NavLink } from 'react-router-dom';

import Button from '../Button';

const Sidebar = () => {
  return (
    <nav className="sidebar">
      <div className="nav-wrapper">
        <ul className="navbar-nav navbar-nav--sidebar">
          <li className="nav-item sidebar__btn-wrap">
            <NavLink to="/frontend/dashboard" activeClassName="selected">
              <Button custom="btn--sidebar" text="Balance">
                <FontAwesomeIcon
                  icon="euro-sign"
                  className="sidebar__icon mr-2"
                  size="lg"
                />
              </Button>
            </NavLink>
          </li>
          <li className="nav-item sidebar__btn-wrap">
            <NavLink to="/frontend/contracts" activeClassName="selected">
              <Button custom="btn--sidebar" text="Contracts">
                <FontAwesomeIcon
                  icon="address-book"
                  className="sidebar__icon mr-2"
                  size="lg"
                />
              </Button>
            </NavLink>
          </li>
          <li className="nav-item sidebar__btn-wrap">
            <NavLink to="/frontend/invoices" activeClassName="selected">
              <Button custom="btn--sidebar" text="Invoices">
                <FontAwesomeIcon
                  icon="file-invoice-dollar"
                  size="lg"
                  className="sidebar__icon mr-2"
                />
              </Button>
            </NavLink>
          </li>
          <li className="nav-item sidebar__btn-wrap">
            <NavLink to="/frontend/profile" activeClassName="selected">
              <Button custom="btn--sidebar" text="Profile">
                <FontAwesomeIcon
                  icon="user"
                  size="lg"
                  className="sidebar__icon mr-2"
                />
              </Button>
            </NavLink>
          </li>
        </ul>
      </div>
    </nav>
  );
};

export default Sidebar;
