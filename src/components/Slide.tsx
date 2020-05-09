import * as React from 'react';

interface IProps {
  image: string;
  href: string;
  width?: string;
  height?: string;
}

class Slide extends React.Component<IProps> {

  render(): JSX.Element {
    return (
      <div>
        <a href={this.props.href}><img src={this.props.image} /></a>
      </div>
    );
  }

}

export default Slide;
