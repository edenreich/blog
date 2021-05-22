export default {
	port: process.env.PORT || 3000,
	secret: process.env.SECRET || 'secret',
} as const;
