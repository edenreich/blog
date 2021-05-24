import router from '@app/routes/web';
import Koa from 'koa';
import BodyParser from 'koa-bodyparser';
import Logger from 'koa-logger';

export const server: Koa = new Koa();

server.use(Logger());
server.use(BodyParser());
server.use(router.routes());
server.use(router.allowedMethods());
