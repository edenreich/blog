
import * as React from 'react';
import Link from 'next/link';
import { FaFacebook, FaGithub, FaInstagram, FaLinkedin } from 'react-icons/fa';

import styles from './Footer.module.scss';

class Footer extends React.Component {

  render(): JSX.Element {
    return (
      <footer className={styles.footer}>
        <div className={styles.footer__social_media}>
          <ul>
            <li>
              <Link href="https://www.facebook.com/eden.reich.7">
                <a target="_blank"><FaFacebook color="blue" size="40px" /></a>
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
                <a target="_blank"><FaLinkedin color="blue" size="40px" /></a>
              </Link>
            </li>
          </ul>
        </div>
        <div className={styles.footer__copy_rights}>
          <small>© 2020 Eden Reich.</small>
        </div>
      </footer>
    );
  }

}

export default Footer;
