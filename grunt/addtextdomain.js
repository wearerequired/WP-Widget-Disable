module.exports = {
  dist: {
    options: {
      textdomain   : 'wp-widget-disable',
      updateDomains: []
    },
    target : {
      files: {
        src: ['*.php', '**/*.php', '!node_modules/**', '!tests/**']
      }
    }
  }
};