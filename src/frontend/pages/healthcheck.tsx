import { NextPage, NextPageContext } from 'next';

const Healthcheck: NextPage<{}> = (props: any) => {
    return props;
};

Healthcheck.getInitialProps = async (ctx: NextPageContext) => {
  return ctx.res?.end(JSON.stringify({healthy: true}));
};

export default Healthcheck;
