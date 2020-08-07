import React from 'react';
import { BrowserRouter as Router, Route } from 'react-router-dom';

import Homepage from './homepage/Homepage';
import Header from './Header';
import Footer from './footer/Footer';
import Balance from './dashboard/Balance';
//import history from '../history';

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
          <Footer />
        </div>
      </Router>
    </React.Fragment>
  );
};

export default App;
