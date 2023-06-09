/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

import * as Bootstrap from "bootstrap";

// start the Stimulus application
import './bootstrap';

import Glide, { Controls } from "@glidejs/glide/dist/glide.modular.esm";

// Navigation
let toggleClassScrollEvent = function() {
    let navigation = document.getElementById("navbar");
    let top = window.pageYOffset;

    if (top != 0) {
        navigation.classList.remove("bg-opacity-50", "py-4");
    }

    if (top == 0) {
        navigation.classList.add("bg-opacity-50", "py-4");
    }
}

toggleClassScrollEvent();
window.addEventListener("scroll", function (e) {
    toggleClassScrollEvent();
});