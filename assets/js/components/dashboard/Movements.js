import React from 'react';
import { connect } from 'react-redux';

import { fetchMovements } from '../../actions';
import MovementCard from './MovementCard';
import MovementsTable from './MovementsTable';
import Loader from '../Loader';
import stringDate from '../../helpers/stringDate';
import amountFormatting from '../../helpers/amountFormatting';

/******
Fetches the movement from the API, filters the data if necessary, and renders cards or table depending on screen size
******/

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

  componentWillUnmount() {
    window.removeEventListener('resize', this.handleWindowSizeChange);
  }

  // Functions to filter data as determined by search form
  keywordFilter(movement, keyword) {
    if (keyword) {
      const communicationLookUp = movement.communication
        .toLowerCase()
        .includes(keyword.toLowerCase());
      const operationsLookUp = movement.operations
        .toLowerCase()
        .includes(keyword.toLowerCase());
      return communicationLookUp || operationsLookUp;
    }
    return true;
  }

  amountFilter(movement, minAmount, maxAmount) {
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
    return true;
  }

  dateFilter(movement, startDatePicker, endDatePicker) {
    const movementDate = movement.operationdate;
    if (startDatePicker && endDatePicker) {
      return startDatePicker <= movementDate && movementDate <= endDatePicker;
    } else if (startDatePicker) {
      return startDatePicker <= movementDate;
    } else if (endDatePicker) {
      return movementDate <= endDatePicker;
    }
    return true;
  }

  tableData() {
    if (this.props.formData) {
      const {
        keyword,
        minAmount,
        maxAmount,
        startDatePicker,
        endDatePicker,
      } = this.props.formData;
      const filteredData = this.props.movements
        .filter((movement) => {
          return this.keywordFilter(movement, keyword);
        })
        .filter((movement) => {
          return this.amountFilter(movement, minAmount, maxAmount);
        })
        .filter((movement) => {
          return this.dateFilter(movement, startDatePicker, endDatePicker);
        });
      return filteredData;
    } else {
      return this.props.movements;
    }
  }

  renderCards() {
    return this.tableData().map((movement) => {
     
      return (
        <MovementCard
          key={movement.id}
          date={stringDate(movement.operationdate)}
          transfer={movement.operations}
          detail={movement.communication}
          amount={amountFormatting(movement.amount)}
        />
      );
    });
  }

  render() {
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

const mapStateToProps = (state) => {
  return {
    movements: state.movements
  };
};

export default connect(mapStateToProps, { fetchMovements })(Movements);
