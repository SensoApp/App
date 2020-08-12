import React from 'react';
import TableTemplate from '../TableTemplate';

const MovementsTable = (props) => {
  const data = props.data;
  const columns = React.useMemo(() => [
    {
      Header: 'Operation',
      accessor: 'operations',
    },
    {
      Header: 'Communication',
      accessor: 'communication',
    },
    {
      Header: 'Date',
      accessor: 'operationdate',
    },
    {
      Header: 'Amount',
      accessor: 'amount',
    },
  ]);

  return <TableTemplate columns={columns} data={data} />;
};

export default MovementsTable;
