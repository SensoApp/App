import { combineReducers } from 'redux';

import movementsReducer from './movementsReducer';

export default combineReducers({
  movements: movementsReducer,
});
