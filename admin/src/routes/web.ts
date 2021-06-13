import config from '@app/config';
import { auth } from '@app/middlewares/auth';
import axios, { AxiosResponse } from 'axios';
import { Context } from 'koa';
import Router from 'koa-router';
import { resolve } from 'path';

const router: Router = new Router();

type EntryPoint = { css: string[], js: string[] };

interface IEntryPointManifest {
  entrypoints: { [key: string]: EntryPoint };
}

router.get('web.healthcheck', '/healthcheck', (ctx: any) => {
  ctx.body = {
    status: 'OK.'
  };
  ctx.status = 200;
});

router.get('web.login', '/login', async (ctx: Context | any) => {
  const manifest: IEntryPointManifest = await import(resolve(__dirname, '../static/entrypoints.json'));

  // todo - find a way to implement this function for all views
  // maybe also use standard webpack instead of encore
  await ctx.render('security/login.html.twig', {
    encore_entry_link_tags: (entry: string) => {
      return manifest.entrypoints[entry]?.css.map((path: string) => `<link href="${path}" rel="stylesheet">`).join('\n');
    },
    encore_entry_script_tags: (entry: string) => {
      return manifest.entrypoints[entry]?.js.map((path: string) => `<script src="${path}"></script>`).join('\n');
    },
  });
});

router.post('web.login', '/login', async (ctx: Context | any) => {
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

router.get('web.dashboard', '/dashboard', auth, async (ctx: Context | any) => {
  const navigation: [] = [];
  const manifest: IEntryPointManifest = await import(resolve(__dirname, '../static/entrypoints.json'));
  await ctx.render('dashboard/index.html.twig', {
    user: ctx.state.user.data,
    navigation,
    encore_entry_link_tags: (entry: string) => {
      return manifest.entrypoints[entry]?.css.map((path: string) => `<link href="${path}" rel="stylesheet">`).join('\n');
    },
    encore_entry_script_tags: (entry: string) => {
      return manifest.entrypoints[entry]?.js.map((path: string) => `<script src="${path}"></script>`).join('\n');
    },
  });
});

router.get('web.logout', '/logout', auth, async (ctx: Context | any) => {
  ctx.set('Set-Cookie', `token=deleted;expires=${new Date(new Date().setHours(new Date().getHours() - 1)).toUTCString()}`);
  ctx.response.redirect('/login');
});

export default router;
