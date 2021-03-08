const path = require('path');
const fs = require('fs');
const env = process.env.APP_ENV || 'production'; 

module.exports = {
  webpack(config) {
    config.resolve.alias['@'] = path.resolve(__dirname);

    config.module.rules.push({
      test: /\.(eot|woff|woff2|ttf|svg|png|jpg|gif)$/,
      use: {
        loader: 'url-loader',
        options: {
          limit: 100000,
          name: '[name].[ext]'
        }
      }
    });

    return config;
  },
  publicRuntimeConfig: {
    app: JSON.parse(fs.readFileSync(path.join('config', 'environments', env, 'app.json'), { encoding: 'utf-8' })),
    apis: JSON.parse(fs.readFileSync(path.join('config', 'environments', env, 'apis.json'), { encoding: 'utf-8' }))
  }
};
