import React from 'react';

import SearchForm from './SearchForm';
import Sidebar from './Sidebar.js';
import Movements from './Movements.js';
import ExcelExport from '../ExcelExport.js';

const Contracts = () => {
  return (
    <main className="grid-container">
      <section className="content-section">
        <hgroup className="headings">
          <h1 className="heading-huge heading-huge--blue">Username</h1>
          <h2 className="heading-large heading-large--green font-italic">
            My Contracts
          </h2>
        </hgroup>
        <div className="buttons-container">
          <SearchForm />
          <ExcelExport />
        </div>
        <Movements />
      </section>
      <Sidebar />
    </main>
  );
};

export default Contracts;
