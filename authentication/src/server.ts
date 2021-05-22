import Koa, { Context } from 'koa';
import Logger from 'koa-logger';
import BodyParser from 'koa-bodyparser';
import router from './routes/api';
import config from './config';

const app: Koa = new Koa();

app.use(Logger());
app.use(BodyParser({
  onerror: (err: Error, ctx: Context) => {
    ctx.throw('Invalid JSON!', 422);
  }
}));
app.use(router.routes());
app.use(router.allowedMethods());
app.listen(config.port, () => {
  for (const route of router.stack) {
    console.table([{ Route: route.name, Methods: route.methods, Path: route.path }]);
  }
  console.info(`Listening to connections on http://0.0.0.0:${config.port} 🚀`);
});
