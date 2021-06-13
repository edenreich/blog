import router from '@app/routes/web';
import Koa from 'koa';
import BodyParser from 'koa-bodyparser';
import Logger from 'koa-logger';
import KoaStatic from 'koa-static';
import KoaViews from 'koa-views';
import { resolve } from 'path';
import { readFileSync } from 'fs';

export const server: Koa = new Koa();

// todo - check why this global functions doesn't work, if it's not possible switch to a different template engine
// type EntryPoint = { css: string[], js: string[] };
// interface IEntryPointManifest {
//   entrypoints: { [key: string]: EntryPoint };
// }
// const manifest: IEntryPointManifest = JSON.parse(readFileSync(resolve(__dirname, './static/entrypoints.json'), {encoding: 'utf-8'}));
server.use(KoaStatic(resolve(__dirname, 'static')));
server.use(KoaViews(resolve(__dirname, 'templates'), {
  options: {
    namespaces: { 
      admin: resolve(__dirname, 'templates') 
    },
    // functions: {
    //   encore_entry_link_tags: (entry: string): string => {
    //     return manifest.entrypoints[entry]?.css.map((path: string) => `<link href="${path}" rel="stylesheet">`).join('\n');
    //   },
    //   encore_entry_script_tags: (entry: string): string => {
    //     return manifest.entrypoints[entry]?.js.map((path: string) => `<script src="${path}"></script>`).join('\n');
    //   },
    // }
  },
}));
server.use(Logger());
server.use(BodyParser());
server.use(router.routes());
server.use(router.allowedMethods());
