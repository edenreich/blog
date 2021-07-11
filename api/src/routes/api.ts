async function routes(fastify, options) {
  fastify.get('/api/v1/healthcheck', async (request, response) => {
    return { status: 'OK.' };
  });

  fastify.get('/api/v1/articles', async (request, response) => {
    // @todo fetch all articles from database.
    return {};
  });

  fastify.post('/api/v1/articles', async (request, response) => {
    // @todo create a new article in the database and upload base64 image to media
    return {};
  });
}

export = routes;
