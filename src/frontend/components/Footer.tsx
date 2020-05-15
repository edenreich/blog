
import * as React from 'react';
import { FaFacebook, FaGithub, FaInstagram, FaLinkedin } from 'react-icons/fa';

import './Footer.scss';

interface IProps {
  className: string;
}

class Footer extends React.Component<IProps> {

  render(): JSX.Element {
    return (
      <footer className={this.props.className + ' footer'}>
        <div className="social-media">
          <ul>
            <li><a href="https://www.facebook.com/eden.reich.7" target="_blank"><FaFacebook size="40px" /></a></li>
            <li><a href="https://github.com/edenreich" target="_blank"><FaGithub color="gray" size="40px" /></a></li>
            <li><a href="https://www.instagram.com/eden.reich.7" target="_blank"><FaInstagram color="pink" size="40px" /></a></li>
            <li><a href="https://www.linkedin.com/in/eden-reich-411020100" target="_blank"><FaLinkedin size="40px" /></a></li>
          </ul>
        </div>
        <div className="copy-rights">
          <small>© 2020 Eden Reich. All rights reserved.</small>
        </div>
      </footer>
    );
  }

}

export default Footer;