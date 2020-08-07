import React from 'react';
import MovementCard from './MovementCard';

const MovementsTable = () => {
  return (
    <div className="table-container">
      <div className="cards-list">
        <MovementCard
          date="19 June 2019"
          transfer="Virement SENSO SARL"
          detail="Lunch vouchers July 2020 PON"
          amount="-194.40 €"
        />
        <MovementCard
          date="2 June 2019"
          transfer="Virement Européen LEVERAGE CONSULTING LUXEMBOURG"
          detail="0054-PN"
          amount="14,040.00 €"
        />
        <MovementCard
          date="25 May 2019"
          transfer="Virement SENSO SARL"
          detail="Cotisations sociales PON Juin 2020"
          amount="-194.40 €"
        />
        <MovementCard
          date="12 May 2019"
          transfer="Virement SENSO SARL"
          detail="TVA invoice 0054-PN"
          amount="-2,040.00 €"
        />
        <MovementCard
          date="28 April 2019"
          transfer="Virement SENSO SARL"
          detail="Impot revenu mai 2020 PON"
          amount="-1,800.20 €"
        />
      </div>
      <table className="table table-borderless">
        <thead>
          <tr>
            <th scope="col" className="table__header">
              Operation
            </th>
            <th scope="col" className="table__header">
              Communication
            </th>
            <th scope="col" className="table__header">
              Date
            </th>
            <th scope="col" className="table__header">
              Amount
            </th>
          </tr>
        </thead>
        <tbody className="table__body">
          <tr className="table__row">
            <td>Virement</td>
            <td>Lunch vouchers July 2020 PON</td>
            <td>19 June 2019</td>
            <td className="negative">-194.40 €</td>
          </tr>
          <tr className="table__row">
            <td>Virement</td>
            <td>0054-PN</td>
            <td>12 June 2019</td>
            <td className="positive">14,040.00 €</td>
          </tr>
          <tr className="table__row">
            <td>Virement</td>
            <td>Cotisations sociales PON Juin 2020</td>
            <td>7 June 2019</td>
            <td className="negative">-194.40 €</td>
          </tr>
          <tr className="table__row">
            <td>Virement</td>
            <td>TVA invoice 0054-PN</td>
            <td>2 June 2019</td>
            <td className="negative">-2,040.00 €</td>
          </tr>
          <tr className="table__row">
            <td>Virement</td>
            <td>Impot revenu mai 2020 PON</td>
            <td>30 May 2019</td>
            <td className="negative">-1,800.20 €</td>
          </tr>
        </tbody>
      </table>
    </div>
  );
};

export default MovementsTable;
