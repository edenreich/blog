
import * as React from 'react';
import { AiFillLike, AiFillDislike, AiFillHeart } from 'react-icons/ai';
import axios, { AxiosResponse } from 'axios';
import { IVisitor } from '@/interfaces/visitor';
import getConfig from 'next/config';

const { publicRuntimeConfig: { apis: { frontend } } } = getConfig();

import styles from './Reactions.module.scss';

type selection = 'like' | 'love' | 'dislike' | null;

interface IProps {
  articleId: string | undefined;
  visitor: IVisitor;
}

interface IState {
  like: number;
  love: number;
  dislike: number;
  selected: selection;
}

class Reactions extends React.Component<IProps, IState> {

  constructor(props: IProps) {
    super(props);

    this.state = {
      like: 0,
      love: 0,
      dislike: 0,
      selected: null
    };

    axios.get(`${frontend.url}/likes/count?article=${this.props.articleId}`, { headers: { 'Content-Type': 'application/json' } }).then((response: AxiosResponse) => {
      this.setState({
        like: response.data.like,
        love: response.data.love,
        dislike: response.data.dislike,
        selected: null
      });
    }).catch((error) => {
      console.error(`[components][reactions][constructor] ${JSON.stringify(error)}`);
    });
  }

  async handleReaction(event: React.MouseEvent): Promise<void> {
    const reactionType: any = event.currentTarget.id;
    const { articleId, visitor } = this.props;

    if (['like', 'love', 'dislike'].indexOf(reactionType) === -1) {
      console.warn('Invalid reaction type!');
      return;
    }

    const payload: any = {
      session: visitor.id,
      reaction_type: reactionType,
      article: articleId
    };

    try {
      await axios.post(`${frontend.url}/likes`, payload, { headers: { 'Content-Type': 'application/json' } });
      const response: AxiosResponse = await axios.get(`${frontend.url}/likes/count?article=${articleId}`, { headers: { 'Content-Type': 'application/json' } });
      this.setState({
        like: response.data.like,
        love: response.data.love,
        dislike: response.data.dislike,
        selected: reactionType
      });
    } catch (error) {
      console.error(`[components][reactions][handleReaction] ${JSON.stringify(error)}`);
    }
  }

  render(): JSX.Element {
    return (
      <div className={`${styles.reactions}`}>
        <div className={`${styles.reactions__hint}`}>
          Did you find this article helpful ?
        </div>
        <div className={`${styles.reactions__icons}`}>
          <div className={styles.reactions__reaction}>
            <AiFillLike id="like" size="20px" cursor={'pointer'} onClick={(event) => this.handleReaction(event)} style={{ color: this.state.selected === 'like' ? '#4c98c9' : '#9a8eb2' }} />
            <div className={styles.reactions__reaction__count}>
              {this.state.like}
            </div>
          </div>
          <div className={styles.reactions__reaction}>
            <AiFillHeart id="love" size="20px" cursor={'pointer'} onClick={(event) => this.handleReaction(event)} style={{ color: this.state.selected === 'love' ? '#e45050' : '#9a8eb2' }} />
            <div className={styles.reactions__reaction__count}>
              {this.state.love}
            </div>
          </div>
          <div className={styles.reactions__reaction}>
            <AiFillDislike id="dislike" size="20px" cursor={'pointer'} onClick={(event) => this.handleReaction(event)} style={{ color: this.state.selected === 'dislike' ? '#272727' : '#9a8eb2' }} />
            <div className={styles.reactions__reaction__count}>
              {this.state.dislike}
            </div>
          </div>
        </div>
      </div>
    );
  }

}

export default Reactions;
