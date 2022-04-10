class ThumbnailHelper
{
  // Extracts the width from the input string.
  static parseWidth(thumbWidth, defaultWidth = 150)
  {
    if (typeof thumbWidth === 'number')
    {
      return thumbWidth;
    }

    if (typeof thumbWidth === 'string')
    {
      return parseInt(
        thumbWidth
          .trim()
          .replace(/width\s?:\s?(\d+)[\w%]*$/i, '$1'),
        10,
      );
    }

    if (typeof defaultWidth === 'number')
    {
      return defaultWidth;
    }

    throw new Error('Thumbnail width is neither a number or string.');
  }
}

module.exports.ThumbnailHelper = ThumbnailHelper;
