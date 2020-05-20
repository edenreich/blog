import * as React from 'react';
import Head from 'next/head';

import ProgressBar from '../components/ProgressBar';
import Carousel from '../components/Carousel';
import Slide from '../components/Slide';
import Lazyload from 'react-lazyload';

import './about.scss';

interface IProps {
  children?: React.ReactNode;
  route: string;
}

class AboutPage extends React.Component<IProps> {

  render(): JSX.Element {
    return (
      <div id="about" className="about">
        <Head>
          <title>Blog | About Page</title>
          <meta charSet="utf-8" />
          <meta name="viewport" content="initial-scale=1.0, width=device-width" />
          <meta name="author" content="Eden Reich" />
          <meta name="keywords" content="Eden,Eden Reich,PHP,C++,Typescript,Javascript,CPP,Go,Web" />
          <meta name="description" content="welcome to my blog, here you may find interesting content about web app development." />
        </Head>
        <section className="content__section">
          <div className="content__wrapper grid-content-wrapper">
            <div className="grid-column">
              <h2 className="section-title text-center">
                About Me
              </h2>
              <figure>
                <img className="about__me" src="pictures/profile_600.png" />
              </figure>
              <h3>Who am I?</h3>
              <p className="lead">
                I’m a passionate developer specializing in C++, PHP, Typescript, Javascript, Rust, CSS and some Go. I like to build desktop as well as web applications.
                I'm based in Berlin.
              </p>
              <h3>My Skills</h3>
              <ul className="progress-list">
                <ProgressBar label={'PHP'} color={'blue'} progress={95} />
                <ProgressBar label={'Typescript'} color={'blue'} progress={85} />
                <ProgressBar label={'CSS'} color={'purple'} progress={85} />
                <ProgressBar label={'Javascript'} color={'yellow'} progress={85} />
                <ProgressBar label={'C++'} color={'pink'} progress={90} />
                <ProgressBar label={'Rust'} color={'brown'} progress={85} />
                <ProgressBar label={'SQL'} color={'pink'} progress={85} />
                <ProgressBar label={'Go'} color={'aqua'} progress={65} />
              </ul>
            </div>
          </div>
        </section>
        <section className="content__section">
          <div className="content__wrapper grid-content-wrapper">
            <div className="grid-column">
              <h2>Instagram</h2>
              <Carousel>
                <Lazyload>
                  <Slide
                    image="https://scontent-ber1-1.cdninstagram.com/v/t51.2885-15/e35/53109565_338009383489138_2071617065123033113_n.jpg?_nc_ht=scontent-ber1-1.cdninstagram.com&_nc_cat=110&_nc_ohc=1gcZGNFEl6kAX9V8elg&oh=52fa176076a255ab9809a2d43a9184bf&oe=5ED55FC7"
                    href="https://scontent-ber1-1.cdninstagram.com/v/t51.2885-15/e35/53109565_338009383489138_2071617065123033113_n.jpg?_nc_ht=scontent-ber1-1.cdninstagram.com&_nc_cat=110&_nc_ohc=1gcZGNFEl6kAX9V8elg&oh=52fa176076a255ab9809a2d43a9184bf&oe=5ED55FC7"
                  />
                </Lazyload>
                <Lazyload>
                  <Slide
                    image="https://scontent-ber1-1.cdninstagram.com/v/t51.2885-15/e35/54513301_651181831980437_5306293933786417294_n.jpg?_nc_ht=scontent-ber1-1.cdninstagram.com&_nc_cat=105&_nc_ohc=1gnwOISxotgAX-_L58S&oh=720ce935b80b1e9fd4d26ac42756062b&oe=5ED8BECE"
                    href="https://scontent-ber1-1.cdninstagram.com/v/t51.2885-15/e35/54513301_651181831980437_5306293933786417294_n.jpg?_nc_ht=scontent-ber1-1.cdninstagram.com&_nc_cat=105&_nc_ohc=1gnwOISxotgAX-_L58S&oh=720ce935b80b1e9fd4d26ac42756062b&oe=5ED8BECE"
                  />
                </Lazyload>
                <Lazyload>
                  <Slide
                    image="https://scontent-ber1-1.cdninstagram.com/v/t51.2885-15/e35/53431936_2178274215821224_3031523814579311667_n.jpg?_nc_ht=scontent-ber1-1.cdninstagram.com&_nc_cat=104&_nc_ohc=FKY-EeAJ5aUAX-DFwNs&oh=4453934ccd9c69d4594e73a141936000&oe=5ED5A8EA"
                    href="https://scontent-ber1-1.cdninstagram.com/v/t51.2885-15/e35/53431936_2178274215821224_3031523814579311667_n.jpg?_nc_ht=scontent-ber1-1.cdninstagram.com&_nc_cat=104&_nc_ohc=FKY-EeAJ5aUAX-DFwNs&oh=4453934ccd9c69d4594e73a141936000&oe=5ED5A8EA"
                  />
                </Lazyload>
                <Lazyload>
                  <Slide
                    image="https://scontent-ber1-1.cdninstagram.com/v/t51.2885-15/e35/41831975_167550757497913_131146595508302785_n.jpg?_nc_ht=scontent-ber1-1.cdninstagram.com&_nc_cat=101&_nc_ohc=7RsR499mEcQAX80JSac&oh=a58020731a99aaaa302f604ccf2bb389&oe=5ED7F89A"
                    href="https://scontent-ber1-1.cdninstagram.com/v/t51.2885-15/e35/41831975_167550757497913_131146595508302785_n.jpg?_nc_ht=scontent-ber1-1.cdninstagram.com&_nc_cat=101&_nc_ohc=7RsR499mEcQAX80JSac&oh=a58020731a99aaaa302f604ccf2bb389&oe=5ED7F89A"
                  />
                </Lazyload>
                <Lazyload>
                <Slide
                  image="https://scontent-ber1-1.cdninstagram.com/v/t51.2885-15/e35/67427029_861149417589964_1109692469947108598_n.jpg?_nc_ht=scontent-ber1-1.cdninstagram.com&_nc_cat=104&_nc_ohc=U_8AQlYhxwEAX9kKPov&oh=94146e911b2daecf6e19fa83ecc973ea&oe=5ED7CFFC"
                  href="https://scontent-ber1-1.cdninstagram.com/v/t51.2885-15/e35/67427029_861149417589964_1109692469947108598_n.jpg?_nc_ht=scontent-ber1-1.cdninstagram.com&_nc_cat=104&_nc_ohc=U_8AQlYhxwEAX9kKPov&oh=94146e911b2daecf6e19fa83ecc973ea&oe=5ED7CFFC"
                />
                </Lazyload>
              </Carousel>
            </div>
          </div>
        </section>
      </div>
    );
  }

}

export default AboutPage;
