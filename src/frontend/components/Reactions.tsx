
import * as React from 'react';
import { AiFillLike, AiFillDislike, AiFillHeart } from 'react-icons/ai';

import './Reactions.scss';

type selection = 'like' | 'love' | 'dislike' | null;

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

class Reactions extends React.Component<IProps, IState> {

  constructor(props: IProps) {
    super(props);

    this.state = {
      like: 2,
      love: 5,
      dislike: 4,
      selected: null
    }
  }

  handleReaction(event: React.MouseEvent): void {
    const reactionType: any = event.currentTarget.id;
    // const { articleId, visitor } = this.props;

    switch (reactionType) {
      case 'like':
        // send: { id: visitor, type: 'like', article_id: articleId } to api and set state after response
        // retrieve: total likes
        this.setState({like: this.state.like+1}); 
      break;
      case 'love':
        // send love to api and set state after response
        this.setState({love: this.state.love+1}); 
      break;
      case 'dislike':
        // send dislike to api and set state after response
        this.setState({dislike: this.state.dislike+1}); 
      break;
    }

    this.setState({selected: reactionType});
    
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