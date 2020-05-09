import { NextPage, NextPageContext } from 'next';

interface IProps {

}

const Healthcheck: NextPage<IProps> = (props: any) => {
    return props;
};

Healthcheck.getInitialProps = async (ctx: NextPageContext) => {
  return ctx.res?.end(JSON.stringify({healthy: true}));
}

export default Healthcheck;