import router from '@app/routes/web';
import Koa from 'koa';
import BodyParser from 'koa-bodyparser';
import Logger from 'koa-logger';
import KoaStatic from 'koa-static';
import KoaViews from 'koa-views';
import { resolve } from 'path';

export const server: Koa = new Koa();

server.use(Logger());
server.use(KoaStatic(resolve(__dirname, 'static'), {
  root: '/static',
  index: '/static',
}));
server.use(KoaViews(resolve(__dirname, 'templates'), {
  options: {
    namespaces: { admin: resolve(__dirname, 'templates') },
  },
}));
server.use(BodyParser());
server.use(router.routes());
server.use(router.allowedMethods());
