import * as React from 'react';
import ReactMarkDown from 'react-markdown';
import moment from 'moment';
import Link from 'next/link';
import { asset } from '@/utils/asset';
import { Article } from '@/interfaces/article';

import './PublishedArticles.module.scss';

interface IProps {
  children?: React.ReactNode[];
  articles: Article[];
}

class PublishedArticles extends React.Component<IProps> {

  render(): JSX.Element[] {
    return (
      this.props.articles?.map((article: Article, key: number) => {
        return (
          <article className="article" key={key}>
            <div className="article__title">
              <img src={`${asset(article.meta_thumbnail.formats.thumbnail?.url)}`} />
              <h3>{article.title}</h3>
              <span className="article__date"><small>{moment(article.published_at).fromNow()}</small></span>
            </div>
            <div className="article__content">
              <ReactMarkDown source={article.content.substring(0, 300)} escapeHtml={false} />
            </div>
            <div className="article__text-preview">
              <Link href={`/${article.slug}`}>
                <a className="button-primary">Read more..</a>
              </Link>
            </div>
          </article>
        );
      })
    );
  }
}

export default PublishedArticles;
