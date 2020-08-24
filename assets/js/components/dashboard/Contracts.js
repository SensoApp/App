import React from 'react';

import SearchForm from './SearchForm';
import Sidebar from './Sidebar.js';
import Movements from './Movements.js';
import ExcelExport from '../ExcelExport.js';

/*********
Second tab of the DASHBOARD 
Créer un tableau qui reprend les contrats passés et actifs de l'utilisateur
          Données triées du plus récent au plus ancien
          Permettre un tri par ordre alphabétique
          Format mobile : une carte contenant => 
              NOM CLIENT
              Dates du contrat (from x to y)
              rate: x%
*********/

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

        </div>
        <Movements />
      </section>
      <Sidebar />
    </main>
  );
};

export default Contracts;
