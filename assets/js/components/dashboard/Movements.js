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
    console.log(this.props.formValues);
    if (this.props.formValues.length > 0) {
      return this.props.formValues;
    } else {
      return this.props.movements;
    }
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
