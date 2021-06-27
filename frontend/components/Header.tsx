
import * as React from 'react';

import styles from './Header.module.scss';

class Header extends React.Component {

  render(): JSX.Element {
    return (
      <header className={styles.header}>
        {this.props.children}
      </header>
    );
  }

}

export default Header;
