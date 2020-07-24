import React from 'react';
import {
  Button,
  ClickAwayListener,
  Grow,
  MenuList,
  MenuItem,
  Paper,
  Popper,
} from '@material-ui/core';
import { makeStyles } from '@material-ui/core/styles';
import KeyboardArrowDownOutlinedIcon from '@material-ui/icons/KeyboardArrowDownOutlined';

const useStyles = makeStyles((theme) => ({
  btn: {
    textTransform: 'none',
    marginRight: '1rem',
    fontSize: '1rem',
  },
  item: {
    color: theme.palette.primary.main,
  },
}));

const DropdownButton = (props) => {
  const [open, setOpen] = React.useState(false);
  const anchorRef = React.useRef(null);

  const handleToggle = () => {
    setOpen((prevOpen) => !prevOpen);
  };

  const handleClose = (event) => {
    if (anchorRef.current && anchorRef.current.contains(event.target)) {
      return;
    }

    setOpen(false);
  };

  const prevOpen = React.useRef(open);
  React.useEffect(() => {
    if (prevOpen.current === true && open === false) {
      anchorRef.current.focus();
    }

    prevOpen.current = open;
  }, [open]);

  function handleListKeyDown(event) {
    if (event.key === 'Tab') {
      event.preventDefault();
      setOpen(false);
    }
  }

  const classes = useStyles();

  return (
    <div>
      <Button
        className={classes.btn}
        ref={anchorRef}
        aria-controls={open ? 'menu-list-grow' : undefined}
        aria-haspopup="true"
        onClick={handleToggle}
        startIcon={props.startIcon}>
        {props.text}
        <KeyboardArrowDownOutlinedIcon />
      </Button>
      <Popper
        open={open}
        placement="bottom-end"
        anchorEl={anchorRef.current}
        role={undefined}
        transition
        disablePortal>
        {({ TransitionProps, placement }) => (
          <Grow
            {...TransitionProps}
            style={{
              transformOrigin:
                placement === 'bottom' ? 'center top' : 'center bottom',
            }}>
            <Paper>
              <ClickAwayListener onClickAway={handleClose}>
                <MenuList
                  autoFocusItem={open}
                  id="menu-list-grow"
                  onKeyDown={handleListKeyDown}>
                  <MenuItem className={classes.item} onClick={handleClose}>
                    Consulting Services
                  </MenuItem>
                  <MenuItem className={classes.item} onClick={handleClose}>
                    Payroll Services
                  </MenuItem>
                </MenuList>
              </ClickAwayListener>
            </Paper>
          </Grow>
        )}
      </Popper>
    </div>
  );
};

export default DropdownButton;
