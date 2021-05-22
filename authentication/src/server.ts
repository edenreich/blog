import Koa, { Context } from 'koa';
import Logger from 'koa-logger';
import BodyParser from 'koa-bodyparser';
import router from './routes/api';

export const server: Koa = new Koa();

server.use(Logger());
server.use(BodyParser({
  onerror: (_err: Error, ctx: Context) => {
    ctx.throw('Invalid JSON!', 422);
  }
}));
server.use(router.routes());
server.use(router.allowedMethods());
