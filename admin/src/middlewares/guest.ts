import { Context, Next } from 'koa';

export const guest = async (ctx: Context, next: Next) => {
  const matches: RegExpMatchArray | null | undefined = ctx.request.headers.cookie?.match(/token=(.*[^;]);?/);
  const token: string | undefined = matches?.pop();
  if (token) {
    ctx.redirect('/dashboard');
    return;
  }

  return next();
};
