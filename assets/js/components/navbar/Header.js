import React from 'react';
import { Link } from 'react-router-dom';

import { AppBar, Toolbar } from '@material-ui/core';
import { makeStyles } from '@material-ui/core/styles';
import InfoOutlinedIcon from '@material-ui/icons/InfoOutlined';
import WorkOutlineOutlinedIcon from '@material-ui/icons/WorkOutlineOutlined';
import EmailOutlinedIcon from '@material-ui/icons/EmailOutlined';
import MonetizationOnOutlinedIcon from '@material-ui/icons/MonetizationOnOutlined';

import PrimaryButton from './PrimaryButton';
import NavButton from './NavButton';
import DropdownButton from './DropdownButton';
import logo from '../../../../public/img/Logo_Senso_3.png';

const useStyles = makeStyles((theme) => ({
  logo: {
    flexGrow: 1,
  },
  nav: {
    backgroundColor: 'white',
  },
  tool: {
    marginRight: '2rem',
  },
}));

const Header = () => {
  const classes = useStyles();
  return (
    <AppBar position="static" className={classes.nav}>
      <Toolbar className={classes.tool}>
        <Link to="/" className={classes.logo}>
          <img src={logo} />
        </Link>
        <NavButton text="About Us" startIcon={<InfoOutlinedIcon />} />
        <DropdownButton
          text="Our Services"
          startIcon={<WorkOutlineOutlinedIcon />}
        />
        <NavButton text="Contact Us" startIcon={<EmailOutlinedIcon />} />
        <NavButton
          text="Simulation"
          startIcon={<MonetizationOnOutlinedIcon />}
        />
        <PrimaryButton text="My Senso" />
      </Toolbar>
    </AppBar>
  );
};

export default Header;
