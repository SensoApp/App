import React from 'react';
import ReactExport from 'react-data-export';
import { connect } from 'react-redux';

import Button from './Button';

const ExcelFile = ReactExport.ExcelFile;
const ExcelSheet = ReactExport.ExcelFile.ExcelSheet;
const ExcelColumn = ReactExport.ExcelFile.ExcelColumn;

class ExcelExport extends React.Component {

  render() {
    return (
      <ExcelFile
        element={<Button custom="btn-secondary" text="Export to Excel"  />}>
        <ExcelSheet data={this.props.movements} name="Statements">
          <ExcelColumn label="Reference" value="referencemovement" />
          <ExcelColumn label="Operation" value="operations" />
          <ExcelColumn label="Communication" value="communication" />
          <ExcelColumn
            label="Date"
            value="operationdate"
            style={{
              numFmt: 'm/dd/yy',
            }}
          />
          <ExcelColumn label="Amount" value="amount" />
        </ExcelSheet>
      </ExcelFile>
    );
  }
}

const mapStateToProps = (state) => {
  return {
    movements: state.movements,
  };
};

export default connect(mapStateToProps)(ExcelExport);
