'use strict';

/**
 * Read the documentation (https://strapi.io/documentation/3.0.0-beta.x/concepts/controllers.html#core-controllers)
 * to customize this controller
 */

module.exports = {
    check: async (ctx) => {
        return ctx.send(JSON.stringify({healthy: true}));
    }
};