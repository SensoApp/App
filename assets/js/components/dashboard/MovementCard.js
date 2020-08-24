import React from 'react';

const MovementCard = (props) => {
  return (
    <div className="card card--movement">
      <div className="card-body card-body--movement">
        <h6 className="card-subtitle mb-2 text-muted">{props.date}</h6>
        <p className="card-text card-text--movement font-weight-bold">
          {props.transfer}
        </p>
        <div className="movement-detail">
          <p className="card-text card-text--movement font-weight-light">
            {props.detail}
          </p>
          <div className=" movement-amount">{props.amount}</div>
        </div>
      </div>
    </div>
  );
};

export default MovementCard;
