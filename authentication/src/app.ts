import config from './config';
import { server } from './server';
import router from '@app/routes/api';

server.listen(config.port, () => {
  for (const route of router.stack) {
    console.table([{ Route: route.name, Methods: route.methods, Path: route.path }]);
  }
  console.info(`Listening to connections on http://0.0.0.0:${config.port} 🚀`);
});
