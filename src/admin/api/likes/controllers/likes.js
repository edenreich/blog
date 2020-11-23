'use strict';

const { sanitizeEntity } = require('strapi-utils');


/**
 * Read the documentation (https://strapi.io/documentation/3.0.0-beta.x/concepts/controllers.html#core-controllers)
 * to customize this controller
 */

const required = ['session', 'reaction_type', 'article']

module.exports = {

  async create(ctx) {
    const { body } = ctx.request;
    const isValid = await this.validate(body);

    if (!isValid) {
      ctx.send({ message: 'invalid payload' }, 422);
      return;
    }

    const response = await strapi.services.likes.create(body);
    return sanitizeEntity(response, { model: strapi.models.likes });
  },

  async validate(body) {
    for (const attribute of required) {
      if (
        Object.keys(body).indexOf(attribute) === -1 ||
        (Object.keys(body).indexOf(attribute) !== -1 && body[attribute] == '')
      ) {
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
