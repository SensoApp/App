import React from 'react';
import { Button } from '@material-ui/core';
import { makeStyles } from '@material-ui/core/styles';
import LockOutlinedIcon from '@material-ui/icons/LockOutlined';

const useStyles = makeStyles(() => ({
  roundedBtn: {
    borderRadius: 50,
    textTransform: 'none',
    fontSize: '1rem',
    fontWeight: '400',
  },
}));

const PrimaryButton = (props) => {
  const classes = useStyles();
  return (
    <Button
      className={classes.roundedBtn}
      color="primary"
      variant="contained"
      size="large"
      startIcon={<LockOutlinedIcon />}>
      {props.text}
    </Button>
  );
};

export default PrimaryButton;
