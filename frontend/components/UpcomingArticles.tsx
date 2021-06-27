import * as React from 'react';
import { asset } from '@/utils/asset';
import { Article } from '@/interfaces/article';

import styles from './UpcomingArticles.module.scss';

interface IProps {
  children?: React.ReactNode[];
  articles: Article[];
}

class UpcomingArticles extends React.Component<IProps> {

  render(): JSX.Element[] {
    return (
      this.props.articles?.map((article: Article, key: number) => {
        return (
          <article className={styles.article} key={key}>
            <div className={styles.article__title}>
              <img src={`${asset(article.meta_thumbnail)}`} />
              <h3>{article.title}</h3>
            </div>
          </article>
        );
      })
    );
  }
}

export default UpcomingArticles;
