/**
 * Perfect Scrollbar
 */
"use strict";

document.addEventListener("DOMContentLoaded", function () {
  (function () {
    const verticalExample = document.getElementById("vertical-example"),
      horizontalExample = document.getElementById("horizontal-example"),
      statTable1 = document.getElementById("statTable1"),
      horizVertExample = document.getElementById("both-scrollbars-example");

    // Vertical Example
    // --------------------------------------------------------------------
    if (verticalExample) {
      new PerfectScrollbar(verticalExample, {
        wheelPropagation: false,
      });
    }

    // Horizontal Example
    // --------------------------------------------------------------------
    if (horizontalExample) {
      new PerfectScrollbar(horizontalExample, {
        wheelPropagation: false,
        suppressScrollY: true,
      });
    }
    // Both vertical and Horizontal Example
    // --------------------------------------------------------------------
    if (horizVertExample) {
      new PerfectScrollbar(horizVertExample, {
        wheelPropagation: false,
      });
    }

    var scrollbarTable1 = document.getElementById("scrollbarTable1");
    if (scrollbarTable1) {
      new PerfectScrollbar(scrollbarTable1, {
        wheelPropagation: false,
      });
    }

    // Initialize Perfect Scrollbar for the second table
    var scrollbarTable2 = document.getElementById("scrollbarTable2");
    if (scrollbarTable2) {
      new PerfectScrollbar(scrollbarTable2, {
        wheelPropagation: false,
      });
    }
  })();
});
