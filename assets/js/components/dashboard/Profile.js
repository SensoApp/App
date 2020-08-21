import React from 'react';

import Sidebar from './Sidebar.js';

/***************
        Créer encadré qui reprenne: nom prénom, addresse, mail, n° de téléphopne, n° de compte (BIC + IBAN) (=> données modifiables? )
        + section en dessous pour permettre de reset le password
***************/

const Profile = () => {
  return (
    <main className="grid-container">
      <section className="content-section">
        <hgroup className="headings">
          <h1 className="heading-huge heading-huge--blue">Username</h1>
          <h2 className="heading-large heading-large--green font-italic">
            My Profile
          </h2>
        </hgroup>
        
      </section>
      <Sidebar />
    </main>
  );
};

export default Profile;
