import * as React from 'react';
import Head from 'next/head';

import { Article } from '@/interfaces/article';
import Link from 'next/link';
import moment from 'moment';
import ReactMarkDown from 'react-markdown';

import getConfig from 'next/config';
import axios, { AxiosResponse } from 'axios';

import './index.scss';

interface IProps {
  articles?: Article[];
}

class IndexPage extends React.Component<IProps> {

  static async getInitialProps(): Promise<any> {
    const { publicRuntimeConfig } = getConfig();

    const response: AxiosResponse = await axios.get(`${publicRuntimeConfig.apis.default.url}/articles?_sort=created_at:DESC`);
    const articles: Article[] = response.data;

    return { articles };
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
                this.props.articles?.filter((article: Article) => article.published).map((article: Article, key: number) => {
                  return (
                    <article key={key}>
                      <h3>{article.title}<span className="date"> - date: {moment(article.updated_at).fromNow()}</span></h3>
                      <ReactMarkDown source={article.content.substring(0, 300)} escapeHtml={false} />
                      <div className="home__text-preview">
                        <Link href={`${article.slug}`}><a className="button-primary">read more..</a></Link>
                      </div>
                    </article>
                  );
                })
              }
            </div>
          </div>
        </section>
      </div>
    );
  }

}

export default IndexPage;
