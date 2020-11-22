
import * as React from 'react';
import Link from 'next/link';
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
            <li>
              <Link href="https://www.facebook.com/eden.reich.7">
                <a target="_blank"><FaFacebook size="40px" /></a>
              </Link>
            </li>
            <li>
              <Link href="https://github.com/edenreich">
                <a target="_blank"><FaGithub color="gray" size="40px" /></a>
              </Link>
            </li>
            <li>
              <Link href="https://www.instagram.com/eden.reich.7">
                <a target="_blank"><FaInstagram color="pink" size="40px" /></a>
              </Link>
            </li>
            <li>
              <Link href="https://www.linkedin.com/in/eden-reich-411020100">
                <a target="_blank"><FaLinkedin size="40px" /></a>
              </Link>
            </li>
          </ul>
        </div>
        <div className="copy-rights">
          <small>© 2020 Eden Reich.</small>
        </div>
      </footer>
    );
  }

}

export default Footer;
