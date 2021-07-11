async function routes(fastify, options) {
  fastify.get('/api/v1/', async (request, response) => {
    return { };
  });
}

export = routes;
