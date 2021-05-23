
import { ConnectionOptions } from 'typeorm';
import { User } from '@app/entities/User';
import { Role } from '@app/entities/Role';
import config from '@app/config';
import { join } from 'path';

const connectionOptions: ConnectionOptions = {
  type: 'postgres',
  url: config.database_url,
  entities: [
    User,
    Role,
  ],
  synchronize: true,
  dropSchema: false,
  migrationsRun: false,
  logging: ['warn', 'error'],
  logger: 'simple-console',
  migrations: [
    join(__dirname, 'migrations/*.ts')
  ],
  cli: {
    migrationsDir: join(__dirname, 'migrations')
  },
};

export = connectionOptions;
