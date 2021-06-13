export default {
  app_env: process.env.APP_ENV || 'development',
  port: process.env.PORT || 3000,
  authentication_url: process.env.AUTHENTICATION_URL || 'http://authentication:8080',
} as const;
