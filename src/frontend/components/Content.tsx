
import * as React from 'react';

import './Content.module.scss';

interface IProps {
  className: string;
}

class Content extends React.Component<IProps> {

  render(): JSX.Element {
    return (
      <main className={'content ' + this.props.className}>
        {this.props.children}
      </main>
    );
  }

}

export default Content;
