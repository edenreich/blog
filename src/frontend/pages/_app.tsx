import axios, { AxiosResponse } from 'axios';
import App, { AppContext } from 'next/app';
import { AppInitialProps } from 'next/dist/next-server/lib/utils';
import getConfig from 'next/config';

import Layout from '@/components/Layout';
import NextNProgress from 'nextjs-progressbar';
import Header from '@/components/Header';
import Navigation from '@/components/Navigation';
import Content from '@/components/Content';
import Footer from '@/components/Footer';

import { Article } from '@/interfaces/article';
import { IVisitor } from '@/interfaces/visitor';

const { publicRuntimeConfig } = getConfig();

import '@/styles/scss/globals.scss';

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
    const anonymouse: IVisitor = {
      id: '53f36322-9b5b-46db-8a2d-f53d85a09352',
      ip_address: '127.0.0.1',
      updated_at: new Date(),
      created_at: new Date()
    };
    let session: IVisitor;
    try {
      const response: AxiosResponse = await axios.post(`${publicRuntimeConfig.apis.frontend.url}/sessions`, {} ,{ headers:  ctx?.req?.headers });
      session = response.data;
    } catch (error) {
      session = anonymouse;
      console.error(`[app][session] ${JSON.stringify(error)}`);
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
        <Header>
          <Navigation
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
        <Content>
          <this.props.Component visitor={this.props.session} {...this.props.pageProps} />
        </Content>
        <Footer />
      </Layout>
    );
  }
}

export default Blog;
