import * as React from 'react';
import Head from 'next/head';

class IndexPage extends React.Component {

  render(): JSX.Element {
    return (
      <div id="home" className="home">
        <Head>
          <title>Blog | Home Page</title>
          <meta charSet="utf-8" />
          <meta name="viewport" content="initial-scale=1.0, width=device-width" />
          <meta name="author" content="Eden Reich" />
          <meta name="keywords" content="Eden,Eden Reich,PHP,C++,Typescript,Javascript,CPP,Go,Web" />
          <meta name="description" content="welcome to my blog, here you may find interesting content about web app development." />
        </Head>
        <section className="content__section">
          <div className="content__wrapper grid-content-wrapper">
            <div className="grid-column">
              <h2>Welcome</h2>
              <p>
                Welcome to my blog, here you may find interesting content about web app development.
                So if you are a developer or you just happened to visit this website randomly and want to bring your web experience to the next level, stay tuned ;)
              </p>
              </div>
          </div>
        </section>
        <section className="content__section">
          <div className="content__wrapper grid-content-wrapper">
            <div className="grid-column">
              <h2>Blog Feed</h2>
              <p>Take a look on the latested posted articles:</p>
              <article>
                <h3>Build your own Kubernetes Cluster (K3S)<span className="date"> - date: not published yet</span></h3>
                <p>Up comming...</p>
              </article>
            </div>
            {/* <article>
              <h3>Build a lightweight API with PHP using symfony<span className="date"> - date: not published yet</span></h3>
              <p>Up comming...</p>
            </article>
            <article>
              <h3>Great Weblayouts with CSS grids<span className="date"> - date: not published yet</span></h3>
              <p>Up comming...</p>
            </article>
            <article>
              <h3>Build a professional commandline interface with C++<span className="date"> - date: not published yet</span></h3>
              <p>Up comming...</p>
            </article>  */}
          </div>
        </section>
      </div>
    );
  }

}

export default IndexPage;
