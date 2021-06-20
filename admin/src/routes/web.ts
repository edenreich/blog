import config from '@app/config';
import { IArticle } from '@app/entities/article';
import { IAuthenticateableDTO, IAuthenticatedUser } from '@app/entities/user';
import { auth } from '@app/middlewares/auth';
import { guest } from '@app/middlewares/guest';
import axios, { AxiosResponse } from 'axios';
import { Context } from 'koa';
import Router, { IRouterParamContext } from 'koa-router';

const router: Router = new Router();

router.get('web.healthcheck', '/healthcheck', (ctx: any) => {
  ctx.body = {
    status: 'OK.',
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
      },
    });
    ctx.set('Set-Cookie', `token=${response.data.token};expires=${new Date(new Date().setHours(new Date().getHours() + 1)).toUTCString()}`);
    ctx.response.redirect('/dashboard');
  } catch (error) {
    /* tslint:disable:no-console */
    console.log(error);
  }
});

router.get('web.dashboard', '/dashboard', auth, async (ctx: Context) => {
  const user: IAuthenticatedUser = ctx.state.user as IAuthenticatedUser;
  const requestUrlSegments: string[] = ctx.req.url ? ctx.req.url.split('?')[0].split('/').slice(1) : ['/'];
  await ctx.render('dashboard/index', {
    title: 'Admin - Dashboard',
    user: user.data,
    navigation: config.navigation,
    current_url: requestUrlSegments,
  });
});

router.get('web.content', '/content', auth, async (ctx: Context) => {
  ctx.response.redirect('/content/list');
});

router.get('web.content.list', '/content/list', auth, async (ctx: Context) => {
  const user: IAuthenticatedUser = ctx.state.user as IAuthenticatedUser;
  const requestUrlSegments: string[] = ctx.req.url ? ctx.req.url.split('?')[0].split('/').slice(1) : ['/'];

  if (ctx.request.get('X-Requested-With') === 'XMLHttpRequest') {
    const articles: IArticle[] = [
      {
        id: 'fe12b93b-d22d-40f1-ab83-eb71f3cb8e05',
        title: 'title',
        slug: 'slug',
        meta_keywords: 'meta_keywords',
        meta_description: 'meta_description',
        published_at: 'published_at',
        updated_at: 'updated_at',
        created_at: 'created_at',
      },
    ];
    ctx.body = articles;
    return;
  }

  await ctx.render('content/list', {
    title: 'Admin - Content > List',
    user: user.data,
    navigation: config.navigation,
    current_url: requestUrlSegments,
  });
});

router.get('web.content.create', '/content/create', auth, async (ctx: Context) => {
  const user: IAuthenticatedUser = ctx.state.user as IAuthenticatedUser;
  const requestUrlSegments: string[] = ctx.req.url ? ctx.req.url.split('?')[0].split('/').slice(1) : ['/'];
  await ctx.render('content/create', {
    title: 'Admin - Content > Create',
    user: user.data,
    navigation: config.navigation,
    current_url: requestUrlSegments,
  });
});

router.post('web.content.create', '/content/create', auth, async (ctx: Context) => {
  const user: IAuthenticatedUser = ctx.state.user as IAuthenticatedUser;
  const article: IArticle = ctx.request.body as unknown as IArticle;
  // @todo - send an api call to create an article
  // @todo - set a flash message of success or faliure
  await ctx.redirect('/content/create');
});

router.get('web.content.edit', '/content/:id/edit', auth, async (ctx: IRouterParamContext & Context) => {
  const user: IAuthenticatedUser = ctx.state.user as IAuthenticatedUser;
  const requestUrlSegments: string[] = ctx.req.url ? ctx.req.url.split('?')[0].split('/').slice(1) : ['/'];
  const articleId: string = ctx.params.id;
  // @todo - send an api call to fetch article information
  const article: IArticle = {
    id: articleId,
    title: 'title',
    slug: 'slug',
    meta_keywords: 'meta_keywords',
    meta_description: 'meta_description',
    published_at: 'published_at',
    updated_at: 'updated_at',
    created_at: 'created_at',
  };
  await ctx.render('content/edit', {
    title: 'Admin - Content > Edit',
    user: user.data,
    navigation: config.navigation,
    current_url: requestUrlSegments,
    article,
  });
});

router.post('web.content.edit', '/content/:id/edit', auth, async (ctx: Context) => {
  const user: IAuthenticatedUser = ctx.state.user as IAuthenticatedUser;
  // @todo make an api call to edit the article
  // @todo set flash message
  ctx.response.redirect('/content/list');
});

router.get('web.content.delete', '/content/:id/delete', auth, async (ctx: Context) => {
  const user: IAuthenticatedUser = ctx.state.user as IAuthenticatedUser;
  // @todo make an api call to soft delete the article
  // @todo set flash message
  ctx.response.redirect('/content/list');
});

router.get('web.media', '/media', auth, async (ctx: Context) => {
  const user: IAuthenticatedUser = ctx.state.user as IAuthenticatedUser;
  const requestUrlSegments: string[] = ctx.req.url ? ctx.req.url.split('?')[0].split('/').slice(1) : ['/'];
  // @todo send an api call to fetch images
  await ctx.render('media/index', {
    title: 'Admin - Media',
    user: user.data,
    navigation: config.navigation,
    current_url: requestUrlSegments,
    flashes: [],
    images: [],
  });
});

router.post('web.media.upload', '/media/upload', auth, async (ctx: Context) => {
  const user: IAuthenticatedUser = ctx.state.user as IAuthenticatedUser;
  // @todo make an api call to upload an image
  // @todo set flash message
  ctx.response.redirect('/media');
});

router.get('web.logout', '/logout', auth, async (ctx: Context) => {
  ctx.set('Set-Cookie', `token=deleted;expires=${new Date(new Date().setHours(new Date().getHours() - 1)).toUTCString()}`);
  ctx.response.redirect('/login');
});

export default router;
