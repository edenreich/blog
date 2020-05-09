import App from 'next/app';

import '@/assets/scss/reset.scss';
import '@/assets/scss/global.scss';

import Layout from '@/components/Layout';
import NextNProgress from 'nextjs-progressbar';
import Header from '@/components/Header';
import Navigation from '@/components/Navigation';
import Content from '@/components/Content';
import Footer from '@/components/Footer';

class Blog extends App {

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
          />
        </Header>
        <Content className="grid-content">
          <this.props.Component  {...this.props.pageProps} route={this.props.router.route} />
        </Content>
        <Footer className="grid-footer" />
      </Layout>
    );
  }
}

export default Blog;