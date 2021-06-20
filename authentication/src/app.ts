import config from './config';
import { server } from './server';
import router from '@app/routes/api';
import { connection } from '@app/database/connection';
import { IRole, Role } from '@app/entities/Role';
import { User, IUser } from '@app/entities/User';

connection.then(async (_connection) => {
  const appUser: IUser | undefined = await _connection.manager.getRepository<IUser>(User).findOne({username: config.app_username});
  if (!appUser) {
    const roleUser: IRole = await _connection.manager.save(new Role('USER_ROLE'));
    const roleAdmin: IRole = await _connection.manager.save(new Role('ADMIN_ROLE'));
    const user: IUser = new User();
    user.username = config.app_username;
    user.password = config.app_password;
    user.roles = [
      roleUser,
      roleAdmin,
    ];

    await _connection.manager.save(user);
  }

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
