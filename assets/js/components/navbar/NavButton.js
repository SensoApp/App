import React from 'react';
import { Button } from '@material-ui/core';
import { makeStyles } from '@material-ui/core/styles';

const useStyles = makeStyles((theme) => ({
  btn: {
    textTransform: 'none',
    marginRight: '1rem',
    fontSize: '1rem',
  },
}));

const NavButton = (props) => {
  const classes = useStyles();

  return (
    <Button className={classes.btn} startIcon={props.startIcon}>
      {props.text}
    </Button>
  );
};

export default NavButton;
