pimcore.registerNS('pimcore.plugin.member');

pimcore.plugin.member = Class.create(pimcore.plugin.admin, {
  getClassName: function() {
    return 'pimcore.plugin.member';
  },

  initialize: function() {
    pimcore.plugin.broker.registerPlugin(this);
  },

  pimcoreReady: function(params, broker) {
    // alert('Member Ready!');
  }
});

var memberPlugin = new pimcore.plugin.member();
