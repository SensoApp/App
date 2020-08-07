import React, { useState, forwardRef } from 'react';
import DatePicker from 'react-datepicker';

const DateRange = () => {
  const [startDate, setStartDate] = useState(null);
  const [endDate, setEndDate] = useState(null);
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
          ref={ref}
          placeholder={placeholder}
          onKeyDown={onKeyDown}
        />
      );
    }
  );

  return (
    <>
      <DatePicker
        selected={startDate}
        onChange={(date) => setStartDate(date)}
        selectsStart
        startDate={startDate}
        endDate={endDate}
        dateFormat="dd/MM/yyyy"
        placeholderText="Start date"
        customInput={<CustomDateInput ref={ref} placeholder="Start" />}
      />
      <br />
      <DatePicker
        selected={endDate}
        onChange={(date) => setEndDate(date)}
        selectsEnd
        startDate={startDate}
        endDate={endDate}
        minDate={startDate}
        dateFormat="dd/MM/yyyy"
        placeholderText="End date"
        customInput={<CustomDateInput ref={ref} placeholder="End date" />}
      />
    </>
  );
};

export default DateRange;
