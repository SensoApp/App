import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import Button from '../Button';

const Sidebar = () => {
  return (
    <nav className="sidebar">
      <ul className="navbar-nav navbar-nav--sidebar">
        <li className="nav-item sidebar__btn">
          <Button custom="sidebar__link" text="Contact Us">
            <FontAwesomeIcon icon="envelope" className="sidebar__icon mr-2" />
          </Button>
        </li>
        <li className="nav-item sidebar__btn">
          <Button custom="sidebar__link" text="Contact Us">
            <FontAwesomeIcon icon="envelope" className="sidebar__icon mr-2" />
          </Button>
        </li>
        <li className="nav-item sidebar__btn">
          <Button custom="sidebar__link" text="Contact Us">
            <FontAwesomeIcon icon="envelope" className="sidebar__icon mr-2" />
          </Button>
        </li>
        <li className="nav-item sidebar__btn">
          <Button custom="sidebar__link" text="Contact Us">
            <FontAwesomeIcon icon="envelope" className="sidebar__icon mr-2" />
          </Button>
        </li>
      </ul>
    </nav>
  );
};

export default Sidebar;
