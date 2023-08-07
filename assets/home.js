import "./styles/pages/home.scss";
import "./styles/components/_toastify.scss";
import cl_ock from "./js/clock.js";
import {DTSelector} from "./js/dt_selector.js";
import {Messages} from "./js/messages";

document.querySelectorAll(".clock").forEach((el) => {
    cl_ock.start(el);
});

DTSelector.start();
Messages.start();