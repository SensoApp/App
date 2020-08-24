import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
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
      !formValues.startDatePicker &&
      !formValues.endDatePicker
    ) {
      throw new SubmissionError({
        _error: 'Please complete at least one field',
      });
    } else {
      this.props.onSubmit(formValues);
      if (formValues.endDatePicker) {
        formValues.endDatePicker.setHours(23, 59, 59, 999);
      }
    }
  };

  onClick = () => {
    this.props.reset();
    this.props.handleReset();
  };

  render = () => {
    const { error, handleSubmit, pristine, submitting } = this.props;

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
                type="reset"
                className="btn btn-round btn-primary"
                type="button"
                disabled={pristine || submitting}
                onClick={this.onClick}>
                Clear
              </button>
            </div>
          </form>
        </div>
      </div>
    );
  };
}

SearchForm = reduxForm({ form: 'SearchForm', enableReinitialize: true })(
  SearchForm
);

export default SearchForm;
