import * as React from 'react';
import Link from 'next/link';

import './Navigation.scss';

interface LinkInterface {
  id: number;
  name: string;
  href: string;
}

interface IProps {
  className: string;
  brand?: string;
  links?: LinkInterface[];
  active?: string;
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
          className={this.props.active === link.href ? 'active' : ''}
        >
          <Link href={link.href}>
            <a>{link.name}</a>
          </Link>
        </li>
      );
    });

    return (
      <nav role="navigation" className={this.props.className + (this.state.opened ? ' navigation--opened' : ' navigation')}>
        <button type="button" className="grid-button navigation__button" onClick={() => this.toggleMenu()}>
          <i className="navigation__button--line"></i>
          <i className="navigation__button--line"></i>
          <i className="navigation__button--line"></i>
        </button>
        <div className="grid-brand navigation__brand">
          <Link href={'/'}>
            <a>{this.props.brand}</a>
          </Link>
        </div>
        <ul className="grid-links navigation__links">
          {linkFragments}
        </ul>
        <div className={this.state.opened ? 'navigation__overlay--show' : 'navigation__overlay'} onClick={() => this.closeMenu()}></div>
      </nav>
    );
  }

}

export default Navigation;
