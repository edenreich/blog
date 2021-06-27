import * as React from 'react';

import './ProgressBar.module.scss';

interface IProps {
  children?: React.ReactNode;
  label: string;
  progress: number;
  color: string;
}

class ProgressBar extends React.Component<IProps> {

  render(): JSX.Element {
    return (
      <li>
        <p>{this.props.label}</p>
        <div className={'progressbar line border ' + this.props.color} data-value={this.props.progress}>
          <svg viewBox="0 0 100 3" preserveAspectRatio="none" style={{ display: 'block', width: '100%' }}>
            <path d="M 0,1.5 L 100,1.5" stroke="#eee" strokeWidth={3} fillOpacity={0} />
            <path d="M 0,1.5 L 100,1.5" stroke="#555" strokeWidth={3} fillOpacity={0} style={{ strokeDasharray: '100, 100', strokeDashoffset: 100 - this.props.progress }} />
          </svg>
          <div className="progressbar-text" style={{ color: 'inherit', position: 'absolute', right: 0, top: '-30px', padding: 0, margin: 0 }}>{this.props.progress} %</div>
        </div>
      </li>
    );
  }

}

export default ProgressBar;
