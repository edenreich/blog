import * as React from 'react';
import { asset } from '@/utils/asset';
import { Article } from '@/interfaces/article';

import './UpcomingArticles.scss';

interface IProps {
  children?: React.ReactNode[];
  articles: Article[];
}

class UpcomingArticles extends React.Component<IProps> {

  render(): JSX.Element[] {
    return (
      this.props.articles?.map((article: Article, key: number) => {
        return (
          <article className="article" key={key}>
            <div className="article__title">
              <img src={`${asset(article.meta_thumbnail.formats.thumbnail?.url)}`} />
              <h3>{article.title}</h3>
            </div>
          </article>
        );
      })
    );
  }
}

export default UpcomingArticles;
