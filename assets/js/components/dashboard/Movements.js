import React from 'react';
import { connect } from 'react-redux';

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
          <MovementsTable data={this.props.movements} />
        </div>
      );
    }
  }
}

const mapStateToProps = (state) => {
  return { movements: state.movements };
};

export default connect(mapStateToProps, { fetchMovements })(Movements);
