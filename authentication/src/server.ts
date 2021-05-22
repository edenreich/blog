import Koa from 'koa';
import Logger from 'koa-logger';
import { router } from './api/jwt';

const app: Koa = new Koa();
const port = process.env.PORT || 3000;

app.use(Logger());

app.use(router.routes());
app.listen(port, () => {
  console.info('Route\t\tPath\n------\t\t------');
  for (const route of router.stack) {
    console.info(`${route.name}\t\t${route.path}\n`);
  }
  console.info(`Listening to http://0.0.0.0:${port} 🚀`);
});
