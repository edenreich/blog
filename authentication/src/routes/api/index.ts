import { Context } from 'koa';
import Router from 'koa-router';

const router: Router = new Router();

router.post('api.jwt', '/api/jwt', (ctx: Context) => {
  ctx.body = 'Authentication logic';
  ctx.status = 200;
});

router.get('api.healthcheck', '/api/healthcheck', (ctx: Context) => {
  ctx.body = {
    status: 'OK'
  };
  ctx.status = 200;
});

export default router;
