import axios, { AxiosResponse } from 'axios';
import getConfig from 'next/config';
import App, { AppContext } from 'next/app';
import { AppInitialProps } from 'next/dist/next-server/lib/utils';

import Layout from '@/components/Layout';
import NextNProgress from 'nextjs-progressbar';
import Header from '@/components/Header';
import Navigation from '@/components/Navigation';
import Content from '@/components/Content';
import Footer from '@/components/Footer';

import { Article } from '@/interfaces/article';
import { IVisitor } from '@/interfaces/visitor';

import '@/assets/scss/reset.scss';
import '@/assets/scss/global.scss';

const { publicRuntimeConfig } = getConfig();

interface IProps extends AppInitialProps {
  session: IVisitor;
  pageProps: any;
}

interface IState {
  articles: Article[];
}

class Blog extends App<IProps, IState> {

  static async getInitialProps({ Component, ctx }: AppContext): Promise<any> {
    let pageProps = {};

    const ipAddress: string | string[] = ctx.req?.headers['x-real-ip'];
    let session;
    try {
      const response: AxiosResponse = await axios.post(`${publicRuntimeConfig.apis.default.url}/sessions`, { ip_address: ipAddress }, { headers: { 'Content-Type': 'application/json' } });
      session = response.data[0];
    } catch {
      session = null;
    }

    if (Component.getInitialProps) {
      pageProps = await Component.getInitialProps(ctx);
    }

    return { session, pageProps };
  }

  render(): JSX.Element {
    const brand: string = 'Eden Reich';

    return (
      <Layout>
        <NextNProgress color="#29d"
          options={{ trickleSpeed: 50 }}
          showAfterMs={300}
          spinner />
        <Header className="grid-header">
          <Navigation
            className="grid-navigation"
            brand={brand}
            links={[{
              id: 1,
              name: 'Home',
              href: '/'
            },
            {
              id: 2,
              name: 'Projects',
              href: '/projects'
            },
            {
              id: 3,
              name: 'About',
              href: '/about'
            },
            {
              id: 4,
              name: 'Contact',
              href: '/contact'
            }]}
            current={this.props.router.pathname}
          />
        </Header>
        <Content className="grid-content">
          <this.props.Component visitor={this.props.session} {...this.props.pageProps} />
        </Content>
        <Footer className="grid-footer" />
      </Layout>
    );
  }
}

export default Blog;
