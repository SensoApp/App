import React, { forwardRef } from 'react';
import DatePicker from 'react-datepicker';

const DateRange = ({ label, input }) => {
  const ref = React.createRef();

  const CustomDateInput = forwardRef(
    ({ value, onClick, placeholder, onKeyDown }, ref) => {
      return (
        <input
          id={placeholder}
          className="form-control form__input"
          onClick={onClick}
          value={value}
          onChange={onClick}
          placeholder={placeholder}
          onKeyDown={onKeyDown}
          ref={ref}
        />
      );
    }
  );

  return (
    <>
      <DatePicker
        {...input}
        selected={input.value || null}
        onChange={input.onChange}
        dateFormat="dd/MM/yyyy"
        placeholderText={label}
        customInput={<CustomDateInput placeholder={label} ref={ref} />}
      />
    </>
  );
};

export default DateRange;
