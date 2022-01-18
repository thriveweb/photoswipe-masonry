'use strict';

// Extracts the width from the input string.
function parseWidth(thumbWidth, defaultWidth = 150) {
  if (typeof thumbWidth === 'number') {
    return thumbWidth;
  }
  else if (typeof thumbWidth === 'string') {
    return parseInt(
      thumbWidth
        .trim()
        .replace(/width\s?:\s?(\d+)[\w\%]*$/i, '$1')
    );
  }
  else {
    if (typeof defaultWidth  === 'number')
    {
      return defaultWidth
    }
    else
    {
      throw new Error('Thumbnail width is neither a number or string.');
    }
  }
}

if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
  module.exports.parseWidth = parseWidth;
}
else {
  window.ThumbnailHelper = {};
  window.ThumbnailHelper.parseWidth = parseWidth;
}
