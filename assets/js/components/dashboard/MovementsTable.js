import React from 'react';
import TableTemplate from '../TableTemplate';

import stringDate from '../../helpers/stringDate';
import positiveNegative from '../../helpers/positiveNegative';

const MovementsTable = (props) => {
  console.log(props);
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
      Cell: ({ row }) => stringDate(row.original.operationdate),
    },
    {
      Header: 'Amount (€)',
      accessor: 'amount',
      Cell: ({ row }) => positiveNegative(row.original.amount),
    },
  ]);

  return <TableTemplate columns={columns} data={data} />;
};

export default MovementsTable;
