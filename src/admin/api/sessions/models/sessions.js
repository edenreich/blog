'use strict';

const { v4: uuidv4 } = require('uuid');

/**
 * Lifecycle callbacks for the `sessions` model.
 */

module.exports = {
  lifecycles: {

    beforeCreate: async (model) => {
      model.uuid = uuidv4();
    },

  },
};
