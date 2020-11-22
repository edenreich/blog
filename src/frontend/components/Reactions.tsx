
import * as React from 'react';
import { AiFillLike, AiFillDislike, AiFillHeart } from 'react-icons/ai';
import axios, { AxiosResponse } from 'axios';
import getConfig from 'next/config';
import { IVisitor } from '@/interfaces/visitor';

import './Reactions.scss';

const { publicRuntimeConfig } = getConfig();

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

    axios.get(`${publicRuntimeConfig.app.url}/api/likes/count?article=${this.props.articleId}`).then((response: AxiosResponse) => {
      this.setState({
        like: response.data.like,
        love: response.data.love,
        dislike: response.data.dislike,
        selected: null
      });
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
      uuid: visitor.uuid,
      reaction_type: reactionType,
      article: articleId
    };

    await axios.post(`${publicRuntimeConfig.app.url}/api/likes`, payload, { headers: 'Content-Type: application/json' });
    const response: AxiosResponse = await axios.get(`${publicRuntimeConfig.app.url}/api/likes/count?article=${articleId}`);

    this.setState({
      like: response.data.like,
      love: response.data.love,
      dislike: response.data.dislike,
      selected: reactionType
    });
  }

  render(): JSX.Element {
    return (
      <div className="reactions grid-reactions">
        <div className="reactions__hint grid-hint">
          Did you find this article helpful ?
        </div>
        <div className="grid-icons">
          <div className="reactions__reaction">
            <AiFillLike id="like" size="20px" cursor={'pointer'} onClick={(event) => this.handleReaction(event)} style={{ color: this.state.selected === 'like' ? '#4c98c9' : '#9a8eb2' }} />
            <div className="reactions__reaction--count">
              {this.state.like}
            </div>
          </div>
          <div className="reactions__reaction">
            <AiFillHeart id="love" size="20px" cursor={'pointer'} onClick={(event) => this.handleReaction(event)} style={{ color: this.state.selected === 'love' ? '#e45050' : '#9a8eb2' }} />
            <div className="reactions__reaction--count">
              {this.state.love}
            </div>
          </div>
          <div className="reactions__reaction">
            <AiFillDislike id="dislike" size="20px" cursor={'pointer'} onClick={(event) => this.handleReaction(event)} style={{ color: this.state.selected === 'dislike' ? '#272727' : '#9a8eb2' }} />
            <div className="reactions__reaction--count">
              {this.state.dislike}
            </div>
          </div>
        </div>
      </div>
    );
  }

}

export default Reactions;
