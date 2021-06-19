import config from '@app/config';
import { auth } from '@app/middlewares/auth';
import axios, { AxiosResponse } from 'axios';
import { DefaultContext } from 'koa';
import Router from 'koa-router';

const router: Router = new Router();

router.get('web.healthcheck', '/healthcheck', (ctx: any) => {
  ctx.body = {
    status: 'OK.'
  };
  ctx.status = 200;
});

router.get('web.login', '/login', async (ctx: DefaultContext) => {
  await ctx.render('security/login', {
    layout: 'auth.layout',
    title: 'Login',
  });
});

router.post('web.login', '/login', async (ctx: DefaultContext) => {
  try {
    const response: AxiosResponse = await axios.post(`${config.authentication_url}/api/authentication/jwt`, {
      username: ctx.request.body.username,
      password: ctx.request.body.password,
    }, {
      headers: {
        'Content-Type': 'application/json',
      }
    });
    ctx.set('Set-Cookie', `token=${response.data.token};expires=${new Date(new Date().setHours(new Date().getHours() + 1)).toUTCString()}`);
    ctx.response.redirect('/dashboard');
  } catch (error) {
    console.log(error);
  }
});

router.get('web.dashboard', '/dashboard', auth, async (ctx: DefaultContext) => {
  await ctx.render('dashboard/index', {
    title: 'Admin - Dashboard',
    user: ctx.state.user.data,
  });
});

router.get('web.logout', '/logout', auth, async (ctx: DefaultContext) => {
  ctx.set('Set-Cookie', `token=deleted;expires=${new Date(new Date().setHours(new Date().getHours() - 1)).toUTCString()}`);
  ctx.response.redirect('/login');
});

export default router;
