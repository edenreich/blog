
import * as React from 'react';

import styles from './Content.module.scss';

class Content extends React.Component {

  render(): JSX.Element {
    return (
      <main className={styles.content}>
        {this.props.children}
      </main>
    );
  }

}

export default Content;
