import * as React from 'react';
import { NextPageContext } from 'next';
import getConfig from 'next/config';
import Head from 'next/head';
import moment from 'moment';
import Prism from "prismjs";
import "prismjs/themes/prism.css";
import ReactMarkDown from 'react-markdown';
import axios, { AxiosResponse, AxiosRequestConfig } from 'axios';
import { Article as IArticle } from '@/interfaces/article';
import Reactions from '@/components/Reactions';

const { publicRuntimeConfig } = getConfig();

interface IProps {
  visitor: string | undefined;
  article: IArticle | null;
}

class Article extends React.Component<IProps> {

  static async getInitialProps(ctx: NextPageContext): Promise<IProps> {
    const cookie: string | undefined = ctx.req?.headers.cookie; 
    let visitor: string | undefined = cookie?.substring(cookie?.indexOf('=')+1, cookie?.length);
    const config = publicRuntimeConfig;
    let article: IArticle | null;
    let axiosConfig: AxiosRequestConfig = {};

    if (config.app.env === 'development') {
      process.env.NODE_TLS_REJECT_UNAUTHORIZED = '0';
      axiosConfig = {
        headers: { 
          Host: config.apis.default.hostname
        } 
      };
    }

    try {
      const response: AxiosResponse = await axios.get(`${publicRuntimeConfig.apis.default.ip}/articles/${ctx.query.article}`, axiosConfig);
      article = response.data;
    } catch {
      article = null;
    }

    return { visitor, article };
  }

  componentDidMount(): void {
    Prism.highlightAll();
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
                <ReactMarkDown source={this.props.article?.content} escapeHtml={false} />
              </article>
              <Reactions articleId={this.props.article?.id} visitor={this.props.visitor} />
            </div>
          </div>
        </section>
      </div>
    );
  }

}

export default Article;
