'use strict';

/**
 * Read the documentation (https://strapi.io/documentation/3.0.0-beta.x/concepts/services.html#core-services)
 * to customize this service
 */

module.exports = {

  async create(data) {
    const { session, reaction_type, article } = data;
  
    const articleEntity = await strapi.query('articles').find({ id: article });

    if (!articleEntity || articleEntity.length === 0) {
      return {
        count: 0,
        errors: [
          'article does not exists'
        ]
      };
    }

    const entry = await strapi.query('likes').find({ session, article });

    if (entry && entry.length > 0) {
      await strapi.query('likes').update({ session, article }, data); 
    } else {
      await strapi.query('likes').create(data);
    }

    const count = await strapi.query('likes').count({ reaction_type, article });

    return {
      reaction_type,
      count
    };
  },

  async count(article) {

    const articleEntity = await strapi.query('articles').find({ id: article });

    if (!articleEntity || articleEntity.length === 0) {
      return {
        like: 0,
        love: 0,
        dislike: 0,
        errors: [
          'article does not exists'
        ]
      };
    }

    const like = await strapi.query('likes').count({ reaction_type: 'like', article: article });
    const love = await strapi.query('likes').count({ reaction_type: 'love', article: article });
    const dislike = await strapi.query('likes').count({ reaction_type: 'dislike', article: article });

    return {
      like,
      love,
      dislike
    }

  }

};
