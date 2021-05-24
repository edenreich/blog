export default {
  port: process.env.PORT || 3000,
  app_env: process.env.APP_ENV || 'development',
  app_secret: process.env.APP_SECRET || 'secret',
  database_url: process.env.DATABASE_URL || 'postgres://postgres:secret@127.0.0.1:5432/blog_authentication?serverVersion=13&charset=utf8',
} as const;
