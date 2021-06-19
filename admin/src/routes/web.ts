import config from '@app/config';
import { IAuthenticateableDTO, IAuthenticatedUser } from '@app/entities/user';
import { auth } from '@app/middlewares/auth';
import { guest } from '@app/middlewares/guest';
import axios, { AxiosResponse } from 'axios';
import { Context } from 'koa';
import Router from 'koa-router';

const router: Router = new Router();

router.get('web.healthcheck', '/healthcheck', (ctx: any) => {
  ctx.body = {
    status: 'OK.'
  };
  ctx.status = 200;
});

router.get('web.index', '/', async (ctx: Context) => {
  ctx.response.redirect('/login');
});

router.get('web.login', '/login', guest, async (ctx: Context) => {
  await ctx.render('security/login', {
    layout: 'auth.layout',
    title: 'Login',
  });
});

router.post('web.login', '/login', guest, async (ctx: Context) => {
  const credentials: IAuthenticateableDTO = ctx.request.body as unknown as IAuthenticateableDTO;
  try {
    const response: AxiosResponse = await axios.post(`${config.authentication_url}/api/authentication/jwt`, {
      username: credentials.username,
      password: credentials.password,
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

router.get('web.dashboard', '/dashboard', auth, async (ctx: Context) => {
  const user: IAuthenticatedUser = ctx.state.user as IAuthenticatedUser;
  await ctx.render('dashboard/index', {
    title: 'Admin - Dashboard',
    user: user.data,
  });
});

router.get('web.logout', '/logout', auth, async (ctx: Context) => {
  ctx.set('Set-Cookie', `token=deleted;expires=${new Date(new Date().setHours(new Date().getHours() - 1)).toUTCString()}`);
  ctx.response.redirect('/login');
});

export default router;
