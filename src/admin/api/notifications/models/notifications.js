'use strict';

/**
 *  Lifecycle callbacks for the `notifications` model.
 */

const { v4: uuidv4 } = require('uuid');

module.exports = {
  lifecycles: {

    beforeCreate: async (model) => {
      model.uuid = uuidv4();
    },

  },
};
