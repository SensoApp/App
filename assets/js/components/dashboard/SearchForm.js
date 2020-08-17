import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { connect } from 'react-redux';
import { Field, reduxForm, SubmissionError } from 'redux-form';

import Button from '../Button';
import DateRange from '../DateRange';

class SearchForm extends React.Component {
  renderInput = ({ label, input, type, meta: { touched, error } }) => {
    return (
      <>
        {touched && error && <p className="alert alert-danger">{error}</p>}
        <input
          {...input}
          type={type}
          className="form-control form__input"
          aria-describedby="search"
          placeholder={label}
        />
      </>
    );
  };

  onSubmit = (formValues) => {
    if (
      !formValues.keyword &&
      !formValues.minAmount &&
      !formValues.maxAmount &&
      !formValues.startDate &&
      !formValues.endDate
    ) {
      throw new SubmissionError({
        _error: 'Please complete at least one field',
      });
    } else {
      this.props.onSubmit(formValues);
    }
  };

  render = () => {
    console.log(this.props.error);
    const { error, handleSubmit, pristine, reset, submitting } = this.props;
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
            Filter results
          </button>
        </p>
        <div className="collapse" id="searchForm">
          <form className="card form" onSubmit={handleSubmit(this.onSubmit)}>
            <div className="form-group">
              <legend className="legend">Search a keyword</legend>
              <Field
                type="text"
                id="textSearch"
                label="Enter a keyword"
                name="keyword"
                component={this.renderInput}
              />
              <label htmlFor="textSearch" className="form__label">
                Enter a keyword
              </label>
            </div>
            <fieldset className="form-group">
              <legend className="legend">Amount between :</legend>
              <Field
                type="number"
                id="min"
                label="Min"
                name="minAmount"
                component={this.renderInput}
              />
              <label htmlFor="min" className="form__label">
                Min
              </label>
              <Field
                type="number"
                id="max"
                label="Max"
                name="maxAmount"
                component={this.renderInput}
              />
              <label htmlFor="max" className="form__label">
                Max
              </label>
            </fieldset>
            <fieldset className="form-group">
              <legend className="legend">Dates between :</legend>
              <div className="calendar-container">
                <Field
                  name="startDatePicker"
                  label="Start Date"
                  fieldName="startDate"
                  component={DateRange}
                />
                <Field
                  name="endDatePicker"
                  label="End Date"
                  fieldName="endDate"
                  component={DateRange}
                />
              </div>
            </fieldset>
            {error && <p className="alert alert-danger">{error}</p>}
            <div className="submit-container">
              <Button type="submit" text="Submit" />
              <button
                className="btn btn-round btn-primary"
                type="button"
                disabled={pristine || submitting}
                onClick={reset}>
                Clear
              </button>
            </div>
          </form>
        </div>
      </div>
    );
  };
}

SearchForm = reduxForm({ form: 'SearchForm' })(SearchForm);

SearchForm = connect((state) => {
  const { keyword, minAmount, maxAmount, startDate, endDate } = selector(
    state,
    'keyword',
    'minAmount',
    'maxAmount',
    'startDate',
    'endDate'
  );
  return {
    keyword,
    minAmount,
    maxAmount,
    startDate,
    endDate,
  };
})(SearchForm);

export default SearchForm;
