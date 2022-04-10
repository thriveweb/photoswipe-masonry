import Phasonry from './phasonry.js';

document.addEventListener(
  'DOMContentLoaded',
  function(e) {
    let phasonry = new Phasonry();
    phasonry.registerEventListeners();
  }
);
