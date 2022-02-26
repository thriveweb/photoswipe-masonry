const path = require('path');

let sourcePath = path.join(__dirname, '../../src');

const ThumbnailHelper = require(path.join(sourcePath, 'helpers/thumbnail-helper'));

const { expect } = require('chai');

describe('ThumbnailHelper', function() {
  describe('.parseWidth()', function() {
    context('width is a string', function() {
      context('has "width:<number><size-format>" format', function() {
        context('uses pixel-based sizing', function() {
          it('extracts the size', function() {
            expect(ThumbnailHelper.parseWidth('width:150px'))
              .to
              .eq(150);
          });
        });

        context('uses REM-based sizing', function() {
          it('extracts the size', function() {
            expect(ThumbnailHelper.parseWidth('width:150rem'))
              .to
              .eq(150);
          });
        });

        context('uses EM-sizing', function() {
          it('extracts the size', function() {
            expect(ThumbnailHelper.parseWidth('width:170em'))
              .to
              .eq(170);
          });
        });

        context('uses percent-sizing', function() {
          it('extracts the size', function() {
            expect(ThumbnailHelper.parseWidth('width:130%'))
              .to
              .eq(130);
          });
        });

        context('drops the size format', function() {
          it('extracts the size', function() {
            expect(ThumbnailHelper.parseWidth('width:150'))
              .to
              .eq(150);
          });
        });

        context('has a space between the colon and the number', function() {
          it('extracts the size', function() {
            expect(ThumbnailHelper.parseWidth('width: 150'))
              .to
              .eq(150);
          });
        });
      });
    });

    context('width is a number', function() {
      it('returns the number', function() {
        expect(ThumbnailHelper.parseWidth(275))
          .to
          .eq(275);
      });
    });

    context('width is null', function() {
      context('default width has been specified', function() {
        it('returns the default width', function() {
          expect(ThumbnailHelper.parseWidth(null, 260))
            .to
            .eq(260);
        });
      });

      context('default width has not been specified', function() {
        it('returns 150', function() {
          expect(ThumbnailHelper.parseWidth(null))
            .to
            .eq(150);
        });
      });
    });
  });
});
