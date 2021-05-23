import config from './config';
import { server } from './server';
import router from '@app/routes/api';
import { connection } from '@app/database/connection';

connection.then(_connection => {
  server.listen(config.port, () => {
    for (const route of router.stack) {
      /* tslint:disable no-console */
      console.table([{ Route: route.name, Methods: route.methods, Path: route.path }]);
    }
    /* tslint:disable no-console */
    console.info(`Listening to connections on http://0.0.0.0:${config.port} 🚀`);
  });
  /* tslint:disable no-console */
}).catch(error => console.log(error));
