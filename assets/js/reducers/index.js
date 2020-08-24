import { combineReducers } from 'redux';
import { reducer as formReducer } from 'redux-form';
import movementsReducer from './movementsReducer';

export default combineReducers({
  movements: movementsReducer,
  form: formReducer, // reducer created by redux-form
});
