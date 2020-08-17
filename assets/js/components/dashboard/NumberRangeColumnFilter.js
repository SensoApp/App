import React from 'react';

function NumberRangeColumnFilter({
  column: { filterValue = [], preFilteredRows, setFilter, id },
}) {
  const [min, max] = React.useMemo(() => {
    let min = preFilteredRows.length ? preFilteredRows[0].values[id] : 0;
    let max = preFilteredRows.length ? preFilteredRows[0].values[id] : 0;
    preFilteredRows.forEach((row) => {
      min = Math.min(row.values[id], min);
      max = Math.max(row.values[id], max);
    });
    return [min, max];
  }, [id, preFilteredRows]);

  return (
    <fieldset className="form-group">
      <legend className="legend">Amount between :</legend>
      <input
        className="form-control form__input"
        id="min"
        aria-describedby="min amount"
        placeholder="Min"
        value={filterValue[0] || ''}
        type="number"
        onChange={(e) => {
          const val = e.target.value;
          setFilter((old = []) => [
            val ? parseInt(val, 10) : undefined,
            old[1],
          ]);
        }}
      />
      <label htmlFor="min" className="form__label">
        Min
      </label>
      <input
        className="form-control form__input"
        id="max"
        aria-describedby="min amount"
        placeholder="Max"
        value={filterValue[1] || ''}
        type="number"
        onChange={(e) => {
          const val = e.target.value;
          setFilter((old = []) => [
            old[0],
            val ? parseInt(val, 10) : undefined,
          ]);
        }}
      />
      <label htmlFor="max" className="form__label">
        Max
      </label>
    </fieldset>
  );
}

export default NumberRangeColumnFilter;
