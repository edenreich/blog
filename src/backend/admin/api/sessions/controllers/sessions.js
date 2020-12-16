'use strict';

const { sanitizeEntity } = require('strapi-utils');

/**
 * Read the documentation (https://strapi.io/documentation/3.0.0-beta.x/concepts/controllers.html#core-controllers)
 * to customize this controller
 */

const required = ['ip_address'];

module.exports = {

  async create(ctx) {
    const { body } = ctx.request;
    const isValid = await this.validate(body);

    if (!isValid) {
      return { message: 'invalid payload' }
    }

    const response = await strapi.services.sessions.create(body);
    return sanitizeEntity(response, { model: strapi.models.sessions });
  },

  async validate(body) {
    for (const attribute in body) {
      if (required.indexOf(attribute) !== -1 && body[attribute] == '') {
        return false;
      }
    }

    return true;
  },

};
