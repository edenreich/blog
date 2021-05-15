
import * as React from 'react';

import styles from './Section.module.scss';

class Section extends React.Component {

  render(): JSX.Element {
    return (
      <section className={styles.section}>
        <div className={styles.section__wrapper}>
          <div className={styles.section__column}>
            {this.props.children}
          </div>
        </div>
      </section>
    );
  }

}

export default Section;
