import { FETCH_MOVEMENT, FETCH_MOVEMENTS } from '../actions/types';

export default (state = [], action) => {
  switch (action.type) {
    case FETCH_MOVEMENTS:
      return action.payload;
    case FETCH_MOVEMENT:
      return action.payload.id;
    default:
      return state;
  }
};
