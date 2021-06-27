import * as React from 'react';
import Link from 'next/link';

import styles from './Navigation.module.scss';

interface LinkInterface {
  id: number;
  name: string;
  href: string;
}

interface IProps {
  brand?: string;
  links?: LinkInterface[];
  current: string;
}

interface IState {
  opened: boolean;
}

class Navigation extends React.Component<IProps, IState> {

  constructor(props: IProps) {
    super(props);

    this.state = {
      opened: false
    };
  }

  toggleMenu(): void {
    if (this.state.opened) {
      this.setState({ opened: false });
    } else {
      this.setState({ opened: true });
    }
  }

  closeMenu(): void {
    this.setState({ opened: false });
  }

  render(): JSX.Element {
    const linkFragments: JSX.Element[] | undefined = this.props.links?.map((link: LinkInterface) => {
      return (
        <li
          key={link.id}
          className={this.props.current === link.href ? styles.navigation__links__active : ''}
        >
          <Link href={link.href}>
            <a>{link.name}</a>
          </Link>
        </li>
      );
    });

    return (
      <nav role="navigation" className={this.state.opened ? `${styles.navigation} ${styles.navigation__opened}` : styles.navigation}>
        <button type="button" className={styles.navigation__button} onClick={() => this.toggleMenu()}>
          <i className={styles.navigation__button__line}></i>
          <i className={styles.navigation__button__line}></i>
          <i className={styles.navigation__button__line}></i>
        </button>
        <div className={styles.navigation__brand}>
          <Link href={'/'}>
            <a>{this.props.brand}</a>
          </Link>
        </div>
        <ul className={this.state.opened ? `${styles.navigation__links} ${styles.navigation__opened__links}` : styles.navigation__links}>
          {linkFragments}
        </ul>
        <div className={this.state.opened ? `${styles.navigation__overlay} ${styles.navigation__overlay__show}` : styles.navigation__overlay} onClick={() => this.closeMenu()}></div>
      </nav>
    );
  }

}

export default Navigation;
