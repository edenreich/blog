import config from '@app/config';
import router from '@app/routes/web';
import { server } from './server';

server.listen(config.port, () => {
  for (const route of router.stack) {
    /* tslint:disable no-console */
    console.table([{ Route: route.name, Methods: route.methods, Path: route.path }]);
  }
  /* tslint:disable no-console */
  console.info(`Listening to connections on http://0.0.0.0:${config.port} 🚀`);
});
