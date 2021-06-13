import config from "@app/config";
import axios from "axios";
import { Context, Next } from "koa";

export const auth = async (ctx: Context, next: Next) => {
  const matches: RegExpMatchArray | null | undefined = ctx.request.headers.cookie?.match(/token=(.*[^;]);?/);
  const token: string | undefined = matches?.pop();
  if (!token) {
    ctx.redirect('/login');
    return;
  }

  try {
    await axios.post(`${config.authentication_url}/api/authentication/authorize`, {}, {
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
      }
    });
  } catch (error) {
    console.error(error);
    ctx.redirect('/login');
    return;
  }

  ctx.state.user = JSON.parse(atob(token.split('.')[1]));
  return next();
};
