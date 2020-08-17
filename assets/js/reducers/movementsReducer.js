import {
  FETCH_MOVEMENT,
  FETCH_MOVEMENTS,
  FILTER_MOVEMENTS,
} from '../actions/types';

export default (state = [], action) => {
  switch (action.type) {
    case FETCH_MOVEMENTS:
      console.log(action.payload);
      return action.payload;
    case FETCH_MOVEMENT:
      return action.payload.id;
    case FILTER_MOVEMENTS:
      return action.payload;
    default:
      return state;
  }
};
