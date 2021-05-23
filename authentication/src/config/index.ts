export default {
	port: process.env.PORT || 3000,
	app_secret: process.env.APP_SECRET || 'secret',
} as const;
