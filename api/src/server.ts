import Fastify, { FastifyInstance } from 'fastify';
import swagger from 'fastify-swagger';
import routes from './routes/api';

const fastify: FastifyInstance = Fastify({
  logger: true,
});

fastify.addHook('onClose', async (instance, done) => {
  fastify.close();
  done();
});
fastify.register(swagger, {
  exposeRoute: true,
  routePrefix: '/docs',
  swagger: {
    info: { version: 'v1', title: 'API' },
  },
});
fastify.register(routes);


const start = async (): Promise<void> => {
  try {
    await fastify.listen(process.env.PORT || 3000, '0.0.0.0');
  } catch (error) {
    fastify.log.error(error);
    process.exit(1);
  }
};

start();
