import { Context } from 'koa';
import Router from 'koa-router';

export const router: Router = new Router();

router.get('api.jwt', '/api/jwt', (ctx: Context) => {
  ctx.body = 'Authentication logic';
});
