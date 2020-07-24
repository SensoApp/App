import React from 'react';
import { HashRouter, Route } from 'react-router-dom';
import CssBaseline from '@material-ui/core/CssBaseline';

import Homepage from './homepage/Homepage';
import Header from './navbar/Header';
import history from '../history';

const App = () => {
  return (
    <React.Fragment>
      <CssBaseline />
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
