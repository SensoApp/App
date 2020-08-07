import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

import Button from '../Button';
import DateRange from '../DateRange';

const SearchCollapse = () => {
  return (
    <div className="search">
      <p>
        <button
          className="btn search-btn  dropdown-toggle"
          type="button"
          data-toggle="collapse"
          data-target="#searchForm"
          aria-expanded="false"
          aria-controls="collapseExample">
          <FontAwesomeIcon icon="search" className="mr-2" />
          Search for movements
        </button>
      </p>
      <div className="collapse" id="searchForm">
        <form className="card form">
          <div className="form-group">
            <legend className="legend">Textual search :</legend>
            <input
              type="text"
              className="form-control form__input"
              id="textSearch"
              aria-describedby="search"
              placeholder="Enter a keyword"
            />
            <label htmlFor="textSearch" className="form__label">
              Enter a keyword
            </label>
          </div>
          <fieldset className="form-group">
            <legend className="legend">Amount between :</legend>
            <input
              type="number"
              className="form-control form__input"
              id="min"
              aria-describedby="min amount"
              placeholder="Min"
            />
            <label htmlFor="min" className="form__label">
              Min
            </label>
            <input
              type="number"
              className="form-control form__input"
              id="max"
              aria-describedby="min amount"
              placeholder="Max"
            />
            <label htmlFor="max" className="form__label">
              Max
            </label>
          </fieldset>
          <fieldset className="form-group">
            <legend className="legend">Dates between :</legend>
            <div className="calendar-container">
              <DateRange />
            </div>
          </fieldset>
          <Button type="submit" text="Submit" />
        </form>
      </div>
    </div>
  );
};

export default SearchCollapse;
