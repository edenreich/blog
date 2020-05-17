'use strict';

/**
 * Read the documentation (https://strapi.io/documentation/3.0.0-beta.x/concepts/services.html#core-services)
 * to customize this service
 */

module.exports = {

  async create(data) {
    const { uuid, reaction_type, article } = data;
  
    const articleEntity = await strapi.query('articles').find({ id: article });

    if (!articleEntity || articleEntity.length === 0) {
      return {
        count: 0,
        errors: [
          'article does not exists'
        ]
      };
    }

    const entry = await strapi.query('likes').find({ uuid, article });

    if (entry && entry.length > 0) {
      await strapi.query('likes').update({ uuid }, data); 
    } else {
      await strapi.query('likes').create(data);
    }

    const count = await strapi.query('likes').count({ reaction_type, article });

    return {
      reaction_type,
      count
    };
  }

};
