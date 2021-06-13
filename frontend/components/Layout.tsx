import * as React from 'react';

import styles from './Layout.module.scss';

class Layout extends React.Component {

  render(): JSX.Element {
    return (
      <div className={styles.layout}>
        {this.props.children}
      </div>
    );
  }

}

export default Layout;
