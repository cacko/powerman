import "./styles/pages/home.sass";
import typ_e_writer from "./js/typewriter.js";
import cl_ock from "./js/clock.js";
import {Rotator} from "./js/rotator.js";


document.querySelectorAll(".typewriter").forEach((el) => {
    typ_e_writer.write(el);
});

document.querySelectorAll(".clock").forEach((el) => {
    cl_ock.start(el);
});

window.addEventListener("load", () => {
    Rotator.start();
});
window.addEventListener("beforeunload", () => {
    Rotator.stop()
});
