module.exports = ({ env }) => ({
    defaultConnection: 'default',
    connections: {
      default: {
        connector: 'bookshelf',
        settings: {
          client: 'postgres',
          host: env('DATABASE_HOST', '192.168.1.30'),
          port: env.int('DATABASE_PORT', 5432),
          database: env('DATABASE_NAME', 'blog_stage'),
          username: env('DATABASE_USERNAME', 'root'),
          password: env('DATABASE_PASSWORD', 'secret'),
          ssl: env.bool('DATABASE_SSL', false),
        },
        options: {}
      },
    },
  });