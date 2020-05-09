import * as React from 'react';

import './Layout.scss';

class Layout extends React.Component {

  render(): JSX.Element {
    return (
      <div className="grid-layout">
        {this.props.children}
      </div>
    );
  }

}

export default Layout;
