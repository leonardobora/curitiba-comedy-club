(function () {
    "use strict";

    function initFullwidthCarousel(component) {
        var track = component.querySelector("[data-ccc-carousel-track]");
        var slides = component.querySelectorAll("[data-ccc-carousel-slide]");
        var prevButton = component.querySelector("[data-ccc-carousel-prev]");
        var nextButton = component.querySelector("[data-ccc-carousel-next]");

        if (!track || !slides.length) {
            return;
        }

        var currentIndex = 0;
        var autoplayEnabled = component.getAttribute("data-ccc-carousel-autoplay") === "1";
        var interval = parseInt(component.getAttribute("data-ccc-carousel-interval"), 10);
        var timer = null;

        if (isNaN(interval) || interval < 2500) {
            interval = 5000;
        }

        function render() {
            track.style.transform = "translateX(-" + (currentIndex * 100) + "%)";
        }

        function goNext() {
            currentIndex = (currentIndex + 1) % slides.length;
            render();
        }

        function goPrev() {
            currentIndex = (currentIndex - 1 + slides.length) % slides.length;
            render();
        }

        function stopAutoplay() {
            if (timer) {
                window.clearInterval(timer);
                timer = null;
            }
        }

        function startAutoplay() {
            if (!autoplayEnabled || slides.length < 2 || timer) {
                return;
            }

            timer = window.setInterval(goNext, interval);
        }

        if (nextButton) {
            nextButton.addEventListener("click", function () {
                goNext();
                stopAutoplay();
                startAutoplay();
            });
        }

        if (prevButton) {
            prevButton.addEventListener("click", function () {
                goPrev();
                stopAutoplay();
                startAutoplay();
            });
        }

        component.addEventListener("mouseenter", stopAutoplay);
        component.addEventListener("mouseleave", startAutoplay);

        // Keep slide positioning stable after zoom or viewport changes.
        window.addEventListener("resize", render);
        window.addEventListener("orientationchange", render);

        if (typeof window.ResizeObserver === "function") {
            var observer = new ResizeObserver(function () {
                render();
            });
            observer.observe(component);
        }

        render();
        startAutoplay();
    }

    document.addEventListener("DOMContentLoaded", function () {
        var components = document.querySelectorAll("[data-ccc-ui-component]");

        components.forEach(function (component) {
            component.setAttribute("data-ccc-ui-ready", "1");

            if (component.getAttribute("data-ccc-ui-component") === "fullwidth-carousel") {
                initFullwidthCarousel(component);
            }
        });
    });
})();
