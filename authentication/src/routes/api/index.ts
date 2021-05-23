import { Context } from 'koa';
import Router from 'koa-router';
import jwt from 'jsonwebtoken';
import config from '@app/config';

const router: Router = new Router();

interface User {
  username: string;
  password: string;
}

interface Payload {
  data: User;
  iat: number;
  exp: number;
}

const mockedUser: User = {
  username: 'test',
  password: 'somepassword'
}

router.post('api.jwt', '/api/jwt', (ctx: Context) => {
  const validCredentials: boolean = ctx.request.body.username === mockedUser.username && ctx.request.body.password === mockedUser.password;
  if (!validCredentials) {
    ctx.body = {
      error: 'Invalid credentails.',
    };
    ctx.status = 401;
    return;
  }
  const token: string = jwt.sign({
    data: {
      username: mockedUser.username
    },
  }, config.app_secret, { expiresIn: '1h' });
  ctx.body = {
    token,
  };
  ctx.status = 200;
});

router.post('api.authorize', '/api/authorize', (ctx: Context) => {
  const token: string[] | undefined = ctx.headers.authorization?.split(' ');
  if (!token) {
    ctx.body = {
      error: 'Not a valid Authorization header sent.',
    };
    ctx.status = 422;
    return;
  }
  
  if (token[0] !== 'Bearer' && !token[1]) {
    ctx.body = {
      error: 'Invalid token.',
    };
    ctx.status = 422;
    return;
  }

  try {
    jwt.verify(token[1], config.app_secret) as Payload;
    ctx.status = 200;
    return;
  } catch (_err) {
    ctx.body = {
      error: 'Invalid signature.',
    };
    ctx.status = 401;
    return;
  }
});

router.get('api.healthcheck', '/api/healthcheck', (ctx: Context) => {
  ctx.body = {
    status: 'OK.'
  };
  ctx.status = 200;
});

export default router;
