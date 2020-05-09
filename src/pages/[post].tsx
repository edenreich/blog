import * as React from 'react';
import Head from 'next/head';
import axios from 'axios';
import { Article } from '@/interfaces/article';

interface IProps {
  article: Article;
}


class Post extends React.Component<IProps> {

  static async getInitialProps() {
    const res = await axios.get('http://localhost:3000/api/articles');

    return { article: res.data[0] };
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
              <h3>{ this.props.article.title }<span className="date"> - date: { this.props.article.published_at }</span></h3>
            </div>
          </div>
        </section>
        <section className="content__section">
          <div className="content__wrapper grid-content-wrapper">
            <div className="grid-column">
              <article>
                <p>{ this.props.article.content }</p>
              </article>
            </div>
          </div>
        </section>
      </div>
    );
  }

}

export default Post;
