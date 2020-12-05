import * as React from 'react';
import Head from 'next/head';

import { Article } from '@/interfaces/article';
import Link from 'next/link';
import moment from 'moment';
import ReactMarkDown from 'react-markdown';
import { GrClose } from 'react-icons/gr';
import { IoMdNotificationsOff, IoMdNotificationsOutline } from 'react-icons/io';
import Modal from 'react-modal';

import getConfig from 'next/config';
import axios, { AxiosResponse } from 'axios';
import { asset } from '@/utils/asset';
import { IVisitor } from '@/interfaces/visitor';
import { INotification } from '@/interfaces/notification';

import './index.scss';

const { publicRuntimeConfig } = getConfig();

interface IProps {
  visitor?: IVisitor;
  articles?: Article[];
}

interface IState {
  modalIsOpen: boolean;
  email: string;
  invalid: boolean;
  notification: INotification | null;
}

class IndexPage extends React.Component<IProps, IState> {

  static async getInitialProps(): Promise<any> {
    let articles: Article[];
    try {
      const response: AxiosResponse = await axios.get(`${publicRuntimeConfig.app.url}/api/articles`);
      articles = response.data;
    } catch (error) {
      articles = [];
    }

    return { articles };
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
      const response: AxiosResponse = await axios.get(`${publicRuntimeConfig.app.url}/api/notifications/get?session_id=${this.props.visitor.uuid}`);
      const notification: INotification = response.data;
      await this.setState({ notification });
    } catch (error) {
      console.error(error);
      await this.setState({});
    }
  }

  async openModal(event: React.MouseEvent): Promise<void> {
    event.preventDefault();

    if (this.state.notification?.is_enabled) {
      try {
        const notification: INotification = await axios.put(`${publicRuntimeConfig.app.url}/api/notifications/remove`, {
          session: this.props.visitor?.uuid,
          email: this.state.email,
          is_enabled: false,
        }, { headers: { 'Content-Type': 'application/json' } });
        this.setState({ notification });
      } catch (error) {
        console.error(error);
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
      const response: AxiosResponse = await axios.post(`${publicRuntimeConfig.app.url}/api/notifications/add`, {
        session: this.props.visitor.uuid,
        email: this.state.email,
      }, { headers: { 'Content-Type': 'application/json' } });
      const notification: INotification = response.data;
      this.setState({ notification, modalIsOpen: false });
    } catch (error) {
      console.error(error);
      this.setState({ modalIsOpen: false });
    }
  }

  render(): JSX.Element {
    const title = 'Blog | Home Page';
    const description = 'Welcome to my blog, I\'ll be posting about web app development, native apps, DevOps and more.';

    return (
      <div id="home" className="home">
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
          <div className="modal__header">
            <h2>Notifications</h2>
            <Link href="/#">
              <a
                className="modal__close"
                onClick={this.closeModal}
              >
                <GrClose size="20px" />
              </a>
            </Link>
          </div>
          <div className="modal__content">
            <div>
              <p>
                Stay up to date when a new article is being published.
              </p>
            </div>
            <form
              id="notification-form"
              ref="notification-form"
              method="POST"
              action={`${publicRuntimeConfig.app.url}/api/notifications`}
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
        <section className="content__section">
          <div className="content__wrapper grid-content-wrapper">
            <div className="grid-column">
              <h2>Welcome</h2>
              <p>
                Welcome to my blog, I'll be posting about web app development, native apps, DevOps and more.
                So if you are a developer or you just happened to visit this website randomly and want to bring your web experience to the next level, stay tuned ;)
              </p>
            </div>
          </div>
        </section>
        <section className="content__section">
          <div className="content__wrapper grid-content-wrapper">
            <div className="grid-column">
              <div className="home__notifications">
                <Link href="/#">
                  <a onClick={(event: React.MouseEvent) => this.openModal(event)}>
                    {!this.state.notification && <IoMdNotificationsOutline title="Enable notifications" size="30px" />}
                    {this.state.notification && !this.state.notification.is_enabled && <IoMdNotificationsOutline title="Enable notifications" size="30px" />}
                    {this.state.notification && this.state.notification.is_enabled && <IoMdNotificationsOff title="Disable notifications" size="30px" />}
                  </a>
                </Link>
              </div>
              <h2>Blog Feed</h2>
              <p>Latest posted articles:</p>
              {
                this.props.articles?.filter((article: Article) => article.published_at !== undefined).map((article: Article, key: number) => {
                  return (
                    <article key={key}>
                      <div className="home__article__title">
                        <img src={`${asset(article.meta_thumbnail.formats.thumbnail?.url)}`} />
                        <h3>{article.title}</h3>
                        <span className="home__article__date"><small>{moment(article.published_at).fromNow()}</small></span>
                      </div>
                      <div className="home__article__content">
                        <ReactMarkDown source={article.content.substring(0, 300)} escapeHtml={false} />
                      </div>
                      <div className="home__article__text-preview">
                        <Link href={`/${article.slug}`}>
                          <a className="button-primary">Read more..</a>
                        </Link>
                      </div>
                    </article>
                  );
                })
              }
            </div>
          </div>
        </section>
      </div>
    );
  }

}

export default IndexPage;
