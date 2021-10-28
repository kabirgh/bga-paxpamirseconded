define(["dojo", "dojo/_base/declare"], (dojo, declare) => {
  return declare("paxpamir.ui", null, {
    changeLayoutViewMode: function (event) {
      const element = dojo.byId(event.target.id);
      const targetSection = event.target.id.split('-')[1]
      dojo.toggleClass(element, 'disabled-layout-action-button');
      dojo.toggleClass(`paxpamir-${targetSection}`, 'section-visible');
    },
  })
})
