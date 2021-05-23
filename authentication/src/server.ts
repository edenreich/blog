import Koa, { Context, Next } from 'koa';
import Logger from 'koa-logger';
import BodyParser from 'koa-bodyparser';
import router from '@app/routes/api';

export const server: Koa = new Koa();

server.use(Logger());
server.use(async (ctx: Context, next: Next) => {
  if (ctx.headers['content-type'] && ctx.headers['content-type'] !== 'application/json') {
    ctx.body = {
      error: 'Invalid content type requested. Please request application/json',
    };
    ctx.status = 422;
    return;
  }
  await next();
});
server.use(BodyParser({
  enableTypes: ['json'],
  onerror: (_err: Error, ctx: Context) => {
    ctx.throw('Invalid JSON!', 422);
  }
}));
server.use(router.routes());
server.use(router.allowedMethods());
