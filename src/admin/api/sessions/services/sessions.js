'use strict';

/**
 * Read the documentation (https://strapi.io/documentation/3.0.0-beta.x/concepts/services.html#core-services)
 * to customize this service
 */

module.exports = {

  async create(data) {
    const { ip_address } = data;

    if (typeof ip_address === 'undefined') return;

    const session = await strapi.query('sessions').find({ ip_address });

    if (session && session.length > 0) {
      return session;
    } else {
      await strapi.query('sessions').create(data);
      return await strapi.query('sessions').findOne({ ip_address });
    }
  },

};
