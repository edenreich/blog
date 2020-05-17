
import * as React from 'react';
import { AiFillLike, AiFillDislike, AiFillHeart } from 'react-icons/ai';
import axios, { AxiosResponse, AxiosRequestConfig } from 'axios';
import getConfig from 'next/config';

import './Reactions.scss';

type selection = 'like' | 'love' | 'dislike' | null;

const { publicRuntimeConfig } = getConfig();

interface IProps {
  articleId: string | undefined;
  visitor: string | undefined;
} 

interface IState {
  like: number;
  love: number;
  dislike: number;
  selected: selection;
}

const axiosConfig: AxiosRequestConfig = { 
  headers: { 
    Host: publicRuntimeConfig.apis.default.hostname
  } 
};

class Reactions extends React.Component<IProps, IState> {

  constructor(props: IProps) {
    super(props);

    this.state = {
      like: 0,
      love: 0,
      dislike: 0,
      selected: null
    };

    axios.get(`/api/likes/count?article=${this.props.articleId}`, axiosConfig).then((response: AxiosResponse) => {
      this.setState({
        like: response.data.like,
        love: response.data.love,
        dislike: response.data.dislike,
        selected: null
      })
    });
  }

  async handleReaction(event: React.MouseEvent): Promise<void> {
    const reactionType: any = event.currentTarget.id;
    const { articleId, visitor } = this.props;

    let response: AxiosResponse;
    let payload: any;

    switch (reactionType) {
      case 'like':
        payload = {
          uuid: visitor,
          ip_address: '127.0.0.1',
          reaction_type: 'like',
          article: articleId
        };

        await axios.post('/api/likes', payload, axiosConfig);
      break;
      case 'love':
        payload = {
          uuid: visitor,
          ip_address: '127.0.0.1',
          reaction_type: 'love',
          article: articleId
        };

        await axios.post('/api/likes', payload, axiosConfig);
      break;
      case 'dislike':
        payload = {
          uuid: visitor,
          ip_address: '127.0.0.1',
          reaction_type: 'dislike',
          article: articleId
        };

        await axios.post('/api/likes', payload, axiosConfig);
      break;
    }

    response = await axios.get(`/api/likes/count?article=${articleId}`, axiosConfig);

    this.setState({
      like: response.data.like,
      love: response.data.love,
      dislike: response.data.dislike,
      selected: reactionType
    });
    
  }

  render(): JSX.Element {
    return (
      <div className="reactions">
        <div className="reactions__reaction">
          <AiFillLike id="like" size="20px" cursor={'pointer'} onClick={(event) => this.handleReaction(event)} style={{color: this.state.selected == 'like' ? 'blue' : '#3b3d3c'}} />
          <div className="reactions__reaction--count">
            {this.state.like}
          </div>
        </div>
        <div className="reactions__reaction">
          <AiFillHeart id="love" size="20px" cursor={'pointer'} onClick={(event) => this.handleReaction(event)} style={{color: this.state.selected == 'love' ? 'blue' : '#3b3d3c'}} />
          <div className="reactions__reaction--count">
            {this.state.love}
          </div>
        </div>
        <div className="reactions__reaction">
          <AiFillDislike id="dislike" size="20px" cursor={'pointer'} onClick={(event) => this.handleReaction(event)} style={{color: this.state.selected == 'dislike' ? 'blue' : '#3b3d3c'}} />
          <div className="reactions__reaction--count">
            {this.state.dislike}
          </div>
        </div>
      </div>
    );
  }

}

export default Reactions;