import { Context } from 'koa';
import Router from 'koa-router';

const router: Router = new Router();

router.get('web.login', '/admin/login', async (ctx: Context) => {
  ctx.body = {
    error: 'Hello World.',
  };
  ctx.status = 200;
});

export default router;
