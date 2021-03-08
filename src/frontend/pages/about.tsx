import * as React from 'react';
import Head from 'next/head';

import ProgressBar from '../components/ProgressBar';

import './about.module.scss';

interface IProps {
  children?: React.ReactNode;
  route: string;
}

class AboutPage extends React.Component<IProps> {

  render(): JSX.Element {
    const title = 'Blog | About';
    const description = 'Welcome to my blog, I\'ll be posting about web app development, native apps, DevOps and more.';

    return (
      <div id="about" className="about">
        <Head>
          <title>{title}</title>
          <meta charSet="utf-8" />
          <meta name="viewport" content="initial-scale=1.0, width=device-width" />
          <meta name="author" content="Eden Reich" />
          <meta name="keywords" content="Eden,Eden Reich,PHP,C++,Typescript,Javascript,CPP,Go,Web" />
          <meta name="description" content={description} />
          <meta property="og:site_name" content="Eden Reich" />
          <meta property="og:title" content={title} />
          <meta property="og:image" content="/pictures/profile_600.png" />
          <meta property="og:description" content={description} />
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
      </div>
    );
  }

}

export default AboutPage;
