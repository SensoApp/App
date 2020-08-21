import React from 'react';
import TableTemplate from '../TableTemplate';

import stringDate from '../../helpers/stringDate';
import amountFormatting from '../../helpers/amountFormatting';

// Specific set up for the movements table sent to the TableTemplate
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
      Cell: ({ row }) => stringDate(row.original.operationdate),
    },
    {
      Header: 'Amount',
      accessor: 'amount',
      Cell: ({ row }) => amountFormatting(row.original.amount)

    },
  ]);

  return <TableTemplate columns={columns} data={data} />;
};

export default MovementsTable;
