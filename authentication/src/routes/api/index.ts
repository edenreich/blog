import { Context } from 'koa';
import Router from 'koa-router';
import jwt from 'jsonwebtoken';
import config from '@app/config';
import { IUser, User } from '@app/entities/User';
import { connection } from '@app/database/connection';
import { Connection, Repository } from 'typeorm';
import { compare } from 'bcrypt';

const router: Router = new Router();

router.post('api.authentication.jwt', '/api/authentication/jwt', async (ctx: Context) => {
  const conn: Connection = await connection;
  const repository: Repository<User> = conn.getRepository(User);
  const user: IUser | undefined = await repository.findOne({username: ctx.request.body.username});
  if (!user) {
    ctx.body = {
      error: 'Invalid credentails.',
    };
    ctx.status = 401;
    return;
  }

  try {
    const validCredentials: boolean = await compare(ctx.request.body.password, user.password);
    if (!validCredentials) {
      ctx.body = {
        error: 'Invalid credentails.',
      };
      ctx.status = 401;
      return;
    }
  } catch (error) {
    ctx.body = {
      error: 'Invalid credentails.',
    };
    ctx.status = 401;
    return;
  }

  const token: string = jwt.sign({
    data: {
      id: user.id,
      username: user.username,
      roles: user.roles
    },
  }, config.app_secret, { expiresIn: '1h' });
  ctx.body = {
    token,
  };
  ctx.status = 200;
});

router.post('api.authentication.authorize', '/api/authentication/authorize', (ctx: Context) => {
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
    jwt.verify(token[1], config.app_secret);
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

router.get('api.authentication.healthcheck', '/api/authentication/healthcheck', (ctx: Context) => {
  ctx.body = {
    status: 'OK.'
  };
  ctx.status = 200;
});

export default router;
