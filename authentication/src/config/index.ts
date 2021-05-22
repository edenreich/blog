export default {
	port: process.env.PORT || 3000,
	secret: process.env.APP_SECRET || 'secret',
} as const;
