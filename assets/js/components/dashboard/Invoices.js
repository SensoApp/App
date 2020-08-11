import React from 'react';

import SearchCollapse from './SearchCollapse';
import Sidebar from './Sidebar.js';
import MovementsTable from './MovementsTable.js';
import ExcelExport from '../ExcelExport.js';

const Invoices = () => {
  return (
    <main className="grid-container">
      <section className="content-section">
        <hgroup className="headings">
          <h1 className="heading-huge heading-huge--blue">Username</h1>
          <h2 className="heading-large heading-large--green font-italic">
            My Invoices
          </h2>
        </hgroup>
        <SearchCollapse />
        <MovementsTable />
        <div className="excel-container">
          <ExcelExport />
        </div>
      </section>
      <Sidebar />
    </main>
  );
};

export default Invoices;
