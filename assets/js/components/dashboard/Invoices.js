import React from 'react';

import SearchForm from './SearchForm';
import Sidebar from './Sidebar.js';
import Movements from './Movements.js';
import ExcelExport from '../ExcelExport.js';

/********  
        Créer un tableau qui reprend les invoices de l'utilisateur
          Données triées du plus récent au plus ancien
          Permettre plusieurs options de tri des données
          Format mobile : reprendre le format des statements (movementcard)
          Export Excel
**********/

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
        <div className="buttons-container">
          <SearchForm />
          <ExcelExport />
        </div>
        <Movements />
        
        <div className="excel-container"></div>
      </section>
      <Sidebar />
    </main>
  );
};

export default Invoices;
