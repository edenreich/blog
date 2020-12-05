'use strict';

const { sanitizeEntity } = require('strapi-utils');

/**
 * notifications controller
 */

module.exports = {

  async create(ctx) {
    const required = ['session', 'email'];
    const { body } = ctx.request;

    const isValid = await this.validate(body, required);
    if (!isValid) {
      ctx.send({ message: 'invalid payload' }, 422);
      return;
    }

    const response = await strapi.services.notifications.create(body);
    return sanitizeEntity(response, { model: strapi.models.notifications });
  },

  async update(ctx) {
    const { request, params } = ctx;
    const { uuid } = params;
    const { body } = request;
    const response = await strapi.services.notifications.update(uuid, body);
    return sanitizeEntity(response, { model: strapi.models.notifications });
  },

  async findOne(ctx) {
    const { session_id } = ctx.params;
    const notification = await strapi.services.notifications.findOne({ session_id });
    return sanitizeEntity(notification, { model: strapi.models.notifications });
  },

  async validate(body, required) {
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

};
