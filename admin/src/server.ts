import router from '@app/routes/web';
import Koa from 'koa';
import BodyParser from 'koa-bodyparser';
import Logger from 'koa-logger';
import KoaStatic from 'koa-static';
import ejs from 'koa-ejs';
import { join } from 'path';

export const server: Koa = new Koa();

ejs(server, {
  root: join(__dirname, 'views'),
  layout: 'admin.layout',
  viewExt: 'html',
  cache: false,
  debug: false,
});

server.use(KoaStatic(join(__dirname, 'static')));
server.use(Logger());
server.use(BodyParser());
server.use(router.routes());
server.use(router.allowedMethods());
