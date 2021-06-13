export default {
  app_env: process.env.APP_ENV || 'development',
  port: process.env.PORT || 3000,
} as const;
