import React from 'react';
import { HashRouter, Route } from 'react-router-dom';

import Homepage from './homepage/Homepage';
import Header from './navbar/Header';
import history from '../history';

const App = () => {
  return (
    <React.Fragment>
      <HashRouter history={history}>
        <div>
          <Header />
          <Route path="/" exact component={Homepage} />
        </div>
      </HashRouter>
    </React.Fragment>
  );
};

export default App;
