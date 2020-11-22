import * as React from 'react';
import Link from 'next/link';

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
        <Link href={this.props.href}>
          <a><img src={this.props.image} /></a>
        </Link>
      </div>
    );
  }

}

export default Slide;
