import React from 'react';
import { BrowserRouter as Router, Route } from 'react-router-dom';

import Homepage from './homepage/Homepage';
import Header from './Header';
import Footer from './Footer';
import Balance from './dashboard/Balance';
import Contracts from './dashboard/Contracts';
import Invoices from './dashboard/Invoices';
import Profile from './dashboard/Profile';

const App = () => {
  // urlPrefix for development purposes; routes to be defined
  const urlPrefix = '/frontend';
  return (
    <React.Fragment>
      <Router>
        <div>
          <Header />
          <Route path={`${urlPrefix}/`} exact component={Homepage} />
          <Route path={`${urlPrefix}/dashboard`} exact component={Balance} />
          <Route path={`${urlPrefix}/contracts`} exact component={Contracts} />
          <Route path={`${urlPrefix}/invoices`} exact component={Invoices} />
          <Route path={`${urlPrefix}/profile`} exact component={Profile} />
          <Footer />
        </div>
      </Router>
    </React.Fragment>
  );
};

export default App;
