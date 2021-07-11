import Fastify, { FastifyInstance } from 'fastify';
import swagger from 'fastify-swagger';
import routes from './routes/api';

const app: FastifyInstance = Fastify({
  logger: true,
});

app.addHook('onClose', async (instance, done) => {
  app.close();
  done();
});
app.register(swagger, {
  exposeRoute: true,
  routePrefix: '/docs',
  swagger: {
    info: { version: 'v1', title: 'API' },
  },
});
app.register(routes);

const start = async (): Promise<void> => {
  try {
    await app.listen(process.env.PORT || 3000, '0.0.0.0');
  } catch (error) {
    app.log.error(error);
    process.exit(1);
  }
};

start();
