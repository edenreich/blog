'use strict';

/**
 * notifications service
 */

module.exports = {

  async create(data) {
    const { session, email } = data;

    const notificationBySession = await strapi.query('notifications').findOne({ session });
    if (notificationBySession) {
      await strapi.query('notifications').update({ session }, { is_enabled: true });
      return await strapi.query('notifications').findOne({ session });
    }

    const notificationByEmail = await strapi.query('notifications').findOne({ email });
    if (notificationByEmail) {
      await strapi.query('notifications').update({ email }, { is_enabled: true, session });
      return await strapi.query('notifications').findOne({ email });
    }

    await strapi.query('notifications').create(data);
    return strapi.query('notifications').findOne({ session });
  },

  async update(uuid, data) {
    return await strapi.query('notifications').update({ uuid }, { is_enabled: data.is_enabled });
  },

  async findOne(data) {
    const { session_id, email } = data;

    const notificationBySession = await strapi.query('notifications').findOne({ session: session_id });
    if (notificationBySession) {
      return notificationBySession;
    }

    return await strapi.query('notifications').findOne({ session: session_id, email });
  }
};
