import movements from '../apis/movements';
import { FETCH_MOVEMENT, FETCH_MOVEMENTS, FILTER_MOVEMENTS } from './types';

export const fetchMovements = () => {
  return async (dispatch) => {
    const response = await movements.get('/statement_files.json');
    const data = response.data;
    // Transform string to date
    data.forEach((movement) => {

      movement.operationdate = new Date(movement.operationdate);
      return movement;
    });

    dispatch({ type: FETCH_MOVEMENTS, payload: data });
  };
};

export const fetchMovement = (id) => {
  return async (dispatch) => {
    const response = await movements.get(`/statement_files.json/${id}`);
    dispatch({ type: FETCH_MOVEMENT, payload: response.data });
  };
};

export const filterMovements = () => {
  return {
    type: FILTER_MOVEMENTS,
    payload: filtered,
  };
};
