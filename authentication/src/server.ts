import Koa from 'koa';
import Router from 'koa-router';
import Logger from 'koa-logger';

const app: Koa = new Koa();
const router: Router = new Router();
const port = process.env.PORT || 3000;

app.use(Logger());

router.get('/', (ctx: Koa.Context) => {
  ctx.body = 'Hello World';
});

app.use(router.routes());
app.listen(port, () => {
  console.info(`Listening to http://0.0.0.0:${port} 🚀`);
});
