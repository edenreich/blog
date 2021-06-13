import Router from 'koa-router';
import { resolve } from 'path';

const router: Router = new Router();

type EntryPoint = { css: string[], js: string[] };

interface IEntryPointManifest {
  entrypoints: { [key: string]: EntryPoint };
}

router.get('web.login', '/login', async (ctx: any) => {
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

export default router;
