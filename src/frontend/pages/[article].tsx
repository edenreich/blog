import * as React from 'react';
import { NextPageContext } from 'next';
import getConfig from 'next/config';
import Head from 'next/head';
import moment from 'moment';
import Prism from 'prismjs';
import 'prismjs/themes/prism.css';
import ReactMarkDown from 'react-markdown';
import axios, { AxiosResponse } from 'axios';
import { Article as IArticle } from '@/interfaces/article';
import Reactions from '@/components/Reactions';
import { IVisitor } from '@/interfaces/visitor';

const { publicRuntimeConfig } = getConfig();

import './article.scss';

interface IProps {
  visitor?: IVisitor;
  article: IArticle | null;
}

class Article extends React.Component<IProps> {

  static async getInitialProps(ctx: NextPageContext): Promise<IProps> {
    let article: IArticle | null;

    try {
      const response: AxiosResponse = await axios.get(`${publicRuntimeConfig.app.url}/api/articles/${ctx.query.article}`, { headers:  ctx?.req?.headers });
      article = response.data;
    } catch (error) {
      article = null;
      console.error(error);
    }

    return { article };
  }

  componentDidMount(): void {
    Prism.highlightAll();
  }

  render(): JSX.Element {
    return (
      <div id="article" className="article">
        <Head>
          <title>Blog | {this.props.article.title}</title>
          <meta charSet="utf-8" />
          <meta name="viewport" content="initial-scale=1.0, width=device-width" />
          <meta name="author" content="Eden Reich" />
          <meta name="keywords" content={this.props.article.meta_keywords} />
          <meta name="description" content={this.props.article.meta_description} />
          <meta property="og:site_name" content="Eden Reich" />
          <meta property="og:title" content={this.props.article.title} />
          <meta property="og:image" content={`${publicRuntimeConfig.apis.default.url}${this.props.article.meta_thumbnail.formats.thumbnail.url}`} />
          <meta property="og:description" content={this.props.article.meta_description} />
        </Head>
        <section className="content__section">
          <div className="content__wrapper grid-content-wrapper">
            <div className="grid-column">
              <span className="article__date"><small>{moment(this.props.article?.published_at).fromNow()}</small></span>
              <h3>{this.props.article?.title || 'No Title'}</h3>
            </div>
          </div>
        </section>
        <section className="content__section">
          <div className="content__wrapper grid-content-wrapper">
            <div className="grid-column">
              <article>
                <ReactMarkDown source={this.props.article?.content} escapeHtml={false} linkTarget="_blank" />
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
