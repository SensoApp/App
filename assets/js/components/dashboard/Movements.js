import React from 'react';
import { connect } from 'react-redux';
import { formValueSelector } from 'redux-form';

import { fetchMovements } from '../../actions';
import MovementCard from './MovementCard';
import MovementsTable from './MovementsTable';
import Loader from '../Loader';
import stringDate from '../../helpers/stringDate';
import positiveNegative from '../../helpers/positiveNegative';

class Movements extends React.Component {
  state = {
    width: window.innerWidth,
    formData: null,
  };

  handleWindowSizeChange = () => {
    this.setState({ width: window.innerWidth });
  };

  componentDidMount() {
    window.addEventListener('resize', this.handleWindowSizeChange);
    this.props.fetchMovements();
  }

  renderCards() {
    return this.props.movements.map((movement) => {
      return (
        <MovementCard
          key={movement.id}
          date={stringDate(movement.operationdate)}
          transfer={movement.operations}
          detail={movement.communication}
          amount={positiveNegative(movement.amount, true)}
        />
      );
    });
  }

  tableData() {
    if (this.props.formData != null) {
      const {
        keyword,
        minAmount,
        maxAmount,
        startDatePicker,
        endDatePicker,
      } = this.props.formData;
      const filteredData = this.props.movements.filter((movement) => {
        if (keyword) {
          const communicationLookUp = movement.communication
            .toLowerCase()
            .includes(keyword.toLowerCase());
          const operationsLookUp = movement.operations
            .toLowerCase()
            .includes(keyword.toLowerCase());
          return communicationLookUp || operationsLookUp;
        }

        if (minAmount && maxAmount) {
          return (
            parseInt(minAmount, 10) <= movement.amount &&
            movement.amount <= parseInt(maxAmount, 10)
          );
        } else if (minAmount && !maxAmount) {
          return parseInt(minAmount, 10) <= movement.amount;
        } else if (!minAmount && maxAmount) {
          return movement.amount <= parseInt(maxAmount, 10);
        }

        if (startDatePicker && endDatePicker) {
          return (
            startDatePicker.setHours(0, 0, 0, 0) <=
              movement.operationdate.setHours(0, 0, 0, 0) &&
            movement.operationdate.setHours(0, 0, 0, 0) <=
              endDatePicker.setHours(0, 0, 0, 0)
          );
        } else if (startDatePicker && !endDatePicker) {
          return (
            startDatePicker.setHours(0, 0, 0, 0) <=
            movement.operationdate.setHours(0, 0, 0, 0)
          );
        } else if (!startDatePicker && endDatePicker) {
          return (
            movement.operationdate.setHours(0, 0, 0, 0) <=
            endDatePicker.setHours(0, 0, 0, 0)
          );
        }
      });
      return filteredData;
    }
    return this.props.movements;
  }

  render() {
    //console.log(this.props.formData);
    if (this.props.movements.length === 0) {
      return <Loader />;
    }

    const { width } = this.state;
    const mobileWidth = 834; // IPad pro size
    const isMobile = width <= mobileWidth;

    if (isMobile) {
      return <div>{this.renderCards()}</div>;
    } else {
      return (
        <div className="table-container">
          <MovementsTable data={this.tableData()} />
        </div>
      );
    }
  }
}

const selector = formValueSelector('SearchForm');

const mapStateToProps = (state) => {
  return {
    movements: state.movements,
    formValues: selector(
      state,
      'keyword',
      'minAmount',
      'maxAmount',
      'startDate',
      'endDate'
    ),
  };
};

export default connect(mapStateToProps, { fetchMovements })(Movements);
