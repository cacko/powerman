import "./styles/pages/home.sass";
import typ_e_writer from "./js/typewriter.js";
import cl_ock from "./js/clock.js";
import {DTSelector} from "./js/dt_selector.js";

document.querySelectorAll(".typewriter").forEach((el) => {
    typ_e_writer.write(el);
});

document.querySelectorAll(".clock").forEach((el) => {
    cl_ock.start(el);
});

DTSelector.start();
