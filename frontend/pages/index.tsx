import * as React from 'react';
import Head from 'next/head';
import { Article } from '@/interfaces/article';
import Link from 'next/link';
import { GrClose } from 'react-icons/gr';
import { IoMdNotificationsOff, IoMdNotificationsOutline } from 'react-icons/io';
import Modal from 'react-modal';
import axios, { AxiosResponse } from 'axios';
import { IVisitor } from '@/interfaces/visitor';
import { INotification } from '@/interfaces/notification';
import Section from '@/components/Section';
import PublishedArticles from '@/components/PublishedArticles';
import UpcomingArticles from '@/components/UpcomingArticles';
import getConfig from 'next/config';

const { publicRuntimeConfig: { apis: { frontend } } } = getConfig();

import styles from './index.module.scss';

interface IProps {
  visitor?: IVisitor;
  publishedArticles?: Article[];
  upcomingArticles?: Article[];
}

interface IState {
  modalIsOpen: boolean;
  email: string;
  invalid: boolean;
  notification: INotification | null;
}

class IndexPage extends React.Component<IProps, IState> {

  static async getInitialProps(): Promise<any> {
    let publishedArticles: Article[];
    try {
      const response: AxiosResponse = await axios.get(`${frontend.url}/articles`);
      publishedArticles = response.data;
    } catch (error) {
      publishedArticles = [];
    }

    let upcomingArticles: Article[];
    try {
      const response: AxiosResponse = await axios.get(`${frontend.url}/articles/upcoming`);
      upcomingArticles = response.data;
    } catch (error) {
      upcomingArticles = [];
    }

    return { publishedArticles, upcomingArticles };
  }

  constructor(props: IProps) {
    super(props);

    this.state = {
      modalIsOpen: false,
      email: '',
      invalid: false,
      notification: null,
    };

    this.closeModal = this.closeModal.bind(this);
    this.setEmail = this.setEmail.bind(this);
    this.submitNotificationForm = this.submitNotificationForm.bind(this);
  }

  async componentDidMount(): Promise<void> {
    Modal.setAppElement('#home');
    try {
      const response: AxiosResponse = await axios.get(`/api/notifications/get?session_id=${this.props?.visitor.id}`);
      const notification: INotification = response.data;
      await this.setState({ notification });
    } catch (error) {
      console.warn(`[pages][index][componentDidMount] ${JSON.stringify(error)}`);
      await this.setState({});
    }
  }

  async openModal(event: React.MouseEvent): Promise<void> {
    event.preventDefault();

    if (this.state.notification?.is_enabled) {
      try {
        const notification: INotification = await axios.put('/api/notifications/remove', {
          session: this.props.visitor?.id,
          email: this.state.email,
          is_enabled: false,
        }, { headers: { 'Content-Type': 'application/json' } });
        this.setState({ notification });
      } catch (error) {
        console.error(`[pages][index][openModal] ${JSON.stringify(error)}`);
      }

      return;
    }

    this.setState({ modalIsOpen: true });
  }

  closeModal(event: React.MouseEvent): void {
    event.preventDefault();
    this.setState({ modalIsOpen: false });
  }

  setEmail(event: React.FormEvent<HTMLInputElement>): void {
    this.setState({ invalid: false });
    this.setState({ email: event.currentTarget.value });
  }

  async submitNotificationForm(event: React.FormEvent<HTMLFormElement>): Promise<void> {
    event.preventDefault();

    if (!/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i.test(this.state.email)) {
      this.setState({ invalid: true });
      return;
    }

    try {
      const response: AxiosResponse = await axios.post('/api/notifications/add', {
        session: this.props.visitor.id,
        email: this.state.email,
      }, { headers: { 'Content-Type': 'application/json' } });
      const notification: INotification = response.data;
      this.setState({ notification, modalIsOpen: false });
    } catch (error) {
      console.error(`[pages][index][submitNotificationForm] ${JSON.stringify(error)}`);
      this.setState({ modalIsOpen: false });
    }
  }

  render(): JSX.Element {
    const title = 'Blog | Home Page';
    const description = 'Welcome to my blog, I\'ll be posting about web app development, native apps, DevOps and more.';

    return (
      <div id="home" className={styles.home}>
        <Head>
          <title>{title}</title>
          <meta charSet="utf-8" />
          <meta name="viewport" content="initial-scale=1.0, width=device-width" />
          <meta name="author" content="Eden Reich" />
          <meta name="keywords" content="Eden,Eden Reich,PHP,C++,Typescript,Javascript,CPP,Go,Web" />
          <meta name="description" content={description} />
          <meta property="og:site_name" content="Eden Reich" />
          <meta property="og:title" content={title} />
          <meta property="og:image" content="/pictures/profile_600.png" />
          <meta property="og:description" content={description} />
        </Head>
        <Modal
          isOpen={this.state.modalIsOpen}
          onRequestClose={this.closeModal}
          style={{
            content: {
              top: '50%',
              left: '50%',
              right: 'auto',
              bottom: 'auto',
              marginRight: '-50%',
              transform: 'translate(-50%, -50%)'
            }
          }}
          contentLabel="Notifications"
        >
          <div className={styles.modal__header}>
            <h2>Notifications</h2>
            <Link href="/#">
              <a
                className={styles.modal__close}
                onClick={this.closeModal}
              >
                <GrClose size="20px" />
              </a>
            </Link>
          </div>
          <div className={styles.modal__content}>
            <div>
              <p>
                Stay up to date when a new article is being published.
              </p>
            </div>
            <form
              id="notification-form"
              ref="notification-form"
              method="POST"
              action={'/api/notifications'}
              onSubmit={this.submitNotificationForm}
            >
              <br />
              <input
                name="email"
                className={this.state.invalid ? 'form-control--error' : 'form-control'}
                placeholder="Email..."
                value={this.state.email}
                onChange={this.setEmail}
              />
              <br />
              <input
                className="form-button"
                type="submit"
                form="notification-form"
                value="Save"
              />
            </form>
          </div>
        </Modal>
        <Section>
              <h2>Welcome</h2>
              <p>
                Welcome to my blog, I'll be posting about web app development, native apps, DevOps and more.
                So if you are a developer or you just happened to visit this website randomly and want to bring your web experience to the next level, stay tuned ;)
              </p>
        </Section>
        <Section>
          <div className={styles.home__notifications}>
            <Link href="/#">
              <a onClick={(event: React.MouseEvent) => this.openModal(event)}>
                {!this.state.notification && <IoMdNotificationsOutline title="Enable notifications" size="30px" />}
                {this.state.notification && !this.state.notification.is_enabled && <IoMdNotificationsOutline title="Enable notifications" size="30px" />}
                {this.state.notification && this.state.notification.is_enabled && <IoMdNotificationsOff title="Disable notifications" size="30px" />}
              </a>
            </Link>
          </div>
          <h2>Blog Feed</h2>
          <p>Latest published articles:</p>
          <PublishedArticles articles={this.props.publishedArticles} />
        </Section>
        <Section>
          <h2>Upcoming articles</h2>
          <UpcomingArticles articles={this.props.upcomingArticles} />
        </Section>
      </div>
    );
  }

}

export default IndexPage;
