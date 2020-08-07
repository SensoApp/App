import React from 'react';
import SearchCollapse from './SearchCollapse.js';
import Sidebar from './Sidebar.js';
import MovementsTable from './MovementsTable.js';

const Balance = () => {
  return (
    <main className="grid-container">
      <section className="content-section">
        <hgroup className="headings">
          <h1 className="heading-huge heading-huge--blue">Username</h1>
          <h2 className="heading-large heading-large--green font-italic">
            My Balance
          </h2>
        </hgroup>
        <div className="balance">
          <div className="balance_left">
            <p className="heading-small">Current Balance</p>
            <span className="balance-amount">€ 22.345,56</span>
          </div>
          <div className="balance_right">
            <p className="text--basic estimated mb-2">Estimated balance</p>
            <span className="text--basic estimated estimated_amount">
              € 21.845.56
            </span>
          </div>
        </div>
        <SearchCollapse />
        <MovementsTable />
      </section>
      <Sidebar />
    </main>
  );
};

export default Balance;
