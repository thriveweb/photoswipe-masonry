class GalleryHelper
{
  static parseHash(galleryHash)
  {
    const params = {};

    if (galleryHash.length < 5) {
      return params;
    }

    const vars = galleryHash.split('&');

    for (let i = 0; i < vars.length; i++) {
      if (!vars[i]) {
        continue;
      }
      const pair = vars[i].split('=');
      if (pair.length < 2) {
        continue;
      }
      params[pair[0]] = pair[1];
    }

    params.pid = parseInt(params.pid, 10);
    return params;
  }
}

module.exports.GalleryHelper = GalleryHelper;
