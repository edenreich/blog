import * as React from 'react';
import { NextPageContext } from 'next';
import Head from 'next/head';
import axios, { AxiosResponse } from 'axios';
import { Article } from '@/interfaces/article';
import getConfig from 'next/config';
import moment from 'moment';
import ReactMarkDown from 'react-markdown';

const { serverRuntimeConfig } = getConfig();

interface IProps {
  article: Article | null;
}

class Post extends React.Component<IProps> {

  static async getInitialProps(ctx: NextPageContext): Promise<IProps> {
    let article: Article | null;
    
    try {
      const response: AxiosResponse = await axios.get(`${serverRuntimeConfig.apis.default}/articles/${ctx.query.article}`);
      article = response.data;
    } catch {
      article = null;
    }

    return { article: article };
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
              <h3>{this.props.article?.title || 'No Title'}<span className="date"> - date: {moment(this.props.article?.published_at).fromNow()}</span></h3>
            </div>
          </div>
        </section>
        <section className="content__section">
          <div className="content__wrapper grid-content-wrapper">
            <div className="grid-column">
              <article>
                <ReactMarkDown source={this.props.article?.content}/>
              </article>
            </div>
          </div>
        </section>
      </div>
    );
  }

}

export default Post;
