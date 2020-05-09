import * as React from 'react';
import Head from 'next/head';
import axios from 'axios';
import { Article } from '@/interfaces/article';
import Link from 'next/link';

interface IProps {
  articles?: Article[];
}


class IndexPage extends React.Component<IProps> {

  static async getInitialProps() {
    const res = await axios.get('http://localhost:3000/api/articles');

    return { articles: res.data };
  }

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
              <p>Take a look on the latest posted articles:</p>
              {
                this.props.articles?.map((article: Article, key: number) => {
                  return (
                    <article key={key}>
                      <h3>{ article.title }<span className="date"> - date: { article.published_at }</span></h3>
                      <p>{ article.content.substring(0, 200) } <Link href={article.link}><a>read more..</a></Link></p>
                    </article>
                  )
                })
              }
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
