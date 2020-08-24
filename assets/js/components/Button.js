import React from 'react';

class Button extends React.Component {
  render() {
    return (
      <button className={`btn ${this.props.custom}`}>
        {this.props.children}
        {this.props.text}
      </button>
    );
  }
}

Button.defaultProps = {
  custom: 'btn-secondary btn-round',
  text: '',
};

export default Button;
