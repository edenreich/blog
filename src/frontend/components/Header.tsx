
import * as React from 'react';

import './Header.module.scss';

interface IProps {
  className: string;
}

class Header extends React.Component<IProps> {

  render(): JSX.Element {
    return (
      <header className={'header ' + this.props.className}>
          {this.props.children}
      </header>
    );
  }

}

export default Header;
