(function () {
    "use strict";

    document.addEventListener("DOMContentLoaded", function () {
        var components = document.querySelectorAll("[data-ccc-ui-component]");

        components.forEach(function (component) {
            component.setAttribute("data-ccc-ui-ready", "1");
        });
    });
})();
