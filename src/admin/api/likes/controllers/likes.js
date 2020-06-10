'use strict';

const { sanitizeEntity } = require('strapi-utils');


/**
 * Read the documentation (https://strapi.io/documentation/3.0.0-beta.x/concepts/controllers.html#core-controllers)
 * to customize this controller
 */

const required = ['uuid', 'ip_address', 'reaction_type', 'article']

module.exports = {

  async create(ctx) {
    const { body } = ctx.request;
    const isValid = await this.validate(body);

    if (!isValid) {
      return { message: 'invalid payload' }
    }


    const response = await strapi.services.likes.create(body);
    return sanitizeEntity(response, { model: strapi.models.likes });
  },

  async validate(body) {
    for (const attribute in body) {
      if (required.indexOf(attribute) !== -1 && body[attribute] == '') {
        return false;
      }
    }

    return true;
  },

  async count(ctx) {
    const { query } = ctx.request;
    const { article } = query;

    const response = await strapi.services.likes.count(article);
    return response;
  }

};
