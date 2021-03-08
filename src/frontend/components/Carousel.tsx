import * as React from 'react';
import Slider, { Settings } from 'react-slick';

import 'slick-carousel/slick/slick-theme.scss';
import 'slick-carousel/slick/slick.scss';
import './Carousel.module.scss';

interface IProps {
  children?: React.ReactNode[];
}

class Carousel extends React.Component<IProps> {

  render(): JSX.Element {
    const settings: Settings = {
      dots: true,
      infinite: true,
      speed: 500,
      slidesToShow: 3,
      slidesToScroll: 1,
      autoplay: true
    };
    return (
      <Slider {...settings}>
        {this.props.children}
      </Slider>
    );
  }

}

export default Carousel;
