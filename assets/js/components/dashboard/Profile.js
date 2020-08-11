import React from 'react';

import Sidebar from './Sidebar.js';

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
