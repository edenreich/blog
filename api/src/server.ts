import Fastify, { FastifyInstance } from 'fastify';
import swagger from 'fastify-swagger';
import postgres from 'fastify-postgres';
import typeorm from 'fastify-typeorm-plugin';
import routes from './routes/api';

const fastify: FastifyInstance = Fastify({
  logger: true,
});

fastify.register(swagger, {
  exposeRoute: true,
  routePrefix: '/docs',
  swagger: {
    info: { version: 'v1', title: 'API' },
  },
});
fastify.register(routes);
fastify.register(postgres, {
  connectionString:
    process.env.DATABASE_URL || 'postgres://postgres:secret@postgres/blog_api',
});
fastify.register(typeorm, {
  type: 'postgres',
  url: process.env.DATABASE_URL || 'postgres://postgres:secret@postgres/blog_api',
  synchronize: true,
  logging: false,
  entities: [
    "src/entities/**/*.js"
  ],
  migrations: [
    "src/migrations/**/*.js"
  ],
  cli: {
    entitiesDir: "src/entity",
    migrationsDir: "src/migration",
  }
})

fastify.addHook('onClose', async (instance, done) => {
  fastify.close();
  done();
});

const start = async (): Promise<void> => {
  try {
    await fastify.listen(process.env.PORT || 3000, '0.0.0.0');
  } catch (error) {
    fastify.log.error(error);
    process.exit(1);
  }
};

start();
