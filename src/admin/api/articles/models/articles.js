'use strict';

/**
 * Lifecycle callbacks for the `articles` model.
 */

const { v4: uuidv4 } = require('uuid');

module.exports = {
  lifecycles: {

    beforeCreate: async (model) => {
      model.created_at = new Date;
      model.uuid = uuidv4();
    },

    beforeUpdate: async (params, model) => {
      if (model.published === true) {
        model.published_at = new Date;
      }

      model.updated_at = new Date;
    },

  },
};
