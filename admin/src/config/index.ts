export default {
  app_env: process.env.APP_ENV || 'development',
  port: process.env.PORT || 3000,
  authentication_url: process.env.AUTHENTICATION_URL || 'http://authentication:8080',
  navigation: [
    {
      id: 1,
      name: 'Dashboard',
      url: '/dashboard',
      icon: 'fas fa-tachometer-alt',
    },
    {
      id: 2,
      name: 'Content',
      url: '/content',
      icon: 'fas fas fa-edit',
      children: [
        {
          id: 3,
          name: 'list',
          url: '/content/list',
          icon: 'fas fa-circle nav-icon',
        },
        {
          id: 4,
          name: 'create',
          url: '/content/create',
          icon: 'fas fa-circle nav-icon',
        },
      ],
    },
    {
      id: 5,
      name: 'Media',
      url: '/media',
      icon: 'fas fa-photo-video',
    },
  ],
} as const;
