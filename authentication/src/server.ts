import Koa from 'koa';
import Logger from 'koa-logger';
import router from './routes/api';
import config from './config';

const app: Koa = new Koa();

app.use(Logger());

app.use(router.routes());
app.listen(config.port, () => {
  for (const route of router.stack) {
    console.table([{ Route: route.name, Methods: route.methods, Path: route.path }]);
  }
  console.info(`Listening to http://0.0.0.0:${config.port} 🚀`);
});
