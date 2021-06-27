import * as React from 'react';
import moment from 'moment';
import Link from 'next/link';
import { asset } from '@/utils/asset';
import { Article } from '@/interfaces/article';

import styles from './PublishedArticles.module.scss';

interface IProps {
  children?: React.ReactNode[];
  articles: Article[];
}

class PublishedArticles extends React.Component<IProps> {

  render(): JSX.Element[] {
    return (
      this.props.articles?.map((article: Article) => {
        return (
          <article className={styles.article} key={article.id}>
            <div className={styles.article__title}>
              <img src={`${asset('')}`} />
              <h3>{article.title}</h3>
              <span className={styles.article__date}><small>{moment(article.published_at).fromNow()}</small></span>
            </div>
            <div className={styles.article__content} dangerouslySetInnerHTML={{__html: article.content.substring(0, 300)}} />
            <div className={styles['article__text-preview']}>
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
